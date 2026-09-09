<?php
ob_start(); // Prevents "Headers already sent" errors across all tab views
session_start();

if (file_exists('db.php')) {
    require_once 'db.php';
}

// Access Control
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || ($_SESSION['role'] ?? 'admin') !== 'admin') {
    header('Location: login.php');
    exit;
}

// Handle Logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}

// Determine Active Tab
$tab = $_GET['tab'] ?? 'inventory';

// Global Data Fetching for Inventory Fallbacks
$inventory_list = [];
if (isset($pdo)) {
    try {
        $stmt = $pdo->query("SELECT * FROM products ORDER BY name ASC");
        $inventory_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {}
}

if (empty($inventory_list) && file_exists('products.json')) {
    $inventory_list = json_decode(file_get_contents('products.json'), true) ?? [];
}

$flash_msg = '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TENSION — Management Dashboard</title>
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
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background-color: var(--dark-bg); color: var(--text-main); min-height: 100vh; }
        .site-header { display: flex; justify-content: space-between; align-items: center; padding: 20px 40px; background: #000000; border-bottom: 1px solid var(--box-border); }
        .logo-word { font-family: 'Anton', sans-serif; font-size: 1.8rem; color: #ffffff; text-decoration: none; }
        .logo-word .accent { color: var(--lime); }
        .main-nav { display: flex; gap: 15px; }
        .nav-link { color: var(--text-muted); text-decoration: none; font-size: 0.85rem; font-weight: 700; padding: 8px 16px; border-radius: 6px; }
        .nav-link:hover, .nav-link.active { color: #000000; background: var(--lime); }
        .admin-hero { padding: 40px 20px; display: flex; justify-content: center; }
        .admin-card { width: 100%; max-width: 1200px; background: var(--card-bg); border: 1px solid var(--box-border); border-radius: 16px; padding: 36px; }
        .admin-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px dashed rgba(255, 255, 255, 0.15); padding-bottom: 20px; margin-bottom: 28px; }
        .admin-header h1 { font-family: 'Anton', sans-serif; font-size: 2.2rem; }
        .admin-badge { background: rgba(175, 250, 1, 0.15); border: 1px solid var(--lime); color: var(--lime); padding: 6px 14px; border-radius: 20px; font-size: 0.85rem; font-weight: 700; }
        .metrics-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 32px; }
        .metric-card { background: var(--box-bg); border: 1px solid var(--box-border); border-radius: 12px; padding: 20px; }
        .metric-label { font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); font-weight: 600; margin-bottom: 8px; }
        .metric-value { font-family: 'Anton', sans-serif; font-size: 2rem; }
        .metric-value.lime { color: var(--lime); }
        .dashboard-section { background: var(--box-bg); border: 1px solid var(--box-border); border-radius: 12px; padding: 24px; margin-bottom: 32px; }
        .section-title { font-family: 'Anton', sans-serif; font-size: 1.5rem; color: var(--lime); margin-bottom: 20px; text-transform: uppercase; }
        .add-product-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; align-items: end; }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-group label { font-size: 0.75rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; }
        .admin-input { background: #1f1f1f; border: 1px solid #333; color: #fff; padding: 10px 12px; border-radius: 6px; }
        .btn-action { background: var(--lime); color: #000; border: none; padding: 10px 18px; border-radius: 6px; font-weight: 700; cursor: pointer; text-transform: uppercase; }
        .inventory-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        .inventory-table th { text-align: left; font-size: 0.75rem; color: var(--text-muted); padding: 12px 16px; border-bottom: 1px solid var(--box-border); }
        .inventory-table td { padding: 14px 16px; border-bottom: 1px solid rgba(255, 255, 255, 0.05); vertical-align: middle; }
        .product-thumbnail { width: 48px; height: 48px; object-fit: contain; background: #000; border: 1px solid var(--box-border); border-radius: 6px; }
    </style>
</head>
<body>

    <header class="site-header">
        <a href="dashboard.php" class="logo-word"><span class="accent">T</span>ension Control</a>
        <nav class="main-nav">
            <a href="dashboard.php?tab=inventory" class="nav-link <?= $tab === 'inventory' ? 'active' : '' ?>">INVENTORY</a>
            <a href="dashboard.php?tab=product" class="nav-link <?= $tab === 'product' ? 'active' : '' ?>">PRODUCTS</a>
            <a href="dashboard.php?tab=user" class="nav-link <?= $tab === 'user' ? 'active' : '' ?>">USERS</a>
            <a href="dashboard.php?tab=orderhistory" class="nav-link <?= $tab === 'orderhistory' ? 'active' : '' ?>">ORDERS</a>
        </nav>
    </header>

    <main class="admin-hero">
        <div class="admin-card">
            <div class="admin-header">
                <h1>MANAGEMENT <span class="accent"><?= htmlspecialchars(strtoupper($tab)) ?></span></h1>
                <div>
                    <span class="admin-badge"><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></span>
                    <a href="dashboard.php?action=logout" class="btn-action" style="background:#333; color:#fff; text-decoration:none; margin-left:10px; padding: 6px 14px;">Logout</a>
                </div>
            </div>

            <?php
            switch ($tab) {
                case 'product':
                    include 'product.php';
                    break;
                case 'user':
                    include 'user.php';
                    break;
                case 'orderhistory':
                    include 'orderhistory.php';
                    break;
                case 'inventory':
                default:
                    include 'inventory.php';
                    break;
            }
            ?>
        </div>
    </main>

</body>
</html>
<?php ob_end_flush(); ?>