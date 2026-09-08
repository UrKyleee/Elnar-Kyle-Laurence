<?php
session_start();

// Database Connection Support (graceful fallback to session storage if db.php is missing or fails)
if (file_exists('db.php')) {
    require_once 'db.php';
}

// Enforce Admin Access Control
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || ($_SESSION['role'] ?? 'admin') !== 'admin') {
    header('Location: login.php');
    exit;
}

// Handle Admin Logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}

// 1. Default Product Catalog (Session Fallback Storage)
$default_shop_products = [
    [
        'id'    => 'grapes-single',
        'name'  => 'Tension Grapes',
        'price' => 3.50,
        'stock' => 120,
        'image' => 'images/tension-double-purple.png',
    ],
    [
        'id'    => 'grapes-6pack',
        'name'  => 'Tension Grapes (6-Pack)',
        'price' => 19.99,
        'stock' => 55,
        'image' => 'images/tension-double-purple.png',
    ],
    [
        'id'    => 'grapes-12pack',
        'name'  => 'Tension Grapes (12-Pack)',
        'price' => 36.99,
        'stock' => 30,
        'image' => 'images/tension-double-purple.png',
    ],
    [
        'id'    => 'apple-single',
        'name'  => 'Tension Apple',
        'price' => 3.50,
        'stock' => 140,
        'image' => 'images/tension-double-red.png',
    ],
    [
        'id'    => 'apple-6pack',
        'name'  => 'Tension Apple (6-Pack)',
        'price' => 19.99,
        'stock' => 40,
        'image' => 'images/tension-double-red.png',
    ],
    [
        'id'    => 'apple-12pack',
        'name'  => 'Tension Apple (12-Pack)',
        'price' => 36.99,
        'stock' => 25,
        'image' => 'images/tension-double-red.png',
    ],
    [
        'id'    => 'lime-single',
        'name'  => 'Tension Lime',
        'price' => 3.50,
        'stock' => 200,
        'image' => 'images/tension-double-lime.png',
    ],
    [
        'id'    => 'lime-6pack',
        'name'  => 'Tension Lime (6-Pack)',
        'price' => 19.99,
        'stock' => 60,
        'image' => 'images/tension-double-lime.png',
    ],
    [
        'id'    => 'lime-12pack',
        'name'  => 'Tension Lime (12-Pack)',
        'price' => 36.99,
        'stock' => 35,
        'image' => 'images/tension-double-lime.png',
    ],
    [
        'id'    => 'variety-12pack',
        'name'  => 'Tension Variety Pack',
        'price' => 38.99,
        'stock' => 50,
        'image' => 'images/tension-cans-collection.png',
    ],
];

// Initialize session inventory state if absent
if (!isset($_SESSION['inventory_products'])) {
    $_SESSION['inventory_products'] = $default_shop_products;
}

$flash_msg = '';
$flash_type = 'success';

// 2. Handle Order Status Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_order_status'])) {
    $order_id   = (int)($_POST['order_id'] ?? 0);
    $new_status = trim($_POST['status'] ?? 'Pending');
    $allowed_statuses = ['Pending', 'Completed', 'Cancelled'];

    if ($order_id > 0 && in_array($new_status, $allowed_statuses, true) && isset($pdo)) {
        try {
            $stmt = $pdo->prepare("UPDATE orders SET status = :status WHERE id = :id");
            $stmt->execute(['status' => $new_status, 'id' => $order_id]);
            $flash_msg = "Order #{$order_id} status updated to '{$new_status}'.";
        } catch (PDOException $e) {
            $flash_msg = "Failed to update order: " . $e->getMessage();
            $flash_type = 'error';
        }
    }
}

// 3. Handle Edit Product Name, Price, Stock
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    $product_id    = trim($_POST['product_id'] ?? '');
    $updated_name  = trim($_POST['name'] ?? '');
    $updated_price = (float)($_POST['price'] ?? 0.00);
    $updated_stock = (int)($_POST['stock'] ?? 0);

    if (!empty($product_id) && !empty($updated_name)) {
        // Update DB if PDO exists
        if (isset($pdo)) {
            try {
                $stmt = $pdo->prepare("UPDATE products SET name = :name, price = :price, stock = :stock WHERE id = :id");
                $stmt->execute([
                    'name'  => $updated_name,
                    'price' => $updated_price,
                    'stock' => $updated_stock,
                    'id'    => $product_id
                ]);
            } catch (Exception $e) {
                // Fallback attempt without stock column
                try {
                    $stmt = $pdo->prepare("UPDATE products SET name = :name, price = :price WHERE id = :id");
                    $stmt->execute(['name' => $updated_name, 'price' => $updated_price, 'id' => $product_id]);
                } catch (Exception $ex) {
                    // Ignore DB failures to allow session update
                }
            }
        }

        // Update Session Data
        $updated = false;
        foreach ($_SESSION['inventory_products'] as &$product) {
            if ((string)$product['id'] === (string)$product_id) {
                $product['name']  = $updated_name;
                $product['price'] = $updated_price;
                $product['stock'] = $updated_stock;
                $updated = true;
                break;
            }
        }
        unset($product);

        $flash_msg = "Product '{$updated_name}' updated successfully.";
    } else {
        $flash_msg = "Failed to update product. Required fields missing.";
        $flash_type = 'error';
    }
}

// 4. Handle Add New Product to Inventory
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $new_name  = trim($_POST['new_name'] ?? $_POST['name'] ?? '');
    $new_price = (float)($_POST['new_price'] ?? $_POST['price'] ?? 0.00);
    $new_stock = (int)($_POST['new_stock'] ?? $_POST['stock'] ?? 0);
    $new_image = trim($_POST['new_image'] ?? $_POST['image'] ?? '');

    if (empty($new_image)) {
        $new_image = 'images/tension-double-lime.png';
    }

    if (!empty($new_name)) {
        $new_id = 'prod-' . time() . '-' . rand(100, 999);

        // Save into DB if available
        if (isset($pdo)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO products (id, name, price, stock, image) VALUES (:id, :name, :price, :stock, :image)");
                $stmt->execute([
                    'id'    => $new_id,
                    'name'  => $new_name,
                    'price' => $new_price,
                    'stock' => $new_stock,
                    'image' => $new_image
                ]);
            } catch (Exception $e) {
                try {
                    $stmt = $pdo->prepare("INSERT INTO products (name, price) VALUES (:name, :price)");
                    $stmt->execute(['name' => $new_name, 'price' => $new_price]);
                } catch (Exception $ex) {
                    // Fall back to session
                }
            }
        }

        // Save into Session catalog
        $_SESSION['inventory_products'][] = [
            'id'    => $new_id,
            'name'  => $new_name,
            'price' => $new_price,
            'stock' => $new_stock,
            'image' => $new_image
        ];

        $flash_msg = "New product '{$new_name}' added to inventory.";
    } else {
        $flash_msg = "Product name cannot be empty.";
        $flash_type = 'error';
    }
}

// 5. Fetch Metrics & Data
$total_revenue    = 0.00;
$total_orders     = 0;
$completed_orders = 0;
$low_stock_count  = 0;
$recent_orders    = [];

if (isset($pdo)) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as total_count, COALESCE(SUM(total_amount), 0) as revenue FROM orders");
        $metrics = $stmt->fetch(PDO::FETCH_ASSOC);
        $total_orders  = (int)($metrics['total_count'] ?? 0);
        $total_revenue = (float)($metrics['revenue'] ?? 0.00);

        $stmt = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'Completed'");
        $completed_orders = (int)$stmt->fetchColumn();

        try {
            $stmt = $pdo->query("SELECT COUNT(*) FROM products WHERE stock < 100");
            $low_stock_count = (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            $low_stock_count = 0;
        }

        $stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 15");
        $recent_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $pdo->query("SELECT * FROM products ORDER BY name ASC");
        $db_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($db_list)) {
            $inventory_list = $db_list;
        } else {
            $inventory_list = $_SESSION['inventory_products'];
        }
    } catch (PDOException $e) {
        $inventory_list = $_SESSION['inventory_products'];
    }
} else {
    $inventory_list = $_SESSION['inventory_products'];
    foreach ($inventory_list as $p) {
        if (($p['stock'] ?? 0) < 100) {
            $low_stock_count++;
        }
    }
}

$nav_links = [
    ['label' => 'Home',   'href' => 'index.php'],
    ['label' => 'Shop',   'href' => 'shop.php'],
    ['label' => 'Cart',   'href' => 'cart.php'],
    ['label' => 'Admin',  'href' => 'admin.php'],
    ['label' => 'Logout', 'href' => 'admin.php?action=logout'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TENSION — Admin & Inventory Management</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --lime: #AFFA01;
            --lime-hover: #9be043;
            --dark-bg: #0d0d0d;
            --card-bg: rgba(22, 22, 22, 0.96);
            --box-bg: #141414;
            --box-border: rgba(255, 255, 255, 0.1);
            --text-main: #ffffff;
            --text-muted: #a0a0a0;
            --danger: #ff4d4d;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-main);
            min-height: 100vh;
        }

        .site-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background: #000000;
            border-bottom: 1px solid var(--box-border);
        }

        .logo-word {
            font-family: 'Anton', sans-serif;
            font-size: 1.8rem;
            color: #ffffff;
            text-decoration: none;
            letter-spacing: 1px;
        }

        .logo-word .accent {
            color: var(--lime);
        }

        .main-nav {
            display: flex;
            gap: 20px;
        }

        .nav-link {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 1px;
            transition: color 0.2s;
        }

        .nav-link:hover {
            color: var(--lime);
        }

        .admin-hero {
            padding: 40px 20px;
            display: flex;
            justify-content: center;
        }

        .admin-card {
            width: 100%;
            max-width: 1200px;
            background: var(--card-bg);
            border: 1px solid var(--box-border);
            border-radius: 16px;
            padding: 36px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.8);
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px dashed rgba(255, 255, 255, 0.15);
            padding-bottom: 20px;
            margin-bottom: 28px;
        }

        .admin-header h1 {
            font-family: 'Anton', sans-serif;
            font-size: 2.2rem;
            color: #ffffff;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .admin-header .accent {
            color: var(--lime);
        }

        .admin-badge {
            background: rgba(175, 250, 1, 0.15);
            border: 1px solid var(--lime);
            color: var(--lime);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .alert-msg {
            padding: 14px 20px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .alert-msg.success {
            background: rgba(175, 250, 1, 0.15);
            border: 1px solid var(--lime);
            color: #ffffff;
        }

        .alert-msg.error {
            background: rgba(255, 77, 77, 0.15);
            border: 1px solid var(--danger);
            color: #ffffff;
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .metric-card {
            background: var(--box-bg);
            border: 1px solid var(--box-border);
            border-radius: 12px;
            padding: 20px;
        }

        .metric-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            margin-bottom: 8px;
            font-weight: 600;
        }

        .metric-value {
            font-family: 'Anton', sans-serif;
            font-size: 2rem;
            color: #ffffff;
        }

        .metric-value.lime {
            color: var(--lime);
        }

        .dashboard-section {
            background: var(--box-bg);
            border: 1px solid var(--box-border);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 32px;
        }

        .section-title {
            font-family: 'Anton', sans-serif;
            font-size: 1.5rem;
            color: var(--lime);
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .add-product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            align-items: end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            font-weight: 600;
        }

        .admin-input {
            background: #1f1f1f;
            border: 1px solid #333333;
            color: #ffffff;
            padding: 10px 12px;
            border-radius: 6px;
            font-size: 0.9rem;
            width: 100%;
        }

        .admin-input:focus {
            outline: none;
            border-color: var(--lime);
        }

        .btn-action {
            background: var(--lime);
            color: #000000;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            font-weight: 700;
            cursor: pointer;
            font-size: 0.85rem;
            text-transform: uppercase;
            transition: background 0.2s, transform 0.1s;
        }

        .btn-action:hover {
            background: var(--lime-hover);
            transform: translateY(-1px);
        }

        .inventory-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }

        .inventory-table th {
            text-align: left;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.75rem;
            color: var(--text-muted);
            padding: 12px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .inventory-table td {
            padding: 14px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            vertical-align: middle;
        }

        .product-thumbnail {
            width: 48px;
            height: 48px;
            object-fit: contain;
            background: #000000;
            border: 1px solid var(--box-border);
            border-radius: 6px;
            padding: 4px;
        }

        .site-footer {
            padding: 24px;
            text-align: center;
            font-size: 0.8rem;
            color: var(--text-muted);
            border-top: 1px solid var(--box-border);
        }
    </style>
</head>
<body>

    <header class="site-header">
        <a href="index.php" class="logo-word"><span class="accent">T</span>ension</a>
        <nav class="main-nav">
            <?php foreach ($nav_links as $link): ?>
                <a href="<?= htmlspecialchars($link['href']) ?>" class="nav-link"><?= htmlspecialchars(strtoupper($link['label'])) ?></a>
            <?php endforeach; ?>
        </nav>
    </header>

    <main class="admin-hero">
        <div class="admin-card">
            
            <div class="admin-header">
                <h1>ADMIN & INVENTORY <span class="accent">MANAGEMENT</span></h1>
                <div>
                    <span class="admin-badge">Authenticated: <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></span>
                    <a href="admin.php?action=logout" class="btn-action" style="background:#333; color:#fff; text-decoration:none; margin-left:10px; padding: 6px 14px; font-size:0.8rem;">Logout</a>
                </div>
            </div>

            <?php if (!empty($flash_msg)): ?>
                <div class="alert-msg <?= $flash_type ?>"><?= htmlspecialchars($flash_msg) ?></div>
            <?php endif; ?>

            <!-- DASHBOARD METRICS -->
            <div class="metrics-grid">
                <div class="metric-card">
                    <div class="metric-label">Total Revenue</div>
                    <div class="metric-value lime">$<?= number_format($total_revenue, 2) ?></div>
                </div>
                <div class="metric-card">
                    <div class="metric-label">Total Orders</div>
                    <div class="metric-value"><?= $total_orders ?></div>
                </div>
                <div class="metric-card">
                    <div class="metric-label">Completed Orders</div>
                    <div class="metric-value"><?= $completed_orders ?></div>
                </div>
                <div class="metric-card">
                    <div class="metric-label">Low Stock Alerts</div>
                    <div class="metric-value"><?= $low_stock_count ?></div>
                </div>
            </div>

            <!-- ORDER MANAGEMENT SECTION -->
            <?php if (isset($pdo)): ?>
            <div class="dashboard-section">
                <div class="section-title">Order Management</div>
                <div style="overflow-x: auto;">
                    <table class="inventory-table">
                        <thead>
                            <tr>
                                <th>Order Ref</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recent_orders)): ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 20px;">No order records found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recent_orders as $o): ?>
                                    <tr>
                                        <td style="font-weight: 600; color: #ffffff;">#<?= htmlspecialchars($o['order_number'] ?? $o['id']) ?></td>
                                        <td><?= date('M j, Y H:i', strtotime($o['created_at'])) ?></td>
                                        <td style="font-weight: 600; color: var(--lime);">$<?= number_format($o['total_amount'], 2) ?></td>
                                        <td><?= htmlspecialchars($o['payment_method'] ?? 'Online') ?></td>
                                        <td><?= htmlspecialchars($o['status']) ?></td>
                                        <td>
                                            <form method="post" action="admin.php" style="display: flex; gap: 8px;">
                                                <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                                                <select name="status" class="admin-input" style="width: auto; padding: 6px;">
                                                    <option value="Completed" <?= $o['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
                                                    <option value="Pending" <?= $o['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                                    <option value="Cancelled" <?= $o['status'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                                </select>
                                                <button type="submit" name="update_order_status" class="btn-action" style="padding: 6px 12px; font-size: 0.75rem;">Save</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <!-- ADD NEW PRODUCT FORM -->
            <div class="dashboard-section">
                <div class="section-title">Add New Product</div>
                <form method="post" action="admin.php">
                    <div class="add-product-grid">
                        <div class="form-group">
                            <label for="new_name">Product Name</label>
                            <input type="text" id="new_name" name="new_name" class="admin-input" placeholder="e.g. Tension Mango" required>
                        </div>
                        <div class="form-group">
                            <label for="new_price">Price ($)</label>
                            <input type="number" step="0.01" id="new_price" name="new_price" class="admin-input" placeholder="3.50" required>
                        </div>
                        <div class="form-group">
                            <label for="new_stock">Initial Stock</label>
                            <input type="number" id="new_stock" name="new_stock" class="admin-input" placeholder="100" value="100" required>
                        </div>
                        <div class="form-group">
                            <label for="new_image">Image Path</label>
                            <input type="text" id="new_image" name="new_image" class="admin-input" placeholder="images/tension-double-lime.png">
                        </div>
                        <div class="form-group">
                            <button type="submit" name="add_product" class="btn-action">+ Add Product</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- INVENTORY MANAGEMENT TABLE -->
            <div class="dashboard-section" style="margin-bottom: 0;">
                <div class="section-title">Products Inventory</div>
                <div style="overflow-x: auto;">
                    <table class="inventory-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Price ($)</th>
                                <th>Stock</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inventory_list as $p): ?>
                                <?php 
                                    $form_id = 'prod-form-' . htmlspecialchars($p['id']); 
                                    $img_src = !empty($p['image']) ? $p['image'] : (!empty($p['image_url']) ? $p['image_url'] : 'images/tension-double-lime.png');
                                ?>
                                <tr>
                                    <!-- Product Image -->
                                    <td>
                                        <img src="<?= htmlspecialchars($img_src) ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="product-thumbnail" onerror="this.src='images/tension-double-lime.png'">
                                    </td>

                                    <!-- Editable Product Name -->
                                    <td>
                                        <input form="<?= $form_id ?>" type="text" name="name" value="<?= htmlspecialchars($p['name']) ?>" class="admin-input" required>
                                    </td>

                                    <!-- Editable Price -->
                                    <td>
                                        <input form="<?= $form_id ?>" type="number" step="0.01" name="price" value="<?= number_format((float)$p['price'], 2, '.', '') ?>" class="admin-input" style="width: 100px;" required>
                                    </td>

                                    <!-- Editable Stock -->
                                    <td>
                                        <input form="<?= $form_id ?>" type="number" name="stock" value="<?= (int)($p['stock'] ?? 0) ?>" class="admin-input" style="width: 90px;" required>
                                    </td>

                                    <!-- Save Action Button -->
                                    <td>
                                        <form id="<?= $form_id ?>" method="post" action="admin.php">
                                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($p['id']) ?>">
                                            <button type="submit" name="update_product" class="btn-action">Save</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <footer class="site-footer">
        &copy; <?= date('Y') ?> — TENSION Energy Drink Company LLC. Admin Panel.
    </footer>

</body>
</html>