<?php
/**
 * GymPro - Book a Trainer
 */
$pageTitle = 'Book a Trainer';
require_once '../includes/functions.php';
requireLogin();

$user = getCurrentUser();
$trainers = getAllTrainers(true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trainerId = intval($_POST['trainer_id'] ?? 0);
    $date = sanitize($_POST['booking_date'] ?? '');
    $timeSlot = sanitize($_POST['time_slot'] ?? '');
    $notes = sanitize($_POST['notes'] ?? '');
    
    if ($trainerId && $date && $timeSlot) {
        $result = bookTrainer($user['id'], $trainerId, $date, $timeSlot, $notes);
        setFlashMessage($result['success'] ? 'success' : 'warning', $result['message']);
        header('Location: ' . SITE_URL . '/user/book-trainer.php');
        exit;
    }
}

require_once '../includes/header.php';
?>

<div class="dashboard-wrapper">
    <div class="container">
        <div class="mb-4" data-aos="fade-up">
            <h2>Book a <span class="text-gradient">Trainer</span></h2>
            <p class="text-muted">Schedule a personal training session with our expert trainers</p>
        </div>
        
        <div class="row g-4">
            <?php foreach ($trainers as $i => $trainer): ?>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?= ($i + 1) * 100 ?>">
                <div class="dash-card">
                    <div class="dash-card-body text-center">
                        <div style="width:80px;height:80px;background:var(--color-accent-subtle);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                            <i class="fas fa-user fs-2 text-accent"></i>
                        </div>
                        <h5 class="mb-1"><?= sanitize($trainer['name']) ?></h5>
                        <div class="text-accent small mb-2"><?= sanitize($trainer['specialization']) ?></div>
                        <div class="text-warning small mb-2">
                            <?php for ($s = 1; $s <= 5; $s++): ?>
                                <i class="fas fa-star<?= $s <= round($trainer['rating']) ? '' : '-half-alt' ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <p class="text-muted small mb-3"><?= sanitize($trainer['experience_years']) ?> years experience</p>
                        <p class="fw-bold mb-3"><?= formatCurrency($trainer['hourly_rate']) ?> / session</p>
                        <button class="btn btn-accent-outline btn-sm w-100" data-bs-toggle="modal" data-bs-target="#bookModal<?= $trainer['id'] ?>">
                            <i class="fas fa-calendar-plus me-1"></i>Book Session
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Booking Modal -->
            <div class="modal fade" id="bookModal<?= $trainer['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content" style="background:var(--color-bg-card);border:1px solid var(--color-border);border-radius:var(--card-radius);">
                        <div class="modal-header" style="border-color:var(--color-border);">
                            <h5 class="modal-title">Book <?= sanitize($trainer['name']) ?></h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST">
                            <input type="hidden" name="trainer_id" value="<?= $trainer['id'] ?>">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Date *</label>
                                    <input type="date" name="booking_date" class="form-control" min="<?= date('Y-m-d') ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Time Slot *</label>
                                    <select name="time_slot" class="form-select" required>
                                        <option value="">Select time</option>
                                        <option>6:00 AM - 7:00 AM</option>
                                        <option>7:00 AM - 8:00 AM</option>
                                        <option>8:00 AM - 9:00 AM</option>
                                        <option>9:00 AM - 10:00 AM</option>
                                        <option>10:00 AM - 11:00 AM</option>
                                        <option>4:00 PM - 5:00 PM</option>
                                        <option>5:00 PM - 6:00 PM</option>
                                        <option>6:00 PM - 7:00 PM</option>
                                        <option>7:00 PM - 8:00 PM</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Notes</label>
                                    <textarea name="notes" class="form-control" rows="2" placeholder="Any specific goals or requests..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer" style="border-color:var(--color-border);">
                                <button type="button" class="btn btn-accent-outline" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-accent"><i class="fas fa-check me-1"></i>Confirm Booking</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
