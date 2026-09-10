<?php
session_start();

if (file_exists('db.php')) {
    require_once 'db.php';
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// 1. Default synchronized catalog
$catalog = [
    'grapes-single'  => ['name' => 'Tension Grapes', 'flavour' => 'Grapes', 'size' => 'Single Can · 16oz', 'price' => 3.50, 'image' => 'images/tension-double-purple.png', 'stock' => 100],
    'grapes-6pack'   => ['name' => 'Tension Grapes', 'flavour' => 'Grapes', 'size' => '6-Pack · 16oz cans', 'price' => 19.99, 'image' => 'images/tension-double-purple.png', 'stock' => 100],
    'grapes-12pack'  => ['name' => 'Tension Grapes', 'flavour' => 'Grapes', 'size' => '12-Pack Case · 16oz cans', 'price' => 36.99, 'image' => 'images/tension-double-purple.png', 'stock' => 100],

    'apple-single'   => ['name' => 'Tension Apple', 'flavour' => 'Apple', 'size' => 'Single Can · 16oz', 'price' => 3.50, 'image' => 'images/tension-double-red.png', 'stock' => 100],
    'apple-6pack'    => ['name' => 'Tension Apple', 'flavour' => 'Apple', 'size' => '6-Pack · 16oz cans', 'price' => 19.99, 'image' => 'images/tension-double-red.png', 'stock' => 100],
    'apple-12pack'   => ['name' => 'Tension Apple', 'flavour' => 'Apple', 'size' => '12-Pack Case · 16oz cans', 'price' => 36.99, 'image' => 'images/tension-double-red.png', 'stock' => 100],

    'lime-single'    => ['name' => 'Tension Lime', 'flavour' => 'Lime', 'size' => 'Single Can · 16oz', 'price' => 3.50, 'image' => 'images/tension-double-lime.png', 'stock' => 100],
    'lime-6pack'     => ['name' => 'Tension Lime', 'flavour' => 'Lime', 'size' => '6-Pack · 16oz cans', 'price' => 19.99, 'image' => 'images/tension-double-lime.png', 'stock' => 100],
    'lime-12pack'    => ['name' => 'Tension Lime', 'flavour' => 'Lime', 'size' => '12-Pack Case · 4 of each flavor', 'price' => 38.99, 'image' => 'images/tension-cans-collection.png', 'stock' => 100],
];

// 2. Dynamic catalog merge from products.json
if (file_exists('products.json')) {
    $json_products = json_decode(file_get_contents('products.json'), true);

    if (is_array($json_products)) {
        foreach ($json_products as $key => $item) {
            $pid = $item['id'] ?? $item['pid'] ?? (is_string($key) ? $key : '');

            if (!empty($pid)) {
                $catalog[$pid] = [
                    'name'    => $item['name'] ?? 'Tension Energy',
                    'flavour' => $item['flavour'] ?? $item['flavor'] ?? '',
                    'size'    => $item['size'] ?? 'Single Can · 16oz',
                    'price'   => (float)($item['price'] ?? 0),
                    'image'   => !empty($item['image']) ? $item['image'] : 'images/tension-cans-collection.png',
                    'stock'   => (int)($item['stock'] ?? 999)
                ];
            }
        }
    }
}

// 3. Dynamic catalog merge from database if PDO exists
if (isset($pdo)) {
    try {
        $stmt = $pdo->query("SELECT * FROM products");
        $db_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($db_products as $item) {
            $pid = $item['id'];

            $catalog[$pid] = [
                'name'    => $item['name'] ?? 'Tension Energy',
                'flavour' => $item['flavor'] ?? $item['flavour'] ?? '',
                'size'    => $item['size'] ?? 'Single Can · 16oz',
                'price'   => (float)($item['price'] ?? 0),
                'image'   => !empty($item['image']) ? $item['image'] : 'images/tension-cans-collection.png',
                'stock'   => (int)($item['stock'] ?? 999)
            ];
        }
    } catch (Exception $e) {
        error_log("Database catalog load failed: " . $e->getMessage());
    }
}

// Promo Code / Coupon Handling
$coupon_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_coupon'])) {

    $code = strtoupper(trim($_POST['coupon_code'] ?? ''));

    if ($code === 'TENSION10') {
        $_SESSION['discount'] = 0.10;
        $_SESSION['coupon_code'] = 'TENSION10';
    } else {
        $coupon_error = 'Invalid promo code.';
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_coupon'])) {

    unset($_SESSION['discount'], $_SESSION['coupon_code']);
}

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['add_to_cart'])) {

        $pid = $_POST['product_id'] ?? '';
        $qty = max(1, (int)($_POST['quantity'] ?? 1));

        if (isset($catalog[$pid])) {

            $item_data = $catalog[$pid];

        } elseif (!empty($pid) && isset($_POST['name'])) {

            $item_data = [
                'name'    => $_POST['name'],
                'flavour' => $_POST['flavour'] ?? '',
                'size'    => $_POST['size'] ?? 'Single Can · 16oz',
                'price'   => (float)($_POST['price'] ?? 0),
                'image'   => $_POST['image'] ?? 'images/tension-cans-collection.png',
                'stock'   => (int)($_POST['stock'] ?? 0)
            ];

        } else {

            $item_data = null;
        }

        if ($item_data) {

            $available_stock = (int)($item_data['stock'] ?? 999);
            $current_in_cart = $_SESSION['cart'][$pid]['qty'] ?? 0;

            if ($available_stock <= 0) {
                header('Location: shop.php?error=out_of_stock');
                exit;
            }

            $new_qty = min($available_stock, $current_in_cart + $qty);

            if (isset($_SESSION['cart'][$pid])) {

                $_SESSION['cart'][$pid]['qty'] = $new_qty;
                $_SESSION['cart'][$pid]['stock'] = $available_stock;

            } else {

                $_SESSION['cart'][$pid] = [
                    'pid'     => $pid,
                    'name'    => $item_data['name'],
                    'flavour' => $item_data['flavour'] ?? '',
                    'size'    => $item_data['size'],
                    'price'   => $item_data['price'],
                    'image'   => $item_data['image'],
                    'stock'   => $available_stock,
                    'qty'     => $new_qty,
                ];
            }
        }

        $redirect = $_POST['redirect_to'] ?? $_SERVER['HTTP_REFERER'] ?? 'shop.php';

        header('Location: ' . $redirect);
        exit;

    } elseif (isset($_POST['update_qty'])) {

        $pid = $_POST['product_id'] ?? '';
        $qty = (int)($_POST['quantity'] ?? 1);

        if (isset($_SESSION['cart'][$pid])) {

            $stock = (int)(
                $_SESSION['cart'][$pid]['stock']
                ?? $catalog[$pid]['stock']
                ?? 999
            );

            if ($qty > $stock) {
                $qty = $stock;
            }

            if ($qty > 0) {
                $_SESSION['cart'][$pid]['qty'] = $qty;
            } else {
                unset($_SESSION['cart'][$pid]);
            }
        }

        header('Location: cart.php');
        exit;

    } elseif (isset($_POST['remove_item'])) {

        $pid = $_POST['product_id'] ?? '';

        unset($_SESSION['cart'][$pid]);

        header('Location: cart.php');
        exit;

    } elseif (isset($_POST['clear_cart'])) {

        $_SESSION['cart'] = [];

        unset($_SESSION['discount'], $_SESSION['coupon_code']);

        header('Location: cart.php');
        exit;
    }
}

// Calculate Cart Totals
$subtotal = 0;
$total_items = 0;

foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['qty'];
    $total_items += $item['qty'];
}

$discount_rate = $_SESSION['discount'] ?? 0;
$discount_amount = $subtotal * $discount_rate;
$subtotal_after_discount = $subtotal - $discount_amount;

$free_shipping_threshold = 35.00;

$amount_needed_for_free_shipping = max(
    0,
    $free_shipping_threshold - $subtotal_after_discount
);

$shipping = (
    $subtotal_after_discount >= $free_shipping_threshold
    || $subtotal == 0
) ? 0.00 : 5.99;

$tax = $subtotal_after_discount * 0.08;

$grand_total = $subtotal_after_discount + $shipping + $tax;

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TENSION — Shopping Cart</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

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
            --danger: #ff4d4d;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-main);
            margin: 0;
            padding: 0;
        }

        .cart-wrapper {
            min-height: 100vh;
            padding: 40px 20px;
            background-image:
                linear-gradient(
                    rgba(15, 15, 15, 0.94),
                    rgba(15, 15, 15, 0.94)
                ),
                url('images/background-2.jpg');

            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;

            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .cart-card {
            width: 100%;
            max-width: 1200px;
            background: var(--card-bg);
            border: 1px solid var(--box-border);
            border-radius: 20px;
            padding: 40px;

            box-shadow:
                0 25px 50px -12px rgba(0, 0, 0, 0.7);

            backdrop-filter: blur(14px);
        }

        .cart-brand-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;

            padding-bottom: 24px;
            margin-bottom: 28px;

            border-bottom:
                1px solid rgba(255, 255, 255, 0.1);
        }

        .brand-logo {
            font-family: 'Anton', sans-serif;
            font-size: 2.2rem;
            color: #ffffff;
            text-decoration: none;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .brand-logo .accent {
            color: var(--lime);
        }

        .back-shop {
            color: var(--lime);
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;

            display: inline-flex;
            align-items: center;
            gap: 8px;

            padding: 10px 18px;

            border-radius: 8px;

            background: rgba(139, 197, 63, 0.08);
            border: 1px solid rgba(139, 197, 63, 0.2);

            transition: all 0.2s ease;
        }

        .back-shop:hover {
            color: #ffffff;
            background: var(--lime);
            border-color: var(--lime);
        }

        .free-shipping-bar {
            background: #181818;
            border: 1px solid rgba(139, 197, 63, 0.2);

            padding: 16px 20px;

            border-radius: 12px;

            margin-bottom: 30px;
        }

        .shipping-progress-bg {
            height: 8px;

            background: #2a2a2a;

            border-radius: 999px;

            overflow: hidden;

            margin-top: 10px;
        }

        .shipping-progress-fill {
            height: 100%;

            background: var(--lime);

            transition: width 0.3s ease;
        }

        .cart-layout {
            display: grid;

            grid-template-columns: 1fr 400px;

            gap: 36px;
        }

        .cart-item {
            display: grid;

            grid-template-columns:
                90px 1fr auto auto;

            align-items: center;

            gap: 20px;

            background: var(--box-bg);

            border: 1px solid var(--box-border);

            padding: 22px;

            border-radius: 16px;

            margin-bottom: 16px;

            transition: all 0.2s ease;
        }

        .cart-item:hover {
            border-color:
                rgba(139, 197, 63, 0.4);
        }

        .cart-item-img-wrap {
            width: 90px;
            height: 90px;

            background:
                radial-gradient(
                    circle,
                    rgba(255,255,255,0.06) 0%,
                    rgba(0,0,0,0.2) 100%
                );

            border:
                1px solid rgba(255, 255, 255, 0.08);

            border-radius: 12px;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 6px;
        }

        .cart-item img {
            width: 100%;
            height: 100%;

            object-fit: contain;
        }

        .cart-item-info {
            display: flex;

            flex-direction: column;

            gap: 4px;
        }

        .cart-item-name {
            font-family: 'Anton', sans-serif;

            font-size: 1.35rem;

            color: #ffffff;

            letter-spacing: 0.5px;

            text-transform: uppercase;
        }

        .cart-item-meta {
            font-size: 0.85rem;

            color: var(--text-muted);

            display: flex;

            gap: 12px;
        }

        .cart-item-price {
            font-size: 0.95rem;

            color: var(--text-muted);

            margin-top: 4px;
        }

        .stock-badge {
            font-size: 0.72rem;

            font-weight: 700;

            color: var(--lime);

            text-transform: uppercase;

            letter-spacing: 0.5px;
        }

        .qty-controls {
            display: flex;

            align-items: center;

            gap: 6px;

            background: #181818;

            padding: 6px;

            border-radius: 10px;

            border:
                1px solid rgba(255, 255, 255, 0.1);
        }

        .qty-btn {
            background: #262626;

            border: 1px solid #3d3d3d;

            color: #ffffff;

            width: 32px;
            height: 32px;

            border-radius: 6px;

            cursor: pointer;

            font-size: 1.1rem;

            font-weight: 700;

            display: flex;

            align-items: center;

            justify-content: center;
        }

        .qty-btn:hover {
            background: var(--lime);

            color: #000000;

            border-color: var(--lime);
        }

        .qty-val {
            width: 36px;

            text-align: center;

            background: transparent;

            border: none;

            color: #ffffff;

            font-size: 1rem;

            font-weight: 700;
        }

        .item-line-total {
            text-align: right;

            display: flex;

            flex-direction: column;

            align-items: flex-end;

            gap: 8px;
        }

        .line-price {
            font-family: 'Anton', sans-serif;

            font-size: 1.3rem;

            color: var(--lime);
        }

        .remove-btn {
            background: none;

            border: none;

            color: #ff6b6b;

            cursor: pointer;

            font-size: 0.82rem;

            font-weight: 600;

            padding: 4px 8px;

            border-radius: 4px;

            transition: all 0.2s ease;
        }

        .remove-btn:hover {
            background:
                rgba(255, 77, 77, 0.15);

            color: var(--danger);
        }

        .cart-actions-bar {
            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-top: 20px;
        }

        .clear-btn {
            background:
                rgba(255, 255, 255, 0.05);

            border:
                1px solid rgba(255, 255, 255, 0.12);

            color: var(--text-muted);

            padding: 10px 18px;

            border-radius: 8px;

            cursor: pointer;

            font-size: 0.85rem;

            font-weight: 600;
        }

        .clear-btn:hover {
            background:
                rgba(255, 77, 77, 0.15);

            color: var(--danger);

            border-color: var(--danger);
        }

        .summary-card {
            background: var(--box-bg);

            padding: 28px;

            border-radius: 16px;

            border: 1px solid var(--box-border);

            height: fit-content;
        }

        .summary-card h2 {
            font-family: 'Anton', sans-serif;

            font-size: 1.6rem;

            color: var(--lime);

            margin-bottom: 20px;

            letter-spacing: 0.5px;

            text-transform: uppercase;

            border-bottom:
                1px solid rgba(255,255,255,0.08);

            padding-bottom: 12px;
        }

        .summary-row {
            display: flex;

            justify-content: space-between;

            margin-bottom: 14px;

            font-size: 1rem;

            color: #d1d1d1;
        }

        .coupon-box {
            margin: 20px 0;

            padding-top: 16px;

            border-top:
                1px dashed rgba(255, 255, 255, 0.12);
        }

        .coupon-form {
            display: flex;

            gap: 8px;
        }

        .coupon-input {
            flex: 1;

            background: #1c1c1c;

            border:
                1px solid rgba(255, 255, 255, 0.15);

            padding: 10px 12px;

            border-radius: 8px;

            color: #fff;

            font-size: 0.9rem;

            text-transform: uppercase;
        }

        .coupon-btn {
            background: #333;

            border: 1px solid #444;

            color: #fff;

            padding: 10px 16px;

            border-radius: 8px;

            cursor: pointer;

            font-size: 0.85rem;

            font-weight: 700;
        }

        .coupon-btn:hover {
            background: var(--lime);

            color: #000;

            border-color: var(--lime);
        }

        .summary-total {
            border-top:
                1px solid rgba(255, 255, 255, 0.15);

            padding-top: 16px;

            margin-top: 16px;

            font-family: 'Anton', sans-serif;

            font-size: 1.6rem;

            color: #ffffff;
        }

        .summary-total span:last-child {
            color: var(--lime);
        }

        .checkout-btn {
            display: block;

            text-align: center;

            text-decoration: none;

            width: 100%;

            background: var(--lime);

            color: #000000;

            border: none;

            padding: 18px;

            font-size: 1.1rem;

            font-weight: 800;

            text-transform: uppercase;

            letter-spacing: 1px;

            border-radius: 10px;

            cursor: pointer;

            margin-top: 24px;

            box-shadow:
                0 6px 20px var(--lime-glow);

            transition: all 0.2s ease;
        }

        .checkout-btn:hover {
            background: var(--lime-hover);

            transform: translateY(-2px);
        }

        .trust-badges {
            margin-top: 24px;

            display: flex;

            flex-direction: column;

            gap: 10px;

            font-size: 0.8rem;

            color: var(--text-muted);

            border-top:
                1px solid rgba(255,255,255,0.08);

            padding-top: 16px;
        }

        .trust-item {
            display: flex;

            align-items: center;

            gap: 8px;
        }

        .empty-cart-box {
            text-align: center;

            padding: 80px 20px;
        }

        .empty-msg {
            font-size: 1.4rem;

            color: var(--text-muted);

            margin-bottom: 24px;
        }

        @media (max-width: 960px) {

            .cart-layout {
                grid-template-columns: 1fr;
            }

            .cart-item {
                grid-template-columns:
                    80px 1fr;
            }

            .qty-controls,
            .item-line-total {
                grid-column: span 2;

                justify-self: start;
            }
        }

    </style>

</head>

<body>

    <main class="cart-wrapper">

        <div class="cart-card">

            <!-- Brand Header without full nav -->
            <div class="cart-brand-bar">

                <a
                    href="index.php"
                    class="brand-logo"
                >
                    <span class="accent">T</span>ENSION
                </a>

                <a
                    href="shop.php"
                    class="back-shop"
                >
                    ← Continue Shopping
                </a>

            </div>

            <?php if (empty($_SESSION['cart'])): ?>

                <div class="empty-cart-box">

                    <div class="empty-msg">
                        Your shopping cart is currently empty.
                    </div>

                    <a
                        href="shop.php"
                        class="checkout-btn"
                        style="display: inline-block; width: auto; padding: 14px 32px;"
                    >
                        Start Shopping
                    </a>

                </div>

            <?php else: ?>

                <!-- Free Shipping Indicator -->
                <div class="free-shipping-bar">

                    <div
                        style="font-size: 0.9rem; font-weight: 600;"
                    >

                        <?php if ($amount_needed_for_free_shipping > 0): ?>

                            Add
                            <span style="color: var(--lime);">
                                $<?= number_format($amount_needed_for_free_shipping, 2) ?>
                            </span>

                            more to qualify for
                            <strong>FREE Shipping</strong>!

                        <?php else: ?>

                            You qualify for
                            <strong>FREE Shipping</strong>!

                        <?php endif; ?>

                    </div>

                    <?php
                    $pct = min(
                        100,
                        ($subtotal_after_discount / $free_shipping_threshold) * 100
                    );
                    ?>

                    <div class="shipping-progress-bg">

                        <div
                            class="shipping-progress-fill"
                            style="width: <?= $pct ?>%;"
                        ></div>

                    </div>

                </div>

                <div class="cart-layout">

                    <!-- Left: Cart Items List -->
                    <div>

                        <?php foreach ($_SESSION['cart'] as $pid => $item): ?>

                            <?php
                            $line_total = $item['price'] * $item['qty'];
                            ?>

                            <div class="cart-item">

                                <div class="cart-item-img-wrap">

                                    <img
                                        src="<?= htmlspecialchars($item['image']) ?>"
                                        alt="<?= htmlspecialchars($item['name']) ?>"
                                        onerror="this.src='images/tension-cans-collection.png'"
                                    >

                                </div>

                                <div class="cart-item-info">

                                    <div class="cart-item-name">
                                        <?= htmlspecialchars($item['name']) ?>
                                    </div>

                                    <div class="cart-item-meta">

                                        <span>
                                            <?= htmlspecialchars($item['size']) ?>
                                        </span>

                                        <?php if (!empty($item['flavour'])): ?>

                                            <span>
                                                • <?= htmlspecialchars($item['flavour']) ?>
                                            </span>

                                        <?php endif; ?>

                                    </div>

                                    <div class="cart-item-price">
                                        $<?= number_format($item['price'], 2) ?> each
                                    </div>

                                    <div class="stock-badge">
                                        In Stock
                                        (<?= (int)$item['stock'] ?> max)
                                    </div>

                                </div>

                                <!-- Quantity Selector -->
                                <form
                                    method="post"
                                    action="cart.php"
                                    class="qty-controls"
                                >

                                    <input
                                        type="hidden"
                                        name="update_qty"
                                        value="1"
                                    >

                                    <input
                                        type="hidden"
                                        name="product_id"
                                        value="<?= htmlspecialchars($pid) ?>"
                                    >

                                    <button
                                        type="submit"
                                        name="quantity"
                                        value="<?= $item['qty'] - 1 ?>"
                                        class="qty-btn"
                                    >
                                        −
                                    </button>

                                    <input
                                        type="text"
                                        readonly
                                        class="qty-val"
                                        value="<?= $item['qty'] ?>"
                                    >

                                    <button
                                        type="submit"
                                        name="quantity"
                                        value="<?= $item['qty'] + 1 ?>"
                                        class="qty-btn"
                                    >
                                        +
                                    </button>

                                </form>

                                <!-- Line Subtotal & Remove -->
                                <div class="item-line-total">

                                    <div class="line-price">
                                        $<?= number_format($line_total, 2) ?>
                                    </div>

                                    <form
                                        method="post"
                                        action="cart.php"
                                    >

                                        <input
                                            type="hidden"
                                            name="remove_item"
                                            value="1"
                                        >

                                        <input
                                            type="hidden"
                                            name="product_id"
                                            value="<?= htmlspecialchars($pid) ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="remove-btn"
                                        >
                                            Remove
                                        </button>

                                    </form>

                                </div>

                            </div>

                        <?php endforeach; ?>

                        <div class="cart-actions-bar">

                            <form
                                method="post"
                                action="cart.php"
                            >

                                <button
                                    type="submit"
                                    name="clear_cart"
                                    class="clear-btn"
                                    onclick="return confirm('Clear entire cart?');"
                                >
                                    Clear Cart
                                </button>

                            </form>

                            <div
                                style="font-size: 0.9rem; color: var(--text-muted);"
                            >
                                Total Items:
                                <strong>
                                    <?= $total_items ?>
                                </strong>
                            </div>

                        </div>

                    </div>

                    <!-- Right: Order Summary -->
                    <div>

                        <div class="summary-card">

                            <h2>
                                Order Summary
                            </h2>

                            <div class="summary-row">

                                <span>
                                    Subtotal
                                </span>

                                <span>
                                    $<?= number_format($subtotal, 2) ?>
                                </span>

                            </div>

                            <?php if ($discount_amount > 0): ?>

                                <div
                                    class="summary-row"
                                    style="color: var(--lime);"
                                >

                                    <span>
                                        Discount
                                        (<?= $_SESSION['coupon_code'] ?>)
                                    </span>

                                    <span>
                                        -$<?= number_format($discount_amount, 2) ?>
                                    </span>

                                </div>

                            <?php endif; ?>

                            <div class="summary-row">

                                <span>
                                    Estimated Shipping
                                </span>

                                <span>

                                    <?=
                                        $shipping === 0.0
                                        ? '<span style="color:var(--lime); font-weight:bold;">FREE</span>'
                                        : '$' . number_format($shipping, 2)
                                    ?>

                                </span>

                            </div>

                            <div class="summary-row">

                                <span>
                                    Est. Sales Tax (8%)
                                </span>

                                <span>
                                    $<?= number_format($tax, 2) ?>
                                </span>

                            </div>

                            <!-- Coupon Promo Code Box -->
                            <div
                                class="summary-row summary-total"
                            >

                                <span>
                                    Grand Total
                                </span>

                                <span>
                                    $<?= number_format($grand_total, 2) ?>
                                </span>

                            </div>


                            <!-- LOGIN / CHECKOUT PROTECTION -->
                            <?php if (isset($_SESSION['user_id'])): ?>

                                <!-- User is logged in -->
                                <a
                                    href="checkout.php"
                                    class="checkout-btn"
                                >
                                    Proceed to Checkout →
                                </a>

                            <?php else: ?>

                                <!-- User is NOT logged in -->
                                <a
                                    href="login.php?redirect=checkout.php"
                                    class="checkout-btn"
                                >
                                    Login to Checkout →
                                </a>

                            <?php endif; ?>


                            <div class="trust-badges">

                                <div class="trust-item">
                                    🔒 256-Bit SSL Encrypted Checkout
                                </div>

                                <div class="trust-item">
                                    ⚡ Instant Order Processing
                                </div>

                                <div class="trust-item">
                                    📦 Trackable Expedited Shipping
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </main>

</body>

</html>