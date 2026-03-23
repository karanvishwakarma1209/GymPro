<?php
/**
 * GymPro - Admin: Manage Users
 */
$pageTitle = 'Manage Users';
require_once '../includes/functions.php';
requireLogin();
requireAdmin();

// Handle user status toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_user'])) {
    $userId = intval($_POST['user_id']);
    $db = getDB();
    $stmt = $db->prepare("UPDATE users SET is_active = NOT is_active WHERE id = ? AND role != 'admin'");
    $stmt->execute([$userId]);
    setFlashMessage('success', 'User status updated.');
    header('Location: ' . SITE_URL . '/admin/users.php');
    exit;
}

// Handle user deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $userId = intval($_POST['user_id']);
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
    $stmt->execute([$userId]);
    setFlashMessage('success', 'User deleted successfully.');
    header('Location: ' . SITE_URL . '/admin/users.php');
    exit;
}

$users = getAllUsers();

require_once '../includes/header.php';
?>

<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-heading">Main</div>
    <a href="<?= SITE_URL ?>/admin/dashboard.php" class="nav-link"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
    <a href="<?= SITE_URL ?>/admin/users.php" class="nav-link active"><i class="fas fa-users"></i>Users</a>
    <a href="<?= SITE_URL ?>/admin/plans.php" class="nav-link"><i class="fas fa-crown"></i>Plans</a>
    <a href="<?= SITE_URL ?>/admin/payments.php" class="nav-link"><i class="fas fa-credit-card"></i>Payments</a>
    <div class="sidebar-heading">Other</div>
    <a href="<?= SITE_URL ?>/admin/messages.php" class="nav-link"><i class="fas fa-envelope"></i>Messages</a>
    <a href="<?= SITE_URL ?>/index.php" class="nav-link"><i class="fas fa-globe"></i>View Site</a>
    <a href="<?= SITE_URL ?>/logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i>Logout</a>
</aside>

<div class="admin-content">
    <button class="btn btn-accent btn-sm d-lg-none mb-3" onclick="document.getElementById('adminSidebar').classList.toggle('show')">
        <i class="fas fa-bars me-1"></i> Menu
    </button>

    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
        <div>
            <h2>Manage <span class="text-gradient">Users</span></h2>
            <p class="text-muted mb-0"><?= count($users) ?> total users</p>
        </div>
    </div>

    <div class="dash-card">
        <div class="dash-card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark-custom mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                        <tr>
                            <td>#<?= $u['id'] ?></td>
                            <td class="fw-bold"><?= sanitize($u['full_name']) ?></td>
                            <td class="text-muted"><?= sanitize($u['email']) ?></td>
                            <td><?= sanitize($u['phone'] ?? '-') ?></td>
                            <td>
                                <span class="badge-status <?= $u['role'] === 'admin' ? 'badge-active' : 'badge-pending' ?>">
                                    <?= ucfirst($u['role']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($u['is_active']): ?>
                                    <span class="badge-status badge-active">Active</span>
                                <?php else: ?>
                                    <span class="badge-status badge-expired">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-muted small"><?= formatDate($u['created_at'], 'd M Y') ?></td>
                            <td>
                                <?php if ($u['role'] !== 'admin'): ?>
                                <div class="d-flex gap-1">
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                        <button type="submit" name="toggle_user" class="btn btn-sm btn-accent-outline" style="padding:4px 10px;font-size:0.75rem;" title="Toggle status">
                                            <i class="fas fa-<?= $u['is_active'] ? 'ban' : 'check' ?>"></i>
                                        </button>
                                    </form>
                                    <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                        <button type="submit" name="delete_user" class="btn btn-sm" style="padding:4px 10px;font-size:0.75rem;background:rgba(239,68,68,0.1);color:var(--color-danger);border:1px solid rgba(239,68,68,0.2);">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
