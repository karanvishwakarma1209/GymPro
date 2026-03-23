<?php
/**
 * GymPro - Admin: Manage Plans
 */
$pageTitle = 'Manage Plans';
require_once '../includes/functions.php';
requireLogin();
requireAdmin();

$db = getDB();

// Handle add/edit plan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_plan'])) {
        $name = sanitize($_POST['name']);
        $slug = strtolower(str_replace(' ', '-', $name));
        $duration = intval($_POST['duration_months']);
        $price = floatval($_POST['price']);
        $originalPrice = floatval($_POST['original_price']) ?: null;
        $description = sanitize($_POST['description']);
        $features = array_filter(array_map('trim', explode("\n", $_POST['features'])));
        $featuresJson = json_encode(array_values($features));
        $isPopular = isset($_POST['is_popular']) ? 1 : 0;
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $planId = intval($_POST['plan_id'] ?? 0);
        
        if ($planId > 0) {
            // Update
            $stmt = $db->prepare("UPDATE membership_plans SET name=?, slug=?, duration_months=?, price=?, original_price=?, description=?, features=?, is_popular=?, is_active=? WHERE id=?");
            $stmt->execute([$name, $slug, $duration, $price, $originalPrice, $description, $featuresJson, $isPopular, $isActive, $planId]);
            setFlashMessage('success', 'Plan updated successfully.');
        } else {
            // Insert
            $stmt = $db->prepare("INSERT INTO membership_plans (name, slug, duration_months, price, original_price, description, features, is_popular, is_active) VALUES (?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$name, $slug, $duration, $price, $originalPrice, $description, $featuresJson, $isPopular, $isActive]);
            setFlashMessage('success', 'Plan created successfully.');
        }
        header('Location: ' . SITE_URL . '/admin/plans.php');
        exit;
    }
    
    if (isset($_POST['delete_plan'])) {
        $planId = intval($_POST['plan_id']);
        $stmt = $db->prepare("DELETE FROM membership_plans WHERE id = ?");
        $stmt->execute([$planId]);
        setFlashMessage('success', 'Plan deleted.');
        header('Location: ' . SITE_URL . '/admin/plans.php');
        exit;
    }
}

$plans = getAllPlans(false);

require_once '../includes/header.php';
?>

<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-heading">Main</div>
    <a href="<?= SITE_URL ?>/admin/dashboard.php" class="nav-link"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
    <a href="<?= SITE_URL ?>/admin/users.php" class="nav-link"><i class="fas fa-users"></i>Users</a>
    <a href="<?= SITE_URL ?>/admin/plans.php" class="nav-link active"><i class="fas fa-crown"></i>Plans</a>
    <a href="<?= SITE_URL ?>/admin/payments.php" class="nav-link"><i class="fas fa-credit-card"></i>Payments</a>
    <div class="sidebar-heading">Other</div>
    <a href="<?= SITE_URL ?>/admin/messages.php" class="nav-link"><i class="fas fa-envelope"></i>Messages</a>
    <a href="<?= SITE_URL ?>/index.php" class="nav-link"><i class="fas fa-globe"></i>View Site</a>
    <a href="<?= SITE_URL ?>/logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i>Logout</a>
</aside>

<div class="admin-content">
    <button class="btn btn-accent btn-sm d-lg-none mb-3" onclick="document.getElementById('adminSidebar').classList.toggle('show')">
        <i class="fas fa-bars me-1"></i> Menu
    </button>

    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
        <div>
            <h2>Manage <span class="text-gradient">Plans</span></h2>
            <p class="text-muted mb-0"><?= count($plans) ?> plans</p>
        </div>
        <button class="btn btn-accent btn-sm" data-bs-toggle="modal" data-bs-target="#planModal" onclick="resetPlanForm()">
            <i class="fas fa-plus me-1"></i> Add Plan
        </button>
    </div>

    <div class="row g-4">
        <?php foreach ($plans as $plan): ?>
        <div class="col-md-6 col-lg-4">
            <div class="pricing-card <?= !$plan['is_active'] ? 'opacity-50' : '' ?>">
                <?php if ($plan['is_popular']): ?>
                    <div class="popular-badge">Popular</div>
                <?php endif; ?>
                <div class="plan-name"><?= sanitize($plan['name']) ?></div>
                <div class="plan-price">
                    <span class="amount"><?= CURRENCY_SYMBOL . number_format($plan['price']) ?></span>
                    <span class="period">/ <?= $plan['duration_months'] ?> mo</span>
                </div>
                <?php if ($plan['original_price']): ?>
                    <div class="original-price"><?= CURRENCY_SYMBOL . number_format($plan['original_price']) ?></div>
                <?php endif; ?>
                <p class="text-muted small"><?= sanitize($plan['description']) ?></p>
                
                <div class="d-flex gap-2 mt-3">
                    <button class="btn btn-accent-outline btn-sm flex-fill" onclick='editPlan(<?= json_encode($plan) ?>)'>
                        <i class="fas fa-edit me-1"></i>Edit
                    </button>
                    <form method="POST" class="d-inline" onsubmit="return confirm('Delete this plan?')">
                        <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">
                        <button type="submit" name="delete_plan" class="btn btn-sm" style="background:rgba(239,68,68,0.1);color:var(--color-danger);border:1px solid rgba(239,68,68,0.2);">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Plan Modal -->
<div class="modal fade" id="planModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background:var(--color-bg-card);border:1px solid var(--color-border);border-radius:var(--card-radius);">
            <div class="modal-header" style="border-color:var(--color-border);">
                <h5 class="modal-title" id="planModalTitle"><i class="fas fa-crown text-accent me-2"></i>Add Plan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="plan_id" id="planId" value="0">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Plan Name *</label>
                        <input type="text" name="name" id="planName" class="form-control" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label">Duration (months) *</label>
                            <input type="number" name="duration_months" id="planDuration" class="form-control" required min="1">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Price (₹) *</label>
                            <input type="number" name="price" id="planPrice" class="form-control" required step="0.01" min="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Original Price (₹)</label>
                        <input type="number" name="original_price" id="planOriginalPrice" class="form-control" step="0.01" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="planDescription" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Features (one per line)</label>
                        <textarea name="features" id="planFeatures" class="form-control" rows="4" placeholder="Access to gym&#10;Personal locker&#10;Group classes"></textarea>
                    </div>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_popular" id="planPopular" style="background-color:var(--color-bg-input);border-color:var(--color-border);">
                            <label class="form-check-label text-muted" for="planPopular">Popular</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="planActive" checked style="background-color:var(--color-bg-input);border-color:var(--color-border);">
                            <label class="form-check-label text-muted" for="planActive">Active</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-color:var(--color-border);">
                    <button type="button" class="btn btn-accent-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="save_plan" class="btn btn-accent"><i class="fas fa-save me-1"></i>Save Plan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function resetPlanForm() {
    document.getElementById('planModalTitle').innerHTML = '<i class="fas fa-crown text-accent me-2"></i>Add Plan';
    document.getElementById('planId').value = 0;
    document.getElementById('planName').value = '';
    document.getElementById('planDuration').value = '';
    document.getElementById('planPrice').value = '';
    document.getElementById('planOriginalPrice').value = '';
    document.getElementById('planDescription').value = '';
    document.getElementById('planFeatures').value = '';
    document.getElementById('planPopular').checked = false;
    document.getElementById('planActive').checked = true;
}

function editPlan(plan) {
    document.getElementById('planModalTitle').innerHTML = '<i class="fas fa-crown text-accent me-2"></i>Edit Plan';
    document.getElementById('planId').value = plan.id;
    document.getElementById('planName').value = plan.name;
    document.getElementById('planDuration').value = plan.duration_months;
    document.getElementById('planPrice').value = plan.price;
    document.getElementById('planOriginalPrice').value = plan.original_price || '';
    document.getElementById('planDescription').value = plan.description || '';
    
    let features = [];
    try { features = JSON.parse(plan.features) || []; } catch(e) {}
    document.getElementById('planFeatures').value = features.join('\n');
    
    document.getElementById('planPopular').checked = plan.is_popular == 1;
    document.getElementById('planActive').checked = plan.is_active == 1;
    
    new bootstrap.Modal(document.getElementById('planModal')).show();
}
</script>

<?php require_once '../includes/footer.php'; ?>
