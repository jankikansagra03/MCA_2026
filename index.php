<?php
/*
 * index.php - HOME
 * Each page follows the same four steps; see README.md for the explanation.
 */

// STEP 1: Settings used by the shared layout.
$page_title  = 'TrendCart - Electronics, Fashion & Everyday Essentials';
$active_page = 'home';

// STEP 2: Start capturing this page's HTML.
ob_start();
?>

<!-- ========== HERO ========== -->
<section class="hero-bg hero-showcase text-white py-5">
    <div class="container py-lg-4">
        <div class="row align-items-center g-5">
            <div class="col-xl-7">
                <span class="badge rounded-pill text-bg-light text-primary px-3 py-2 mb-3">
                    <i class="fa-solid fa-wand-magic-sparkles me-1"></i>NEW SEASON · FRESH FINDS
                </span>
                <h1 class="display-4 fw-bold lh-sm mb-3">
                    Everyday picks.<br>
                    <span class="on-dark-accent">Exceptional prices.</span>
                </h1>
                <p class="lead col-xl-10 mb-4">
                    Smart gadgets, easy fashion and home essentials — handpicked so you spend
                    less time searching and less money checking out.
                </p>
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <a href="product.php" class="btn btn-light btn-lg px-4 text-primary">
                        Shop all products <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>
                    <a href="offers.php" class="btn btn-outline-light btn-lg px-4">
                        Today's deals
                    </a>
                </div>

                <div class="hero-coupon d-inline-flex align-items-center gap-3 px-3 py-2 rounded-3">
                    <i class="fa-solid fa-ticket fa-lg on-dark-accent"></i>
                    <div>
                        <span class="d-block small fw-semibold">WELCOME10</span>
                        <span class="hint text-white-50">10% off your first order</span>
                    </div>
                    <span class="badge text-bg-light text-primary">NEW</span>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-4 mt-4 hint text-white-50">
                    <span><i class="fa-solid fa-star on-dark-accent me-1"></i>4.8/5 from 12,000+ shoppers</span>
                    <span><i class="fa-solid fa-truck on-dark-accent me-1"></i>Free delivery over &#8377;999</span>
                </div>
            </div>

            <div class="col-xl-5">
                <div class="hero-stage position-relative">
                    <!-- Main featured product card. -->
                    <div class="card store-card hero-feature shadow-lg">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge discount-badge text-bg-primary">-42%</span>
                                <span class="rating-stars small"><i class="fa-solid fa-star me-1"></i>4.9</span>
                            </div>
                            <div class="product-img rounded-3 d-flex align-items-center justify-content-center mb-3">
                                <i class="fa-solid fa-headphones text-primary"></i>
                            </div>
                            <p class="text-muted hint mb-1">FEATURED PICK</p>
                            <h2 class="h5 fw-bold mb-1">Pulse Wireless Headphones</h2>
                            <p class="mb-3">
                                <span class="fw-bold fs-5">&#8377;2,499</span>
                                <span class="price-old ms-2">&#8377;4,299</span>
                            </p>
                            <div class="d-flex gap-2">
                                <a href="product-detail.php?product=headphones" class="product-icon-btn btn btn-sm btn-outline-primary rounded-circle" title="View product">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <button class="product-icon-btn btn btn-sm btn-outline-primary rounded-circle" title="Add to wishlist">
                                    <i class="fa-regular fa-heart"></i>
                                </button>
                                <button class="btn btn-sm btn-primary flex-fill" title="Add to cart">
                                    <i class="fa-solid fa-cart-plus me-1"></i>Add to cart
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Small delivery note (not a product tile). -->
                    <div class="hero-delivery-chip card store-card shadow d-none d-xl-flex">
                        <div class="chip-icon"><i class="fa-solid fa-truck-fast text-primary"></i></div>
                        <div>
                            <p class="hint text-muted mb-0">DELIVERED FAST</p>
                            <p class="small fw-bold mb-0">Free over &#8377;999</p>
                        </div>
                    </div>

                    <div class="hero-sale-stamp d-none d-xl-flex flex-column align-items-center justify-content-center rounded-circle text-white shadow">
                        <span class="fw-bold">UP TO</span>
                        <span class="fs-4 fw-bold">50%</span>
                        <span class="fw-bold">OFF</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== TRUST STRIP ========== -->
<section class="bg-white border-bottom py-4">
    <div class="container">
        <div class="row g-3 text-center">
            <div class="col-6 col-lg-3">
                <i class="fa-solid fa-truck-fast fa-2x text-primary mb-2"></i>
                <h6 class="fw-bold small mb-1">Fast Delivery</h6>
                <p class="text-muted hint mb-0">Dispatched within 24 hours</p>
            </div>
            <div class="col-6 col-lg-3">
                <i class="fa-solid fa-rotate-left fa-2x text-success mb-2"></i>
                <h6 class="fw-bold small mb-1">Easy Returns</h6>
                <p class="text-muted hint mb-0">7-day hassle-free returns</p>
            </div>
            <div class="col-6 col-lg-3">
                <i class="fa-solid fa-shield-halved fa-2x text-warning mb-2"></i>
                <h6 class="fw-bold small mb-1">Secure Payments</h6>
                <p class="text-muted hint mb-0">100% safe checkout</p>
            </div>
            <div class="col-6 col-lg-3">
                <i class="fa-solid fa-headset fa-2x text-danger mb-2"></i>
                <h6 class="fw-bold small mb-1">Help When You Need</h6>
                <p class="text-muted hint mb-0">Support 6 days a week</p>
            </div>
        </div>
    </div>
</section>

<!-- ========== CATEGORIES ========== -->
<section class="py-5">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h3 fw-bold mb-1">Shop by Category</h2>
                <p class="text-muted hint mb-0">Find exactly what you're looking for.</p>
            </div>
            <a href="product.php" class="text-decoration-none">
                View all categories <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-3">
            <div class="col-6 col-md-4 col-lg-2">
                <a href="product.php" class="card store-card category-tile h-100 text-center text-decoration-none">
                    <div class="card-body py-4">
                        <div class="fs-1 text-primary mb-3"><i class="fa-solid fa-laptop"></i></div>
                        <h6 class="card-title text-dark fw-bold small mb-0">Electronics</h6>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="product.php" class="card store-card category-tile h-100 text-center text-decoration-none">
                    <div class="card-body py-4">
                        <div class="fs-1 text-danger mb-3"><i class="fa-solid fa-shirt"></i></div>
                        <h6 class="card-title text-dark fw-bold small mb-0">Fashion</h6>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="product.php" class="card store-card category-tile h-100 text-center text-decoration-none">
                    <div class="card-body py-4">
                        <div class="fs-1 text-success mb-3"><i class="fa-solid fa-gamepad"></i></div>
                        <h6 class="card-title text-dark fw-bold small mb-0">Gaming</h6>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="product.php" class="card store-card category-tile h-100 text-center text-decoration-none">
                    <div class="card-body py-4">
                        <div class="fs-1 text-warning mb-3"><i class="fa-solid fa-book"></i></div>
                        <h6 class="card-title text-dark fw-bold small mb-0">Books</h6>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="product.php" class="card store-card category-tile h-100 text-center text-decoration-none">
                    <div class="card-body py-4">
                        <div class="fs-1 text-info mb-3"><i class="fa-solid fa-couch"></i></div>
                        <h6 class="card-title text-dark fw-bold small mb-0">Home &amp; Living</h6>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="product.php" class="card store-card category-tile h-100 text-center text-decoration-none">
                    <div class="card-body py-4">
                        <div class="fs-1 text-secondary mb-3"><i class="fa-solid fa-dumbbell"></i></div>
                        <h6 class="card-title text-dark fw-bold small mb-0">Sports</h6>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ========== BESTSELLERS ========== -->
<section class="py-5 bg-light" id="bestsellers">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h3 fw-bold mb-1">Bestsellers This Week</h2>
                <p class="text-muted hint mb-0">The picks our customers keep coming back for.</p>
            </div>
            <a href="product.php" class="text-decoration-none">
                See all products <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card store-card h-100">
                    <div
                        class="product-img d-flex align-items-center justify-content-center bg-white text-primary position-relative">
                        <span
                            class="badge discount-badge text-bg-danger position-absolute top-0 start-0 m-2">-25%</span>
                        <div class="position-absolute top-0 end-0 m-2 d-flex gap-1">
                            <a href="product-detail.php?product=headphones"
                                class="product-icon-btn btn btn-sm btn-light rounded-circle" title="View product">
                                <i class="fa-regular fa-eye text-secondary"></i>
                            </a>
                            <button class="product-icon-btn btn btn-sm btn-light rounded-circle"
                                title="Add to wishlist">
                                <i class="fa-regular fa-heart text-danger"></i>
                            </button>
                            <button class="product-icon-btn btn btn-sm btn-light rounded-circle" title="Add to cart">
                                <i class="fa-solid fa-cart-shopping text-primary"></i>
                            </button>
                        </div>
                        <i class="fa-solid fa-headphones"></i>
                    </div>
                    <div class="card-body">
                        <small class="text-muted">Electronics</small>
                        <h5 class="h6 fw-bold mt-1">Pulse Wireless Headphones</h5>
                        <div class="rating-stars small">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i>
                            <span class="text-muted">(128)</span>
                        </div>
                        <div class="d-flex align-items-baseline gap-2 mt-2">
                            <span class="fs-5 fw-bold">&#8377;2,499</span>
                            <span class="price-old hint">&#8377;3,299</span>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card store-card h-100">
                    <div
                        class="product-img d-flex align-items-center justify-content-center bg-white text-success position-relative">
                        <span
                            class="badge discount-badge text-bg-danger position-absolute top-0 start-0 m-2">-28%</span>
                        <div class="position-absolute top-0 end-0 m-2 d-flex gap-1">
                            <a href="product-detail.php?product=smartwatch"
                                class="product-icon-btn btn btn-sm btn-light rounded-circle" title="View product">
                                <i class="fa-regular fa-eye text-secondary"></i>
                            </a>
                            <button class="product-icon-btn btn btn-sm btn-light rounded-circle"
                                title="Add to wishlist">
                                <i class="fa-regular fa-heart text-danger"></i>
                            </button>
                            <button class="product-icon-btn btn btn-sm btn-light rounded-circle" title="Add to cart">
                                <i class="fa-solid fa-cart-shopping text-primary"></i>
                            </button>
                        </div>
                        <i class="fa-solid fa-stopwatch"></i>
                    </div>
                    <div class="card-body">
                        <small class="text-muted">Electronics</small>
                        <h5 class="h6 fw-bold mt-1">Nova Smartwatch 2</h5>
                        <div class="rating-stars small">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star-half-stroke"></i>
                            <span class="text-muted">(86)</span>
                        </div>
                        <div class="d-flex align-items-baseline gap-2 mt-2">
                            <span class="fs-5 fw-bold">&#8377;4,999</span>
                            <span class="price-old hint">&#8377;6,999</span>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card store-card h-100">
                    <div
                        class="product-img d-flex align-items-center justify-content-center bg-white text-warning-emphasis position-relative">
                        <span
                            class="badge discount-badge text-bg-danger position-absolute top-0 start-0 m-2">-12%</span>
                        <div class="position-absolute top-0 end-0 m-2 d-flex gap-1">
                            <a href="product-detail.php?product=laptop"
                                class="product-icon-btn btn btn-sm btn-light rounded-circle" title="View product">
                                <i class="fa-regular fa-eye text-secondary"></i>
                            </a>
                            <button class="product-icon-btn btn btn-sm btn-light rounded-circle"
                                title="Add to wishlist">
                                <i class="fa-regular fa-heart text-danger"></i>
                            </button>
                            <button class="product-icon-btn btn btn-sm btn-light rounded-circle" title="Add to cart">
                                <i class="fa-solid fa-cart-shopping text-primary"></i>
                            </button>
                        </div>
                        <i class="fa-solid fa-laptop"></i>
                    </div>
                    <div class="card-body">
                        <small class="text-muted">Electronics</small>
                        <h5 class="h6 fw-bold mt-1">Zenith 14 Laptop</h5>
                        <div class="rating-stars small">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i>
                            <span class="text-muted">(54)</span>
                        </div>
                        <div class="d-flex align-items-baseline gap-2 mt-2">
                            <span class="fs-5 fw-bold">&#8377;54,990</span>
                            <span class="price-old hint">&#8377;62,990</span>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card store-card h-100">
                    <div
                        class="product-img d-flex align-items-center justify-content-center bg-white text-danger position-relative">
                        <span
                            class="badge discount-badge text-bg-danger position-absolute top-0 start-0 m-2">-25%</span>
                        <div class="position-absolute top-0 end-0 m-2 d-flex gap-1">
                            <a href="product-detail.php?product=controller"
                                class="product-icon-btn btn btn-sm btn-light rounded-circle" title="View product">
                                <i class="fa-regular fa-eye text-secondary"></i>
                            </a>
                            <button class="product-icon-btn btn btn-sm btn-light rounded-circle"
                                title="Add to wishlist">
                                <i class="fa-regular fa-heart text-danger"></i>
                            </button>
                            <button class="product-icon-btn btn btn-sm btn-light rounded-circle" title="Add to cart">
                                <i class="fa-solid fa-cart-shopping text-primary"></i>
                            </button>
                        </div>
                        <i class="fa-solid fa-gamepad"></i>
                    </div>
                    <div class="card-body">
                        <small class="text-muted">Gaming</small>
                        <h5 class="h6 fw-bold mt-1">ProPlay Wireless Controller</h5>
                        <div class="rating-stars small">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star-half-stroke"></i>
                            <span class="text-muted">(97)</span>
                        </div>
                        <div class="d-flex align-items-baseline gap-2 mt-2">
                            <span class="fs-5 fw-bold">&#8377;2,999</span>
                            <span class="price-old hint">&#8377;3,999</span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== PROMO BANNER ========== -->
<section class="py-5">
    <div class="container">
        <div
            class="promo-bg rounded-3 text-white p-4 p-lg-5 d-flex flex-column flex-md-row align-items-center justify-content-between gap-4">
            <div>
                <span class="badge text-bg-light text-primary mb-3"><i class="fa-solid fa-fire me-1"></i>TODAY
                    ONLY</span>
                <h2 class="h2 fw-bold mb-2">Flat &#8377;500 off on your first laptop</h2>
                <p class="mb-0">Use code <strong>LAPTOP500</strong> at checkout. Offer valid while stocks last.</p>
            </div>
            <a href="product.php" class="btn btn-light btn-lg px-4 text-primary fw-bold">
                Shop Laptops <i class="fa-solid fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- ========== TESTIMONIALS ========== -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="h3 fw-bold text-center mb-1">Loved by 50,000+ Shoppers</h2>
        <p class="text-muted text-center hint mb-5">Real words from our customers.</p>

        <div class="row g-4">
            <div class="col-12 col-md-4">
                <div class="card store-card h-100">
                    <div class="card-body p-4">
                        <i class="fa-solid fa-quote-left fa-2x text-primary opacity-25"></i>
                        <div class="rating-stars small mt-3">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i>
                        </div>
                        <p class="mt-3">"Ordered on Monday, delivered on Wednesday. The headphones sound great and the
                            packaging was lovely."</p>
                        <div class="d-flex align-items-center gap-2 mt-4">
                            <div class="avatar avatar-sm bg-primary-subtle text-primary">RS</div>
                            <div>
                                <div class="fw-bold small">Riya S.</div>
                                <div class="text-muted hint">Verified Buyer</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card store-card h-100">
                    <div class="card-body p-4">
                        <i class="fa-solid fa-quote-left fa-2x text-primary opacity-25"></i>
                        <div class="rating-stars small mt-3">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i>
                        </div>
                        <p class="mt-3">"The smartwatch was exactly as described. Customer support answered my question
                            within a few hours."</p>
                        <div class="d-flex align-items-center gap-2 mt-4">
                            <div class="avatar avatar-sm bg-success-subtle text-success">AK</div>
                            <div>
                                <div class="fw-bold small">Arjun K.</div>
                                <div class="text-muted hint">Verified Buyer</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card store-card h-100">
                    <div class="card-body p-4">
                        <i class="fa-solid fa-quote-left fa-2x text-primary opacity-25"></i>
                        <div class="rating-stars small mt-3">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i>
                        </div>
                        <p class="mt-3">"Great prices and an easy return process. I've recommended TrendCart to all my
                            classmates."</p>
                        <div class="d-flex align-items-center gap-2 mt-4">
                            <div class="avatar avatar-sm bg-warning-subtle text-warning-emphasis">MP</div>
                            <div>
                                <div class="fw-bold small">Meera P.</div>
                                <div class="text-muted hint">Verified Buyer</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// STEP 3: Save the captured HTML and stop buffering.
$page_content = ob_get_clean();

// STEP 4: Render it inside the shared layout.
require 'layout.php';