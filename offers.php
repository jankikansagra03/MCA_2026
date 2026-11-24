<?php
/*
 * offers.php - OFFERS & DEALS
 * Follows the same four-step layout pattern as every other page.
 */

// STEP 1: Settings used by the shared layout.
$page_title  = 'Offers & Deals | TrendCart';
$active_page = 'offers';

// STEP 2: Start capturing this page's HTML.
ob_start();
?>

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Offers &amp; Deals</li>
        </ol>
    </nav>

    <!-- ========== PAGE HEADING ========== -->
    <div class="page-header">
        <span class="badge text-bg-primary mb-3 px-3 py-2">
            <i class="fa-solid fa-tag me-1"></i>SAVE MORE
        </span>
        <h1 class="h2 fw-bold mb-3">Offers &amp; Deals</h1>
        <p class="text-muted">
            Good prices get better. Pick a coupon, shop the deal and enjoy free delivery
            on orders above &#8377;999.
        </p>
    </div>

    <!-- ========== COUPON CARDS ========== -->
    <h2 class="h4 fw-bold mb-1">Coupon Codes</h2>
    <p class="text-muted hint mb-4">Copy a code and paste it at checkout.</p>

    <div class="row g-4 mb-5">
        <div class="col-12 col-md-4">
            <div class="card store-card h-100 text-center">
                <div class="card-body p-4">
                    <div class="fs-1 text-primary mb-3"><i class="fa-solid fa-gift"></i></div>
                    <h3 class="h5 fw-bold">10% Off Your First Order</h3>
                    <p class="text-muted hint mb-3">New customers save on everything in the store.</p>
                    <span class="badge text-bg-light border text-primary fw-bold px-3 py-2">WELCOME10</span>
                    <p class="text-muted hint mt-3 mb-0">
                        <i class="fa-solid fa-clock me-1"></i>Valid for new accounts
                    </p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card store-card h-100 text-center">
                <div class="card-body p-4">
                    <div class="fs-1 text-success mb-3"><i class="fa-solid fa-laptop"></i></div>
                    <h3 class="h5 fw-bold">Flat &#8377;500 Off Laptops</h3>
                    <p class="text-muted hint mb-3">A little extra off our biggest gadget category.</p>
                    <span class="badge text-bg-light border text-primary fw-bold px-3 py-2">LAPTOP500</span>
                    <p class="text-muted hint mt-3 mb-0">
                        <i class="fa-solid fa-clock me-1"></i>Valid while stocks last
                    </p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card store-card h-100 text-center">
                <div class="card-body p-4">
                    <div class="fs-1 text-danger mb-3"><i class="fa-solid fa-truck-fast"></i></div>
                    <h3 class="h5 fw-bold">Free Delivery</h3>
                    <p class="text-muted hint mb-3">No delivery charge on orders above &#8377;499.</p>
                    <span class="badge text-bg-light border text-primary fw-bold px-3 py-2">FREESHIP</span>
                    <p class="text-muted hint mt-3 mb-0">
                        <i class="fa-solid fa-clock me-1"></i>Valid on all addresses in India
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== DEALS OF THE DAY ========== -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 fw-bold mb-1">Deals of the Day</h2>
            <p class="text-muted hint mb-0">Popular products at their lowest prices this week.</p>
        </div>
        <a href="product.php" class="text-decoration-none">
            See all products <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card store-card h-100">
                <div
                    class="product-img d-flex align-items-center justify-content-center bg-light text-primary position-relative">
                    <span class="badge discount-badge text-bg-danger position-absolute top-0 start-0 m-2">-25%</span>
                    <div class="position-absolute top-0 end-0 m-2 d-flex gap-1">
                        <a href="product-detail.php?product=headphones"
                            class="product-icon-btn btn btn-sm btn-light rounded-circle" title="View product">
                            <i class="fa-regular fa-eye text-secondary"></i>
                        </a>
                        <button class="product-icon-btn btn btn-sm btn-light rounded-circle" title="Add to wishlist">
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
                    <h3 class="h6 fw-bold mt-1">Pulse Wireless Headphones</h3>
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
                    class="product-img d-flex align-items-center justify-content-center bg-light text-success position-relative">
                    <span class="badge discount-badge text-bg-danger position-absolute top-0 start-0 m-2">-28%</span>
                    <div class="position-absolute top-0 end-0 m-2 d-flex gap-1">
                        <a href="product-detail.php?product=smartwatch"
                            class="product-icon-btn btn btn-sm btn-light rounded-circle" title="View product">
                            <i class="fa-regular fa-eye text-secondary"></i>
                        </a>
                        <button class="product-icon-btn btn btn-sm btn-light rounded-circle" title="Add to wishlist">
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
                    <h3 class="h6 fw-bold mt-1">Nova Smartwatch 2</h3>
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
                    class="product-img d-flex align-items-center justify-content-center bg-light text-danger position-relative">
                    <span class="badge discount-badge text-bg-danger position-absolute top-0 start-0 m-2">-33%</span>
                    <div class="position-absolute top-0 end-0 m-2 d-flex gap-1">
                        <a href="product-detail.php?product=tshirt"
                            class="product-icon-btn btn btn-sm btn-light rounded-circle" title="View product">
                            <i class="fa-regular fa-eye text-secondary"></i>
                        </a>
                        <button class="product-icon-btn btn btn-sm btn-light rounded-circle" title="Add to wishlist">
                            <i class="fa-regular fa-heart text-danger"></i>
                        </button>
                        <button class="product-icon-btn btn btn-sm btn-light rounded-circle" title="Add to cart">
                            <i class="fa-solid fa-cart-shopping text-primary"></i>
                        </button>
                    </div>
                    <i class="fa-solid fa-shirt"></i>
                </div>
                <div class="card-body">
                    <small class="text-muted">Fashion</small>
                    <h3 class="h6 fw-bold mt-1">Everywear Cotton T-Shirt</h3>
                    <div class="rating-stars small">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                            class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                            class="fa-solid fa-star"></i>
                        <span class="text-muted">(213)</span>
                    </div>
                    <div class="d-flex align-items-baseline gap-2 mt-2">
                        <span class="fs-5 fw-bold">&#8377;799</span>
                        <span class="price-old hint">&#8377;1,199</span>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card store-card h-100">
                <div
                    class="product-img d-flex align-items-center justify-content-center bg-light text-warning-emphasis position-relative">
                    <span class="badge discount-badge text-bg-danger position-absolute top-0 start-0 m-2">-27%</span>
                    <div class="position-absolute top-0 end-0 m-2 d-flex gap-1">
                        <a href="product-detail.php?product=hamper"
                            class="product-icon-btn btn btn-sm btn-light rounded-circle" title="View product">
                            <i class="fa-regular fa-eye text-secondary"></i>
                        </a>
                        <button class="product-icon-btn btn btn-sm btn-light rounded-circle" title="Add to wishlist">
                            <i class="fa-regular fa-heart text-danger"></i>
                        </button>
                        <button class="product-icon-btn btn btn-sm btn-light rounded-circle" title="Add to cart">
                            <i class="fa-solid fa-cart-shopping text-primary"></i>
                        </button>
                    </div>
                    <i class="fa-solid fa-gem"></i>
                </div>
                <div class="card-body">
                    <small class="text-muted">Gifts</small>
                    <h3 class="h6 fw-bold mt-1">Aura Gift Hamper</h3>
                    <div class="rating-stars small">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                            class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                            class="fa-solid fa-star-half-stroke"></i>
                        <span class="text-muted">(68)</span>
                    </div>
                    <div class="d-flex align-items-baseline gap-2 mt-2">
                        <span class="fs-5 fw-bold">&#8377;1,299</span>
                        <span class="price-old hint">&#8377;1,799</span>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- ========== BANK & WALLET OFFERS ========== -->
    <div class="card store-card mb-4">
        <div class="card-body p-4 p-lg-5">
            <h2 class="h4 fw-bold mb-1">Bank &amp; Wallet Offers</h2>
            <p class="text-muted hint mb-4">Save more with the payment method you already use.</p>

            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <div class="border rounded p-3 h-100">
                        <div class="fs-4 text-primary mb-2"><i class="fa-solid fa-credit-card"></i></div>
                        <h3 class="h6 fw-bold mb-1">10% Instant Discount</h3>
                        <p class="text-muted hint mb-0">On HDFC Bank credit cards. Maximum saving &#8377;1,000.</p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="border rounded p-3 h-100">
                        <div class="fs-4 text-success mb-2"><i class="fa-solid fa-mobile-screen-button"></i></div>
                        <h3 class="h6 fw-bold mb-1">UPI Cashback</h3>
                        <p class="text-muted hint mb-0">Get up to &#8377;200 cashback on your first UPI payment.</p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="border rounded p-3 h-100">
                        <div class="fs-4 text-danger mb-2"><i class="fa-solid fa-mobile"></i></div>
                        <h3 class="h6 fw-bold mb-1">App-Only Extra 5%</h3>
                        <p class="text-muted hint mb-0">Additional 5% off when you shop through the TrendCart app.</p>
                    </div>
                </div>
            </div>

            <p class="text-muted hint mt-4 mb-0">
                <i class="fa-solid fa-circle-info text-primary me-1"></i>
                Only one coupon can be used per order. Offers cannot be combined with each other.
            </p>
        </div>
    </div>

    <!-- ========== CTA ========== -->
    <div class="bg-light rounded-3 p-4 p-lg-5 text-center">
        <div class="fs-1 text-primary mb-3"><i class="fa-solid fa-bolt"></i></div>
        <h2 class="h3 fw-bold">Ready to grab a deal?</h2>
        <p class="text-muted mb-4">Browse the full collection and find something you'll love.</p>
        <a href="product.php" class="btn btn-primary btn-lg px-4">
            <i class="fa-solid fa-cart-shopping me-2"></i>Shop All Products
        </a>
    </div>
</div>

<?php
// STEP 3: Save the captured HTML and stop buffering.
$page_content = ob_get_clean();

// STEP 4: Render it inside the shared layout.
require 'layout.php';