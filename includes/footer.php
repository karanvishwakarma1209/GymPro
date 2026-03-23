    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-top">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-brand">
                            <a href="<?= SITE_URL ?>/index.php" class="d-flex align-items-center mb-3">
                                <i class="fas fa-bolt brand-icon me-2"></i>
                                <span class="brand-text fs-3">Gym<span class="text-accent">Pro</span></span>
                            </a>
                            <p class="text-muted mb-4">Transform your body and mind with our world-class facilities, expert trainers, and supportive community. Your fitness journey starts here.</p>
                            <div class="social-links">
                                <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <h5 class="footer-heading">Quick Links</h5>
                        <ul class="footer-links">
                            <li><a href="<?= SITE_URL ?>/index.php">Home</a></li>
                            <li><a href="<?= SITE_URL ?>/about.php">About Us</a></li>
                            <li><a href="<?= SITE_URL ?>/services.php">Services</a></li>
                            <li><a href="<?= SITE_URL ?>/plans.php">Plans</a></li>
                            <li><a href="<?= SITE_URL ?>/contact.php">Contact</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h5 class="footer-heading">Our Services</h5>
                        <ul class="footer-links">
                            <li><a href="<?= SITE_URL ?>/services.php">Personal Training</a></li>
                            <li><a href="<?= SITE_URL ?>/services.php">Cardio Zone</a></li>
                            <li><a href="<?= SITE_URL ?>/services.php">Strength Training</a></li>
                            <li><a href="<?= SITE_URL ?>/services.php">Yoga & Meditation</a></li>
                            <li><a href="<?= SITE_URL ?>/services.php">Nutrition Counseling</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h5 class="footer-heading">Contact Info</h5>
                        <ul class="footer-contact">
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <span>123 Fitness Street, Mumbai,<br>Maharashtra 400001</span>
                            </li>
                            <li>
                                <i class="fas fa-phone-alt"></i>
                                <span>+91 98765 43210</span>
                            </li>
                            <li>
                                <i class="fas fa-envelope"></i>
                                <span>info@gympro.com</span>
                            </li>
                            <li>
                                <i class="fas fa-clock"></i>
                                <span>Mon-Sat: 5:00 AM - 10:00 PM<br>Sunday: 6:00 AM - 8:00 PM</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="mb-0">&copy; <?= date('Y') ?> GymPro Fitness. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <p class="mb-0">Designed with <i class="fas fa-heart text-accent"></i> for Fitness</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="backToTop" class="btn-back-top" title="Back to top">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation JS -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <!-- Custom JS -->
    <script src="<?= SITE_URL ?>/assets/js/main.js"></script>
    <?php if (isset($extraJS)) echo $extraJS; ?>
</body>
</html>
