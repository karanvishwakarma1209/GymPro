<?php
/**
 * GymPro - Admin: View Payments
 */
$pageTitle = 'Payments';
require_once '../includes/functions.php';
requireLogin();
requireAdmin();

$payments = getAllPayments(100);

require_once '../includes/header.php';
?>

<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-heading">Main</div>
    <a href="<?= SITE_URL ?>/admin/dashboard.php" class="nav-link"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
    <a href="<?= SITE_URL ?>/admin/users.php" class="nav-link"><i class="fas fa-users"></i>Users</a>
    <a href="<?= SITE_URL ?>/admin/plans.php" class="nav-link"><i class="fas fa-crown"></i>Plans</a>
    <a href="<?= SITE_URL ?>/admin/payments.php" class="nav-link active"><i class="fas fa-credit-card"></i>Payments</a>
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
            <h2>All <span class="text-gradient">Payments</span></h2>
            <p class="text-muted mb-0"><?= count($payments) ?> payment records</p>
        </div>
    </div>
    
    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <?php
        $completed = array_filter($payments, fn($p) => $p['status'] === 'completed');
        $pending = array_filter($payments, fn($p) => $p['status'] === 'pending');
        $totalAmount = array_sum(array_column($completed, 'amount'));
        ?>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                <div class="stat-value"><?= count($completed) ?></div>
                <div class="stat-label">Completed</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
                <div class="stat-value"><?= count($pending) ?></div>
                <div class="stat-label">Pending</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon purple"><i class="fas fa-rupee-sign"></i></div>
                <div class="stat-value"><?= CURRENCY_SYMBOL . number_format($totalAmount) ?></div>
                <div class="stat-label">Total Revenue</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-receipt"></i></div>
                <div class="stat-value"><?= count($payments) ?></div>
                <div class="stat-label">Total Transactions</div>
            </div>
        </div>
    </div>

    <div class="dash-card">
        <div class="dash-card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark-custom mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Plan</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Transaction ID</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($payments)): ?>
                            <tr><td colspan="8" class="text-center text-muted py-4">No payments found</td></tr>
                        <?php else: ?>
                            <?php foreach ($payments as $p): ?>
                            <tr>
                                <td>#<?= $p['id'] ?></td>
                                <td class="fw-bold"><?= sanitize($p['full_name']) ?></td>
                                <td><?= sanitize($p['plan_name'] ?? 'N/A') ?></td>
                                <td class="fw-bold"><?= formatCurrency($p['amount']) ?></td>
                                <td><?= ucfirst($p['payment_method']) ?></td>
                                <td class="text-muted small"><?= sanitize($p['transaction_id'] ?? '-') ?></td>
                                <td><span class="badge-status badge-<?= $p['status'] ?>"><?= ucfirst($p['status']) ?></span></td>
                                <td class="text-muted small"><?= formatDate($p['payment_date'], 'd M Y h:i A') ?></td>
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
