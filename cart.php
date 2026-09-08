<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Full synchronized catalog for lookup processing
$catalog = [
    'grapes-single' => ['name' => 'Tension Grapes', 'flavour' => 'Grapes', 'size' => 'Single Can · 16oz', 'price' => 3.50, 'image' => 'images/tension-double-purple.png'],
    'grapes-6pack'  => ['name' => 'Tension Grapes', 'flavour' => 'Grapes', 'size' => '6-Pack · 16oz cans', 'price' => 19.99, 'image' => 'images/tension-double-purple.png'],
    'grapes-12pack' => ['name' => 'Tension Grapes', 'flavour' => 'Grapes', 'size' => '12-Pack Case · 16oz cans', 'price' => 36.99, 'image' => 'images/tension-double-purple.png'],
    'apple-single'  => ['name' => 'Tension Apple', 'flavour' => 'Apple', 'size' => 'Single Can · 16oz', 'price' => 3.50, 'image' => 'images/tension-double-red.png'],
    'apple-6pack'   => ['name' => 'Tension Apple', 'flavour' => 'Apple', 'size' => '6-Pack · 16oz cans', 'price' => 19.99, 'image' => 'images/tension-double-red.png'],
    'apple-12pack'  => ['name' => 'Tension Apple', 'flavour' => 'Apple', 'size' => '12-Pack Case · 16oz cans', 'price' => 36.99, 'image' => 'images/tension-double-red.png'],
    'lime-single'   => ['name' => 'Tension Lime', 'flavour' => 'Lime', 'size' => 'Single Can · 16oz', 'price' => 3.50, 'image' => 'images/tension-double-lime.png'],
    'lime-6pack'    => ['name' => 'Tension Lime', 'flavour' => 'Lime', 'size' => '6-Pack · 16oz cans', 'price' => 19.99, 'image' => 'images/tension-double-lime.png'],
    'lime-12pack'   => ['name' => 'Tension Lime', 'flavour' => 'Lime', 'size' => '12-Pack Case · 16oz cans', 'price' => 36.99, 'image' => 'images/tension-double-lime.png'],
    'variety-12pack' => ['name' => 'Tension Variety Pack', 'flavour' => 'Variety', 'size' => '12-Pack Case · 4 of each flavor', 'price' => 38.99, 'image' => 'images/tension-cans-collection.png'],
];

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $pid = $_POST['product_id'] ?? '';
        $qty = max(1, (int)($_POST['quantity'] ?? 1));
        if (isset($catalog[$pid])) {
            if (isset($_SESSION['cart'][$pid])) {
                $_SESSION['cart'][$pid]['qty'] += $qty;
            } else {
                $_SESSION['cart'][$pid] = [
                    'pid'   => $pid,
                    'name'  => $catalog[$pid]['name'],
                    'size'  => $catalog[$pid]['size'],
                    'price' => $catalog[$pid]['price'],
                    'image' => $catalog[$pid]['image'],
                    'qty'   => $qty,
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
        header('Location: cart.php');
        exit;
    }
}

$subtotal = 0;
$total_items = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['qty'];
    $total_items += $item['qty'];
}
$shipping = ($subtotal > 35 || $subtotal == 0) ? 0.00 : 5.99;
$tax = $subtotal * 0.08;
$grand_total = $subtotal + $shipping + $tax;

$nav_links = [
    ['label' => 'Home',      'href' => 'index.php'],
    ['label' => 'Shop',      'href' => 'shop.php'],
    ['label' => 'News',      'href' => 'news.php'],
    ['label' => 'Training',  'href' => 'training.php'],
    ['label' => 'Lifestyle', 'href' => 'lifestyle.php'],
    ['label' => 'About',     'href' => 'about.php'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TENSION — Your Shopping Cart</title>

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
        }

        /* Shared Background Hero Style with checkout.php */
        .cart-hero {
            min-height: calc(100vh - 120px);
            padding: 60px 20px;
            background-image: linear-gradient(rgba(15, 15, 15, 0.92), rgba(15, 15, 15, 0.92)), url('images/background-2.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        /* Glassmorphism Main Card Container */
        .cart-card {
            width: 100%;
            max-width: 1180px;
            background: var(--card-bg);
            border: 1px solid var(--box-border);
            border-radius: 20px;
            padding: 48px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(14px);
        }

        .cart-header {
            text-align: left;
            border-bottom: 1px dashed rgba(255, 255, 255, 0.15);
            padding-bottom: 28px;
            margin-bottom: 36px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-header h1 {
            font-family: 'Anton', sans-serif;
            font-size: 3rem;
            color: #ffffff;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .cart-header .accent {
            color: var(--lime);
        }

        .cart-header a.back-shop {
            color: var(--lime);
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 18px;
            border-radius: 8px;
            background: rgba(139, 197, 63, 0.08);
            border: 1px solid rgba(139, 197, 63, 0.2);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .cart-header a.back-shop:hover {
            color: #ffffff;
            background: var(--lime);
            border-color: var(--lime);
            transform: translateX(-3px);
        }

        .cart-layout {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 40px;
        }

        /* Enlarged Cart Item Cards */
        .cart-item {
            display: flex;
            align-items: center;
            gap: 28px;
            background: var(--box-bg);
            border: 1px solid var(--box-border);
            padding: 28px;
            border-radius: 16px;
            margin-bottom: 20px;
            transition: all 0.25s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .cart-item:hover {
            border-color: rgba(139, 197, 63, 0.5);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4), 0 0 15px var(--lime-glow);
        }

        .cart-item-img-wrap {
            width: 105px;
            height: 105px;
            background: radial-gradient(circle, rgba(255,255,255,0.06) 0%, rgba(0,0,0,0.2) 100%);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            flex-shrink: 0;
        }

        .cart-item img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 6px 8px rgba(0, 0, 0, 0.5));
        }

        .cart-item-info {
            flex: 1;
        }

        .cart-item-name {
            font-family: 'Anton', sans-serif;
            font-size: 1.65rem;
            color: #ffffff;
            letter-spacing: 0.8px;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .cart-item-size {
            font-size: 1.1rem;
            color: var(--text-muted);
            margin-bottom: 10px;
            font-weight: 500;
        }

        .cart-item-price {
            font-family: 'Anton', sans-serif;
            color: var(--lime);
            font-size: 1.5rem;
            letter-spacing: 0.5px;
        }

        /* Enlarged Interactive Controls */
        .qty-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #1a1a1a;
            padding: 8px 14px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .qty-btn {
            background: #282828;
            border: 1px solid #444444;
            color: #ffffff;
            width: 42px;
            height: 42px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.35rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .qty-btn:hover {
            background: var(--lime);
            color: #000000;
            border-color: var(--lime);
        }

        .qty-val {
            width: 48px;
            text-align: center;
            background: transparent;
            border: none;
            color: #ffffff;
            font-size: 1.3rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
        }

        .remove-btn {
            background: rgba(255, 75, 75, 0.1);
            border: 1px solid rgba(255, 75, 75, 0.25);
            color: #ff6b6b;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            padding: 12px 18px;
            border-radius: 8px;
            margin-left: 12px;
            transition: all 0.2s ease;
        }

        .remove-btn:hover {
            background: #ff4d4d;
            color: #ffffff;
            border-color: #ff4d4d;
            box-shadow: 0 4px 12px rgba(255, 77, 77, 0.3);
        }

        /* Prominent Summary Box */
        .summary-card {
            background: var(--box-bg);
            padding: 32px;
            border-radius: 16px;
            border: 1px solid var(--box-border);
            height: fit-content;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            position: sticky;
            top: 20px;
        }

        .summary-card h2 {
            font-family: 'Anton', sans-serif;
            font-size: 1.8rem;
            color: var(--lime);
            margin-bottom: 24px;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            padding-bottom: 12px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 16px;
            font-size: 1.15rem;
            color: #d1d1d1;
            font-weight: 500;
        }

        .shipping-badge {
            font-size: 0.85rem;
            background: rgba(139, 197, 63, 0.15);
            color: var(--lime);
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .summary-total {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            padding-top: 20px;
            margin-top: 20px;
            font-family: 'Anton', sans-serif;
            font-size: 1.8rem;
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
            padding: 20px;
            font-size: 1.2rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 28px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 6px 20px var(--lime-glow);
        }

        .checkout-btn:hover {
            background: var(--lime-hover);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(139, 197, 63, 0.4);
        }

        .empty-cart-box {
            text-align: center;
            padding: 80px 20px;
        }

        .empty-msg {
            font-size: 1.5rem;
            color: var(--text-muted);
            margin-bottom: 28px;
            font-weight: 500;
        }

        @media (max-width: 960px) {
            .cart-layout {
                grid-template-columns: 1fr;
            }
            .cart-item {
                flex-wrap: wrap;
                gap: 18px;
            }
            .cart-card {
                padding: 28px 20px;
            }
            .cart-header h1 {
                font-size: 2.2rem;
            }
        }
    </style>
</head>
<body>

    <header class="site-header">
        <a href="index.php" class="logo">
            <span class="logo-word"><span class="accent">T</span>ension</span>
        </a>
        <nav class="main-nav" aria-label="Main navigation">
            <?php foreach ($nav_links as $link): ?>
                <a href="<?= htmlspecialchars($link['href']) ?>" class="nav-link"><?= htmlspecialchars(strtoupper($link['label'])) ?></a>
            <?php endforeach; ?>
        </nav>
    </header>

    <main class="cart-hero">
        <div class="cart-card">
            <div class="cart-header">
                <h1>YOUR <span class="accent">CART</span> (<?= $total_items ?>)</h1>
                <a href="shop.php" class="back-shop">← Back to Shop</a>
            </div>

            <?php if (empty($_SESSION['cart'])): ?>
                <div class="empty-cart-box">
                    <p class="empty-msg">Your shopping cart is currently empty.</p>
                    <a href="shop.php" class="checkout-btn" style="display: inline-block; width: auto; padding: 16px 38px;">Start Shopping</a>
                </div>
            <?php else: ?>
                <div class="cart-layout">
                    <div>
                        <?php foreach ($_SESSION['cart'] as $pid => $item): ?>
                            <div class="cart-item">
                                <div class="cart-item-img-wrap">
                                    <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                </div>
                                
                                <div class="cart-item-info">
                                    <div class="cart-item-name"><?= htmlspecialchars($item['name']) ?></div>
                                    <div class="cart-item-size"><?= htmlspecialchars($item['size']) ?></div>
                                    <div class="cart-item-price">$<?= number_format($item['price'], 2) ?></div>
                                </div>

                                <form method="post" action="cart.php" class="qty-controls">
                                    <input type="hidden" name="update_qty" value="1">
                                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($pid) ?>">
                                    <button type="submit" name="quantity" value="<?= $item['qty'] - 1 ?>" class="qty-btn" aria-label="Decrease quantity">−</button>
                                    <input type="text" readonly class="qty-val" value="<?= $item['qty'] ?>">
                                    <button type="submit" name="quantity" value="<?= $item['qty'] + 1 ?>" class="qty-btn" aria-label="Increase quantity">+</button>
                                </form>

                                <form method="post" action="cart.php" style="display:inline;">
                                    <input type="hidden" name="remove_item" value="1">
                                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($pid) ?>">
                                    <button type="submit" class="remove-btn">Remove</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div>
                        <div class="summary-card">
                            <h2>Order Summary</h2>
                            <div class="summary-row">
                                <span>Subtotal</span>
                                <span>$<?= number_format($subtotal, 2) ?></span>
                            </div>
                            <div class="summary-row">
                                <span>Shipping</span>
                                <span><?= $shipping === 0.0 ? '<span class="shipping-badge">FREE</span>' : '$' . number_format($shipping, 2) ?></span>
                            </div>
                            <div class="summary-row">
                                <span>Est. Tax (8%)</span>
                                <span>$<?= number_format($tax, 2) ?></span>
                            </div>
                            <div class="summary-row summary-total">
                                <span>Grand Total</span>
                                <span>$<?= number_format($grand_total, 2) ?></span>
                            </div>
                            <a href="checkout.php" class="checkout-btn">Proceed to Checkout →</a>
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