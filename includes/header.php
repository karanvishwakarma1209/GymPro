<?php
startSecureSession();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$flash = getFlashMessage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="GymPro Fitness - Premium gym and fitness center. Join today for world-class training, expert coaches, and state-of-the-art facilities.">
    <title><?= isset($pageTitle) ? $pageTitle . ' | ' . SITE_NAME : SITE_NAME ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- AOS Animations -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= SITE_URL ?>/assets/css/style.css" rel="stylesheet">
    <?php if (isset($extraCSS)) echo $extraCSS; ?>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="<?= SITE_URL ?>/index.php">
                <i class="fas fa-bolt brand-icon"></i>
                <span class="brand-text">Gym<span class="text-accent">Pro</span></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'index' ? 'active' : '' ?>" href="<?= SITE_URL ?>/index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'about' ? 'active' : '' ?>" href="<?= SITE_URL ?>/about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'services' ? 'active' : '' ?>" href="<?= SITE_URL ?>/services.php">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'plans' ? 'active' : '' ?>" href="<?= SITE_URL ?>/plans.php">Plans</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'trainers' ? 'active' : '' ?>" href="<?= SITE_URL ?>/trainers.php">Trainers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'contact' ? 'active' : '' ?>" href="<?= SITE_URL ?>/contact.php">Contact</a>
                    </li>
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle user-menu" href="#" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i> <?= sanitize($_SESSION['user_name']) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
                                <?php if (isAdmin()): ?>
                                    <li><a class="dropdown-item" href="<?= SITE_URL ?>/admin/dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Admin Panel</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="<?= SITE_URL ?>/user/dashboard.php"><i class="fas fa-columns me-2"></i>Dashboard</a></li>
                                <li><a class="dropdown-item" href="<?= SITE_URL ?>/user/profile.php"><i class="fas fa-user-edit me-2"></i>Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= SITE_URL ?>/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-accent btn-sm px-3" href="<?= SITE_URL ?>/login.php">
                                <i class="fas fa-sign-in-alt me-1"></i> Login
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <?php if ($flash): ?>
    <div class="flash-message">
        <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show m-0 rounded-0 text-center" role="alert">
            <?= $flash['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main>
