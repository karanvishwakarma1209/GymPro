-- ============================================
-- GymPro - Gym Management System Database
-- ============================================

CREATE DATABASE IF NOT EXISTS gym_db;
USE gym_db;

-- ============================================
-- Users Table
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone VARCHAR(20) DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    avatar VARCHAR(255) DEFAULT NULL,
    role ENUM('user', 'admin', 'trainer') DEFAULT 'user',
    gender ENUM('male', 'female', 'other') DEFAULT NULL,
    date_of_birth DATE DEFAULT NULL,
    address TEXT DEFAULT NULL,
    emergency_contact VARCHAR(100) DEFAULT NULL,
    height_cm DECIMAL(5,1) DEFAULT NULL,
    weight_kg DECIMAL(5,1) DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    email_verified TINYINT(1) DEFAULT 0,
    verification_token VARCHAR(255) DEFAULT NULL,
    reset_token VARCHAR(255) DEFAULT NULL,
    reset_token_expiry DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Membership Plans Table
-- ============================================
CREATE TABLE IF NOT EXISTS membership_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    duration_months INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    original_price DECIMAL(10,2) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    features JSON DEFAULT NULL,
    is_popular TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    max_members INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- User Memberships (Subscriptions) Table
-- ============================================
CREATE TABLE IF NOT EXISTS user_memberships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    plan_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status ENUM('active', 'expired', 'cancelled', 'pending') DEFAULT 'pending',
    auto_renew TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (plan_id) REFERENCES membership_plans(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Payments Table
-- ============================================
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    membership_id INT DEFAULT NULL,
    amount DECIMAL(10,2) NOT NULL,
    currency VARCHAR(10) DEFAULT 'INR',
    payment_method ENUM('razorpay', 'stripe', 'cash', 'bank_transfer') DEFAULT 'razorpay',
    transaction_id VARCHAR(255) DEFAULT NULL,
    razorpay_order_id VARCHAR(255) DEFAULT NULL,
    razorpay_payment_id VARCHAR(255) DEFAULT NULL,
    razorpay_signature VARCHAR(255) DEFAULT NULL,
    status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (membership_id) REFERENCES user_memberships(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Attendance Table
-- ============================================
CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    check_in DATETIME NOT NULL,
    check_out DATETIME DEFAULT NULL,
    duration_minutes INT DEFAULT NULL,
    notes VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Trainers Table
-- ============================================
CREATE TABLE IF NOT EXISTS trainers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    name VARCHAR(100) NOT NULL,
    specialization VARCHAR(255) DEFAULT NULL,
    experience_years INT DEFAULT 0,
    bio TEXT DEFAULT NULL,
    certifications TEXT DEFAULT NULL,
    photo VARCHAR(255) DEFAULT NULL,
    hourly_rate DECIMAL(10,2) DEFAULT NULL,
    availability JSON DEFAULT NULL,
    rating DECIMAL(3,2) DEFAULT 0.00,
    is_active TINYINT(1) DEFAULT 1,
    social_instagram VARCHAR(255) DEFAULT NULL,
    social_twitter VARCHAR(255) DEFAULT NULL,
    social_facebook VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Trainer Bookings Table
-- ============================================
CREATE TABLE IF NOT EXISTS trainer_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    trainer_id INT NOT NULL,
    booking_date DATE NOT NULL,
    time_slot VARCHAR(50) NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (trainer_id) REFERENCES trainers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Workout Logs Table
-- ============================================
CREATE TABLE IF NOT EXISTS workout_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    workout_type VARCHAR(100) NOT NULL,
    duration_minutes INT DEFAULT NULL,
    calories_burned INT DEFAULT NULL,
    exercises JSON DEFAULT NULL,
    notes TEXT DEFAULT NULL,
    workout_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Services Table
-- ============================================
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT DEFAULT NULL,
    icon VARCHAR(50) DEFAULT NULL,
    image VARCHAR(255) DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Contact Messages Table
-- ============================================
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    subject VARCHAR(255) DEFAULT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    replied TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Notifications Table
-- ============================================
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'success', 'warning', 'danger') DEFAULT 'info',
    is_read TINYINT(1) DEFAULT 0,
    link VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Site Settings Table
-- ============================================
CREATE TABLE IF NOT EXISTS site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT DEFAULT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Insert Default Data
-- ============================================

-- Admin User (password: Admin@123)
INSERT INTO users (full_name, email, phone, password, role, is_active, email_verified) VALUES
('Admin User', 'admin@gympro.com', '9876543210', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, 1);

-- Demo User (password: User@123)
INSERT INTO users (full_name, email, phone, password, role, gender, is_active, email_verified, height_cm, weight_kg) VALUES
('John Doe', 'john@example.com', '9876543211', '$2y$10$xLSNEME9slLJMsEDxhEM5.dWT5Yno0jGwGe0EIN7jRgNkCfk7Krgu', 'user', 'male', 1, 1, 175.0, 78.5);

-- Membership Plans
INSERT INTO membership_plans (name, slug, duration_months, price, original_price, description, features, is_popular) VALUES
('Monthly Basic', 'monthly-basic', 1, 999.00, 1499.00, 'Perfect for beginners starting their fitness journey', '["Access to gym floor","Basic equipment usage","Locker facility","1 fitness assessment"]', 0),
('Quarterly Pro', 'quarterly-pro', 3, 2499.00, 3999.00, 'Most popular plan for serious fitness enthusiasts', '["Full gym access","All equipment","Personal locker","2 personal training sessions/month","Diet consultation","Group classes"]', 1),
('Yearly Elite', 'yearly-elite', 12, 7999.00, 14999.00, 'Ultimate membership for maximum results', '["Unlimited gym access 24/7","All equipment & classes","Premium locker","4 personal training sessions/month","Monthly diet plan","Spa access","Guest passes (2/month)","Priority booking"]', 0);

-- Trainers
INSERT INTO trainers (name, specialization, experience_years, bio, photo, hourly_rate, rating, social_instagram, social_twitter) VALUES
('Mike Johnson', 'Strength & Conditioning', 8, 'Certified strength coach with 8 years of experience helping clients achieve their peak physical performance. Former competitive powerlifter and NSCA certified.', 'trainer1.jpg', 1500.00, 4.9, '#', '#'),
('Sarah Williams', 'Yoga & Flexibility', 6, 'Internationally certified yoga instructor specializing in Hatha and Vinyasa yoga. Passionate about holistic wellness and mind-body connection.', 'trainer2.jpg', 1200.00, 4.8, '#', '#'),
('David Chen', 'CrossFit & HIIT', 10, 'CrossFit Level 3 certified trainer with a decade of experience. Known for intense and effective workout programming that delivers real results.', 'trainer3.jpg', 1800.00, 4.95, '#', '#'),
('Priya Sharma', 'Nutrition & Weight Loss', 5, 'Certified nutritionist and personal trainer specializing in sustainable weight management. Holds a Masters in Sports Nutrition.', 'trainer4.jpg', 1300.00, 4.7, '#', '#');

-- Services
INSERT INTO services (name, slug, description, icon, sort_order) VALUES
('Personal Training', 'personal-training', 'Get personalized 1-on-1 coaching from our certified trainers to fast-track your fitness goals.', 'fas fa-dumbbell', 1),
('Cardio Zone', 'cardio-zone', 'State-of-the-art cardio equipment including treadmills, ellipticals, rowing machines, and cycling bikes.', 'fas fa-heartbeat', 2),
('Strength Training', 'strength-training', 'Full range of free weights, machines, and strength equipment for building muscle and power.', 'fas fa-weight-hanging', 3),
('Yoga & Meditation', 'yoga-meditation', 'Find your inner peace with our yoga and meditation classes led by certified instructors.', 'fas fa-spa', 4),
('Group Classes', 'group-classes', 'Energizing group fitness classes including Zumba, Aerobics, Spinning, and Boot Camps.', 'fas fa-users', 5),
('Nutrition Counseling', 'nutrition-counseling', 'Expert dietary guidance and customized meal plans to complement your training routine.', 'fas fa-apple-alt', 6);

-- Site Settings
INSERT INTO site_settings (setting_key, setting_value) VALUES
('site_name', 'GymPro Fitness'),
('site_email', 'info@gympro.com'),
('site_phone', '+91 98765 43210'),
('site_address', '123 Fitness Street, Mumbai, Maharashtra 400001, India'),
('razorpay_key_id', 'rzp_test_YOUR_KEY_HERE'),
('razorpay_key_secret', 'YOUR_SECRET_HERE'),
('google_maps_embed', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d241317.14571813285!2d72.7409838!3d19.0825223!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7c6306644edc1%3A0x5da4ed8f8d648c69!2sMumbai%2C+Maharashtra!5e0!3m2!1sen!2sin!4v1234567890'),
('currency', 'INR'),
('currency_symbol', '₹');

-- Sample Attendance Data for demo user (user_id = 2)
INSERT INTO attendance (user_id, check_in, check_out, duration_minutes) VALUES
(2, '2026-03-01 06:30:00', '2026-03-01 08:00:00', 90),
(2, '2026-03-03 07:00:00', '2026-03-03 08:30:00', 90),
(2, '2026-03-05 06:45:00', '2026-03-05 08:15:00', 90),
(2, '2026-03-08 07:15:00', '2026-03-08 08:45:00', 90),
(2, '2026-03-10 06:30:00', '2026-03-10 08:00:00', 90),
(2, '2026-03-12 07:00:00', '2026-03-12 08:30:00', 90),
(2, '2026-03-15 06:30:00', '2026-03-15 08:00:00', 90),
(2, '2026-03-17 07:00:00', '2026-03-17 08:30:00', 90),
(2, '2026-03-19 06:45:00', '2026-03-19 08:15:00', 90),
(2, '2026-03-21 07:00:00', '2026-03-21 08:30:00', 90);

-- Sample Workout Logs
INSERT INTO workout_logs (user_id, workout_type, duration_minutes, calories_burned, workout_date) VALUES
(2, 'Strength Training', 60, 450, '2026-03-01'),
(2, 'Cardio', 45, 380, '2026-03-03'),
(2, 'Yoga', 60, 200, '2026-03-05'),
(2, 'HIIT', 30, 400, '2026-03-08'),
(2, 'Strength Training', 75, 520, '2026-03-10'),
(2, 'Cardio', 50, 420, '2026-03-12'),
(2, 'CrossFit', 45, 480, '2026-03-15'),
(2, 'Strength Training', 60, 460, '2026-03-17'),
(2, 'Yoga', 60, 190, '2026-03-19'),
(2, 'HIIT', 35, 430, '2026-03-21');

-- Sample Payments
INSERT INTO payments (user_id, membership_id, amount, payment_method, transaction_id, status, payment_date) VALUES
(2, NULL, 2499.00, 'razorpay', 'TXN_DEMO_001', 'completed', '2026-01-15 10:30:00'),
(2, NULL, 2499.00, 'razorpay', 'TXN_DEMO_002', 'completed', '2026-03-15 11:00:00');

-- Sample Membership for demo user
INSERT INTO user_memberships (user_id, plan_id, start_date, end_date, status) VALUES
(2, 2, '2026-03-15', '2026-06-15', 'active');

-- Update payment with membership reference
UPDATE payments SET membership_id = 1 WHERE transaction_id = 'TXN_DEMO_002';

-- Sample Notifications
INSERT INTO notifications (user_id, title, message, type) VALUES
(2, 'Welcome to GymPro!', 'Your membership has been activated. Start your fitness journey today!', 'success'),
(2, 'New Class Available', 'Check out our new Yoga Flow class every Wednesday at 6 PM.', 'info'),
(2, 'Payment Received', 'Your payment of ₹2,499 for Quarterly Pro plan has been confirmed.', 'success');
