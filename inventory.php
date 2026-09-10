<?php
require_once 'db.php';

// Handle Direct Quick-Stock Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_stock_quick'])) {
    $productId = (int)($_POST['product_id'] ?? 0);
    $newStock  = max(0, (int)($_POST['stock_qty'] ?? 0));

    if ($productId > 0 && isset($pdo)) {
        try {
            $stmt = $pdo->prepare("
                UPDATE products
                SET stock = ?
                WHERE id = ?
            ");
            $stmt->execute([$newStock, $productId]);

            if ($stmt->rowCount() === 1) {
                header("Location: dashboard.php?tab=inventory&msg=stock_updated");
                exit;
            }

            $flash_msg = "No product was found with ID " . $productId;
        } catch (PDOException $e) {
            $flash_msg = "Failed to update stock: " . $e->getMessage();
        }
    }
}

// Handle Delete Request
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $productId = (int)$_GET['id'];

    if (isset($pdo)) {
        try {
            $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
            $stmt->execute([$productId]);
            $imgProduct = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($imgProduct && !empty($imgProduct['image']) && file_exists($imgProduct['image'])) {
                @unlink($imgProduct['image']);
            }

            $deleteStmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $deleteStmt->execute([$productId]);

            header("Location: dashboard.php?tab=inventory&msg=deleted");
            exit;
        } catch (PDOException $e) {
            $flash_msg = "Failed to delete product: " . $e->getMessage();
        }
    }
}

// Search and Fetch Products
$search = trim($_GET['search'] ?? '');
$products = [];

if (isset($pdo)) {
    try {
        if ($search !== '') {
            $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ? OR id = ? ORDER BY id DESC");
            $stmt->execute(["%$search%", $search]);
        } else {
            $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
        }
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {}
}

// Calculate Metrics
$total_products = count($products);
$low_stock = 0;
$out_of_stock = 0;

foreach ($products as $p) {
    $stock_qty = (int)($p['stock'] ?? 0);
    if ($stock_qty === 0) {
        $out_of_stock++;
    } elseif ($stock_qty <= 5) {
        $low_stock++;
    }
}
?>

<style>
    body, .dashboard-container, .main-content, .dashboard-wrapper {
        background-image: linear-gradient(rgba(15, 15, 15, 0.94), rgba(15, 15, 15, 0.94)), url('images/background-2.jpg') !important;
        background-size: cover !important;
        background-position: center !important;
        background-repeat: no-repeat !important;
        background-attachment: fixed !important;
    }
</style>

<div class="metrics-grid">
    <div class="metric-card">
        <div class="metric-label">Total Catalog Products</div>
        <div class="metric-value lime"><?= $total_products ?></div>
    </div>
    <div class="metric-card">
        <div class="metric-label">Low Stock (≤ 5)</div>
        <div class="metric-value"><?= $low_stock ?></div>
    </div>
    <div class="metric-card">
        <div class="metric-label">Out of Stock</div>
        <div class="metric-value" style="<?= $out_of_stock > 0 ? 'color: var(--danger);' : '' ?>"><?= $out_of_stock ?></div>
    </div>
</div>

<div class="dashboard-section">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
        <div class="section-title" style="margin-bottom: 0;">Product Catalog</div>
        <a href="dashboard.php?tab=product" class="btn-action" style="text-decoration: none;">+ Add Product</a>
    </div>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'saved'): ?>
        <div style="color: var(--lime); margin-bottom: 15px; font-size: 0.85rem; font-weight: 600;">Product saved successfully.</div>
    <?php endif; ?>
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'stock_updated'): ?>
        <div style="color: var(--lime); margin-bottom: 15px; font-size: 0.85rem; font-weight: 600;">Stock updated successfully without changing other details.</div>
    <?php endif; ?>
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
        <div style="color: var(--danger); margin-bottom: 15px; font-size: 0.85rem; font-weight: 600;">Product deleted successfully.</div>
    <?php endif; ?>

    <form method="GET" action="dashboard.php" style="margin-bottom: 20px;">
        <input type="hidden" name="tab" value="inventory">
        <input type="text" name="search" class="admin-input" placeholder="Search by name or ID..." value="<?= htmlspecialchars($search) ?>" style="width: auto; min-width: 260px;">
    </form>

    <table class="inventory-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>ID</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Status</th>
                <th>Quick Stock Update</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($products)): ?>
                <tr>
                    <td colspan="7">No products found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($products as $item): ?>
                    <?php 
                        $stock_qty = (int)($item['stock'] ?? 0);
                        $is_out_of_stock = ($stock_qty === 0);
                        $is_low_stock = ($stock_qty > 0 && $stock_qty <= 5);
                    ?>
                    <tr>
                        <td>
                            <?php if (!empty($item['image']) && file_exists($item['image'])): ?>
                                <img src="<?= htmlspecialchars($item['image']) ?>" alt="Product" class="product-thumbnail" style="object-fit: cover;">
                            <?php else: ?>
                                <div class="product-thumbnail" style="display:flex; align-items:center; justify-content:center; color: var(--text-muted); font-size: 0.65rem;">NO IMG</div>
                            <?php endif; ?>
                        </td>
                        <td>#<?= $item['id'] ?></td>
                        <td><strong><?= htmlspecialchars($item['name']) ?></strong></td>
                        <td>$<?= number_format((float)$item['price'], 2) ?></td>
                        <td>
                            <?php if ($is_out_of_stock): ?>
                                <span style="background: rgba(255, 77, 77, 0.15); color: var(--danger); border: 1px solid var(--danger); padding: 4px 10px; border-radius: 999px; font-size: 0.72rem; font-weight: 700;">OUT OF STOCK</span>
                            <?php elseif ($is_low_stock): ?>
                                <span style="background: rgba(230, 126, 34, 0.15); color: #e67e22; border: 1px solid #e67e22; padding: 4px 10px; border-radius: 999px; font-size: 0.72rem; font-weight: 700;">LOW STOCK</span>
                            <?php else: ?>
                                <span style="background: rgba(175, 250, 1, 0.15); color: var(--lime); border: 1px solid var(--lime); padding: 4px 10px; border-radius: 999px; font-size: 0.72rem; font-weight: 700;">IN STOCK</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="POST" action="dashboard.php?tab=inventory" style="display: flex; gap: 6px; align-items: center;">
                                <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                <input type="number" name="stock_qty" value="<?= $stock_qty ?>" min="0" class="admin-input" style="width: 70px; padding: 4px 8px;">
                                <button type="submit" name="update_stock_quick" class="btn-action" style="padding: 4px 10px; font-size: 0.75rem;">Save</button>
                            </form>
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="dashboard.php?tab=product&id=<?= $item['id'] ?>" class="btn-action" style="text-decoration: none; padding: 6px 12px;">Edit</a>
                                <a href="dashboard.php?tab=inventory&action=delete&id=<?= $item['id'] ?>" onclick="return confirm('Delete this product?');" class="btn-action" style="background: #333; color: var(--danger); text-decoration: none; padding: 6px 12px;">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>