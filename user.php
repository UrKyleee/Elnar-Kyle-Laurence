<?php
require_once 'db.php';

$flash_msg = '';

// 1. Handle Delete User (Delete)
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $userId = (int)$_GET['id'];
    if ($userId > 0 && isset($pdo)) {
        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            header("Location: dashboard.php?tab=user&msg=deleted");
            exit;
        } catch (PDOException $e) {
            $flash_msg = "Failed to delete user: " . $e->getMessage();
        }
    }
}

// 2. Handle Add / Edit User Form Submission (Create & Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_user'])) {
        $userId   = (int)($_POST['user_id'] ?? 0);
        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $phone    = trim($_POST['phone'] ?? '');
        $address  = trim($_POST['address'] ?? '');
        $role     = strtolower(trim($_POST['role'] ?? 'user'));
        $password = trim($_POST['password'] ?? '');

        if ($username !== '' && $email !== '' && isset($pdo)) {
            try {
                if ($userId > 0) {
                    // Update existing user
                    if ($password !== '') {
                        $hashed = password_hash($password, PASSWORD_DEFAULT);
                        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, phone = ?, address = ?, role = ?, password = ? WHERE id = ?");
                        $stmt->execute([$username, $email, $phone, $address, $role, $hashed, $userId]);
                    } else {
                        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, phone = ?, address = ?, role = ? WHERE id = ?");
                        $stmt->execute([$username, $email, $phone, $address, $role, $userId]);
                    }
                    header("Location: dashboard.php?tab=user&msg=updated");
                    exit;
                } else {
                    // Create new user
                    if ($password !== '') {
                        $hashed = password_hash($password, PASSWORD_DEFAULT);
                        $stmt = $pdo->prepare("INSERT INTO users (username, email, phone, address, role, password) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->execute([$username, $email, $phone, $address, $role, $hashed]);
                        header("Location: dashboard.php?tab=user&msg=created");
                        exit;
                    } else {
                        $flash_msg = "Password is required for new accounts.";
                    }
                }
            } catch (PDOException $e) {
                $flash_msg = "Database error: " . $e->getMessage();
            }
        } else {
            $flash_msg = "Username and Email are required.";
        }
    }
}

// 3. Fetch User for Editing
$editUser = null;
if (isset($_GET['edit']) && isset($pdo)) {
    $editId = (int)$_GET['edit'];
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$editId]);
        $editUser = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {}
}

// 4. Fetch Users and Separate into Admins and Regular Users
$admin_list = [];
$user_list = [];

if (isset($pdo)) {
    try {
        $stmt = $pdo->query("SELECT id, username, email, phone, address, role, created_at FROM users ORDER BY id DESC");
        $all_users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($all_users as $u) {
            if (strtolower($u['role'] ?? '') === 'admin') {
                $admin_list[] = $u;
            } else {
                $user_list[] = $u;
            }
        }
    } catch (PDOException $e) {
        $admin_list = [];
        $user_list = [];
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
        <div class="metric-label">Admin Accounts</div>
        <div class="metric-value lime"><?= count($admin_list) ?></div>
    </div>
    <div class="metric-card">
        <div class="metric-label">Regular Users</div>
        <div class="metric-value"><?= count($user_list) ?></div>
    </div>
    <div class="metric-card">
        <div class="metric-label">Current Session</div>
        <div class="metric-value" style="font-size: 1.1rem; line-height: 1.5;">
            <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?>
            <span style="display:block; font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">
                Role: <?= htmlspecialchars($_SESSION['role'] ?? 'admin') ?>
            </span>
        </div>
    </div>
</div>

<!-- Section 1: User Add / Edit Form -->
<div class="dashboard-section">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div class="section-title" style="margin-bottom: 0;"><?= $editUser ? 'Edit Account' : 'Add New Account' ?></div>
        <?php if ($editUser): ?>
            <a href="dashboard.php?tab=user" class="btn-action" style="background: #333; color: #fff; text-decoration: none; padding: 6px 14px;">+ Cancel Edit</a>
        <?php endif; ?>
    </div>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'created'): ?>
        <div style="color: var(--lime); margin-bottom: 15px; font-size: 0.85rem; font-weight: 600;">Account created successfully.</div>
    <?php endif; ?>
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
        <div style="color: var(--lime); margin-bottom: 15px; font-size: 0.85rem; font-weight: 600;">Account updated successfully.</div>
    <?php endif; ?>
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
        <div style="color: var(--danger); margin-bottom: 15px; font-size: 0.85rem; font-weight: 600;">Account deleted successfully.</div>
    <?php endif; ?>
    <?php if (!empty($flash_msg)): ?>
        <div style="color: var(--danger); margin-bottom: 15px; font-size: 0.85rem; font-weight: 600;"><?= htmlspecialchars($flash_msg) ?></div>
    <?php endif; ?>

    <form method="POST" action="dashboard.php?tab=user">
        <input type="hidden" name="user_id" value="<?= $editUser['id'] ?? 0 ?>">
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; max-width: 900px;">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="admin-input" required value="<?= htmlspecialchars($editUser['username'] ?? '') ?>" placeholder="e.g. john_doe">
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="admin-input" required value="<?= htmlspecialchars($editUser['email'] ?? '') ?>" placeholder="e.g. john@example.com">
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" class="admin-input" value="<?= htmlspecialchars($editUser['phone'] ?? '') ?>" placeholder="e.g. +1 555-0199">
            </div>

            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" class="admin-input" value="<?= htmlspecialchars($editUser['address'] ?? '') ?>" placeholder="e.g. 123 Main St, NY">
            </div>

            <div class="form-group">
                <label>Role</label>
                <select name="role" class="admin-input">
                    <option value="user" <?= (($editUser['role'] ?? '') === 'user') ? 'selected' : '' ?>>User</option>
                    <option value="admin" <?= (($editUser['role'] ?? '') === 'admin') ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>

            <div class="form-group">
                <label>Password <?= $editUser ? '<span style="font-size:0.75rem; color:var(--text-muted);">(Leave blank to keep current)</span>' : '' ?></label>
                <input type="password" name="password" class="admin-input" <?= $editUser ? '' : 'required' ?> placeholder="••••••••">
            </div>
        </div>

        <div style="margin-top: 16px;">
            <button type="submit" name="save_user" class="btn-action"><?= $editUser ? 'Update Account' : 'Create Account' ?></button>
        </div>
    </form>
</div>

<!-- Section 2: Administrator Accounts Table -->
<div class="dashboard-section">
    <div class="section-title">Administrator Accounts</div>
    <table class="inventory-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Role</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($admin_list)): ?>
                <tr>
                    <td colspan="8">No administrator accounts found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($admin_list as $a): ?>
                    <tr>
                        <td>#<?= htmlspecialchars($a['id']) ?></td>
                        <td><strong><?= htmlspecialchars($a['username'] ?? 'N/A') ?></strong></td>
                        <td><?= htmlspecialchars($a['email'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($a['phone'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($a['address'] ?? 'N/A') ?></td>
                        <td><span class="admin-badge" style="background: rgba(175, 250, 1, 0.15); color: var(--lime); border: 1px solid var(--lime);">ADMIN</span></td>
                        <td><?= htmlspecialchars($a['created_at'] ?? 'N/A') ?></td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="dashboard.php?tab=user&edit=<?= $a['id'] ?>" class="btn-action" style="text-decoration: none; padding: 6px 12px;">Edit</a>
                                <a href="dashboard.php?tab=user&action=delete&id=<?= $a['id'] ?>" onclick="return confirm('Delete this admin account?');" class="btn-action" style="background: #333; color: var(--danger); text-decoration: none; padding: 6px 12px;">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Section 3: Regular User Accounts Table -->
<div class="dashboard-section">
    <div class="section-title">Regular User Accounts</div>
    <table class="inventory-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Role</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($user_list)): ?>
                <tr>
                    <td colspan="8">No regular user accounts found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($user_list as $u): ?>
                    <tr>
                        <td>#<?= htmlspecialchars($u['id']) ?></td>
                        <td><strong><?= htmlspecialchars($u['username'] ?? 'N/A') ?></strong></td>
                        <td><?= htmlspecialchars($u['email'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($u['phone'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($u['address'] ?? 'N/A') ?></td>
                        <td><span class="admin-badge" style="background: rgba(255, 255, 255, 0.08); color: var(--text-muted); border: 1px solid #444;">USER</span></td>
                        <td><?= htmlspecialchars($u['created_at'] ?? 'N/A') ?></td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="dashboard.php?tab=user&edit=<?= $u['id'] ?>" class="btn-action" style="text-decoration: none; padding: 6px 12px;">Edit</a>
                                <a href="dashboard.php?tab=user&action=delete&id=<?= $u['id'] ?>" onclick="return confirm('Delete this user account?');" class="btn-action" style="background: #333; color: var(--danger); text-decoration: none; padding: 6px 12px;">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>