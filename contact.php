<?php
/**
 * GymPro - Contact Page
 */
$pageTitle = 'Contact Us';
require_once 'includes/functions.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');
    
    // Validation
    if (empty($name)) $errors[] = 'Name is required.';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
    if (empty($message)) $errors[] = 'Message is required.';
    
    if (empty($errors)) {
        if (saveContactMessage($name, $email, $phone, $subject, $message)) {
            $success = true;
        } else {
            $errors[] = 'Something went wrong. Please try again.';
        }
    }
}

require_once 'includes/header.php';
?>

<!-- Page Banner -->
<section class="page-banner text-center">
    <div class="container">
        <h1 data-aos="fade-up">Contact <span class="text-gradient">Us</span></h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/index.php">Home</a></li>
                <li class="breadcrumb-item active">Contact</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Contact Info Cards -->
<section class="section-padding pb-0">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                <div class="contact-card">
                    <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <h5>Our Location</h5>
                    <p>123 Fitness Street,<br>Mumbai 400001</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                <div class="contact-card">
                    <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
                    <h5>Phone</h5>
                    <p>+91 98765 43210<br>+91 98765 43211</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                <div class="contact-card">
                    <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                    <h5>Email</h5>
                    <p>info@gympro.com<br>support@gympro.com</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                <div class="contact-card">
                    <div class="contact-icon"><i class="fas fa-clock"></i></div>
                    <h5>Working Hours</h5>
                    <p>Mon-Sat: 5AM - 10PM<br>Sunday: 6AM - 8PM</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form & Map -->
<section class="section-padding">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <span class="badge-label">Get in Touch</span>
                <h2 class="mb-3">Send Us a <span class="text-gradient">Message</span></h2>
                <p class="text-muted mb-4">Have a question or want to learn more? Fill out the form and we'll get back to you within 24 hours.</p>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>Thank you! Your message has been sent successfully. We'll get back to you soon.
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <div><i class="fas fa-exclamation-circle me-1"></i><?= $error ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" class="needs-validation" novalidate>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Your Name *</label>
                            <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="tel" name="phone" class="form-control" placeholder="+91 98765 43210">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" placeholder="Membership Inquiry">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Message *</label>
                            <textarea name="message" class="form-control" rows="5" placeholder="Tell us how we can help you..." required></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-accent btn-lg w-100">
                                <i class="fas fa-paper-plane me-2"></i>Send Message
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d241317.14571813285!2d72.7409838!3d19.0825223!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7c6306644edc1%3A0x5da4ed8f8d648c69!2sMumbai%2C+Maharashtra!5e0!3m2!1sen!2sin" allowfullscreen loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
