<?php
/**
 * GymPro - Register Page
 */
$pageTitle = 'Register';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    header('Location: ' . SITE_URL . '/user/dashboard.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Validation
    if (empty($name) || strlen($name) < 2) $errors[] = 'Full name is required (min 2 characters).';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
    if (empty($password) || strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
    if ($password !== $confirmPassword) $errors[] = 'Passwords do not match.';
    if (!isset($_POST['terms'])) $errors[] = 'You must agree to the terms & conditions.';
    
    if (empty($errors)) {
        $result = registerUser($name, $email, $phone, $password);
        if ($result['success']) {
            setFlashMessage('success', 'Registration successful! Please login to continue.');
            header('Location: ' . SITE_URL . '/login.php');
            exit;
        } else {
            $errors[] = $result['message'];
        }
    }
}

require_once 'includes/header.php';
?>

<div class="auth-wrapper">
    <div class="auth-card" style="max-width:520px;" data-aos="fade-up">
        <div class="auth-header">
            <a href="<?= SITE_URL ?>/index.php" class="d-inline-block mb-3">
                <i class="fas fa-bolt text-accent fs-2"></i>
            </a>
            <h2>Create Account</h2>
            <p>Join GymPro and start your fitness journey</p>
        </div>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger py-2">
                <?php foreach ($errors as $error): ?>
                    <div class="small"><i class="fas fa-exclamation-circle me-1"></i><?= $error ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" placeholder="John Doe" value="<?= isset($name) ? $name : '' ?>" required minlength="2">
            </div>
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="you@example.com" value="<?= isset($email) ? $email : '' ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="tel" name="phone" class="form-control" placeholder="+91 98765 43210" value="<?= isset($phone) ? $phone : '' ?>">
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <div class="password-toggle">
                        <input type="password" name="password" class="form-control" placeholder="Min 6 characters" required minlength="6">
                        <button type="button" class="toggle-btn"><i class="fas fa-eye"></i></button>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Confirm Password</label>
                    <div class="password-toggle">
                        <input type="password" name="confirm_password" class="form-control" placeholder="Re-enter password" required>
                        <button type="button" class="toggle-btn"><i class="fas fa-eye"></i></button>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="terms" id="terms" required style="background-color:var(--color-bg-input);border-color:var(--color-border);">
                    <label class="form-check-label text-muted small" for="terms">
                        I agree to the <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a>
                    </label>
                </div>
            </div>
            <button type="submit" class="btn btn-accent w-100 btn-lg mb-3">
                <i class="fas fa-user-plus me-2"></i>Create Account
            </button>
        </form>
        
        <div class="text-center mt-3">
            <p class="text-muted mb-0">Already have an account? <a href="<?= SITE_URL ?>/login.php">Sign In</a></p>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
