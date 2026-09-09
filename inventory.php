<?php
// Shared Helper Functions (Guarded against redeclaration errors)
if (!function_exists('resolve_flavor_image')) {
    function resolve_flavor_image($name, $custom_image = '') {
        if (!empty($custom_image) && file_exists($custom_image)) {
            return $custom_image;
        }
        $lower = strtolower($name);
        if (strpos($lower, 'mango') !== false) return 'images/tension-mango.jpg';
        if (strpos($lower, 'orange') !== false) return 'images/tension-orange.jpg';
        if (strpos($lower, 'pineapple') !== false) return 'images/tension-pineapple.jpg';
        
        return 'images/tension-mango.jpg';
    }
}

if (!function_exists('get_json_products')) {
    function get_json_products($file, $default = []) {
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $data = json_decode($content, true);
            if (is_array($data) && !empty($data)) return $data;
        }
        $default_data = [
            ['id' => 'prod-mango', 'name' => 'Tension Mango', 'price' => 3.50, 'stock' => 120, 'image' => 'images/tension-mango.jpg'],
            ['id' => 'prod-orange', 'name' => 'Tension Orange', 'price' => 3.50, 'stock' => 140, 'image' => 'images/tension-orange.jpg'],
            ['id' => 'prod-pineapple', 'name' => 'Tension Pineapple', 'price' => 3.50, 'stock' => 150, 'image' => 'images/tension-pineapple.jpg'],
        ];
        $initial = !empty($default) ? $default : $default_data;
        file_put_contents($file, json_encode($initial, JSON_PRETTY_PRINT));
        return $initial;
    }
}

if (!function_exists('save_json_products')) {
    function save_json_products($file, $data) {
        file_put_contents($file, json_encode(array_values($data), JSON_PRETTY_PRINT));
    }
}

// 1. CREATE INVENTORY SKU
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_inventory_sku'])) {
    $sku_name  = trim($_POST['sku_name'] ?? '');
    $sku_stock = (int)($_POST['sku_stock'] ?? 0);
    $sku_price = (float)($_POST['sku_price'] ?? 3.50);
    $sku_image = resolve_flavor_image($sku_name);

    if (!empty($sku_name)) {
        $new_id = 'prod-' . time() . '-' . rand(100, 999);
        if (isset($pdo)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO products (id, name, price, stock, image) VALUES (:id, :name, :price, :stock, :image)");
                $stmt->execute(['id' => $new_id, 'name' => $sku_name, 'price' => $sku_price, 'stock' => $sku_stock, 'image' => $sku_image]);
            } catch (PDOException $e) {}
        }
        
        $json_data = get_json_products('products.json');
        $json_data[] = ['id' => $new_id, 'name' => $sku_name, 'price' => $sku_price, 'stock' => $sku_stock, 'image' => $sku_image];
        save_json_products('products.json', $json_data);
        $flash_msg = "New inventory item added!";
    }
}

// 2. UPDATE STOCK LEVEL
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_stock_only'])) {
    $product_id = trim($_POST['product_id'] ?? '');
    $new_stock  = (int)($_POST['stock'] ?? 0);

    if (isset($pdo) && !empty($product_id)) {
        try {
            $stmt = $pdo->prepare("UPDATE products SET stock = :stock WHERE id = :id");
            $stmt->execute(['stock' => $new_stock, 'id' => $product_id]);
            $flash_msg = "Stock updated successfully.";
        } catch (PDOException $e) {
            $flash_msg = "Error updating stock: " . $e->getMessage();
        }
    }
    
    $json_data = get_json_products('products.json');
    foreach ($json_data as &$item) {
        if ($item['id'] === $product_id) {
            $item['stock'] = $new_stock;
            break;
        }
    }
    save_json_products('products.json', $json_data);
    $flash_msg = "Stock updated successfully.";
}

// 3. DELETE INVENTORY ITEM
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_inventory_item'])) {
    $product_id = trim($_POST['product_id'] ?? '');

    if (!empty($product_id)) {
        if (isset($pdo)) {
            try {
                $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
                $stmt->execute(['id' => $product_id]);
            } catch (PDOException $e) {}
        }
        $json_data = get_json_products('products.json');
        $json_data = array_filter($json_data, function($p) use ($product_id) {
            return $p['id'] !== $product_id;
        });
        save_json_products('products.json', $json_data);
        $flash_msg = "Item removed from inventory.";
    }
}

// 4. READ INVENTORY (Directly synchronized with Product Catalog)
$inventory_items = get_json_products('products.json');

$low_stock_items = array_filter($inventory_items, function($item) {
    return ($item['stock'] ?? 0) < 100;
});
?>

<div class="metrics-grid">
    <div class="metric-card">
        <div class="metric-label">Low Stock Count</div>
        <div class="metric-value lime"><?= count($low_stock_items) ?></div>
    </div>
    <div class="metric-card">
        <div class="metric-label">Total SKUs in Stock</div>
        <div class="metric-value"><?= count($inventory_items) ?></div>
    </div>
</div>

<!-- CREATE: ADD NEW INVENTORY SKU -->
<div class="dashboard-section">
    <div class="section-title">Add Stock Item</div>
    <form method="post" action="dashboard.php?tab=inventory">
        <div class="add-product-grid">
            <div class="form-group">
                <label for="sku_name">Flavor Name</label>
                <input type="text" id="sku_name" name="sku_name" class="admin-input" placeholder="e.g. Tension Mango" required>
            </div>
            <div class="form-group">
                <label for="sku_stock">Initial Stock</label>
                <input type="number" id="sku_stock" name="sku_stock" class="admin-input" placeholder="100" value="100" required>
            </div>
            <div class="form-group">
                <label for="sku_price">Price ($)</label>
                <input type="number" step="0.01" id="sku_price" name="sku_price" class="admin-input" value="3.50" required>
            </div>
            <div class="form-group">
                <button type="submit" name="add_inventory_sku" class="btn-action">+ Add SKU</button>
            </div>
        </div>
    </form>
</div>

<!-- READ, UPDATE & DELETE: INVENTORY LIST -->
<div class="dashboard-section">
    <div class="section-title">Stock Inventory Levels</div>
    <div style="overflow-x: auto;">
        <table class="inventory-table">
            <thead>
                <tr>
                    <th>Can Image</th>
                    <th>Product Name</th>
                    <th>Current Stock</th>
                    <th>Stock Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($inventory_items as $p): ?>
                    <?php $img_src = resolve_flavor_image($p['name'], $p['image'] ?? ''); ?>
                    <tr>
                        <td>
                            <img src="<?= htmlspecialchars($img_src) ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="product-thumbnail" onerror="this.src='images/tension-mango.jpg'">
                        </td>
                        <td><strong><?= htmlspecialchars($p['name']) ?></strong></td>
                        <td>
                            <form id="stock-form-<?= htmlspecialchars($p['id']) ?>" method="post" action="dashboard.php?tab=inventory" style="display:inline-block;">
                                <input type="hidden" name="product_id" value="<?= htmlspecialchars($p['id']) ?>">
                                <input type="number" name="stock" value="<?= (int)($p['stock'] ?? 0) ?>" class="admin-input" style="width: 100px;" required>
                            </form>
                        </td>
                        <td>
                            <?php if (($p['stock'] ?? 0) < 100): ?>
                                <span style="color: var(--danger); font-weight: bold;">Low Stock</span>
                            <?php else: ?>
                                <span style="color: var(--lime); font-weight: bold;">In Stock</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <button type="submit" form="stock-form-<?= htmlspecialchars($p['id']) ?>" name="update_stock_only" class="btn-action">Update Stock</button>
                                <form method="post" action="dashboard.php?tab=inventory" onsubmit="return confirm('Remove this SKU from inventory?');">
                                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($p['id']) ?>">
                                    <button type="submit" name="delete_inventory_item" class="btn-action" style="background: var(--danger); color: #fff;">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>