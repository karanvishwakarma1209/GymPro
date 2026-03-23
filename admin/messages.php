<?php
/**
 * GymPro - Admin: Contact Messages
 */
$pageTitle = 'Messages';
require_once '../includes/functions.php';
requireLogin();
requireAdmin();

$db = getDB();

// Mark as read
if (isset($_GET['read'])) {
    $msgId = intval($_GET['read']);
    $stmt = $db->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?");
    $stmt->execute([$msgId]);
    header('Location: ' . SITE_URL . '/admin/messages.php');
    exit;
}

// Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_msg'])) {
    $msgId = intval($_POST['msg_id']);
    $stmt = $db->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->execute([$msgId]);
    setFlashMessage('success', 'Message deleted.');
    header('Location: ' . SITE_URL . '/admin/messages.php');
    exit;
}

$messages = getAllContactMessages(100);

require_once '../includes/header.php';
?>

<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-heading">Main</div>
    <a href="<?= SITE_URL ?>/admin/dashboard.php" class="nav-link"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
    <a href="<?= SITE_URL ?>/admin/users.php" class="nav-link"><i class="fas fa-users"></i>Users</a>
    <a href="<?= SITE_URL ?>/admin/plans.php" class="nav-link"><i class="fas fa-crown"></i>Plans</a>
    <a href="<?= SITE_URL ?>/admin/payments.php" class="nav-link"><i class="fas fa-credit-card"></i>Payments</a>
    <div class="sidebar-heading">Other</div>
    <a href="<?= SITE_URL ?>/admin/messages.php" class="nav-link active"><i class="fas fa-envelope"></i>Messages</a>
    <a href="<?= SITE_URL ?>/index.php" class="nav-link"><i class="fas fa-globe"></i>View Site</a>
    <a href="<?= SITE_URL ?>/logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i>Logout</a>
</aside>

<div class="admin-content">
    <button class="btn btn-accent btn-sm d-lg-none mb-3" onclick="document.getElementById('adminSidebar').classList.toggle('show')">
        <i class="fas fa-bars me-1"></i> Menu
    </button>

    <div class="mb-4">
        <h2>Contact <span class="text-gradient">Messages</span></h2>
        <p class="text-muted mb-0"><?= count($messages) ?> messages received</p>
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
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($messages)): ?>
                            <tr><td colspan="8" class="text-center text-muted py-4">No messages</td></tr>
                        <?php else: ?>
                            <?php foreach ($messages as $msg): ?>
                            <tr style="<?= !$msg['is_read'] ? 'background:rgba(255,77,77,0.03);' : '' ?>">
                                <td>#<?= $msg['id'] ?></td>
                                <td class="fw-bold"><?= sanitize($msg['name']) ?></td>
                                <td class="text-muted"><?= sanitize($msg['email']) ?></td>
                                <td><?= sanitize($msg['subject'] ?? '-') ?></td>
                                <td class="text-muted small" style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= sanitize($msg['message']) ?></td>
                                <td class="text-muted small"><?= formatDate($msg['created_at'], 'd M Y') ?></td>
                                <td>
                                    <?php if ($msg['is_read']): ?>
                                        <span class="badge-status badge-active">Read</span>
                                    <?php else: ?>
                                        <span class="badge-status badge-pending">New</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <?php if (!$msg['is_read']): ?>
                                        <a href="<?= SITE_URL ?>/admin/messages.php?read=<?= $msg['id'] ?>" class="btn btn-sm btn-accent-outline" style="padding:4px 10px;font-size:0.75rem;" title="Mark as read">
                                            <i class="fas fa-check"></i>
                                        </a>
                                        <?php endif; ?>
                                        <form method="POST" class="d-inline" onsubmit="return confirm('Delete this message?')">
                                            <input type="hidden" name="msg_id" value="<?= $msg['id'] ?>">
                                            <button type="submit" name="delete_msg" class="btn btn-sm" style="padding:4px 10px;font-size:0.75rem;background:rgba(239,68,68,0.1);color:var(--color-danger);border:1px solid rgba(239,68,68,0.2);">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
