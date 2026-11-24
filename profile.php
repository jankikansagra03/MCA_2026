<?php
/*
 * profile.php - MY PROFILE
 * A read-only summary of the account details, with links to edit them.
 */

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

    <div class="row g-4">
        <!-- ========== SHARED SIDEBAR ========== -->
        <aside class="col-12 col-lg-3">
            <?php require 'account-nav.php'; ?>
        </aside>

        <div class="col-12 col-lg-9">
            <div class="card store-card mb-4">
                <div class="card-body p-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="profile-avatar rounded-circle bg-primary text-white d-flex align-items-center justify-content-center">
                            <i class="fa-regular fa-user fa-2x"></i>
                        </div>
                        <div>
                            <h2 class="h4 fw-bold mb-1">Aarav Mehta</h2>
                            <p class="hint text-muted mb-0">TrendCart ID TC-102493 &middot; Member since January 2024</p>
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
                            <h2 class="h6 fw-bold mb-3">Personal Details</h2>
                            <dl class="row mb-0">
                                <dt class="col-sm-4 hint text-muted">Full Name</dt>
                                <dd class="col-sm-8 fw-semibold">Aarav Mehta</dd>
                                <dt class="col-sm-4 hint text-muted">Email Address</dt>
                                <dd class="col-sm-8 fw-semibold">aarav.mehta@example.com</dd>
                                <dt class="col-sm-4 hint text-muted">Mobile Number</dt>
                                <dd class="col-sm-8 fw-semibold">+91 98765 43210</dd>
                                <dt class="col-sm-4 hint text-muted">Date of Birth</dt>
                                <dd class="col-sm-8 fw-semibold">14 August 1996</dd>
                                <dt class="col-sm-4 hint text-muted">Gender</dt>
                                <dd class="col-sm-8 fw-semibold">Male</dd>
                                <dt class="col-sm-4 hint text-muted">Member Since</dt>
                                <dd class="col-sm-8 fw-semibold">January 2024</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-5">
                    <div class="card store-card h-100">
                        <div class="card-body p-4">
                            <h2 class="h6 fw-bold mb-3">Account Preferences</h2>
                            <div class="d-flex align-items-start gap-3 mb-3">
                                <div class="pref-icon text-primary">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                                <div>
                                    <p class="small fw-bold mb-0">Email updates</p>
                                    <p class="hint text-muted mb-0">Order confirmations and offers</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-start gap-3 mb-3">
                                <div class="pref-icon text-primary">
                                    <i class="fa-solid fa-mobile-screen"></i>
                                </div>
                                <div>
                                    <p class="small fw-bold mb-0">SMS alerts</p>
                                    <p class="hint text-muted mb-0">Delivery and status messages</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-start gap-3">
                                <div class="pref-icon text-primary">
                                    <i class="fa-solid fa-gift"></i>
                                </div>
                                <div>
                                    <p class="small fw-bold mb-0">Reward points</p>
                                    <p class="hint text-muted mb-0">1,240 points available</p>
                                </div>
                            </div>
                            <hr>
                            <a href="edit-profile.php" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fa-solid fa-sliders me-1"></i>Manage Preferences
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <p class="hint text-muted text-center mt-4 mb-0">
                Your details are used only for this storefront demo.
                <a href="faq.php" class="text-decoration-none fw-semibold">Read our privacy FAQ</a>.
            </p>
        </div>
    </div>
</div>

<?php
// STEP 3: Save the captured HTML and stop buffering.
$page_content = ob_get_clean();

// STEP 4: Render it inside the shared layout.
require 'layout.php';
