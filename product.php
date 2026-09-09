<?php
require_once 'db.php';

$id = $_GET['id'] ?? null;
$product = [
    'name'  => '',
    'price' => '',
    'stock' => '',
    'image' => ''
];
$errors = [];

// 1. Fetch existing item first so existing fields (like image) are never lost during updates
if ($id && isset($pdo)) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([(int)$id]);
        $existingProduct = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($existingProduct) {
            $product = $existingProduct;
        }
    } catch (PDOException $e) {}
}

// 2. Handle Delete Request
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

            header("Location: dashboard.php?tab=product&msg=deleted");
            exit;
        } catch (PDOException $e) {
            $errors[] = "Failed to delete product: " . $e->getMessage();
        }
    }
}

// 3. Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_product'])) {
    $name  = trim($_POST['name'] ?? '');
    $price = trim($_POST['price'] ?? '0');
    $stock = trim($_POST['stock'] ?? '0');
    
    // Retain existing image path if no new file is uploaded
    $imagePath = $product['image'];

    if ($name === '') $errors[] = "Product name is required.";
    if (!is_numeric($price) || $price < 0) $errors[] = "Enter a valid price.";
    if (!is_numeric($stock) || $stock < 0) $errors[] = "Enter a valid stock quantity.";

    // File Upload Handling
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath   = $_FILES['image']['tmp_name'];
        $fileName      = $_FILES['image']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExts   = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (in_array($fileExtension, $allowedExts, true)) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $newFileName = time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '', $fileName);
            $targetPath  = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $targetPath)) {
                // Delete previous image if replaced
                if (!empty($product['image']) && file_exists($product['image']) && $product['image'] !== $targetPath) {
                    @unlink($product['image']);
                }
                $imagePath = $targetPath;
            } else {
                $errors[] = "Failed to upload image.";
            }
        } else {
            $errors[] = "Allowed formats: JPG, JPEG, PNG, WEBP, GIF.";
        }
    }

    if (empty($errors) && isset($pdo)) {
        try {
            if ($id) {
                $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, stock = ?, image = ? WHERE id = ?");
                $stmt->execute([$name, $price, $stock, $imagePath, (int)$id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO products (name, price, stock, image) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $price, $stock, $imagePath]);
            }
            header("Location: dashboard.php?tab=product&msg=saved");
            exit;
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}

// Fetch All Products for table list
$all_products = [];
if (isset($pdo)) {
    try {
        $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
        $all_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {}
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

<!-- Section 1: Product Form -->
<div class="dashboard-section">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div class="section-title" style="margin-bottom: 0;"><?= $id ? 'Edit Product' : 'Add New Product' ?></div>
        <?php if ($id): ?>
            <a href="dashboard.php?tab=product" class="btn-action" style="background: #333; color: #fff; text-decoration: none; padding: 6px 14px;">+ Add New Instead</a>
        <?php endif; ?>
    </div>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'saved'): ?>
        <div style="color: var(--lime); margin-bottom: 15px; font-size: 0.85rem; font-weight: 600;">Product saved successfully.</div>
    <?php endif; ?>
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
        <div style="color: var(--danger); margin-bottom: 15px; font-size: 0.85rem; font-weight: 600;">Product deleted successfully.</div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div style="color: var(--danger); margin-bottom: 20px; font-size: 0.85rem;">
            <?php foreach ($errors as $error): ?>
                <div><?= htmlspecialchars($error) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="dashboard.php?tab=product<?= $id ? '&id=' . $id : '' ?>" enctype="multipart/form-data">
        <div style="display: grid; gap: 16px; max-width: 600px;">
            <div class="form-group">
                <label>Product Name</label>
                <input type="text" name="name" class="admin-input" required value="<?= htmlspecialchars($product['name']) ?>" placeholder="e.g. Wireless Mouse">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label>Price ($)</label>
                    <input type="number" name="price" step="0.01" min="0" class="admin-input" required value="<?= htmlspecialchars($product['price']) ?>" placeholder="0.00">
                </div>

                <div class="form-group">
                    <label>Stock Quantity</label>
                    <input type="number" name="stock" min="0" class="admin-input" required value="<?= htmlspecialchars($product['stock']) ?>" placeholder="0">
                </div>
            </div>

            <div class="form-group">
                <label>Product Image</label>
                <input type="file" name="image" class="admin-input" accept="image/*">
                <?php if (!empty($product['image'])): ?>
                    <div style="margin-top: 10px;">
                        <img src="<?= htmlspecialchars($product['image']) ?>" alt="Preview" class="product-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                    </div>
                <?php endif; ?>
            </div>

            <div style="margin-top: 10px;">
                <button type="submit" name="save_product" class="btn-action"><?= $id ? 'Update Product' : 'Save Product' ?></button>
            </div>
        </div>
    </form>
</div>

<!-- Section 2: All Products Table -->
<div class="dashboard-section">
    <div class="section-title">All Products</div>
    <table class="inventory-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>ID</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Status</th>
                <th>Stock</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($all_products)): ?>
                <tr>
                    <td colspan="7">No products found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($all_products as $item): ?>
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
                        <td><?= $stock_qty ?></td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="dashboard.php?tab=product&id=<?= $item['id'] ?>" class="btn-action" style="text-decoration: none; padding: 6px 12px;">Edit</a>
                                <a href="dashboard.php?tab=product&action=delete&id=<?= $item['id'] ?>" onclick="return confirm('Delete this product?');" class="btn-action" style="background: #333; color: var(--danger); text-decoration: none; padding: 6px 12px;">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>