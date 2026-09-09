<?php
$users_list = [];
if (isset($pdo)) {
    try {
        $stmt = $pdo->query("SELECT id, username, email, role, created_at FROM users ORDER BY id DESC");
        $users_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $users_list = [];
    }
}
?>

<div class="metrics-grid">
    <div class="metric-card">
        <div class="metric-label">Current Session User</div>
        <div class="metric-value lime"><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></div>
    </div>
    <div class="metric-card">
        <div class="metric-label">Session Role</div>
        <div class="metric-value"><?= htmlspecialchars($_SESSION['role'] ?? 'admin') ?></div>
    </div>
</div>

<div class="dashboard-section">
    <div class="section-title">Registered Accounts</div>
    <table class="inventory-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username / Email</th>
                <th>Role</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users_list)): ?>
                <tr>
                    <td colspan="4">Active User Session: <strong><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></strong> (Database table empty or not connected)</td>
                </tr>
            <?php else: ?>
                <?php foreach ($users_list as $u): ?>
                    <tr>
                        <td>#<?= htmlspecialchars($u['id']) ?></td>
                        <td><?= htmlspecialchars($u['username'] ?? $u['email']) ?></td>
                        <td><span class="admin-badge"><?= htmlspecialchars($u['role'] ?? 'User') ?></span></td>
                        <td><?= htmlspecialchars($u['created_at'] ?? 'N/A') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>