<?php
/**
 * GymPro - Admin Dashboard
 */
$pageTitle = 'Admin Dashboard';
require_once '../includes/functions.php';
requireLogin();
requireAdmin();

$totalUsers = getTotalUsers();
$activeMembers = getActiveMembers();
$totalRevenue = getTotalRevenue();
$monthlyRevenue = getMonthlyRevenue(6);
$newUsers = getNewUsersPerMonth(6);
$subscriptionTrends = getSubscriptionTrends(6);
$recentPayments = getAllPayments(5);
$contactMessages = getAllContactMessages(5);

// Prepare chart data
$revenueLabels = [];
$revenueData = [];
foreach ($monthlyRevenue as $r) {
    $revenueLabels[] = date('M Y', strtotime($r['month'] . '-01'));
    $revenueData[] = floatval($r['revenue']);
}

$userLabels = [];
$userData = [];
foreach ($newUsers as $u) {
    $userLabels[] = date('M Y', strtotime($u['month'] . '-01'));
    $userData[] = intval($u['new_users']);
}

$subLabels = [];
$subData = [];
foreach ($subscriptionTrends as $s) {
    $subLabels[] = date('M Y', strtotime($s['month'] . '-01'));
    $subData[] = intval($s['subscriptions']);
}

$extraJS = '<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>';

require_once '../includes/header.php';
?>

<!-- Admin Sidebar -->
<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-heading">Main</div>
    <a href="<?= SITE_URL ?>/admin/dashboard.php" class="nav-link active"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
    <a href="<?= SITE_URL ?>/admin/users.php" class="nav-link"><i class="fas fa-users"></i>Users</a>
    <a href="<?= SITE_URL ?>/admin/plans.php" class="nav-link"><i class="fas fa-crown"></i>Plans</a>
    <a href="<?= SITE_URL ?>/admin/payments.php" class="nav-link"><i class="fas fa-credit-card"></i>Payments</a>
    <div class="sidebar-heading">Other</div>
    <a href="<?= SITE_URL ?>/admin/messages.php" class="nav-link"><i class="fas fa-envelope"></i>Messages</a>
    <a href="<?= SITE_URL ?>/index.php" class="nav-link"><i class="fas fa-globe"></i>View Site</a>
    <a href="<?= SITE_URL ?>/logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i>Logout</a>
</aside>

<!-- Admin Content -->
<div class="admin-content">
    <!-- Toggle sidebar on mobile -->
    <button class="btn btn-accent btn-sm d-lg-none mb-3" onclick="document.getElementById('adminSidebar').classList.toggle('show')">
        <i class="fas fa-bars me-1"></i> Menu
    </button>

    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
        <div>
            <h2>Admin <span class="text-gradient">Dashboard</span></h2>
            <p class="text-muted mb-0">Overview of your gym's performance</p>
        </div>
        <div class="text-muted small">
            <i class="fas fa-calendar-alt me-1"></i><?= date('d M Y, h:i A') ?>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon red"><i class="fas fa-users"></i></div>
                <div class="stat-value"><?= $totalUsers ?></div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-user-check"></i></div>
                <div class="stat-value"><?= $activeMembers ?></div>
                <div class="stat-label">Active Members</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon purple"><i class="fas fa-rupee-sign"></i></div>
                <div class="stat-value"><?= CURRENCY_SYMBOL . number_format($totalRevenue) ?></div>
                <div class="stat-label">Total Revenue</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-chart-line"></i></div>
                <div class="stat-value"><?= count($recentPayments) ?></div>
                <div class="stat-label">Recent Payments</div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="dash-card">
                <div class="dash-card-header">
                    <h5><i class="fas fa-chart-bar text-accent me-2"></i>Monthly Revenue</h5>
                </div>
                <div class="dash-card-body">
                    <canvas id="revenueChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="dash-card">
                <div class="dash-card-header">
                    <h5><i class="fas fa-user-plus text-accent me-2"></i>New Users</h5>
                </div>
                <div class="dash-card-body">
                    <canvas id="usersChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="dash-card">
                <div class="dash-card-header">
                    <h5><i class="fas fa-chart-line text-accent me-2"></i>Subscription Trends</h5>
                </div>
                <div class="dash-card-body">
                    <canvas id="subscriptionChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="dash-card">
                <div class="dash-card-header">
                    <h5><i class="fas fa-receipt text-accent me-2"></i>Recent Payments</h5>
                    <a href="<?= SITE_URL ?>/admin/payments.php" class="text-muted small">View All</a>
                </div>
                <div class="dash-card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark-custom mb-0">
                            <thead>
                                <tr><th>User</th><th>Amount</th><th>Status</th><th>Date</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentPayments as $p): ?>
                                <tr>
                                    <td class="fw-bold"><?= sanitize($p['full_name']) ?></td>
                                    <td><?= formatCurrency($p['amount']) ?></td>
                                    <td><span class="badge-status badge-<?= $p['status'] ?>"><?= ucfirst($p['status']) ?></span></td>
                                    <td class="text-muted small"><?= formatDate($p['payment_date'], 'd M') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Messages -->
    <div class="dash-card">
        <div class="dash-card-header">
            <h5><i class="fas fa-envelope text-accent me-2"></i>Recent Messages</h5>
            <a href="<?= SITE_URL ?>/admin/messages.php" class="text-muted small">View All</a>
        </div>
        <div class="dash-card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark-custom mb-0">
                    <thead>
                        <tr><th>Name</th><th>Email</th><th>Subject</th><th>Date</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <?php if (empty($contactMessages)): ?>
                            <tr><td colspan="5" class="text-center text-muted py-3">No messages</td></tr>
                        <?php else: ?>
                            <?php foreach ($contactMessages as $msg): ?>
                            <tr>
                                <td class="fw-bold"><?= sanitize($msg['name']) ?></td>
                                <td class="text-muted"><?= sanitize($msg['email']) ?></td>
                                <td><?= sanitize($msg['subject'] ?? 'No subject') ?></td>
                                <td class="text-muted small"><?= formatDate($msg['created_at'], 'd M Y') ?></td>
                                <td>
                                    <?php if ($msg['is_read']): ?>
                                        <span class="badge-status badge-active">Read</span>
                                    <?php else: ?>
                                        <span class="badge-status badge-pending">New</span>
                                    <?php endif; ?>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#1a1a25',
                titleColor: '#f0f0f5',
                bodyColor: '#9ca3af',
                borderColor: 'rgba(255,255,255,0.1)',
                borderWidth: 1,
                padding: 12,
                cornerRadius: 8,
            }
        },
        scales: {
            x: {
                grid: { color: 'rgba(255,255,255,0.04)' },
                ticks: { color: '#6b7280', font: { size: 11 } }
            },
            y: {
                grid: { color: 'rgba(255,255,255,0.04)' },
                ticks: { color: '#6b7280', font: { size: 11 } }
            }
        }
    };

    // Revenue Chart
    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($revenueLabels) ?>,
            datasets: [{
                label: 'Revenue (₹)',
                data: <?= json_encode($revenueData) ?>,
                backgroundColor: 'rgba(255, 77, 77, 0.6)',
                borderColor: '#ff4d4d',
                borderWidth: 2,
                borderRadius: 8,
                barPercentage: 0.6,
            }]
        },
        options: chartDefaults
    });

    // Users Chart
    new Chart(document.getElementById('usersChart'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($userLabels) ?>,
            datasets: [{
                data: <?= json_encode($userData) ?>,
                backgroundColor: ['#ff4d4d', '#ff8533', '#f59e0b', '#22c55e', '#3b82f6', '#7c3aed'],
                borderColor: '#12121a',
                borderWidth: 3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#9ca3af',
                        padding: 12,
                        font: { size: 11 }
                    }
                }
            }
        }
    });

    // Subscription Trends Chart
    new Chart(document.getElementById('subscriptionChart'), {
        type: 'line',
        data: {
            labels: <?= json_encode($subLabels) ?>,
            datasets: [{
                label: 'Subscriptions',
                data: <?= json_encode($subData) ?>,
                borderColor: '#7c3aed',
                backgroundColor: 'rgba(124, 58, 237, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#7c3aed',
                pointBorderColor: '#12121a',
                pointBorderWidth: 3,
                pointRadius: 5,
            }]
        },
        options: chartDefaults
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>
