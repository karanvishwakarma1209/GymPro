<?php
/**
 * GymPro - Login Page
 */
$pageTitle = 'Login';
require_once 'includes/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: ' . SITE_URL . (isAdmin() ? '/admin/dashboard.php' : '/user/dashboard.php'));
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email)) $errors[] = 'Email is required.';
    if (empty($password)) $errors[] = 'Password is required.';
    
    if (empty($errors)) {
        $result = loginUser($email, $password);
        if ($result['success']) {
            $redirect = ($result['role'] === 'admin') ? '/admin/dashboard.php' : '/user/dashboard.php';
            header('Location: ' . SITE_URL . $redirect);
            exit;
        } else {
            $errors[] = $result['message'];
        }
    }
}

require_once 'includes/header.php';
?>

<div class="auth-wrapper">
    <div class="auth-card" data-aos="fade-up">
        <div class="auth-header">
            <a href="<?= SITE_URL ?>/index.php" class="d-inline-block mb-3">
                <i class="fas fa-bolt text-accent fs-2"></i>
            </a>
            <h2>Welcome Back</h2>
            <p>Sign in to access your dashboard</p>
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
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text" style="background:var(--color-bg-input);border:1px solid var(--color-border);color:var(--color-text-muted);border-radius:var(--btn-radius) 0 0 var(--btn-radius);">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input type="email" name="email" class="form-control" placeholder="you@example.com" value="<?= isset($email) ? $email : '' ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label d-flex justify-content-between">
                    Password
                </label>
                <div class="password-toggle">
                    <div class="input-group">
                        <span class="input-group-text" style="background:var(--color-bg-input);border:1px solid var(--color-border);color:var(--color-text-muted);border-radius:var(--btn-radius) 0 0 var(--btn-radius);">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                    </div>
                    <button type="button" class="toggle-btn" style="right:12px;"><i class="fas fa-eye"></i></button>
                </div>
            </div>
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember" style="background-color:var(--color-bg-input);border-color:var(--color-border);">
                    <label class="form-check-label text-muted small" for="remember">Remember me</label>
                </div>
            </div>
            <button type="submit" class="btn btn-accent w-100 btn-lg mb-3">
                <i class="fas fa-sign-in-alt me-2"></i>Sign In
            </button>
        </form>
        
        <div class="text-center mt-3">
            <p class="text-muted mb-0">Don't have an account? <a href="<?= SITE_URL ?>/register.php">Sign Up</a></p>
        </div>
        
        <div class="mt-4 p-3" style="background:var(--color-bg-surface);border-radius:var(--btn-radius);border:1px solid var(--color-border);">
            <p class="text-muted small mb-2"><i class="fas fa-info-circle me-1"></i>Demo Credentials:</p>
            <div class="small text-muted">
                <strong>Admin:</strong> admin@gympro.com / Admin@123<br>
                <strong>User:</strong> john@example.com / User@123
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
