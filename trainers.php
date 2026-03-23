<?php
/**
 * GymPro - Trainers Page
 */
$pageTitle = 'Our Trainers';
require_once 'includes/functions.php';
$trainers = getAllTrainers(true);
require_once 'includes/header.php';
?>

<!-- Page Banner -->
<section class="page-banner text-center">
    <div class="container">
        <h1 data-aos="fade-up">Our <span class="text-gradient">Trainers</span></h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/index.php">Home</a></li>
                <li class="breadcrumb-item active">Trainers</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Trainers Grid -->
<section class="section-padding">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="badge-label">The Team</span>
            <h2>Meet Your <span class="text-gradient">Expert Coaches</span></h2>
            <p>Certified professionals dedicated to helping you achieve your fitness goals</p>
        </div>
        <div class="row g-4">
            <?php foreach ($trainers as $i => $trainer): ?>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="<?= ($i + 1) * 100 ?>">
                <div class="trainer-card">
                    <div class="trainer-img">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="trainer-info">
                        <h4 class="trainer-name"><?= sanitize($trainer['name']) ?></h4>
                        <div class="trainer-spec"><?= sanitize($trainer['specialization']) ?></div>
                        <div class="trainer-rating">
                            <?php for ($s = 1; $s <= 5; $s++): ?>
                                <i class="fas fa-star<?= $s <= round($trainer['rating']) ? '' : '-half-alt' ?>"></i>
                            <?php endfor; ?>
                            <span class="ms-1 text-muted">(<?= $trainer['rating'] ?>)</span>
                        </div>
                        <p class="trainer-bio"><?= sanitize($trainer['bio']) ?></p>
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="trainer-social">
                                <?php if ($trainer['social_instagram']): ?>
                                    <a href="<?= $trainer['social_instagram'] ?>"><i class="fab fa-instagram"></i></a>
                                <?php endif; ?>
                                <?php if ($trainer['social_twitter']): ?>
                                    <a href="<?= $trainer['social_twitter'] ?>"><i class="fab fa-twitter"></i></a>
                                <?php endif; ?>
                            </div>
                            <small class="text-muted"><?= $trainer['experience_years'] ?> yrs exp.</small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Book a Trainer CTA -->
<section class="section-padding text-center" style="background:var(--color-bg-surface);">
    <div class="container" data-aos="fade-up">
        <h2 class="mb-3">Want to <span class="text-gradient">Book a Trainer</span>?</h2>
        <p class="text-muted mb-4 mx-auto" style="max-width:500px;">Get personalized coaching sessions with our expert trainers. Log in to book your preferred time slot.</p>
        <?php if (isLoggedIn()): ?>
            <a href="<?= SITE_URL ?>/user/book-trainer.php" class="btn btn-accent btn-lg">
                <i class="fas fa-calendar-check me-2"></i>Book a Session
            </a>
        <?php else: ?>
            <a href="<?= SITE_URL ?>/login.php" class="btn btn-accent btn-lg">
                <i class="fas fa-sign-in-alt me-2"></i>Login to Book
            </a>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
