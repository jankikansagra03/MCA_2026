<?php
/*
 * account.php - MY ACCOUNT DASHBOARD
 * Fully dynamic user dashboard showing profile, statistics and recent activity.
 */

require_once __DIR__ . '/check_auth.php';
require_once __DIR__ . '/db.php';

$user        = current_user();
$user_id     = $user['id'];
$user_avatar = user_avatar_url($user);

// Dynamic statistics
try {
    $total_orders    = (int) (db_one("SELECT COUNT(*) as c FROM orders WHERE user_id = ?", [$user_id])['c'] ?? 0);
    $pending_orders  = (int) (db_one("SELECT COUNT(*) as c FROM orders WHERE user_id = ? AND status IN ('pending', 'processing', 'shipped')", [$user_id])['c'] ?? 0);
    $total_wishlist  = (int) (db_one("SELECT COUNT(*) as c FROM wishlists WHERE user_id = ? AND status = 'active'", [$user_id])['c'] ?? 0);
    $total_addresses = (int) (db_one("SELECT COUNT(*) as c FROM addresses WHERE user_id = ? AND status = 'active'", [$user_id])['c'] ?? 0);
    $default_address = (int) (db_one("SELECT COUNT(*) as c FROM addresses WHERE user_id = ? AND is_default = 1 AND status = 'active'", [$user_id])['c'] ?? 0);
    $recent_order    = db_one("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC LIMIT 1", [$user_id]);
} catch (PDOException $e) {
    $total_orders = $pending_orders = $total_wishlist = $total_addresses = $default_address = 0;
    $recent_order = null;
}

// STEP 1: Settings used by the shared layout.
$page_title     = 'My Account | TrendCart';
$active_page    = 'account';
$account_active = 'account';

// STEP 2: Start capturing this page's HTML.
ob_start();
?>

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">My Account</li>
        </ol>
    </nav>

    <div class="page-header">
        <span class="badge text-bg-primary mb-3 px-3 py-2">
            <i class="fa-solid fa-gauge-high me-1"></i>MY ACCOUNT
        </span>
        <h1 class="h2 fw-bold mb-3">Your TrendCart Dashboard</h1>
        <p class="text-muted">Everything about your shopping — orders, favourites and settings — in one place.</p>
    </div>

    <div class="row g-4">
        <!-- ========== SHARED SIDEBAR ========== -->
        <aside class="col-12 col-lg-3">
            <?php require 'account-nav.php'; ?>
        </aside>

        <!-- ========== ACCOUNT CONTENT ========== -->
        <div class="col-12 col-lg-9">
            <!-- Welcome banner -->
            <div class="card store-card overflow-hidden mb-4">
                <div class="order-banner p-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <?php if ($user_avatar): ?>
                            <img src="<?= $user_avatar ?>" alt="Avatar" class="profile-avatar rounded-circle border border-3 border-white object-fit-cover" style="width: 64px; height: 64px;">
                        <?php else: ?>
                            <div class="profile-avatar rounded-circle border border-3 border-white d-flex align-items-center justify-content-center text-white" style="width: 64px; height: 64px;">
                                <i class="fa-regular fa-user fa-2x"></i>
                            </div>
                        <?php endif; ?>
                        <div>
                            <p class="hint mb-1">WELCOME BACK</p>
                            <h2 class="h4 fw-bold mb-1"><?= htmlspecialchars($user['fullname']) ?></h2>
                            <p class="hint mb-0">
                                TrendCart ID: <strong>TC-<?= str_pad((string)$user['id'], 6, '0', STR_PAD_LEFT) ?></strong> &middot; 
                                Member since <?= date('F Y', strtotime($user['created_at'] ?? 'now')) ?>
                            </p>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <span class="badge rounded-pill bg-light text-primary px-3 py-2 fw-semibold">
                            <i class="fa-solid fa-shield-check me-1"></i><?= ucfirst(htmlspecialchars($user['role'] ?? 'Customer')) ?>
                        </span>
                        <a href="edit-profile.php" class="btn btn-light btn-sm px-3 text-primary fw-semibold">
                            <i class="fa-solid fa-user-pen me-1"></i>Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stat tiles -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-xl-3">
                    <div class="card store-card account-stat p-3 h-100">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon text-primary"><i class="fa-solid fa-box-archive"></i></div>
                            <div>
                                <p class="hint text-muted mb-0">Orders</p>
                                <p class="h5 fw-bold mb-0"><?= $total_orders ?></p>
                                <p class="hint text-muted mb-0"><?= $pending_orders ?> in progress</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="card store-card account-stat p-3 h-100">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon text-primary"><i class="fa-regular fa-heart"></i></div>
                            <div>
                                <p class="hint text-muted mb-0">Wishlist</p>
                                <p class="h5 fw-bold mb-0"><?= $total_wishlist ?></p>
                                <p class="hint text-muted mb-0"><a href="wishlist.php" class="text-decoration-none">View items</a></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="card store-card account-stat p-3 h-100">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon text-primary"><i class="fa-solid fa-location-dot"></i></div>
                            <div>
                                <p class="hint text-muted mb-0">Addresses</p>
                                <p class="h5 fw-bold mb-0"><?= $total_addresses ?></p>
                                <p class="hint text-muted mb-0"><?= $default_address ?> default</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="card store-card account-stat p-3 h-100">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon text-primary"><i class="fa-solid fa-user-check"></i></div>
                            <div>
                                <p class="hint text-muted mb-0">Status</p>
                                <p class="h5 fw-bold mb-0 text-success"><?= ucfirst(htmlspecialchars($user['status'])) ?></p>
                                <p class="hint text-muted mb-0">Verified Account</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Recent order -->
                <div class="col-12 col-xl-7">
                    <div class="card store-card h-100">
                        <div class="card-body p-4">
                            <div class="section-heading mb-4">
                                <div class="feature-icon"><i class="fa-solid fa-truck"></i></div>
                                <div class="me-auto">
                                    <h2 class="h6 fw-bold mb-0">Recent Order</h2>
                                    <p class="hint text-muted mb-0">Your latest delivery at a glance.</p>
                                </div>
                                <a href="orders.php" class="hint text-decoration-none fw-semibold">View all</a>
                            </div>

                            <?php if ($recent_order): ?>
                                <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                                    <div class="product-img order-thumb rounded d-flex align-items-center justify-content-center me-1">
                                        <i class="fa-solid fa-box text-primary fa-lg"></i>
                                    </div>
                                    <div class="flex-fill">
                                        <p class="hint text-muted mb-1">
                                            ORDER #TC-<?= str_pad((string)$recent_order['id'], 5, '0', STR_PAD_LEFT) ?> &middot; 
                                            <?= date('d M Y', strtotime($recent_order['created_at'])) ?>
                                        </p>
                                        <p class="fw-bold mb-1">Status: <?= ucfirst(htmlspecialchars($recent_order['status'])) ?></p>
                                        <p class="hint text-muted mb-0">Payment: <?= ucfirst(htmlspecialchars($recent_order['payment_status'] ?? 'Completed')) ?></p>
                                    </div>
                                    <span class="badge text-bg-primary px-3 py-2">
                                        <i class="fa-solid fa-circle-check me-1"></i><?= ucfirst(htmlspecialchars($recent_order['status'])) ?>
                                    </span>
                                </div>

                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 border-top pt-3">
                                    <span class="fs-5 fw-bold">&#8377;<?= number_format(((float)($recent_order['total_amount'] ?? 0)) / 100, 2) ?></span>
                                    <a href="order-detail.php?id=<?= $recent_order['id'] ?>" class="btn btn-primary btn-sm px-3">
                                        <i class="fa-regular fa-eye me-1"></i>View Order
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4 text-muted">
                                    <i class="fa-solid fa-bag-shopping fa-3x mb-3 text-secondary opacity-50"></i>
                                    <p class="mb-2 fw-semibold">No orders yet</p>
                                    <p class="small hint mb-3">Explore our products and place your first order today!</p>
                                    <a href="product.php" class="btn btn-outline-primary btn-sm px-3">
                                        <i class="fa-solid fa-shop me-1"></i>Start Shopping
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Quick links -->
                <div class="col-12 col-xl-5">
                    <div class="card store-card h-100">
                        <div class="card-body p-4">
                            <div class="section-heading mb-3">
                                <div class="feature-icon"><i class="fa-solid fa-bolt"></i></div>
                                <div>
                                    <h2 class="h6 fw-bold mb-0">Quick Links</h2>
                                    <p class="hint text-muted mb-0">Jump straight to where you need to go.</p>
                                </div>
                            </div>

                            <div class="pref-row position-relative">
                                <div class="pref-icon text-primary"><i class="fa-regular fa-heart"></i></div>
                                <div class="me-auto">
                                    <p class="small fw-bold mb-0">See your wishlist</p>
                                    <p class="hint text-muted mb-0"><?= $total_wishlist ?> item<?= $total_wishlist === 1 ? '' : 's' ?> saved</p>
                                </div>
                                <a href="wishlist.php" class="text-primary stretched-link" aria-label="See your wishlist">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>

                            <div class="pref-row position-relative">
                                <div class="pref-icon text-primary"><i class="fa-solid fa-location-dot"></i></div>
                                <div class="me-auto">
                                    <p class="small fw-bold mb-0">Manage addresses</p>
                                    <p class="hint text-muted mb-0"><?= $total_addresses ?> saved address<?= $total_addresses === 1 ? '' : 'es' ?></p>
                                </div>
                                <a href="addresses.php" class="text-primary stretched-link" aria-label="Manage addresses">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>

                            <div class="pref-row position-relative">
                                <div class="pref-icon text-primary"><i class="fa-solid fa-key"></i></div>
                                <div class="me-auto">
                                    <p class="small fw-bold mb-0">Update your password</p>
                                    <p class="hint text-muted mb-0">Keep your account secure</p>
                                </div>
                                <a href="change-password.php" class="text-primary stretched-link" aria-label="Update your password">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>

                            <div class="pref-row position-relative">
                                <div class="pref-icon text-primary"><i class="fa-solid fa-circle-question"></i></div>
                                <div class="me-auto">
                                    <p class="small fw-bold mb-0">Visit the help centre</p>
                                    <p class="hint text-muted mb-0">Answers to common questions</p>
                                </div>
                                <a href="faq.php" class="text-primary stretched-link" aria-label="Visit the help centre">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Support banner -->
            <div class="card store-card mt-4">
                <div class="card-body p-4 d-flex flex-wrap align-items-center gap-3">
                    <div class="feature-icon"><i class="fa-solid fa-headset"></i></div>
                    <div class="me-auto">
                        <p class="fw-bold mb-0">Need a hand with something?</p>
                        <p class="hint text-muted mb-0">Our support team is here every day to help with orders and returns.</p>
                    </div>
                    <a href="contact.php" class="btn btn-outline-primary btn-sm px-3">Contact support</a>
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
