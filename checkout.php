<?php
session_start();

if (file_exists('db.php')) {
    require_once 'db.php';
}

if (isset($_GET['new_order']) || (isset($_GET['action']) && $_GET['action'] === 'clear_receipt')) {
    unset($_SESSION['last_order']);
    header('Location: shop.php');
    exit;
}

if (empty($_SESSION['cart']) && !isset($_SESSION['last_order'])) {
    header('Location: shop.php');
    exit;
}

$order = null;

if (!empty($_SESSION['cart']) && !isset($_GET['view'])) {
    $order = null; 
} elseif (isset($_SESSION['last_order'])) {
    $order = $_SESSION['last_order'];
}

// ORDER PROCESSING HANDLER
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $first_name     = trim($_POST['first_name'] ?? '');
    $last_name      = trim($_POST['last_name'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $phone          = trim($_POST['phone'] ?? '');
    $address        = trim($_POST['address'] ?? '');
    $payment_method = trim($_POST['payment_method'] ?? 'Credit Card');

    $customer_name = trim($first_name . ' ' . $last_name);
    if (empty($customer_name)) {
        $customer_name = $_SESSION['user_name'] ?? 'Guest Customer';
    }

    $subtotal = 0;
    $total_items = 0;
    $items = [];

    foreach ($_SESSION['cart'] as $id => $item) {
        $qty = (int)($item['qty'] ?? $item['quantity'] ?? 1);
        $price = (float)($item['price'] ?? 0);

        if ($qty < 1) {
            $qty = 1;
        }

        $subtotal += $price * $qty;
        $total_items += $qty;
        $items[] = $item;
    }

    $shipping = $subtotal > 35 ? 0.00 : ($subtotal > 0 ? 5.99 : 0.00);
    $tax = $subtotal * 0.08;
    $grand_total = $subtotal + $shipping + $tax;
    $order_number = 'TN-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));

    // DATABASE ORDER + INVENTORY UPDATE
    if (!isset($pdo)) {
        $_SESSION['checkout_error'] = 'Database connection is not available. Your order was not placed.';
        header('Location: checkout.php');
        exit;
    }

    try {
        $pdo->beginTransaction();

        $user_id = $_SESSION['user_id'] ?? null;

        // Create the order.
        $stmt = $pdo->prepare("
            INSERT INTO orders (
                user_id, order_number, customer_name, email, phone, shipping_address,
                subtotal, shipping, tax, total_amount, payment_method, status, created_at
            ) VALUES (
                :user_id, :order_number, :customer_name, :email, :phone, :shipping_address,
                :subtotal, :shipping, :tax, :total_amount, :payment_method, 'Pending', NOW()
            )
        ");

        $stmt->execute([
            'user_id'          => $user_id,
            'order_number'     => $order_number,
            'customer_name'    => $customer_name,
            'email'            => $email,
            'phone'            => $phone,
            'shipping_address' => $address,
            'subtotal'         => $subtotal,
            'shipping'         => $shipping,
            'tax'              => $tax,
            'total_amount'     => $grand_total,
            'payment_method'   => $payment_method
        ]);

        $order_id = $pdo->lastInsertId();

        $item_stmt = $pdo->prepare("
            INSERT INTO order_items (
                order_id, product_id, item_name, flavor, price, quantity
            ) VALUES (
                :order_id, :product_id, :item_name, :flavor, :price, :quantity
            )
        ");

        // products.id and order_items.product_id are INT(11) in your database.
        $stock_stmt = $pdo->prepare("
            UPDATE products
            SET stock = stock - :quantity
            WHERE id = :product_id
              AND stock >= :required_quantity
        ");

        foreach ($_SESSION['cart'] as $pid => $item) {
            $raw_item_id = $item['pid'] ?? $item['id'] ?? $pid;
            $item_id     = (int)$raw_item_id;
            $item_name   = $item['name'] ?? $item['title'] ?? 'Product';
            $flavor      = $item['flavour'] ?? $item['flavor'] ?? '';
            $price       = (float)($item['price'] ?? 0);
            $quantity    = (int)($item['qty'] ?? $item['quantity'] ?? 1);

            if ($item_id <= 0) {
                throw new RuntimeException('Invalid product ID in cart.');
            }

            if ($quantity < 1) {
                throw new RuntimeException('Invalid product quantity.');
            }

            // Lock the product row and check current stock.
            $check_stock = $pdo->prepare("
                SELECT id, name, stock
                FROM products
                WHERE id = :product_id
                FOR UPDATE
            ");
            $check_stock->execute(['product_id' => $item_id]);
            $product = $check_stock->fetch(PDO::FETCH_ASSOC);

            if (!$product) {
                throw new RuntimeException(
                    'Product "' . $item_name . '" could not be found in the database.'
                );
            }

            $current_stock = (int)$product['stock'];

            if ($current_stock < $quantity) {
                throw new RuntimeException(
                    'Not enough stock for "' . $product['name'] . '". Available: ' .
                    $current_stock . ', requested: ' . $quantity . '.'
                );
            }

            // Save purchased quantity.
            $item_stmt->execute([
                'order_id'   => $order_id,
                'product_id' => $item_id,
                'item_name'  => $item_name,
                'flavor'     => $flavor,
                'price'      => $price,
                'quantity'   => $quantity
            ]);

            // Automatically subtract purchased quantity from products.stock.
            $stock_stmt->execute([
                'quantity'          => $quantity,
                'product_id'        => $item_id,
                'required_quantity' => $quantity
            ]);

            if ($stock_stmt->rowCount() !== 1) {
                throw new RuntimeException(
                    'Stock could not be updated for "' . $product['name'] . '".'
                );
            }
        }

        // Order and inventory changes succeed together.
        $pdo->commit();

        // Create the receipt only after the database transaction succeeds.
        $_SESSION['last_order'] = [
            'order_number'   => $order_number,
            'order_date'     => date('F j, Y, g:i a'),
            'customer_name'  => $customer_name,
            'email'          => $email,
            'phone'          => $phone,
            'address'        => $address,
            'payment_method' => $payment_method,
            'items'          => $items,
            'total_items'    => $total_items,
            'subtotal'       => $subtotal,
            'shipping'       => $shipping,
            'tax'            => $tax,
            'grand_total'    => $grand_total
        ];

        // MySQL products.stock is the inventory source of truth.
        unset($_SESSION['cart']);

        header('Location: checkout.php?view=receipt');
        exit;

    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        error_log("Order processing failed: " . $e->getMessage());

        $_SESSION['checkout_error'] = $e->getMessage();
        header('Location: checkout.php');
        exit;
    }
}

$checkout_subtotal = 0;
$checkout_total_items = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $qty = (int)($item['qty'] ?? $item['quantity'] ?? 1);
        $price = (float)($item['price'] ?? 0);
        $checkout_subtotal += $price * $qty;
        $checkout_total_items += $qty;
    }
}
$checkout_shipping = $checkout_subtotal > 35 ? 0.00 : 5.99;
$checkout_tax = $checkout_subtotal * 0.08;
$checkout_grand_total = $checkout_subtotal + $checkout_shipping + $checkout_tax;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TENSION — <?= $order ? 'Order Receipt' : 'Checkout' ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=EB+Garamond:ital,wght@0,400;0,600;1,400&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="style.css">
    <style>
        :root {
            --lime: #8BC53F;
            --lime-hover: #9be043;
            --lime-glow: rgba(139, 197, 63, 0.25);
            --dark-bg: #0d0d0d;
            --card-bg: rgba(22, 22, 22, 0.94);
            --box-bg: #121212;
            --box-border: rgba(255, 255, 255, 0.1);
            --text-main: #ffffff;
            --text-muted: #a0a0a0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-main);
            margin: 0;
            padding: 0;
        }

        .checkout-hero {
            min-height: 100vh;
            padding: 60px 20px;
            background-image: linear-gradient(rgba(15, 15, 15, 0.92), rgba(15, 15, 15, 0.92)), url('images/background-2.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            box-sizing: border-box;
        }

        .receipt-card {
            width: 100%;
            max-width: 1180px;
            background: var(--card-bg);
            border: 1px solid var(--box-border);
            border-radius: 20px;
            padding: 48px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(14px);
        }

        .back-to-cart-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--lime);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 24px;
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .back-to-cart-link:hover {
            color: var(--lime-hover);
            transform: translateX(-4px);
        }

        .receipt-header {
            text-align: center;
            border-bottom: 1px dashed rgba(255, 255, 255, 0.15);
            padding-bottom: 28px;
            margin-bottom: 36px;
        }

        .success-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 72px;
            height: 72px;
            background: rgba(139, 197, 63, 0.12);
            border: 2px solid var(--lime);
            color: var(--lime);
            border-radius: 50%;
            font-size: 2.2rem;
            margin-bottom: 18px;
            box-shadow: 0 0 20px var(--lime-glow);
        }

        .receipt-header h1 {
            font-family: 'Anton', sans-serif;
            font-size: 2.8rem;
            color: #ffffff;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .receipt-header p {
            color: var(--text-muted);
            font-size: 1.1rem;
            font-weight: 500;
        }

        .order-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            background: var(--box-bg);
            padding: 28px;
            border-radius: 14px;
            border: 1px solid var(--box-border);
            margin-bottom: 36px;
        }

        .info-block h4 {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--lime);
            margin-bottom: 8px;
            font-weight: 700;
        }

        .info-block p {
            color: #ffffff;
            font-size: 1.05rem;
            line-height: 1.5;
            margin: 0;
            font-weight: 500;
        }

        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 36px;
            table-layout: fixed;
        }

        .receipt-table th {
            text-align: left;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            padding-bottom: 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        }

        .receipt-table th.text-right,
        .receipt-table td.text-right {
            text-align: right;
        }

        .receipt-table td {
            padding: 20px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            color: #dddddd;
            font-size: 1.05rem;
        }

        .receipt-table td.item-title {
            color: #ffffff;
            font-weight: 600;
            font-size: 1.15rem;
        }

        .totals-section {
            width: 100%;
            max-width: 360px;
            margin-left: auto;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            padding-top: 20px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 1.1rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .total-row.grand-total {
            font-size: 1.5rem;
            font-weight: bold;
            color: #ffffff;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding-top: 16px;
            margin-top: 16px;
            font-family: 'Anton', sans-serif;
        }

        .total-row.grand-total span:last-child {
            color: var(--lime);
        }

        .receipt-actions {
            display: flex;
            gap: 20px;
            margin-top: 44px;
            justify-content: center;
        }

        .btn-action {
            padding: 16px 36px;
            border-radius: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 1.05rem;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: all 0.25s ease;
        }

        .btn-primary {
            background: var(--lime);
            color: #000000;
            border: none;
            box-shadow: 0 6px 20px var(--lime-glow);
        }

        .btn-primary:hover {
            background: var(--lime-hover);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.4);
            transform: translateY(-2px);
        }

        .checkout-layout {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 40px;
        }

        .checkout-summary-box {
            background: var(--box-bg);
            border: 1px solid var(--box-border);
            border-radius: 16px;
            padding: 28px;
            height: fit-content;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        }

        .checkout-summary-box h3 {
            font-family: 'Anton', sans-serif;
            color: var(--lime);
            font-size: 1.6rem;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            letter-spacing: 0.5px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            padding-bottom: 12px;
        }

        .checkout-item-list {
            max-height: 260px;
            overflow-y: auto;
            margin-bottom: 20px;
            padding-right: 8px;
        }

        .checkout-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1rem;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .checkout-item-title {
            color: #ffffff;
            font-weight: 600;
            font-size: 1.05rem;
        }

        .checkout-item-sub {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-top: 2px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #dddddd;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 16px 20px;
            background: #161616;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            color: #ffffff;
            font-size: 1.05rem;
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--lime);
            background: #1d1d1d;
            box-shadow: 0 0 0 4px var(--lime-glow);
        }

        @media (max-width: 960px) {
            .checkout-layout {
                grid-template-columns: 1fr;
            }
            .form-grid {
                grid-template-columns: 1fr;
            }
            .receipt-card {
                padding: 28px 20px;
            }
        }

        @media print {
            .site-footer, .receipt-actions, .back-to-cart-link {
                display: none !important;
            }
            body { background: #ffffff !important; color: #000000 !important; }
            .receipt-card { background: #ffffff !important; color: #000000 !important; border: none !important; box-shadow: none !important; }
            .receipt-header h1, .info-block p, .receipt-table td, .total-row.grand-total { color: #000000 !important; }
            .order-info-grid { background: #f8f8f8 !important; border: 1px solid #ddd !important; }
        }
    </style>
</head>
<body>

    <main class="checkout-hero">
        <div class="receipt-card">
            <?php if (!$order): ?>
                <!-- BACK TO CART REDIRECT LINK -->
                <a href="cart.php" class="back-to-cart-link">
                    &#8592; Back to Shopping Cart
                </a>
            <?php endif; ?>

            <?php if ($order): ?>
                <!-- RECEIPT / ORDER SUMMARY VIEW -->
                <div class="receipt-header">
                    <div class="success-badge">✓</div>
                    <h1>THANK YOU FOR YOUR ORDER!</h1>
                    <p>We've received your order and are preparing it for shipment.</p>
                </div>

                <div class="order-info-grid">
                    <div class="info-block">
                        <h4>Order Number</h4>
                        <p><strong>#<?= htmlspecialchars($order['order_number']) ?></strong></p>
                    </div>
                    <div class="info-block">
                        <h4>Date</h4>
                        <p><?= htmlspecialchars($order['order_date']) ?></p>
                    </div>
                    <div class="info-block">
                        <h4>Total Items</h4>
                        <p><strong><?= (int)($order['total_items'] ?? count($order['items'])) ?> Units</strong></p>
                    </div>
                    <div class="info-block">
                        <h4>Customer</h4>
                        <p><?= htmlspecialchars($order['customer_name']) ?><br><?= htmlspecialchars($order['email']) ?></p>
                    </div>
                    <div class="info-block">
                        <h4>Shipping Address</h4>
                        <p><?= htmlspecialchars($order['address']) ?></p>
                    </div>
                    <div class="info-block">
                        <h4>Payment Method</h4>
                        <p><?= htmlspecialchars($order['payment_method']) ?></p>
                    </div>
                </div>

                <table class="receipt-table">
                    <thead>
                        <tr>
                            <th style="width: 45%;">Item Description</th>
                            <th class="text-right" style="width: 18%;">Price</th>
                            <th class="text-right" style="width: 15%;">Qty</th>
                            <th class="text-right" style="width: 22%;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order['items'] as $item): ?>
                            <?php 
                                $item_name = $item['name'] ?? $item['title'] ?? 'Product';
                                $item_flavor = $item['flavour'] ?? $item['flavor'] ?? $item['size'] ?? '';
                                $item_price = (float)($item['price'] ?? 0);
                                $item_qty = (int)($item['qty'] ?? $item['quantity'] ?? 1);
                            ?>
                            <tr>
                                <td class="item-title">
                                    <?= htmlspecialchars($item_name) ?><br>
                                    <small style="color: #a0a0a0; font-weight: normal; font-size: 0.9rem;"><?= htmlspecialchars($item_flavor) ?></small>
                                </td>
                                <td class="text-right">$<?= number_format($item_price, 2) ?></td>
                                <td class="text-right"><?= $item_qty ?></td>
                                <td class="text-right" style="font-weight: 600; color: #ffffff;">$<?= number_format($item_price * $item_qty, 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="totals-section">
                    <div class="total-row">
                        <span>Subtotal</span>
                        <span>$<?= number_format($order['subtotal'], 2) ?></span>
                    </div>
                    <div class="total-row">
                        <span>Shipping</span>
                        <span>$<?= number_format($order['shipping'], 2) ?></span>
                    </div>
                    <div class="total-row">
                        <span>Estimated Tax (8%)</span>
                        <span>$<?= number_format($order['tax'], 2) ?></span>
                    </div>
                    <div class="total-row grand-total">
                        <span>Total Paid</span>
                        <span>$<?= number_format($order['grand_total'], 2) ?></span>
                    </div>
                </div>

                <div class="receipt-actions">
                    <button type="button" onclick="window.print()" class="btn-action btn-secondary">Print Receipt</button>
                    <a href="checkout.php?new_order=1" class="btn-action btn-primary">Start New Order</a>
                </div>

            <?php else: ?>
                <?php if (!empty($_SESSION['checkout_error'])): ?>
                    <div style="margin-bottom: 24px; padding: 14px 18px; border: 1px solid #ff4d4d; background: rgba(255, 77, 77, 0.10); color: #ff6b6b; border-radius: 10px; font-weight: 600;">
                        <?= htmlspecialchars($_SESSION['checkout_error']) ?>
                    </div>
                    <?php unset($_SESSION['checkout_error']); ?>
                <?php endif; ?>

                <!-- CHECKOUT FORM VIEW -->
                <div class="receipt-header">
                    <h1>CHECKOUT <span class="accent" style="color: var(--lime);">DETAILS</span></h1>
                    <p>Enter your shipping and payment details to complete purchase</p>
                </div>

                <div class="checkout-layout">
                    <form action="checkout.php" method="post">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" id="first_name" name="first_name" class="form-control" required value="<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" id="last_name" name="last_name" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control" required value="<?= htmlspecialchars($_SESSION['user_email'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="tel" id="phone" name="phone" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address">Shipping Address</label>
                            <input type="text" id="address" name="address" class="form-control" placeholder="Street, City, State, Zip Code" required>
                        </div>

                        <div class="form-group">
                            <label for="payment_method">Payment Method</label>
                            <select id="payment_method" name="payment_method" class="form-control">
                                <option value="Credit Card">Credit / Debit Card</option>
                                <option value="Cash on Delivery">Cash on Delivery</option>
                                <option value="PayPal">PayPal</option>
                            </select>
                        </div>

                        <div style="display: flex; gap: 12px; align-items: center; margin-top: 10px;">
                            <a href="cart.php" class="btn-action btn-secondary" style="flex: 1;">&#8592; Cart</a>
                            <button type="submit" name="place_order" class="btn-action btn-primary" style="flex: 2; padding: 20px; font-size: 1.15rem;">Complete Purchase →</button>
                        </div>
                    </form>

                    <div class="checkout-summary-box">
                        <h3>
                            <span>Cart Summary</span>
                            <span style="font-size: 0.95rem; color: #ffffff; background: rgba(255,255,255,0.1); padding: 4px 10px; border-radius: 20px;"><?= $checkout_total_items ?> Item<?= $checkout_total_items !== 1 ? 's' : '' ?></span>
                        </h3>

                        <div class="checkout-item-list">
                            <?php if (!empty($_SESSION['cart'])): ?>
                                <?php foreach ($_SESSION['cart'] as $c_item): ?>
                                    <?php 
                                        $c_name = $c_item['name'] ?? $c_item['title'] ?? 'Product';
                                        $c_price = (float)($c_item['price'] ?? 0);
                                        $c_qty = (int)($c_item['qty'] ?? $c_item['quantity'] ?? 1);
                                    ?>
                                    <div class="checkout-item">
                                        <div>
                                            <div class="checkout-item-title"><?= htmlspecialchars($c_name) ?></div>
                                            <div class="checkout-item-sub">Qty: <?= $c_qty ?> × $<?= number_format($c_price, 2) ?></div>
                                        </div>
                                        <div style="color: #ffffff; font-weight: bold; font-size: 1.05rem;">
                                            $<?= number_format($c_price * $c_qty, 2) ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <div class="total-row">
                            <span>Subtotal</span>
                            <span>$<?= number_format($checkout_subtotal, 2) ?></span>
                        </div>
                        <div class="total-row">
                            <span>Shipping</span>
                            <span><?= $checkout_shipping === 0.0 ? '<span style="color: var(--lime); font-weight: bold;">FREE</span>' : '$' . number_format($checkout_shipping, 2) ?></span>
                        </div>
                        <div class="total-row">
                            <span>Estimated Tax</span>
                            <span>$<?= number_format($checkout_tax, 2) ?></span>
                        </div>
                        <div class="total-row grand-total">
                            <span>Total</span>
                            <span>$<?= number_format($checkout_grand_total, 2) ?></span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer class="site-footer">
        <div class="footer-bottom" style="text-align: center;">
            <span>&copy; <?= date('Y') ?> — TENSION Energy Drink Company LLC. All Rights Reserved.</span>
        </div>
    </footer>

</body>
</html>