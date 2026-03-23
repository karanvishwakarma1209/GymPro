<?php
/**
 * GymPro - About Us Page
 */
$pageTitle = 'About Us';
require_once 'includes/functions.php';
require_once 'includes/header.php';
?>

<!-- Page Banner -->
<section class="page-banner text-center">
    <div class="container">
        <h1 data-aos="fade-up">About <span class="text-gradient">GymPro</span></h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/index.php">Home</a></li>
                <li class="breadcrumb-item active">About Us</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Our Story -->
<section class="section-padding">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div style="width:100%;height:400px;background:var(--color-bg-card);border-radius:var(--card-radius);border:1px solid var(--color-border);display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden;">
                    <div style="position:absolute;inset:0;background:linear-gradient(135deg,rgba(255,77,77,0.05),rgba(124,58,237,0.05));"></div>
                    <i class="fas fa-dumbbell" style="font-size:6rem;color:var(--color-accent);opacity:0.3;position:relative;z-index:1;"></i>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <span class="badge-label">Our Story</span>
                <h2 class="mb-3">More Than Just a <span class="text-gradient">Gym</span></h2>
                <p class="text-muted mb-3">Founded in 2011, GymPro Fitness has grown from a small local gym into one of Mumbai's premier fitness destinations. Our mission has always been simple: make world-class fitness accessible to everyone.</p>
                <p class="text-muted mb-4">We believe that fitness is not just about physical strength — it's about building mental resilience, confidence, and a lifestyle that fosters overall well-being. Our state-of-the-art facilities, expert trainers, and supportive community create the perfect environment for transformation.</p>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="highlight-box">
                            <div class="hl-icon"><i class="fas fa-bullseye"></i></div>
                            <div>
                                <h5>Our Mission</h5>
                                <p>Empowering lives through fitness</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="highlight-box">
                            <div class="hl-icon"><i class="fas fa-eye"></i></div>
                            <div>
                                <h5>Our Vision</h5>
                                <p>Healthier India, one member at a time</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Counter Stats -->
<section class="section-padding" style="background:var(--color-bg-surface);">
    <div class="container">
        <div class="row g-4">
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="counter-box">
                    <div class="counter-value" data-counter data-target="5000">0</div>
                    <div class="counter-label">Happy Members</div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="200">
                <div class="counter-box">
                    <div class="counter-value" data-counter data-target="50" data-suffix="+">0</div>
                    <div class="counter-label">Expert Trainers</div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="300">
                <div class="counter-box">
                    <div class="counter-value" data-counter data-target="15">0</div>
                    <div class="counter-label">Years Experience</div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="400">
                <div class="counter-box">
                    <div class="counter-value" data-counter data-target="20">0</div>
                    <div class="counter-label">Fitness Programs</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Values -->
<section class="section-padding">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="badge-label">Our Values</span>
            <h2>What We <span class="text-gradient">Stand For</span></h2>
        </div>
        <div class="row g-4">
            <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="highlight-box">
                    <div class="hl-icon"><i class="fas fa-fire"></i></div>
                    <div>
                        <h5>Passion</h5>
                        <p>We are passionate about fitness and it shows in everything we do — from our training programs to our facility maintenance.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="highlight-box">
                    <div class="hl-icon"><i class="fas fa-handshake"></i></div>
                    <div>
                        <h5>Integrity</h5>
                        <p>We believe in honest, transparent relationships with our members. No hidden fees, no gimmicks, just results.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="highlight-box">
                    <div class="hl-icon"><i class="fas fa-lightbulb"></i></div>
                    <div>
                        <h5>Innovation</h5>
                        <p>We continuously evolve our methods, equipment, and programs to stay at the cutting edge of fitness science.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="highlight-box">
                    <div class="hl-icon"><i class="fas fa-users"></i></div>
                    <div>
                        <h5>Community</h5>
                        <p>We foster a welcoming, inclusive environment where every member feels supported on their unique fitness journey.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
