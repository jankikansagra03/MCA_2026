<?php
/*
 * account.php - MY ACCOUNT DASHBOARD
 * A friendly starting point that links to every account page.
 */

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
                        <div
                            class="profile-avatar rounded-circle border border-3 border-white d-flex align-items-center justify-content-center text-white">
                            <i class="fa-regular fa-user fa-2x"></i>
                        </div>
                        <div>
                            <p class="hint mb-1">GOOD MORNING</p>
                            <h2 class="h4 fw-bold mb-1">Aarav Mehta</h2>
                            <p class="hint mb-0">Member since January 2024 &middot; ID TC-102493</p>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <span class="badge rounded-pill bg-light text-primary px-3 py-2 fw-semibold">
                            <i class="fa-solid fa-gift me-1"></i>1,240 points
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
                                <p class="h5 fw-bold mb-0">6</p>
                                <p class="hint text-muted mb-0">1 in progress</p>
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
                                <p class="h5 fw-bold mb-0">3</p>
                                <p class="hint text-muted mb-0">2 price drops</p>
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
                                <p class="h5 fw-bold mb-0">2</p>
                                <p class="hint text-muted mb-0">1 default</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="card store-card account-stat p-3 h-100">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon text-primary"><i class="fa-solid fa-gift"></i></div>
                            <div>
                                <p class="hint text-muted mb-0">Reward Points</p>
                                <p class="h5 fw-bold mb-0">1,240</p>
                                <p class="hint text-muted mb-0">Worth &#8377;1,240</p>
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

                            <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                                <div class="d-flex">
                                    <div class="product-img order-thumb rounded d-flex align-items-center justify-content-center me-1">
                                        <i class="fa-solid fa-headphones text-primary"></i>
                                    </div>
                                    <div class="product-img order-thumb rounded d-flex align-items-center justify-content-center">
                                        <i class="fa-solid fa-stopwatch text-primary"></i>
                                    </div>
                                </div>
                                <div class="flex-fill">
                                    <p class="hint text-muted mb-1">ORDER #TC-48291 &middot; 18 NOV 2026</p>
                                    <p class="fw-bold mb-1">Pulse Wireless Headphones &amp; Nova Smartwatch 2</p>
                                    <p class="hint text-muted mb-2">2 items &middot; Delivered on 21 Nov</p>
                                    <div class="mini-progress" aria-label="All delivery steps complete">
                                        <span class="on"></span><span class="on"></span><span class="on"></span><span
                                                class="on"></span>
                                    </div>
                                </div>
                                <span class="badge text-bg-primary px-3 py-2">
                                    <i class="fa-solid fa-circle-check me-1"></i>Delivered
                                </span>
                            </div>

                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 border-top pt-3">
                                <span class="fs-5 fw-bold">&#8377;6,998</span>
                                <a href="order-detail.php" class="btn btn-primary btn-sm px-3">
                                    <i class="fa-regular fa-eye me-1"></i>View Order
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick links -->
                <div class="col-12 col-xl-5">
                    <div class="card store-card h-100">
                        <div class="card-body p-4">
                            <div class="section-heading mb-2">
                                <div class="feature-icon"><i class="fa-solid fa-gauge-high"></i></div>
                                <div>
                                    <h2 class="h6 fw-bold mb-0">Quick Links</h2>
                                    <p class="hint text-muted mb-0">Jump straight to where you need to go.</p>
                                </div>
                            </div>

                            <div class="pref-row">
                                <div class="pref-icon text-primary"><i class="fa-solid fa-location-dot"></i></div>
                                <div class="me-auto">
                                    <p class="small fw-bold mb-0">Manage saved addresses</p>
                                    <p class="hint text-muted mb-0">Home, office and more</p>
                                </div>
                                <a href="addresses.php" class="text-primary" aria-label="Manage saved addresses"><i
                                            class="fa-solid fa-arrow-right"></i></a>
                            </div>

                            <div class="pref-row">
                                <div class="pref-icon text-primary"><i class="fa-regular fa-heart"></i></div>
                                <div class="me-auto">
                                    <p class="small fw-bold mb-0">See your wishlist</p>
                                    <p class="hint text-muted mb-0">3 favourites saved</p>
                                </div>
                                <a href="wishlist.php" class="text-primary" aria-label="See your wishlist"><i
                                            class="fa-solid fa-arrow-right"></i></a>
                            </div>

                            <div class="pref-row">
                                <div class="pref-icon text-primary"><i class="fa-solid fa-key"></i></div>
                                <div class="me-auto">
                                    <p class="small fw-bold mb-0">Update your password</p>
                                    <p class="hint text-muted mb-0">Keep your account secure</p>
                                </div>
                                <a href="change-password.php" class="text-primary" aria-label="Update your password"><i
                                            class="fa-solid fa-arrow-right"></i></a>
                            </div>

                            <div class="pref-row">
                                <div class="pref-icon text-primary"><i class="fa-solid fa-circle-question"></i></div>
                                <div class="me-auto">
                                    <p class="small fw-bold mb-0">Visit the help centre</p>
                                    <p class="hint text-muted mb-0">Answers to common questions</p>
                                </div>
                                <a href="faq.php" class="text-primary" aria-label="Visit the help centre"><i
                                            class="fa-solid fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
