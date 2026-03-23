<?php
/**
 * GymPro - Workout Log
 */
$pageTitle = 'Workout Log';
require_once '../includes/functions.php';
requireLogin();

$user = getCurrentUser();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'workout_type' => sanitize($_POST['workout_type'] ?? ''),
        'duration_minutes' => intval($_POST['duration_minutes'] ?? 0),
        'calories_burned' => intval($_POST['calories_burned'] ?? 0),
        'notes' => sanitize($_POST['notes'] ?? ''),
        'workout_date' => sanitize($_POST['workout_date'] ?? date('Y-m-d')),
    ];
    
    if ($data['workout_type'] && $data['duration_minutes'] > 0) {
        addWorkout($user['id'], $data);
        setFlashMessage('success', 'Workout logged successfully!');
        header('Location: ' . SITE_URL . '/user/workouts.php');
        exit;
    }
}

$workouts = getUserWorkouts($user['id'], 30);
$stats = getWorkoutStats($user['id']);

require_once '../includes/header.php';
?>

<div class="dashboard-wrapper">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4" data-aos="fade-up">
            <div>
                <h2>Workout <span class="text-gradient">Log</span></h2>
                <p class="text-muted mb-0">Track your fitness progress</p>
            </div>
            <button class="btn btn-accent btn-sm" data-bs-toggle="modal" data-bs-target="#addWorkoutModal">
                <i class="fas fa-plus me-1"></i> Log Workout
            </button>
        </div>
        
        <!-- Stats -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3" data-aos="fade-up">
                <div class="stat-card">
                    <div class="stat-icon red"><i class="fas fa-fire"></i></div>
                    <div class="stat-value"><?= number_format($stats['total_calories']) ?></div>
                    <div class="stat-label">Total Calories</div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="fas fa-dumbbell"></i></div>
                    <div class="stat-value"><?= $stats['total_workouts'] ?></div>
                    <div class="stat-label">Workouts</div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-card">
                    <div class="stat-icon green"><i class="fas fa-clock"></i></div>
                    <div class="stat-value"><?= round($stats['total_minutes'] / 60, 1) ?>h</div>
                    <div class="stat-label">Total Hours</div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-card">
                    <div class="stat-icon purple"><i class="fas fa-stopwatch"></i></div>
                    <div class="stat-value"><?= round($stats['avg_duration']) ?></div>
                    <div class="stat-label">Avg Mins/Workout</div>
                </div>
            </div>
        </div>

        <!-- Workout Table -->
        <div class="dash-card" data-aos="fade-up">
            <div class="dash-card-header">
                <h5><i class="fas fa-list text-accent me-2"></i>Workout History</h5>
            </div>
            <div class="dash-card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark-custom mb-0">
                        <thead>
                            <tr><th>Date</th><th>Type</th><th>Duration</th><th>Calories</th><th>Notes</th></tr>
                        </thead>
                        <tbody>
                            <?php if (empty($workouts)): ?>
                                <tr><td colspan="5" class="text-center text-muted py-4">No workouts logged yet</td></tr>
                            <?php else: ?>
                                <?php foreach ($workouts as $w): ?>
                                <tr>
                                    <td><?= formatDate($w['workout_date'], 'd M Y') ?></td>
                                    <td><span class="badge-status badge-active"><?= sanitize($w['workout_type']) ?></span></td>
                                    <td><?= $w['duration_minutes'] ?> min</td>
                                    <td><?= $w['calories_burned'] ?> cal</td>
                                    <td class="text-muted small"><?= sanitize($w['notes'] ?? '-') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Workout Modal -->
<div class="modal fade" id="addWorkoutModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background:var(--color-bg-card);border:1px solid var(--color-border);border-radius:var(--card-radius);">
            <div class="modal-header" style="border-color:var(--color-border);">
                <h5 class="modal-title"><i class="fas fa-plus-circle text-accent me-2"></i>Log Workout</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Workout Type *</label>
                        <select name="workout_type" class="form-select" required>
                            <option value="">Select type</option>
                            <option>Strength Training</option>
                            <option>Cardio</option>
                            <option>HIIT</option>
                            <option>CrossFit</option>
                            <option>Yoga</option>
                            <option>Cycling</option>
                            <option>Swimming</option>
                            <option>Running</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label">Duration (min) *</label>
                            <input type="number" name="duration_minutes" class="form-control" required min="1">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Calories Burned</label>
                            <input type="number" name="calories_burned" class="form-control" min="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="workout_date" class="form-control" value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-color:var(--color-border);">
                    <button type="button" class="btn btn-accent-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-accent">
                        <i class="fas fa-save me-1"></i>Save Workout
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
