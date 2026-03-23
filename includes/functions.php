<?php
/**
 * GymPro - Helper Functions
 */

require_once __DIR__ . '/db.php';

// ============================================
// Session & Authentication Functions
// ============================================

function startSecureSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function isLoggedIn() {
    startSecureSession();
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    startSecureSession();
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . SITE_URL . '/login.php');
        exit;
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        header('Location: ' . SITE_URL . '/index.php');
        exit;
    }
}

function getCurrentUserId() {
    startSecureSession();
    return $_SESSION['user_id'] ?? null;
}

function getCurrentUser() {
    $userId = getCurrentUserId();
    if (!$userId) return null;
    
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch();
}

// ============================================
// User Functions
// ============================================

function registerUser($name, $email, $phone, $password) {
    $db = getDB();
    
    // Check if email exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        return ['success' => false, 'message' => 'Email is already registered.'];
    }
    
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $token = bin2hex(random_bytes(32));
    
    $stmt = $db->prepare("INSERT INTO users (full_name, email, phone, password, verification_token) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $phone, $hashedPassword, $token]);
    
    $userId = $db->lastInsertId();
    
    // Create welcome notification
    createNotification($userId, 'Welcome to GymPro!', 'Thanks for joining GymPro Fitness. Explore our membership plans to get started!', 'success');
    
    return ['success' => true, 'message' => 'Registration successful!', 'user_id' => $userId];
}

function loginUser($email, $password) {
    $db = getDB();
    
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if (!$user || !password_verify($password, $user['password'])) {
        return ['success' => false, 'message' => 'Invalid email or password.'];
    }
    
    startSecureSession();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['full_name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];
    
    return ['success' => true, 'message' => 'Login successful!', 'role' => $user['role']];
}

function logoutUser() {
    startSecureSession();
    session_unset();
    session_destroy();
}

function getAllUsers($limit = null, $offset = 0) {
    $db = getDB();
    $sql = "SELECT * FROM users ORDER BY created_at DESC";
    if ($limit) {
        $sql .= " LIMIT ? OFFSET ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$limit, $offset]);
    } else {
        $stmt = $db->query($sql);
    }
    return $stmt->fetchAll();
}

function getUserById($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function updateUserProfile($userId, $data) {
    $db = getDB();
    $stmt = $db->prepare("UPDATE users SET full_name = ?, phone = ?, gender = ?, date_of_birth = ?, address = ?, emergency_contact = ?, height_cm = ?, weight_kg = ? WHERE id = ?");
    return $stmt->execute([
        $data['full_name'], $data['phone'], $data['gender'],
        $data['date_of_birth'], $data['address'], $data['emergency_contact'],
        $data['height_cm'], $data['weight_kg'], $userId
    ]);
}

function getTotalUsers() {
    $db = getDB();
    $stmt = $db->query("SELECT COUNT(*) as total FROM users WHERE role = 'user'");
    return $stmt->fetch()['total'];
}

function getActiveMembers() {
    $db = getDB();
    $stmt = $db->query("SELECT COUNT(DISTINCT user_id) as total FROM user_memberships WHERE status = 'active' AND end_date >= CURDATE()");
    return $stmt->fetch()['total'];
}

// ============================================
// Membership Functions
// ============================================

function getAllPlans($activeOnly = false) {
    $db = getDB();
    $sql = "SELECT * FROM membership_plans";
    if ($activeOnly) $sql .= " WHERE is_active = 1";
    $sql .= " ORDER BY price ASC";
    $stmt = $db->query($sql);
    return $stmt->fetchAll();
}

function getPlanById($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM membership_plans WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getUserActiveMembership($userId) {
    $db = getDB();
    $stmt = $db->prepare("
        SELECT um.*, mp.name as plan_name, mp.duration_months, mp.features 
        FROM user_memberships um 
        JOIN membership_plans mp ON um.plan_id = mp.id 
        WHERE um.user_id = ? AND um.status = 'active' AND um.end_date >= CURDATE()
        ORDER BY um.end_date DESC LIMIT 1
    ");
    $stmt->execute([$userId]);
    return $stmt->fetch();
}

function createMembership($userId, $planId, $startDate = null) {
    $db = getDB();
    $plan = getPlanById($planId);
    if (!$plan) return false;
    
    if (!$startDate) $startDate = date('Y-m-d');
    $endDate = date('Y-m-d', strtotime($startDate . " + {$plan['duration_months']} months"));
    
    $stmt = $db->prepare("INSERT INTO user_memberships (user_id, plan_id, start_date, end_date, status) VALUES (?, ?, ?, ?, 'active')");
    $stmt->execute([$userId, $planId, $startDate, $endDate]);
    
    return $db->lastInsertId();
}

// ============================================
// Payment Functions
// ============================================

function createPayment($userId, $membershipId, $amount, $method = 'razorpay') {
    $db = getDB();
    $txnId = 'TXN_' . strtoupper(bin2hex(random_bytes(8)));
    
    $stmt = $db->prepare("INSERT INTO payments (user_id, membership_id, amount, payment_method, transaction_id, status) VALUES (?, ?, ?, ?, ?, 'pending')");
    $stmt->execute([$userId, $membershipId, $amount, $method, $txnId]);
    
    return ['id' => $db->lastInsertId(), 'transaction_id' => $txnId];
}

function updatePaymentStatus($paymentId, $status, $razorpayData = []) {
    $db = getDB();
    $sql = "UPDATE payments SET status = ?";
    $params = [$status];
    
    if (!empty($razorpayData['razorpay_payment_id'])) {
        $sql .= ", razorpay_payment_id = ?, razorpay_order_id = ?, razorpay_signature = ?";
        $params[] = $razorpayData['razorpay_payment_id'];
        $params[] = $razorpayData['razorpay_order_id'] ?? '';
        $params[] = $razorpayData['razorpay_signature'] ?? '';
    }
    
    $sql .= " WHERE id = ?";
    $params[] = $paymentId;
    
    $stmt = $db->prepare($sql);
    return $stmt->execute($params);
}

function getUserPayments($userId, $limit = 10) {
    $db = getDB();
    $stmt = $db->prepare("
        SELECT p.*, mp.name as plan_name 
        FROM payments p 
        LEFT JOIN user_memberships um ON p.membership_id = um.id
        LEFT JOIN membership_plans mp ON um.plan_id = mp.id
        WHERE p.user_id = ? 
        ORDER BY p.payment_date DESC 
        LIMIT ?
    ");
    $stmt->execute([$userId, $limit]);
    return $stmt->fetchAll();
}

function getTotalRevenue() {
    $db = getDB();
    $stmt = $db->query("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'completed'");
    return $stmt->fetch()['total'];
}

function getMonthlyRevenue($months = 6) {
    $db = getDB();
    $stmt = $db->prepare("
        SELECT DATE_FORMAT(payment_date, '%Y-%m') as month, 
               SUM(amount) as revenue,
               COUNT(*) as count
        FROM payments 
        WHERE status = 'completed' 
        AND payment_date >= DATE_SUB(CURDATE(), INTERVAL ? MONTH)
        GROUP BY DATE_FORMAT(payment_date, '%Y-%m')
        ORDER BY month ASC
    ");
    $stmt->execute([$months]);
    return $stmt->fetchAll();
}

function getAllPayments($limit = 50) {
    $db = getDB();
    $stmt = $db->prepare("
        SELECT p.*, u.full_name, u.email, mp.name as plan_name
        FROM payments p
        JOIN users u ON p.user_id = u.id
        LEFT JOIN user_memberships um ON p.membership_id = um.id
        LEFT JOIN membership_plans mp ON um.plan_id = mp.id
        ORDER BY p.payment_date DESC
        LIMIT ?
    ");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

// ============================================
// Attendance Functions
// ============================================

function getUserAttendance($userId, $limit = 30) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM attendance WHERE user_id = ? ORDER BY check_in DESC LIMIT ?");
    $stmt->execute([$userId, $limit]);
    return $stmt->fetchAll();
}

function checkIn($userId) {
    $db = getDB();
    
    // Check if already checked in today
    $stmt = $db->prepare("SELECT id FROM attendance WHERE user_id = ? AND DATE(check_in) = CURDATE() AND check_out IS NULL");
    $stmt->execute([$userId]);
    if ($stmt->fetch()) {
        return ['success' => false, 'message' => 'You are already checked in.'];
    }
    
    $stmt = $db->prepare("INSERT INTO attendance (user_id, check_in) VALUES (?, NOW())");
    $stmt->execute([$userId]);
    return ['success' => true, 'message' => 'Checked in successfully!'];
}

function checkOut($userId) {
    $db = getDB();
    $stmt = $db->prepare("
        UPDATE attendance 
        SET check_out = NOW(), 
            duration_minutes = TIMESTAMPDIFF(MINUTE, check_in, NOW())
        WHERE user_id = ? AND DATE(check_in) = CURDATE() AND check_out IS NULL
    ");
    $stmt->execute([$userId]);
    
    if ($stmt->rowCount() > 0) {
        return ['success' => true, 'message' => 'Checked out successfully!'];
    }
    return ['success' => false, 'message' => 'No active check-in found.'];
}

function getMonthlyAttendance($userId, $months = 6) {
    $db = getDB();
    $stmt = $db->prepare("
        SELECT DATE_FORMAT(check_in, '%Y-%m') as month, COUNT(*) as days
        FROM attendance 
        WHERE user_id = ? 
        AND check_in >= DATE_SUB(CURDATE(), INTERVAL ? MONTH)
        GROUP BY DATE_FORMAT(check_in, '%Y-%m')
        ORDER BY month ASC
    ");
    $stmt->execute([$userId, $months]);
    return $stmt->fetchAll();
}

// ============================================
// Workout Functions
// ============================================

function getUserWorkouts($userId, $limit = 10) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM workout_logs WHERE user_id = ? ORDER BY workout_date DESC LIMIT ?");
    $stmt->execute([$userId, $limit]);
    return $stmt->fetchAll();
}

function getWorkoutStats($userId) {
    $db = getDB();
    $stmt = $db->prepare("
        SELECT 
            COUNT(*) as total_workouts,
            COALESCE(SUM(duration_minutes), 0) as total_minutes,
            COALESCE(SUM(calories_burned), 0) as total_calories,
            COALESCE(AVG(duration_minutes), 0) as avg_duration
        FROM workout_logs 
        WHERE user_id = ?
    ");
    $stmt->execute([$userId]);
    return $stmt->fetch();
}

function addWorkout($userId, $data) {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO workout_logs (user_id, workout_type, duration_minutes, calories_burned, notes, workout_date) VALUES (?, ?, ?, ?, ?, ?)");
    return $stmt->execute([
        $userId, $data['workout_type'], $data['duration_minutes'],
        $data['calories_burned'], $data['notes'] ?? '', $data['workout_date']
    ]);
}

// ============================================
// Trainer Functions
// ============================================

function getAllTrainers($activeOnly = true) {
    $db = getDB();
    $sql = "SELECT * FROM trainers";
    if ($activeOnly) $sql .= " WHERE is_active = 1";
    $sql .= " ORDER BY rating DESC";
    $stmt = $db->query($sql);
    return $stmt->fetchAll();
}

function getTrainerById($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM trainers WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function bookTrainer($userId, $trainerId, $date, $timeSlot, $notes = '') {
    $db = getDB();
    
    // Check availability
    $stmt = $db->prepare("SELECT id FROM trainer_bookings WHERE trainer_id = ? AND booking_date = ? AND time_slot = ? AND status != 'cancelled'");
    $stmt->execute([$trainerId, $date, $timeSlot]);
    if ($stmt->fetch()) {
        return ['success' => false, 'message' => 'This time slot is already booked.'];
    }
    
    $stmt = $db->prepare("INSERT INTO trainer_bookings (user_id, trainer_id, booking_date, time_slot, notes) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$userId, $trainerId, $date, $timeSlot, $notes]);
    
    return ['success' => true, 'message' => 'Trainer booked successfully!'];
}

// ============================================
// Services Functions
// ============================================

function getAllServices($activeOnly = true) {
    $db = getDB();
    $sql = "SELECT * FROM services";
    if ($activeOnly) $sql .= " WHERE is_active = 1";
    $sql .= " ORDER BY sort_order ASC";
    $stmt = $db->query($sql);
    return $stmt->fetchAll();
}

// ============================================
// Notification Functions
// ============================================

function createNotification($userId, $title, $message, $type = 'info', $link = null) {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO notifications (user_id, title, message, type, link) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$userId, $title, $message, $type, $link]);
}

function getUserNotifications($userId, $limit = 10) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ?");
    $stmt->execute([$userId, $limit]);
    return $stmt->fetchAll();
}

function getUnreadNotificationCount($userId) {
    $db = getDB();
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0");
    $stmt->execute([$userId]);
    return $stmt->fetch()['count'];
}

function markNotificationRead($notificationId, $userId) {
    $db = getDB();
    $stmt = $db->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
    return $stmt->execute([$notificationId, $userId]);
}

// ============================================
// Contact Functions
// ============================================

function saveContactMessage($name, $email, $phone, $subject, $message) {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$name, $email, $phone, $subject, $message]);
}

function getAllContactMessages($limit = 50) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

// ============================================
// Utility Functions
// ============================================

function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function formatCurrency($amount) {
    return CURRENCY_SYMBOL . number_format($amount, 2);
}

function formatDate($date, $format = 'd M Y') {
    return date($format, strtotime($date));
}

function timeAgo($datetime) {
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    
    if ($diff->y > 0) return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
    if ($diff->m > 0) return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
    if ($diff->d > 0) return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
    if ($diff->h > 0) return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
    if ($diff->i > 0) return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
    return 'Just now';
}

function calculateBMI($weightKg, $heightCm) {
    if ($heightCm <= 0 || $weightKg <= 0) return null;
    $heightM = $heightCm / 100;
    $bmi = $weightKg / ($heightM * $heightM);
    return round($bmi, 1);
}

function getBMICategory($bmi) {
    if ($bmi < 18.5) return ['category' => 'Underweight', 'color' => 'warning'];
    if ($bmi < 25)   return ['category' => 'Normal', 'color' => 'success'];
    if ($bmi < 30)   return ['category' => 'Overweight', 'color' => 'warning'];
    return ['category' => 'Obese', 'color' => 'danger'];
}

function generateCSRFToken() {
    startSecureSession();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    startSecureSession();
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function setFlashMessage($type, $message) {
    startSecureSession();
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlashMessage() {
    startSecureSession();
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// Analytics functions for admin
function getSubscriptionTrends($months = 6) {
    $db = getDB();
    $stmt = $db->prepare("
        SELECT DATE_FORMAT(created_at, '%Y-%m') as month, 
               COUNT(*) as subscriptions
        FROM user_memberships 
        WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL ? MONTH)
        GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ORDER BY month ASC
    ");
    $stmt->execute([$months]);
    return $stmt->fetchAll();
}

function getNewUsersPerMonth($months = 6) {
    $db = getDB();
    $stmt = $db->prepare("
        SELECT DATE_FORMAT(created_at, '%Y-%m') as month, 
               COUNT(*) as new_users
        FROM users 
        WHERE role = 'user'
        AND created_at >= DATE_SUB(CURDATE(), INTERVAL ? MONTH)
        GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ORDER BY month ASC
    ");
    $stmt->execute([$months]);
    return $stmt->fetchAll();
}
