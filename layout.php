<?php
/**
 * layout.php
 * ----------
 * The single shared shell for the whole store: announcement bar, navbar and footer.
 * Each page captures its own HTML into $page_content with ob_start()/ob_get_clean()
 * and then loads this file, so the header and footer are written only once.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/auth.php';

$is_logged_in      = is_logged_in();
$layout_user       = current_user();
$current_user_name = $layout_user['fullname'] ?? ($_SESSION['user_name'] ?? 'Account');
$nav_avatar        = user_avatar_url($layout_user);

$cart_badge_count     = 0;
$wishlist_badge_count = 0;
if ($is_logged_in && !empty($layout_user['id'])) {
    try {
        require_once __DIR__ . '/db.php';
        $cart_row = db_one("SELECT COUNT(*) AS total FROM carts WHERE user_id = ? AND status = 'active'", [$layout_user['id']]);
        $cart_badge_count = (int)($cart_row['total'] ?? 0);

        $wish_row = db_one("SELECT COUNT(*) AS total FROM wishlists WHERE user_id = ? AND status = 'active'", [$layout_user['id']]);
        $wishlist_badge_count = (int)($wish_row['total'] ?? 0);
    } catch (Throwable $e) {}
}

// Defaults, used if a page forgets to set these variables.
// "??" means: use the left value if it exists, otherwise use the right one.
$page_title   = $page_title   ?? 'TrendCart - Shop Smart, Save More';
$active_page  = $active_page  ?? '';
$page_content = $page_content ?? '';

// Any of these pages belongs to the Account menu, so its dropdown stays active.
$account_pages = ['account', 'orders', 'order-detail', 'profile', 'edit-profile',
                  'change-password', 'addresses', 'wishlist', 'cart', 'checkout'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="TrendCart - electronics, fashion, books and everyday essentials delivered to your door.">
    <title><?= htmlspecialchars($page_title) ?></title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- ========== ANNOUNCEMENT BAR ========== -->
    <div class="text-white text-center py-2 topbar">
        <div class="container d-flex flex-wrap justify-content-center gap-2 gap-sm-3">
            <span><i class="fa-solid fa-truck me-1"></i>Free delivery on orders above &#8377;999</span>
            <span class="d-none d-sm-inline">|</span>
            <span><i class="fa-solid fa-rotate-left me-1"></i>7-day easy returns</span>
            <span class="d-none d-sm-inline">|</span>
            <span><i class="fa-solid fa-tag me-1"></i>Extra 10% off with code <strong class="on-dark-accent">WELCOME10</strong></span>
        </div>
    </div>

    <!-- ========== NAVBAR ========== -->
    <header>
        <nav class="navbar navbar-expand-xl navbar-light bg-white shadow-sm fixed-top site-navbar">
            <div class="container">
                <a class="navbar-brand fw-bold fs-3 text-primary" href="index.php">
                    <i class="fa-solid fa-bag-shopping me-2"></i>Trend<span class="text-dark">Cart</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#mainNav" aria-controls="mainNav"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link <?= $active_page === 'home' ? 'active' : '' ?>" href="index.php">
                                <i class="fa-solid fa-house me-1"></i>Home
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle <?= $active_page === 'product' ? 'active' : '' ?>"
                               href="#" id="shopDropdown" role="button" data-bs-toggle="dropdown"
                               aria-expanded="false">
                                <i class="fa-solid fa-layer-group me-1"></i>Shop
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="shopDropdown">
                                <li><a class="dropdown-item" href="product.php">All Products</a></li>
                                <li><a class="dropdown-item" href="product.php">Electronics</a></li>
                                <li><a class="dropdown-item" href="product.php">Fashion</a></li>
                                <li><a class="dropdown-item" href="product.php">Books</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="offers.php">
                                    <i class="fa-solid fa-bolt me-1"></i>Today's Deals
                                </a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $active_page === 'offers' ? 'active' : '' ?>" href="offers.php">
                                <i class="fa-solid fa-tag me-1"></i>Offers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $active_page === 'about' ? 'active' : '' ?>" href="about.php">
                                <i class="fa-solid fa-circle-info me-1"></i>About Us
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $active_page === 'contact' ? 'active' : '' ?>" href="contact.php">
                                <i class="fa-solid fa-headset me-1"></i>Contact
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $active_page === 'faq' ? 'active' : '' ?>" href="faq.php">
                                <i class="fa-solid fa-circle-question me-1"></i>FAQ
                            </a>
                        </li>
                    </ul>

                    <form class="d-flex navbar-search me-lg-3 mb-2 mb-lg-0" role="search" action="product.php" method="get" novalidate>
                        <div class="position-relative flex-grow-1">
                            <input class="form-control me-2" type="search" id="siteSearch" name="q"
                                   placeholder="Search products..."
                                   data-validation="max" data-max="100">
                            <div class="invalid-feedback" id="siteSearchError"></div>
                        </div>
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>

                    <ul class="navbar-nav mb-2 mb-lg-0 align-items-lg-center">
                        <?php if ($is_logged_in): ?>
                            <li class="nav-item">
                                <a class="nav-link <?= $active_page === 'wishlist' ? 'active' : '' ?>" href="wishlist.php" title="Wishlist">
                                    <i class="fa-regular fa-heart"></i>
                                    <?php if ($wishlist_badge_count > 0): ?>
                                        <span class="badge rounded-pill bg-danger"><?= $wishlist_badge_count ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $active_page === 'cart' ? 'active' : '' ?>" href="cart.php" title="Shopping cart">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                    <?php if ($cart_badge_count > 0): ?>
                                        <span class="badge rounded-pill bg-primary"><?= $cart_badge_count ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item dropdown ms-lg-2">
                            <a class="nav-link dropdown-toggle d-flex align-items-center <?= in_array($active_page, $account_pages, true) ? 'active' : '' ?>"
                               href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown"
                               aria-expanded="false">
                                <?php if ($is_logged_in && !empty($nav_avatar)): ?>
                                    <img src="<?= $nav_avatar ?>" alt="Avatar" class="rounded-circle object-fit-cover me-1" style="width: 22px; height: 22px;">
                                <?php else: ?>
                                    <i class="fa-regular fa-user me-1"></i>
                                <?php endif; ?>
                                <?= $is_logged_in ? htmlspecialchars($current_user_name) : 'Account' ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                                <?php if ($is_logged_in): ?>
                                    <li><a class="dropdown-item <?= $active_page === 'account' ? 'active' : '' ?>" href="account.php">
                                        <i class="fa-solid fa-gauge-high me-2"></i>My Account
                                    </a></li>
                                    <li><a class="dropdown-item <?= $active_page === 'orders' ? 'active' : '' ?>" href="orders.php">
                                        <i class="fa-solid fa-box-archive me-2"></i>My Orders
                                    </a></li>
                                    <li><a class="dropdown-item <?= $active_page === 'wishlist' ? 'active' : '' ?>" href="wishlist.php">
                                        <i class="fa-regular fa-heart me-2"></i>Wishlist
                                    </a></li>
                                    <li><a class="dropdown-item <?= $active_page === 'cart' ? 'active' : '' ?>" href="cart.php">
                                        <i class="fa-solid fa-cart-shopping me-2"></i>Shopping Cart
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item <?= $active_page === 'addresses' ? 'active' : '' ?>" href="addresses.php">
                                        <i class="fa-solid fa-location-dot me-2"></i>Address Book
                                    </a></li>
                                    <li><a class="dropdown-item <?= in_array($active_page, ['profile', 'edit-profile', 'change-password'], true) ? 'active' : '' ?>" href="profile.php">
                                        <i class="fa-solid fa-user me-2"></i>Profile &amp; Settings
                                    </a></li>
                                    <?php if (isset($_SESSION['user_role']) && strtolower($_SESSION['user_role']) === 'admin'): ?>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="admin/admin.php">
                                            <i class="fa-solid fa-shield-halved me-2"></i>Admin Panel
                                        </a></li>
                                    <?php endif; ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="logout.php">
                                        <i class="fa-solid fa-right-from-bracket me-2"></i>Sign Out
                                    </a></li>
                                <?php else: ?>
                                    <li><a class="dropdown-item <?= $active_page === 'login' ? 'active' : '' ?>" href="login.php">
                                        <i class="fa-solid fa-right-to-bracket me-2"></i>Sign In
                                    </a></li>
                                    <li><a class="dropdown-item <?= $active_page === 'register' ? 'active' : '' ?>" href="register.php">
                                        <i class="fa-solid fa-user-plus me-2"></i>Create Account
                                    </a></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- ========== PAGE CONTENT ========== -->
    <!-- Pages manage their own containers so full-width banners are possible. -->
    <main class="flex-grow-1">
        <?= $page_content ?>
    </main>

    <!-- ========== FOOTER ========== -->
    <footer class="site-footer text-white pt-5 mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-12 col-md-4">
                    <h4 class="fw-bold">
                        <i class="fa-solid fa-bag-shopping me-2"></i>Trend<span class="on-dark-accent">Cart</span>
                    </h4>
                    <p class="text-white-50 small">
                        Good products, honest prices and a shopping experience that just works.
                        Delivered across India since 2021.
                    </p>
                    <h6 class="text-uppercase small fw-bold mt-4">Newsletter</h6>
                    <p class="text-white-50 hint mb-2">Get first access to offers and new arrivals.</p>
                    <form class="d-flex flex-wrap" action="#" method="post" novalidate>
                        <div class="position-relative flex-grow-1">
                            <input type="email" class="form-control form-control-sm me-2" id="newsletterEmail"
                                   name="newsletterEmail" placeholder="Your email address" aria-label="Email address"
                                   data-validation="required email">
                            <div class="invalid-feedback" id="newsletterEmailError"></div>
                        </div>
                        <button class="btn btn-light btn-sm px-3 text-primary" type="submit">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </form>
                </div>

                <div class="col-6 col-md-2 footer-links">
                    <h6 class="text-uppercase fw-bold">Shop</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="product.php">All Products</a></li>
                        <li class="mb-2"><a href="product.php">Electronics</a></li>
                        <li class="mb-2"><a href="product.php">Fashion</a></li>
                        <li class="mb-2"><a href="product.php">Books</a></li>
                        <li class="mb-2"><a href="offers.php">Offers &amp; Deals</a></li>
                    </ul>
                </div>

                <div class="col-6 col-md-2 footer-links">
                    <h6 class="text-uppercase fw-bold">Customer Care</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="orders.php">Track Your Order</a></li>
                        <li class="mb-2"><a href="account.php">My Account</a></li>
                        <li class="mb-2"><a href="contact.php">Shipping &amp; Delivery</a></li>
                        <li class="mb-2"><a href="faq.php">Returns &amp; FAQs</a></li>
                        <li class="mb-2"><a href="contact.php">Contact Us</a></li>
                    </ul>
                </div>

                <div class="col-6 col-md-2 footer-links">
                    <h6 class="text-uppercase fw-bold">Company</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="about.php">About Us</a></li>
                        <li class="mb-2"><a href="about.php">Our Story</a></li>
                        <li class="mb-2"><a href="about.php">Careers</a></li>
                        <li class="mb-2"><a href="about.php">Terms &amp; Conditions</a></li>
                        <li class="mb-2"><a href="about.php">Privacy Policy</a></li>
                    </ul>
                </div>

                <div class="col-6 col-md-2 footer-links">
                    <h6 class="text-uppercase fw-bold">Stay Connected</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="contact.php"><i class="fa-brands fa-facebook-f me-2"></i>Facebook</a></li>
                        <li class="mb-2"><a href="contact.php"><i class="fa-brands fa-square-x-twitter me-2"></i>Twitter / X</a></li>
                        <li class="mb-2"><a href="contact.php"><i class="fa-brands fa-instagram me-2"></i>Instagram</a></li>
                        <li class="mb-2"><a href="contact.php"><i class="fa-brands fa-youtube me-2"></i>YouTube</a></li>
                    </ul>
                    <p class="small text-white-50 mt-3 mb-0">
                        <i class="fa-solid fa-phone me-2"></i>1800 123 4567
                    </p>
                </div>
            </div>

            <hr class="my-4 bg-white-50">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 pb-4">
                <div>
                    <span class="text-white-50 small me-3">We accept:</span>
                    <i class="fa-brands fa-cc-visa pay-icon"></i>
                    <i class="fa-brands fa-cc-mastercard pay-icon"></i>
                    <i class="fa-brands fa-cc-paypal pay-icon"></i>
                    <i class="fa-brands fa-cc-apple-pay pay-icon"></i>
                    <i class="fa-brands fa-cc-amazon-pay pay-icon"></i>
                </div>
                <span class="text-white-50 small">&copy; <?= date('Y') ?> TrendCart. All rights reserved.</span>
            </div>
        </div>
    </footer>

    <script src="js/jquery.min.js"></script>
    <script src="js/validator.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>
