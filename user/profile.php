<?php
/**
 * GymPro - User Profile Page
 */
$pageTitle = 'My Profile';
require_once '../includes/functions.php';
requireLogin();

$user = getCurrentUser();
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'full_name' => sanitize($_POST['full_name'] ?? ''),
        'phone' => sanitize($_POST['phone'] ?? ''),
        'gender' => sanitize($_POST['gender'] ?? ''),
        'date_of_birth' => sanitize($_POST['date_of_birth'] ?? '') ?: null,
        'address' => sanitize($_POST['address'] ?? ''),
        'emergency_contact' => sanitize($_POST['emergency_contact'] ?? ''),
        'height_cm' => floatval($_POST['height_cm'] ?? 0) ?: null,
        'weight_kg' => floatval($_POST['weight_kg'] ?? 0) ?: null,
    ];
    
    if (empty($data['full_name'])) $errors[] = 'Name is required.';
    
    if (empty($errors)) {
        if (updateUserProfile($user['id'], $data)) {
            setFlashMessage('success', 'Profile updated successfully!');
            header('Location: ' . SITE_URL . '/user/profile.php');
            exit;
        } else {
            $errors[] = 'Failed to update profile.';
        }
    }
}

require_once '../includes/header.php';
?>

<div class="dashboard-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="mb-4" data-aos="fade-up">
                    <h2>My <span class="text-gradient">Profile</span></h2>
                    <p class="text-muted">Update your personal information</p>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $e): ?>
                            <div><i class="fas fa-exclamation-circle me-1"></i><?= $e ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <div class="dash-card" data-aos="fade-up">
                    <div class="dash-card-header">
                        <h5><i class="fas fa-user-edit text-accent me-2"></i>Personal Information</h5>
                    </div>
                    <div class="dash-card-body">
                        <form method="POST">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" name="full_name" class="form-control" value="<?= sanitize($user['full_name']) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="<?= sanitize($user['email']) ?>" disabled>
                                    <small class="text-muted">Email cannot be changed</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone</label>
                                    <input type="tel" name="phone" class="form-control" value="<?= sanitize($user['phone'] ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-select">
                                        <option value="">Select</option>
                                        <option value="male" <?= $user['gender'] === 'male' ? 'selected' : '' ?>>Male</option>
                                        <option value="female" <?= $user['gender'] === 'female' ? 'selected' : '' ?>>Female</option>
                                        <option value="other" <?= $user['gender'] === 'other' ? 'selected' : '' ?>>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" name="date_of_birth" class="form-control" value="<?= $user['date_of_birth'] ?? '' ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Emergency Contact</label>
                                    <input type="text" name="emergency_contact" class="form-control" value="<?= sanitize($user['emergency_contact'] ?? '') ?>" placeholder="Name & phone number">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" class="form-control" rows="2"><?= sanitize($user['address'] ?? '') ?></textarea>
                                </div>
                                
                                <div class="col-12">
                                    <hr style="border-color:var(--color-border);">
                                    <h6 class="text-muted mb-3">Body Measurements</h6>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Height (cm)</label>
                                    <input type="number" name="height_cm" class="form-control" value="<?= $user['height_cm'] ?? '' ?>" step="0.1" placeholder="e.g. 175">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Weight (kg)</label>
                                    <input type="number" name="weight_kg" class="form-control" value="<?= $user['weight_kg'] ?? '' ?>" step="0.1" placeholder="e.g. 70">
                                </div>
                                
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-accent">
                                        <i class="fas fa-save me-2"></i>Save Changes
                                    </button>
                                    <a href="<?= SITE_URL ?>/user/dashboard.php" class="btn btn-accent-outline ms-2">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
