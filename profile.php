<?php
/*
 * profile.php - MY PROFILE
 * Dynamic view of the user's account details with links to edit profile and change password.
 */

require_once __DIR__ . '/check_auth.php';
require_once __DIR__ . '/db.php';

$user        = current_user();
$user_avatar = user_avatar_url($user);

// Flash message (e.g. redirected from edit-profile or change-password)
$flash = $_SESSION['profile_flash'] ?? null;
unset($_SESSION['profile_flash']);

// STEP 1: Settings used by the shared layout.
$page_title     = 'My Profile | TrendCart';
$active_page    = 'profile';
$account_active = 'profile';

// STEP 2: Start capturing this page's HTML.
ob_start();
?>

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="account.php" class="text-decoration-none">My Account</a></li>
            <li class="breadcrumb-item active" aria-current="page">My Profile</li>
        </ol>
    </nav>

    <div class="page-header">
        <span class="badge text-bg-primary mb-3 px-3 py-2">
            <i class="fa-solid fa-user me-1"></i>MY PROFILE
        </span>
        <h1 class="h2 fw-bold mb-3">Your Profile</h1>
        <p class="text-muted">Check the details you use for orders, delivery and account recovery.</p>
    </div>

    <?php if ($flash): ?>
        <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show mb-4" role="alert">
            <?= htmlspecialchars($flash['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- ========== SHARED SIDEBAR ========== -->
        <aside class="col-12 col-lg-3">
            <?php require 'account-nav.php'; ?>
        </aside>

        <div class="col-12 col-lg-9">
            <div class="card store-card mb-4">
                <div class="card-body p-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <?php if ($user_avatar): ?>
                            <img src="<?= $user_avatar ?>" alt="Profile Picture" class="profile-avatar rounded-circle border border-3 border-white object-fit-cover shadow-sm" style="width: 72px; height: 72px;">
                        <?php else: ?>
                            <div class="profile-avatar rounded-circle bg-primary text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 72px; height: 72px;">
                                <i class="fa-regular fa-user fa-2x"></i>
                            </div>
                        <?php endif; ?>
                        <div>
                            <h2 class="h4 fw-bold mb-1"><?= htmlspecialchars($user['fullname']) ?></h2>
                            <p class="hint text-muted mb-0">
                                TrendCart ID: <strong>TC-<?= str_pad((string)$user['id'], 6, '0', STR_PAD_LEFT) ?></strong> &middot; 
                                Member since <?= date('F Y', strtotime($user['created_at'] ?? 'now')) ?>
                            </p>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="edit-profile.php" class="btn btn-primary btn-sm px-3">
                            <i class="fa-solid fa-user-pen me-1"></i>Edit Profile
                        </a>
                        <a href="change-password.php" class="btn btn-outline-primary btn-sm px-3">
                            <i class="fa-solid fa-key me-1"></i>Change Password
                        </a>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-12 col-xl-7">
                    <div class="card store-card h-100">
                        <div class="card-body p-4">
                            <h2 class="h6 fw-bold mb-3 border-bottom pb-2">Personal Details</h2>
                            <dl class="row mb-0 gy-2">
                                <dt class="col-sm-4 hint text-muted">Full Name</dt>
                                <dd class="col-sm-8 fw-semibold"><?= htmlspecialchars($user['fullname']) ?></dd>

                                <dt class="col-sm-4 hint text-muted">Email Address</dt>
                                <dd class="col-sm-8 fw-semibold"><?= htmlspecialchars($user['email']) ?></dd>

                                <dt class="col-sm-4 hint text-muted">Mobile Number</dt>
                                <dd class="col-sm-8 fw-semibold"><?= !empty($user['phone']) ? htmlspecialchars($user['phone']) : '<span class="text-muted font-monospace">Not provided</span>' ?></dd>

                                <dt class="col-sm-4 hint text-muted">Date of Birth</dt>
                                <dd class="col-sm-8 fw-semibold"><?= !empty($user['dob']) ? date('d F Y', strtotime($user['dob'])) : '<span class="text-muted">Not provided</span>' ?></dd>

                                <dt class="col-sm-4 hint text-muted">Gender</dt>
                                <dd class="col-sm-8 fw-semibold"><?= !empty($user['gender']) ? htmlspecialchars($user['gender']) : '<span class="text-muted">Not specified</span>' ?></dd>

                                <dt class="col-sm-4 hint text-muted">Account Role</dt>
                                <dd class="col-sm-8 fw-semibold"><?= ucfirst(htmlspecialchars($user['role'] ?? 'customer')) ?></dd>

                                <dt class="col-sm-4 hint text-muted">Account Status</dt>
                                <dd class="col-sm-8 fw-semibold">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                        <i class="fa-solid fa-circle-check me-1"></i><?= ucfirst(htmlspecialchars($user['status'])) ?>
                                    </span>
                                </dd>

                                <dt class="col-sm-4 hint text-muted">Registered On</dt>
                                <dd class="col-sm-8 fw-semibold"><?= date('d M Y, h:i A', strtotime($user['created_at'] ?? 'now')) ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-5">
                    <div class="card store-card h-100">
                        <div class="card-body p-4">
                            <h2 class="h6 fw-bold mb-3 border-bottom pb-2">Account Actions</h2>
                            <div class="d-flex align-items-start gap-3 mb-3">
                                <div class="pref-icon text-primary"><i class="fa-solid fa-user-pen"></i></div>
                                <div>
                                    <p class="small fw-bold mb-0">Update Contact Details</p>
                                    <p class="hint text-muted mb-1">Change your name, phone number or profile picture.</p>
                                    <a href="edit-profile.php" class="btn btn-sm btn-outline-primary py-1">Edit Details</a>
                                </div>
                            </div>
                            <hr class="my-3">
                            <div class="d-flex align-items-start gap-3 mb-3">
                                <div class="pref-icon text-primary"><i class="fa-solid fa-key"></i></div>
                                <div>
                                    <p class="small fw-bold mb-0">Security &amp; Password</p>
                                    <p class="hint text-muted mb-1">Regularly update your password to keep your account safe.</p>
                                    <a href="change-password.php" class="btn btn-sm btn-outline-primary py-1">Change Password</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// STEP 3: Save the captured HTML and stop buffering.
$page_content = ob_get_clean();

// STEP 4: Render it inside the shared layout.
require 'layout.php';
