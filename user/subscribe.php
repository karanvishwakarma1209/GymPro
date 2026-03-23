<?php
/**
 * GymPro - Subscribe to Plan
 */
$pageTitle = 'Subscribe';
require_once '../includes/functions.php';
requireLogin();

$user = getCurrentUser();
$planId = intval($_GET['plan'] ?? 0);
$plan = $planId ? getPlanById($planId) : null;

if (!$plan) {
    setFlashMessage('danger', 'Invalid plan selected.');
    header('Location: ' . SITE_URL . '/plans.php');
    exit;
}

// Handle payment simulation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simulate_payment'])) {
    // Create membership
    $membershipId = createMembership($user['id'], $plan['id']);
    
    if ($membershipId) {
        // Create payment record
        $payment = createPayment($user['id'], $membershipId, $plan['price'], 'razorpay');
        
        // Simulate successful payment
        updatePaymentStatus($payment['id'], 'completed', [
            'razorpay_payment_id' => 'pay_' . strtoupper(bin2hex(random_bytes(8))),
            'razorpay_order_id' => 'order_' . strtoupper(bin2hex(random_bytes(8))),
        ]);
        
        // Create notification
        createNotification(
            $user['id'],
            'Payment Successful!',
            'Your payment of ' . formatCurrency($plan['price']) . ' for ' . $plan['name'] . ' has been confirmed.',
            'success'
        );
        
        setFlashMessage('success', 'Payment successful! Your membership is now active.');
        header('Location: ' . SITE_URL . '/user/dashboard.php');
        exit;
    } else {
        setFlashMessage('danger', 'Failed to create membership.');
    }
}

$features = json_decode($plan['features'], true) ?? [];

require_once '../includes/header.php';
?>

<div class="dashboard-wrapper">
    <div class="container">
        <div class="row justify-content-center g-4">
            <div class="col-lg-5" data-aos="fade-right">
                <h2 class="mb-4">Complete <span class="text-gradient">Payment</span></h2>
                
                <!-- Order Summary -->
                <div class="dash-card mb-4">
                    <div class="dash-card-header">
                        <h5><i class="fas fa-crown text-accent me-2"></i>Order Summary</h5>
                    </div>
                    <div class="dash-card-body">
                        <h4 class="mb-1"><?= sanitize($plan['name']) ?></h4>
                        <p class="text-muted small mb-3"><?= sanitize($plan['description']) ?></p>
                        
                        <ul class="list-unstyled mb-3">
                            <?php foreach ($features as $f): ?>
                                <li class="mb-2 small"><i class="fas fa-check-circle text-success me-2"></i><?= sanitize($f) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        
                        <hr style="border-color:var(--color-border);">
                        
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Plan Price</span>
                            <?php if ($plan['original_price']): ?>
                                <span class="text-decoration-line-through text-muted"><?= formatCurrency($plan['original_price']) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Duration</span>
                            <span><?= $plan['duration_months'] ?> month<?= $plan['duration_months'] > 1 ? 's' : '' ?></span>
                        </div>
                        <?php if ($plan['original_price']): ?>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Discount</span>
                            <span class="text-success">-<?= formatCurrency($plan['original_price'] - $plan['price']) ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <hr style="border-color:var(--color-border);">
                        
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold fs-5">Total</span>
                            <span class="fw-bold fs-5 text-gradient"><?= formatCurrency($plan['price']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-5" data-aos="fade-left">
                <!-- Payment Methods -->
                <div class="dash-card mb-4">
                    <div class="dash-card-header">
                        <h5><i class="fas fa-credit-card text-accent me-2"></i>Payment Method</h5>
                    </div>
                    <div class="dash-card-body">
                        <div class="mb-3 p-3" style="background:var(--color-bg-surface);border-radius:var(--btn-radius);border:2px solid var(--color-accent);">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="razorpay" checked style="background-color:var(--color-bg-input);border-color:var(--color-accent);">
                                <label class="form-check-label d-flex align-items-center gap-2" for="razorpay">
                                    <i class="fas fa-shield-alt text-accent"></i>
                                    <span>Razorpay (Test Mode)</span>
                                </label>
                            </div>
                            <small class="text-muted d-block ms-4 mt-1">UPI, Cards, Net Banking, Wallets</small>
                        </div>

                        <div class="alert mb-3" style="background:rgba(59,130,246,0.1);border:1px solid rgba(59,130,246,0.2);color:var(--color-info);border-radius:var(--btn-radius);">
                            <i class="fas fa-info-circle me-1"></i>
                            <small>This is a test/demo payment. No real money will be charged.</small>
                        </div>
                        
                        <form method="POST">
                            <button type="submit" name="simulate_payment" class="btn btn-accent w-100 btn-lg">
                                <i class="fas fa-lock me-2"></i>Pay <?= formatCurrency($plan['price']) ?>
                            </button>
                        </form>
                        
                        <div class="text-center mt-3">
                            <small class="text-muted"><i class="fas fa-lock me-1"></i>Secured by 256-bit SSL encryption</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
