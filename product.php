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

// ------------------- CRUD OPERATIONS ------------------- //

// 1. CREATE PRODUCT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $new_name  = trim($_POST['new_name'] ?? '');
    $new_price = (float)($_POST['new_price'] ?? 0.00);
    $new_stock = (int)($_POST['new_stock'] ?? 0);
    $new_image = resolve_flavor_image($new_name);

    if (isset($_FILES['new_image']) && $_FILES['new_image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp  = $_FILES['new_image']['tmp_name'];
        $file_name = basename($_FILES['new_image']['name']);
        $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
            $upload_dir = 'images/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
            $target_file = $upload_dir . time() . '_' . preg_replace("/[^a-zA-Z0-9\._-]/", "", $file_name);
            if (move_uploaded_file($file_tmp, $target_file)) {
                $new_image = $target_file;
            }
        }
    }

    if (!empty($new_name)) {
        $new_id = 'prod-' . time() . '-' . rand(100, 999);
        if (isset($pdo)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO products (id, name, price, stock, image) VALUES (:id, :name, :price, :stock, :image)");
                $stmt->execute(['id' => $new_id, 'name' => $new_name, 'price' => $new_price, 'stock' => $new_stock, 'image' => $new_image]);
            } catch (Exception $e) {}
        }
        $json_products = get_json_products('products.json');
        $json_products[] = ['id' => $new_id, 'name' => $new_name, 'price' => $new_price, 'stock' => $new_stock, 'image' => $new_image];
        save_json_products('products.json', $json_products);
        $flash_msg = "Product added successfully!";
    }
}

// 2. UPDATE PRODUCT (Reflects instantly to Inventory)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    $product_id    = trim($_POST['product_id'] ?? '');
    $updated_name  = trim($_POST['name'] ?? '');
    $updated_price = (float)($_POST['price'] ?? 0.00);
    $updated_stock = (int)($_POST['stock'] ?? 0);
    
    $json_products = get_json_products('products.json');
    $existing_image = resolve_flavor_image($updated_name);

    foreach ($json_products as $item) {
        if ($item['id'] === $product_id) {
            $existing_image = $item['image'] ?? $existing_image;
            break;
        }
    }

    $file_key = 'edit_image_' . $product_id;
    if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] === UPLOAD_ERR_OK) {
        $file_tmp  = $_FILES[$file_key]['tmp_name'];
        $file_name = basename($_FILES[$file_key]['name']);
        $upload_dir = 'images/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
        $target_file = $upload_dir . time() . '_' . preg_replace("/[^a-zA-Z0-9\._-]/", "", $file_name);
        if (move_uploaded_file($file_tmp, $target_file)) {
            $existing_image = $target_file;
        }
    }

    if (isset($pdo) && !empty($product_id)) {
        try {
            $stmt = $pdo->prepare("UPDATE products SET name = :name, price = :price, stock = :stock, image = :image WHERE id = :id");
            $stmt->execute(['name' => $updated_name, 'price' => $updated_price, 'stock' => $updated_stock, 'image' => $existing_image, 'id' => $product_id]);
        } catch (Exception $e) {}
    }

    foreach ($json_products as &$item) {
        if ($item['id'] === $product_id) {
            $item['name']  = $updated_name;
            $item['price'] = $updated_price;
            $item['stock'] = $updated_stock;
            $item['image'] = $existing_image;
            break;
        }
    }
    save_json_products('products.json', $json_products);
    $flash_msg = "Product updated successfully!";
}

// 3. DELETE PRODUCT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $product_id = trim($_POST['product_id'] ?? '');

    if (!empty($product_id)) {
        if (isset($pdo)) {
            try {
                $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
                $stmt->execute(['id' => $product_id]);
            } catch (Exception $e) {}
        }
        $json_products = get_json_products('products.json');
        $json_products = array_filter($json_products, function($p) use ($product_id) {
            return $p['id'] !== $product_id;
        });
        save_json_products('products.json', $json_products);
        $flash_msg = "Product deleted successfully.";
    }
}

// 4. READ PRODUCTS
$display_products = get_json_products('products.json');
?>

<!-- CREATE: ADD PRODUCT FORM -->
<div class="dashboard-section">
    <div class="section-title">Create Product</div>
    <form method="post" action="dashboard.php?tab=product" enctype="multipart/form-data">
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
                <label for="new_image">Can Image File</label>
                <input type="file" id="new_image" name="new_image" class="admin-input" accept="image/*">
            </div>
            <div class="form-group">
                <button type="submit" name="add_product" class="btn-action">+ Add Product</button>
            </div>
        </div>
    </form>
</div>

<!-- READ, UPDATE & DELETE: CATALOG -->
<div class="dashboard-section">
    <div class="section-title">Product Catalog Management</div>
    <table class="inventory-table">
        <thead>
            <tr>
                <th>Can Image</th>
                <th>Product Name</th>
                <th>Price ($)</th>
                <th>Stock</th>
                <th>Upload New Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($display_products as $p): ?>
                <?php $img_src = resolve_flavor_image($p['name'], $p['image'] ?? ''); ?>
                <tr>
                    <form method="post" action="dashboard.php?tab=product" enctype="multipart/form-data">
                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($p['id']) ?>">
                        <td>
                            <img src="<?= htmlspecialchars($img_src) ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="product-thumbnail" onerror="this.src='images/tension-mango.jpg'">
                        </td>
                        <td>
                            <input type="text" name="name" value="<?= htmlspecialchars($p['name']) ?>" class="admin-input" required>
                        </td>
                        <td>
                            <input type="number" step="0.01" name="price" value="<?= number_format((float)$p['price'], 2, '.', '') ?>" class="admin-input" style="width: 90px;" required>
                        </td>
                        <td>
                            <input type="number" name="stock" value="<?= (int)($p['stock'] ?? 0) ?>" class="admin-input" style="width: 80px;" required>
                        </td>
                        <td>
                            <input type="file" name="edit_image_<?= htmlspecialchars($p['id']) ?>" class="admin-input" accept="image/*" style="width: 170px;">
                        </td>
                        <td>
                            <div style="display: flex; gap: 6px;">
                                <button type="submit" name="update_product" class="btn-action">Save</button>
                    </form>
                                <form method="post" action="dashboard.php?tab=product" onsubmit="return confirm('Delete this product?');">
                                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($p['id']) ?>">
                                    <button type="submit" name="delete_product" class="btn-action" style="background: var(--danger); color: #fff;">Delete</button>
                                </form>
                            </div>
                        </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>