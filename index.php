<?php
/**
 * GymPro - Home Page
 */
$pageTitle = 'Home';
require_once 'includes/functions.php';

$services = getAllServices(true);
$plans = getAllPlans(true);
$trainers = getAllTrainers(true);

require_once 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-bg">
        <div class="hero-bg-pattern"></div>
    </div>
    <div class="hero-float hero-float-1"></div>
    <div class="hero-float hero-float-2"></div>
    
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 hero-content">
                <div class="hero-badge">
                    <span class="pulse-dot"></span>
                    #1 Fitness Center in Mumbai
                </div>
                <h1 class="hero-title">
                    <span class="line">Transform Your</span>
                    <span class="line"><span class="text-gradient">Body</span> & <span class="text-gradient">Mind</span></span>
                </h1>
                <p class="hero-description">
                    Join GymPro and unleash your full potential with world-class trainers, cutting-edge equipment, and a community that pushes you to be your best.
                </p>
                <div class="hero-buttons">
                    <a href="<?= SITE_URL ?>/plans.php" class="btn btn-accent btn-lg">
                        <i class="fas fa-crown me-2"></i>Join Now
                    </a>
                    <a href="<?= SITE_URL ?>/services.php" class="btn btn-accent-outline btn-lg">
                        Explore Services <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <span class="number" data-counter data-target="5000">0</span>
                        <span class="label">Members</span>
                    </div>
                    <div class="hero-stat">
                        <span class="number" data-counter data-target="50" data-suffix="+">0</span>
                        <span class="label">Trainers</span>
                    </div>
                    <div class="hero-stat">
                        <span class="number" data-counter data-target="15">0</span>
                        <span class="label">Years</span>
                    </div>
                    <div class="hero-stat">
                        <span class="number" data-counter data-target="98" data-suffix="%">0</span>
                        <span class="label">Satisfaction</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-flex justify-content-center">
                <div style="width:380px;height:480px;background:var(--color-bg-card);border-radius:30px;border:1px solid var(--color-border);display:flex;align-items:center;justify-content:center;font-size:8rem;color:var(--color-accent);position:relative;overflow:hidden;">
                    <div style="position:absolute;inset:0;background:linear-gradient(135deg,rgba(255,77,77,0.05),rgba(124,58,237,0.05));"></div>
                    <i class="fas fa-dumbbell" style="position:relative;z-index:1;filter:drop-shadow(0 0 30px rgba(255,77,77,0.3));"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="section-padding">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="badge-label">Why GymPro</span>
            <h2>Why <span class="text-gradient">Choose Us</span></h2>
            <p>Experience the difference with our premium facilities and expert guidance</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="gp-card h-100">
                    <div class="card-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h4 class="card-title">Expert Trainers</h4>
                    <p class="card-text">Our certified trainers have years of experience and are dedicated to helping you reach your fitness goals safely and effectively.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="gp-card h-100">
                    <div class="card-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h4 class="card-title">Modern Equipment</h4>
                    <p class="card-text">State-of-the-art machines and equipment from leading brands, maintained daily for your safety and comfort.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="gp-card h-100">
                    <div class="card-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h4 class="card-title">Holistic Wellness</h4>
                    <p class="card-text">Beyond workouts — we offer nutrition counseling, yoga, meditation, and spa services for complete mind-body wellness.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
                <div class="gp-card h-100">
                    <div class="card-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h4 class="card-title">Flexible Hours</h4>
                    <p class="card-text">Open early morning to late night with 24/7 access for premium members. Fit your workout into your schedule, not the other way around.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="500">
                <div class="gp-card h-100">
                    <div class="card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h4 class="card-title">Vibrant Community</h4>
                    <p class="card-text">Join a supportive community of fitness enthusiasts who motivate and inspire each other every single day.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="600">
                <div class="gp-card h-100">
                    <div class="card-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4 class="card-title">Hygienic & Safe</h4>
                    <p class="card-text">Regular sanitization, air purification, and safety protocols to ensure a clean and secure workout environment.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Preview -->
<section class="section-padding" style="background:var(--color-bg-surface);">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="badge-label">What We Offer</span>
            <h2>Our <span class="text-gradient">Services</span></h2>
            <p>Everything you need for a complete fitness transformation under one roof</p>
        </div>
        <div class="row g-4">
            <?php foreach (array_slice($services, 0, 6) as $i => $service): ?>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?= ($i + 1) * 100 ?>">
                <div class="gp-card h-100 text-center">
                    <div class="card-icon mx-auto">
                        <i class="<?= sanitize($service['icon']) ?>"></i>
                    </div>
                    <h4 class="card-title"><?= sanitize($service['name']) ?></h4>
                    <p class="card-text"><?= sanitize($service['description']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5" data-aos="fade-up">
            <a href="<?= SITE_URL ?>/services.php" class="btn btn-accent-outline btn-lg">
                View All Services <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Pricing Preview -->
<section class="section-padding">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="badge-label">Membership</span>
            <h2>Choose Your <span class="text-gradient">Plan</span></h2>
            <p>Flexible plans designed to fit every budget and fitness goal</p>
        </div>
        <div class="row g-4 justify-content-center">
            <?php foreach ($plans as $i => $plan): ?>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?= ($i + 1) * 100 ?>">
                <div class="pricing-card <?= $plan['is_popular'] ? 'popular' : '' ?>">
                    <?php if ($plan['is_popular']): ?>
                        <div class="popular-badge">Most Popular</div>
                    <?php endif; ?>
                    <div class="plan-name"><?= sanitize($plan['name']) ?></div>
                    <div class="plan-price">
                        <span class="amount"><?= CURRENCY_SYMBOL . number_format($plan['price']) ?></span>
                        <span class="period">/ <?= $plan['duration_months'] == 1 ? 'month' : $plan['duration_months'] . ' months' ?></span>
                    </div>
                    <?php if ($plan['original_price']): ?>
                        <div class="original-price"><?= CURRENCY_SYMBOL . number_format($plan['original_price']) ?></div>
                    <?php endif; ?>
                    <p class="text-muted small"><?= sanitize($plan['description']) ?></p>
                    <ul class="plan-features">
                        <?php 
                        $features = json_decode($plan['features'], true) ?? [];
                        foreach ($features as $feature): ?>
                            <li><i class="fas fa-check-circle"></i> <?= sanitize($feature) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="<?= SITE_URL ?>/plans.php" class="btn <?= $plan['is_popular'] ? 'btn-accent' : 'btn-accent-outline' ?> w-100">
                        Get Started
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section-padding text-center" style="background:var(--color-bg-surface);position:relative;overflow:hidden;">
    <div style="position:absolute;inset:0;background:radial-gradient(ellipse at center,rgba(255,77,77,0.08),transparent 70%);"></div>
    <div class="container position-relative">
        <div data-aos="fade-up">
            <span class="badge-label">Start Today</span>
            <h2 class="mb-3" style="font-size:2.5rem;">Ready to <span class="text-gradient">Transform</span> Your Life?</h2>
            <p class="text-muted mb-4 mx-auto" style="max-width:500px;">Join thousands of members who have already transformed their bodies and minds with GymPro.</p>
            <a href="<?= SITE_URL ?>/register.php" class="btn btn-accent btn-lg">
                <i class="fas fa-rocket me-2"></i>Start Your Journey
            </a>
        </div>
    </div>
</section>

<!-- BMI Calculator -->
<section class="section-padding">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <span class="badge-label">Health Tool</span>
                <h2 class="mb-3">BMI <span class="text-gradient">Calculator</span></h2>
                <p class="text-muted mb-4">Calculate your Body Mass Index to understand your health status. BMI is a useful screening tool for weight categories.</p>
                <form id="bmiForm">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Weight (kg)</label>
                            <input type="number" class="form-control" id="bmiWeight" placeholder="e.g. 70" step="0.1" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Height (cm)</label>
                            <input type="number" class="form-control" id="bmiHeight" placeholder="e.g. 175" step="0.1" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-accent w-100">
                                <i class="fas fa-calculator me-2"></i>Calculate BMI
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div id="bmiResult" style="display:none;"></div>
                <div id="bmiDefault" class="bmi-result">
                    <i class="fas fa-weight fa-3x mb-3" style="color:var(--color-accent);opacity:0.3;"></i>
                    <p class="text-muted mb-0">Enter your weight and height to calculate your BMI</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
