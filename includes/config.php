<?php
/**
 * GymPro - Configuration File
 * Edit these settings to match your environment
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'gym_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Application Configuration
define('SITE_NAME', 'GymPro Fitness');
define('SITE_URL', 'http://localhost/Antigravity');
define('SITE_EMAIL', 'info@gympro.com');

// Razorpay Configuration (Test Mode)
define('RAZORPAY_KEY_ID', 'rzp_test_YOUR_KEY_HERE');
define('RAZORPAY_KEY_SECRET', 'YOUR_SECRET_HERE');

// Currency
define('CURRENCY', 'INR');
define('CURRENCY_SYMBOL', '₹');

// Session Configuration
define('SESSION_LIFETIME', 3600); // 1 hour

// Paths
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('INCLUDES_PATH', ROOT_PATH . 'includes' . DIRECTORY_SEPARATOR);
define('ASSETS_PATH', SITE_URL . '/assets/');

// Error Reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('Asia/Kolkata');
