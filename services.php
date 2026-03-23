<?php
/**
 * GymPro - Services Page
 */
$pageTitle = 'Our Services';
require_once 'includes/functions.php';
$services = getAllServices(true);
require_once 'includes/header.php';
?>

<!-- Page Banner -->
<section class="page-banner text-center">
    <div class="container">
        <h1 data-aos="fade-up">Our <span class="text-gradient">Services</span></h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/index.php">Home</a></li>
                <li class="breadcrumb-item active">Services</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Services Grid -->
<section class="section-padding">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="badge-label">What We Offer</span>
            <h2>Comprehensive <span class="text-gradient">Fitness Solutions</span></h2>
            <p>From high-intensity training to peaceful yoga sessions, we have everything you need</p>
        </div>
        <div class="row g-4">
            <?php foreach ($services as $i => $service): ?>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?= ($i + 1) * 100 ?>">
                <div class="gp-card h-100">
                    <div class="card-icon">
                        <i class="<?= sanitize($service['icon']) ?>"></i>
                    </div>
                    <h4 class="card-title"><?= sanitize($service['name']) ?></h4>
                    <p class="card-text"><?= sanitize($service['description']) ?></p>
                    <a href="<?= SITE_URL ?>/plans.php" class="btn btn-sm btn-accent-outline mt-3">
                        Learn More <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Schedule CTA -->
<section class="section-padding text-center" style="background:var(--color-bg-surface);">
    <div class="container" data-aos="fade-up">
        <h2 class="mb-3">Want to Try a <span class="text-gradient">Free Session</span>?</h2>
        <p class="text-muted mb-4 mx-auto" style="max-width:500px;">Get a complimentary trial session with one of our expert trainers. No obligations, just pure fitness.</p>
        <a href="<?= SITE_URL ?>/contact.php" class="btn btn-accent btn-lg">
            <i class="fas fa-calendar-check me-2"></i>Book Free Trial
        </a>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
