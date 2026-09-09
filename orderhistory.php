<?php
// Handle Order Status Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_order_status'])) {
    $order_id   = (int)($_POST['order_id'] ?? 0);
    $new_status = trim($_POST['status'] ?? 'Pending');
    $allowed_statuses = ['Pending', 'Completed', 'Cancelled'];

    if ($order_id > 0 && in_array($new_status, $allowed_statuses, true) && isset($pdo)) {
        try {
            $stmt = $pdo->prepare("UPDATE orders SET status = :status WHERE id = :id");
            $stmt->execute(['status' => $new_status, 'id' => $order_id]);
            $flash_msg = "Order #{$order_id} updated to '{$new_status}'.";
        } catch (PDOException $e) {
            $flash_msg = "Failed to update order: " . $e->getMessage();
        }
    }
}

// Fetch Metrics & Orders
$total_revenue = 0.00;
$total_orders = 0;
$completed_orders = 0;
$recent_orders = [];

if (isset($pdo)) {
    try {
        // Updated to sum revenue only for 'Completed' orders
        $stmt = $pdo->query("
            SELECT 
                COUNT(*) as total_count, 
                COALESCE(SUM(CASE WHEN status = 'Completed' THEN total_amount ELSE 0 END), 0) as revenue 
            FROM orders
        ");
        $metrics = $stmt->fetch(PDO::FETCH_ASSOC);
        $total_orders  = (int)($metrics['total_count'] ?? 0);
        $total_revenue = (float)($metrics['revenue'] ?? 0.00);

        $stmt = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'Completed'");
        $completed_orders = (int)$stmt->fetchColumn();

        // Query fetching customer details and orders
        $query = "
            SELECT 
                o.*,
                COALESCE(u.username, u.name, u.email, o.customer_name, CONCAT('User #', o.user_id), 'Guest') AS customer_identifier
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            ORDER BY o.created_at DESC 
            LIMIT 15
        ";

        try {
            $stmt = $pdo->query($query);
            $recent_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            // Fallback query if relational tables aren't present
            $stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 15");
            $recent_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
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
</div>

<div class="dashboard-section">
    <div class="section-title">Order History & Status Updates</div>
    <table class="inventory-table">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($recent_orders)): ?>
                <tr>
                    <td colspan="6">No recent orders found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($recent_orders as $order): ?>
                    <?php
                        $customer = $order['customer_identifier'] 
                            ?? $order['username'] 
                            ?? $order['user_name'] 
                            ?? $order['email'] 
                            ?? (!empty($order['user_id']) ? 'User #' . $order['user_id'] : 'Guest Customer');
                    ?>
                    <tr>
                        <td>#<?= $order['id'] ?></td>
                        <td><strong><?= htmlspecialchars($customer) ?></strong></td>
                        <td>$<?= number_format((float)$order['total_amount'], 2) ?></td>
                        <td><?= htmlspecialchars($order['status']) ?></td>
                        <td><?= htmlspecialchars($order['created_at']) ?></td>
                        <td>
                            <form method="post" action="dashboard.php?tab=orderhistory" style="display:flex; gap:8px;">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                <select name="status" class="admin-input" style="width: auto;">
                                    <option value="Pending" <?= $order['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="Completed" <?= $order['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
                                    <option value="Cancelled" <?= $order['status'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                </select>
                                <button type="submit" name="update_order_status" class="btn-action">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>