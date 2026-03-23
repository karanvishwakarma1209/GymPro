<?php
/**
 * GymPro - User Dashboard
 */
$pageTitle = 'Dashboard';
require_once '../includes/functions.php';
requireLogin();

$user = getCurrentUser();
$membership = getUserActiveMembership($user['id']);
$attendance = getUserAttendance($user['id'], 10);
$workouts = getUserWorkouts($user['id'], 5);
$workoutStats = getWorkoutStats($user['id']);
$payments = getUserPayments($user['id'], 5);
$notifications = getUserNotifications($user['id'], 5);
$unreadCount = getUnreadNotificationCount($user['id']);

// Calculate BMI
$bmi = null;
$bmiCategory = null;
if ($user['height_cm'] && $user['weight_kg']) {
    $bmi = calculateBMI($user['weight_kg'], $user['height_cm']);
    $bmiCategory = getBMICategory($bmi);
}

// Handle check-in/check-out
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['check_in'])) {
        $result = checkIn($user['id']);
        setFlashMessage($result['success'] ? 'success' : 'warning', $result['message']);
    } elseif (isset($_POST['check_out'])) {
        $result = checkOut($user['id']);
        setFlashMessage($result['success'] ? 'success' : 'warning', $result['message']);
    }
    header('Location: ' . SITE_URL . '/user/dashboard.php');
    exit;
}

$attendanceData = getMonthlyAttendance($user['id'], 6);

require_once '../includes/header.php';
?>

<div class="dashboard-wrapper">
    <div class="container">
        <!-- Welcome Header -->
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4" data-aos="fade-up">
            <div>
                <h2 class="mb-1">Welcome back, <span class="text-gradient"><?= sanitize($user['full_name']) ?></span> 👋</h2>
                <p class="text-muted mb-0">Here's your fitness overview</p>
            </div>
            <div class="d-flex gap-2 mt-2 mt-md-0">
                <form method="POST" class="d-inline">
                    <button type="submit" name="check_in" class="btn btn-accent btn-sm">
                        <i class="fas fa-sign-in-alt me-1"></i> Check In
                    </button>
                </form>
                <form method="POST" class="d-inline">
                    <button type="submit" name="check_out" class="btn btn-accent-outline btn-sm">
                        <i class="fas fa-sign-out-alt me-1"></i> Check Out
                    </button>
                </form>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-card">
                    <div class="stat-icon red"><i class="fas fa-fire"></i></div>
                    <div class="stat-value"><?= number_format($workoutStats['total_calories']) ?></div>
                    <div class="stat-label">Calories Burned</div>
                </div>
            </div>
            <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="fas fa-dumbbell"></i></div>
                    <div class="stat-value"><?= $workoutStats['total_workouts'] ?></div>
                    <div class="stat-label">Total Workouts</div>
                </div>
            </div>
            <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-card">
                    <div class="stat-icon green"><i class="fas fa-clock"></i></div>
                    <div class="stat-value"><?= round($workoutStats['total_minutes'] / 60, 1) ?>h</div>
                    <div class="stat-label">Total Hours</div>
                </div>
            </div>
            <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                <div class="stat-card">
                    <div class="stat-icon purple"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-value"><?= count($attendance) ?></div>
                    <div class="stat-label">Gym Visits</div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Active Membership -->
            <div class="col-lg-8" data-aos="fade-up">
                <div class="dash-card">
                    <div class="dash-card-header">
                        <h5><i class="fas fa-crown text-accent me-2"></i>Active Membership</h5>
                    </div>
                    <div class="dash-card-body">
                        <?php if ($membership): ?>
                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                                <div>
                                    <h4 class="text-gradient mb-1"><?= sanitize($membership['plan_name']) ?></h4>
                                    <span class="badge-status badge-active">Active</span>
                                </div>
                                <div class="text-end">
                                    <div class="text-muted small">Valid until</div>
                                    <div class="fw-bold"><?= formatDate($membership['end_date']) ?></div>
                                </div>
                            </div>
                            <?php
                            $totalDays = (strtotime($membership['end_date']) - strtotime($membership['start_date'])) / 86400;
                            $daysLeft = max(0, (strtotime($membership['end_date']) - time()) / 86400);
                            $progress = $totalDays > 0 ? (($totalDays - $daysLeft) / $totalDays) * 100 : 0;
                            ?>
                            <div class="progress mb-2" style="height:8px;background:var(--color-bg-surface);border-radius:4px;">
                                <div class="progress-bar" role="progressbar" style="width:<?= round($progress) ?>%;background:var(--color-gradient-1);border-radius:4px;"></div>
                            </div>
                            <div class="d-flex justify-content-between text-muted small">
                                <span>Started: <?= formatDate($membership['start_date']) ?></span>
                                <span><?= ceil($daysLeft) ?> days remaining</span>
                            </div>
                            <?php
                            $features = json_decode($membership['features'], true) ?? [];
                            if (!empty($features)):
                            ?>
                            <div class="mt-3 pt-3" style="border-top:1px solid var(--color-border);">
                                <div class="row g-2">
                                    <?php foreach ($features as $feature): ?>
                                        <div class="col-md-6">
                                            <small class="text-muted"><i class="fas fa-check-circle text-success me-1"></i><?= sanitize($feature) ?></small>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-crown fa-3x mb-3" style="color:var(--color-text-muted);opacity:0.3;"></i>
                                <p class="text-muted mb-3">No active membership</p>
                                <a href="<?= SITE_URL ?>/plans.php" class="btn btn-accent btn-sm">View Plans</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- BMI Card -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="dash-card">
                    <div class="dash-card-header">
                        <h5><i class="fas fa-weight text-accent me-2"></i>Your BMI</h5>
                        <a href="<?= SITE_URL ?>/user/profile.php" class="text-muted small">Update</a>
                    </div>
                    <div class="dash-card-body text-center">
                        <?php if ($bmi): ?>
                            <div class="bmi-value fs-1 fw-bold" style="color:var(--color-<?= $bmiCategory['color'] ?>);"><?= $bmi ?></div>
                            <div class="mb-3" style="color:var(--color-<?= $bmiCategory['color'] ?>);"><?= $bmiCategory['category'] ?></div>
                            <div class="bmi-gauge">
                                <div class="gauge-marker" style="left:<?= min(max(($bmi - 10) / 30 * 100, 2), 98) ?>%;"></div>
                            </div>
                            <div class="d-flex justify-content-between text-muted small mt-1">
                                <span>Under</span><span>Normal</span><span>Over</span><span>Obese</span>
                            </div>
                        <?php else: ?>
                            <i class="fas fa-weight fa-3x mb-3" style="opacity:0.2;color:var(--color-accent);"></i>
                            <p class="text-muted small">Add your height & weight in profile to see your BMI.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Attendance History -->
            <div class="col-lg-6" data-aos="fade-up">
                <div class="dash-card">
                    <div class="dash-card-header">
                        <h5><i class="fas fa-calendar-alt text-accent me-2"></i>Recent Attendance</h5>
                    </div>
                    <div class="dash-card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-dark-custom mb-0">
                                <thead>
                                    <tr><th>Date</th><th>Check In</th><th>Check Out</th><th>Duration</th></tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($attendance)): ?>
                                        <tr><td colspan="4" class="text-center text-muted py-4">No attendance records</td></tr>
                                    <?php else: ?>
                                        <?php foreach (array_slice($attendance, 0, 7) as $att): ?>
                                        <tr>
                                            <td><?= formatDate($att['check_in'], 'd M') ?></td>
                                            <td><?= formatDate($att['check_in'], 'h:i A') ?></td>
                                            <td><?= $att['check_out'] ? formatDate($att['check_out'], 'h:i A') : '<span class="badge-status badge-pending">Active</span>' ?></td>
                                            <td><?= $att['duration_minutes'] ? $att['duration_minutes'] . ' min' : '-' ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Workouts -->
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                <div class="dash-card">
                    <div class="dash-card-header">
                        <h5><i class="fas fa-running text-accent me-2"></i>Recent Workouts</h5>
                        <a href="<?= SITE_URL ?>/user/workouts.php" class="text-muted small">View All</a>
                    </div>
                    <div class="dash-card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-dark-custom mb-0">
                                <thead>
                                    <tr><th>Date</th><th>Type</th><th>Duration</th><th>Calories</th></tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($workouts)): ?>
                                        <tr><td colspan="4" class="text-center text-muted py-4">No workout history</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($workouts as $w): ?>
                                        <tr>
                                            <td><?= formatDate($w['workout_date'], 'd M') ?></td>
                                            <td><span class="badge-status badge-active"><?= sanitize($w['workout_type']) ?></span></td>
                                            <td><?= $w['duration_minutes'] ?> min</td>
                                            <td><?= $w['calories_burned'] ?> cal</td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            <div class="col-lg-8" data-aos="fade-up">
                <div class="dash-card">
                    <div class="dash-card-header">
                        <h5><i class="fas fa-receipt text-accent me-2"></i>Payment History</h5>
                    </div>
                    <div class="dash-card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-dark-custom mb-0">
                                <thead>
                                    <tr><th>Date</th><th>Plan</th><th>Amount</th><th>Method</th><th>Status</th></tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($payments)): ?>
                                        <tr><td colspan="5" class="text-center text-muted py-4">No payment history</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($payments as $p): ?>
                                        <tr>
                                            <td><?= formatDate($p['payment_date'], 'd M Y') ?></td>
                                            <td><?= sanitize($p['plan_name'] ?? 'N/A') ?></td>
                                            <td class="fw-bold"><?= formatCurrency($p['amount']) ?></td>
                                            <td><?= ucfirst($p['payment_method']) ?></td>
                                            <td><span class="badge-status badge-<?= $p['status'] ?>"><?= ucfirst($p['status']) ?></span></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="dash-card">
                    <div class="dash-card-header">
                        <h5><i class="fas fa-bell text-accent me-2"></i>Notifications
                            <?php if ($unreadCount > 0): ?>
                                <span class="badge bg-danger ms-1" style="font-size:0.65rem;"><?= $unreadCount ?></span>
                            <?php endif; ?>
                        </h5>
                    </div>
                    <div class="dash-card-body">
                        <?php if (empty($notifications)): ?>
                            <p class="text-muted text-center py-3">No notifications</p>
                        <?php else: ?>
                            <?php foreach ($notifications as $notif): ?>
                                <div class="d-flex gap-3 mb-3 pb-3" style="border-bottom:1px solid var(--color-border);">
                                    <div class="flex-shrink-0">
                                        <div class="stat-icon <?= $notif['type'] === 'success' ? 'green' : ($notif['type'] === 'warning' ? 'orange' : 'blue') ?>" style="width:36px;height:36px;font-size:0.8rem;">
                                            <i class="fas fa-<?= $notif['type'] === 'success' ? 'check' : ($notif['type'] === 'warning' ? 'exclamation' : 'info') ?>"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-bold small"><?= sanitize($notif['title']) ?></div>
                                        <div class="text-muted small"><?= sanitize($notif['message']) ?></div>
                                        <div class="text-muted" style="font-size:0.7rem;"><?= timeAgo($notif['created_at']) ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
