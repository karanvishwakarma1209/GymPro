/**
 * GymPro - Main JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {

    // ============================================
    // Initialize AOS (Animate on Scroll)
    // ============================================
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 700,
            easing: 'ease-out-cubic',
            once: true,
            offset: 80,
        });
    }

    // ============================================
    // Navbar Scroll Effect
    // ============================================
    const navbar = document.getElementById('mainNav');
    if (navbar) {
        const handleScroll = () => {
            if (window.scrollY > 60) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        };
        window.addEventListener('scroll', handleScroll, { passive: true });
        handleScroll();
    }

    // ============================================
    // Back to Top Button
    // ============================================
    const backToTop = document.getElementById('backToTop');
    if (backToTop) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 400) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        }, { passive: true });

        backToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // ============================================
    // Close mobile nav on link click
    // ============================================
    const navLinks = document.querySelectorAll('#navbarNav .nav-link');
    const navCollapse = document.getElementById('navbarNav');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (navCollapse.classList.contains('show')) {
                new bootstrap.Collapse(navCollapse).hide();
            }
        });
    });

    // ============================================
    // Password toggle
    // ============================================
    document.querySelectorAll('.toggle-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.closest('.password-toggle').querySelector('input');
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });

    // ============================================
    // Auto-dismiss flash messages
    // ============================================
    const flashMsg = document.querySelector('.flash-message .alert');
    if (flashMsg) {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(flashMsg);
            bsAlert.close();
        }, 5000);
    }

    // ============================================
    // Animated counters
    // ============================================
    function animateCounter(el) {
        const target = parseInt(el.getAttribute('data-target'));
        const duration = 2000;
        const start = 0;
        const startTime = performance.now();

        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const easeOut = 1 - Math.pow(1 - progress, 3);
            const current = Math.floor(start + (target - start) * easeOut);
            el.textContent = current.toLocaleString();
            if (progress < 1) {
                requestAnimationFrame(update);
            } else {
                el.textContent = target.toLocaleString() + (el.dataset.suffix || '');
            }
        }
        requestAnimationFrame(update);
    }

    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                counterObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('[data-counter]').forEach(el => {
        counterObserver.observe(el);
    });

    // ============================================
    // Form Validation Styles
    // ============================================
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // ============================================
    // BMI Calculator
    // ============================================
    const bmiForm = document.getElementById('bmiForm');
    if (bmiForm) {
        bmiForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const weight = parseFloat(document.getElementById('bmiWeight').value);
            const height = parseFloat(document.getElementById('bmiHeight').value);
            
            if (weight > 0 && height > 0) {
                const heightM = height / 100;
                const bmi = (weight / (heightM * heightM)).toFixed(1);
                
                let category, color;
                if (bmi < 18.5) { category = 'Underweight'; color = '#3b82f6'; }
                else if (bmi < 25) { category = 'Normal Weight'; color = '#22c55e'; }
                else if (bmi < 30) { category = 'Overweight'; color = '#f59e0b'; }
                else { category = 'Obese'; color = '#ef4444'; }

                const resultDiv = document.getElementById('bmiResult');
                resultDiv.innerHTML = `
                    <div class="bmi-result">
                        <div class="bmi-value" style="color: ${color}">${bmi}</div>
                        <div class="fs-5 mb-3" style="color: ${color}">${category}</div>
                        <div class="bmi-gauge">
                            <div class="gauge-marker" style="left: ${Math.min(Math.max((bmi - 10) / 30 * 100, 2), 98)}%"></div>
                        </div>
                        <div class="d-flex justify-content-between text-muted small mt-1">
                            <span>Underweight</span>
                            <span>Normal</span>
                            <span>Overweight</span>
                            <span>Obese</span>
                        </div>
                    </div>
                `;
                resultDiv.style.display = 'block';
            }
        });
    }

    // ============================================
    // Smooth scroll for anchor links
    // ============================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

});
