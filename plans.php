<?php
/**
 * GymPro - Membership Plans Page
 */
$pageTitle = 'Membership Plans';
require_once 'includes/functions.php';
$plans = getAllPlans(true);
require_once 'includes/header.php';
?>

<!-- Page Banner -->
<section class="page-banner text-center">
    <div class="container">
        <h1 data-aos="fade-up">Membership <span class="text-gradient">Plans</span></h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/index.php">Home</a></li>
                <li class="breadcrumb-item active">Plans</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Pricing Section -->
<section class="section-padding">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="badge-label">Pricing</span>
            <h2>Find Your Perfect <span class="text-gradient">Plan</span></h2>
            <p>Invest in your health with our affordable, feature-packed membership plans</p>
        </div>
        <div class="row g-4 justify-content-center">
            <?php foreach ($plans as $i => $plan): ?>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?= ($i + 1) * 150 ?>">
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
                    <?php if (isLoggedIn()): ?>
                        <a href="<?= SITE_URL ?>/user/subscribe.php?plan=<?= $plan['id'] ?>" class="btn <?= $plan['is_popular'] ? 'btn-accent' : 'btn-accent-outline' ?> w-100">
                            Subscribe Now
                        </a>
                    <?php else: ?>
                        <a href="<?= SITE_URL ?>/register.php" class="btn <?= $plan['is_popular'] ? 'btn-accent' : 'btn-accent-outline' ?> w-100">
                            Join & Subscribe
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="section-padding" style="background:var(--color-bg-surface);">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="badge-label">FAQ</span>
            <h2>Frequently Asked <span class="text-gradient">Questions</span></h2>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="fade-up">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item" style="background:var(--color-bg-card);border:1px solid var(--color-border);margin-bottom:12px;border-radius:var(--card-radius) !important;overflow:hidden;">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" style="background:var(--color-bg-card);color:var(--color-text-primary);font-weight:600;">
                                Can I switch my plan later?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">Yes! You can upgrade or downgrade your plan at any time. The difference will be adjusted in your next billing cycle.</div>
                        </div>
                    </div>
                    <div class="accordion-item" style="background:var(--color-bg-card);border:1px solid var(--color-border);margin-bottom:12px;border-radius:var(--card-radius) !important;overflow:hidden;">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" style="background:var(--color-bg-card);color:var(--color-text-primary);font-weight:600;">
                                Is there a joining fee?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">No hidden fees! The price shown is the total amount you pay. No joining fee, no hidden charges.</div>
                        </div>
                    </div>
                    <div class="accordion-item" style="background:var(--color-bg-card);border:1px solid var(--color-border);margin-bottom:12px;border-radius:var(--card-radius) !important;overflow:hidden;">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" style="background:var(--color-bg-card);color:var(--color-text-primary);font-weight:600;">
                                Can I freeze my membership?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">Yes, you can freeze your membership for up to 30 days per year. Contact our front desk for assistance.</div>
                        </div>
                    </div>
                    <div class="accordion-item" style="background:var(--color-bg-card);border:1px solid var(--color-border);border-radius:var(--card-radius) !important;overflow:hidden;">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" style="background:var(--color-bg-card);color:var(--color-text-primary);font-weight:600;">
                                What payment methods do you accept?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">We accept all major credit/debit cards, UPI, net banking, and wallets through our secure Razorpay payment gateway.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
