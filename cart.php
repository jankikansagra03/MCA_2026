<?php
/*
 * cart.php - SHOPPING CART
 * Static cart rows, quantity controls and a sticky price summary.
 */

// STEP 1: Settings used by the shared layout.
$page_title     = 'Shopping Cart | TrendCart';
$active_page    = 'cart';
$account_active = 'cart';

// STEP 2: Start capturing this page's HTML.
ob_start();
?>

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
        </ol>
    </nav>

    <div class="page-header">
        <span class="badge text-bg-primary mb-3 px-3 py-2">
            <i class="fa-solid fa-cart-shopping me-1"></i>YOUR CART
        </span>
        <h1 class="h2 fw-bold mb-3">Shopping Cart</h1>
        <p class="text-muted">Review your picks, adjust quantities and check out when you are ready.</p>
    </div>

    <div class="row g-4">
        <!-- ========== SHARED SIDEBAR ========== -->
        <aside class="col-12 col-lg-3">
            <?php require 'account-nav.php'; ?>
        </aside>

        <div class="col-12 col-lg-9">
            <!-- Delivery strip -->
            <div class="card store-card mb-4">
                <div class="card-body p-3 d-flex flex-wrap align-items-center gap-3">
                    <div class="feature-icon"><i class="fa-solid fa-truck"></i></div>
                    <div class="me-auto">
                        <p class="fw-bold mb-0">Delivering to Aarav Mehta &middot; 380001</p>
                        <p class="hint text-muted mb-0">
                            Free delivery on this order. Estimated arrival <strong>22&ndash;24 Nov</strong>.
                        </p>
                    </div>
                    <a href="addresses.php" class="btn btn-sm btn-outline-primary">Change address</a>
                </div>
            </div>

            <div class="row g-4">
                <!-- ========== CART ITEMS ========== -->
                <div class="col-12 col-xl-8">
                    <div class="card store-card">
                        <div class="card-body p-3 p-sm-4 border-bottom">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                <h2 class="h6 fw-bold mb-0">
                                    Items in your cart <span class="badge text-bg-primary ms-1">3</span>
                                </h2>
                                <a href="product.php" class="hint text-decoration-none fw-semibold">
                                    <i class="fa-solid fa-arrow-left me-1"></i>Continue shopping
                                </a>
                            </div>
                        </div>

                        <div class="cart-items">
                            <!-- Cart item 1 -->
                            <div class="cart-item">
                                <div class="row g-3 align-items-center">
                                    <div class="col-auto">
                                        <div
                                            class="product-img order-thumb rounded d-flex align-items-center justify-content-center position-relative">
                                            <span
                                                class="badge discount-badge text-bg-primary position-absolute top-0 start-0 m-1">-24%</span>
                                            <i class="fa-solid fa-headphones text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                            <span class="stock-pill">In stock</span>
                                            <small class="text-muted">Electronics</small>
                                        </div>
                                        <h3 class="h6 fw-bold mb-1">Pulse Wireless Headphones</h3>
                                        <p class="hint text-muted mb-2">Black &middot; Sold by TrendCart</p>
                                        <div class="d-flex gap-1">
                                            <a href="product-detail.php?product=headphones"
                                                class="product-icon-btn btn btn-sm btn-outline-primary rounded-circle"
                                                title="View product"><i class="fa-regular fa-eye"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-auto">
                                        <div class="qty-stepper d-flex align-items-center gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                aria-label="Decrease quantity"><i
                                                    class="fa-solid fa-minus"></i></button>
                                            <input type="text" class="form-control form-control-sm" value="1"
                                                aria-label="Quantity" readonly data-validation="required numeric min" data-min="1">
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                aria-label="Increase quantity"><i class="fa-solid fa-plus"></i></button>
                                        </div>
                                    </div>
                                    <div class="col-auto text-end">
                                        <p class="fw-bold mb-0">&#8377;2,499</p>
                                        <p class="price-old hint mb-1">&#8377;3,299</p>
                                        <button type="button" class="btn btn-link btn-sm text-primary p-0 hint">Save for
                                            later</button>
                                        <button type="button"
                                            class="btn btn-link btn-sm text-muted p-0 hint d-block">Remove</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Cart item 2 -->
                            <div class="cart-item">
                                <div class="row g-3 align-items-center">
                                    <div class="col-auto">
                                        <div
                                            class="product-img order-thumb rounded d-flex align-items-center justify-content-center position-relative">
                                            <span
                                                class="badge discount-badge text-bg-primary position-absolute top-0 start-0 m-1">-29%</span>
                                            <i class="fa-solid fa-stopwatch text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                            <span class="stock-pill">In stock</span>
                                            <small class="text-muted">Electronics</small>
                                        </div>
                                        <h3 class="h6 fw-bold mb-1">Nova Smartwatch 2</h3>
                                        <p class="hint text-muted mb-2">Graphite &middot; Sold by TrendCart</p>
                                        <div class="d-flex gap-1">
                                            <a href="product-detail.php?product=smartwatch"
                                                class="product-icon-btn btn btn-sm btn-outline-primary rounded-circle"
                                                title="View product"><i class="fa-regular fa-eye"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-auto">
                                        <div class="qty-stepper d-flex align-items-center gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                aria-label="Decrease quantity"><i
                                                    class="fa-solid fa-minus"></i></button>
                                            <input type="text" class="form-control form-control-sm" value="1"
                                                aria-label="Quantity" readonly data-validation="required numeric min" data-min="1">
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                aria-label="Increase quantity"><i class="fa-solid fa-plus"></i></button>
                                        </div>
                                    </div>
                                    <div class="col-auto text-end">
                                        <p class="fw-bold mb-0">&#8377;4,999</p>
                                        <p class="price-old hint mb-1">&#8377;6,999</p>
                                        <button type="button" class="btn btn-link btn-sm text-primary p-0 hint">Save for
                                            later</button>
                                        <button type="button"
                                            class="btn btn-link btn-sm text-muted p-0 hint d-block">Remove</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Cart item 3 -->
                            <div class="cart-item">
                                <div class="row g-3 align-items-center">
                                    <div class="col-auto">
                                        <div
                                            class="product-img order-thumb rounded d-flex align-items-center justify-content-center position-relative">
                                            <span
                                                class="badge discount-badge text-bg-primary position-absolute top-0 start-0 m-1">-33%</span>
                                            <i class="fa-solid fa-shirt text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                            <span class="stock-pill">In stock</span>
                                            <small class="text-muted">Fashion</small>
                                        </div>
                                        <h3 class="h6 fw-bold mb-1">Everywear Cotton T-Shirt</h3>
                                        <p class="hint text-muted mb-2">Navy &middot; Size M &middot; Sold by TrendCart
                                        </p>
                                        <div class="d-flex gap-1">
                                            <a href="product-detail.php?product=tshirt"
                                                class="product-icon-btn btn btn-sm btn-outline-primary rounded-circle"
                                                title="View product"><i class="fa-regular fa-eye"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-auto">
                                        <div class="qty-stepper d-flex align-items-center gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                aria-label="Decrease quantity"><i
                                                    class="fa-solid fa-minus"></i></button>
                                            <input type="text" class="form-control form-control-sm" value="2"
                                                aria-label="Quantity" readonly data-validation="required numeric min" data-min="1">
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                aria-label="Increase quantity"><i class="fa-solid fa-plus"></i></button>
                                        </div>
                                    </div>
                                    <div class="col-auto text-end">
                                        <p class="fw-bold mb-0">&#8377;1,598</p>
                                        <p class="hint text-muted mb-1">&#8377;799 each</p>
                                        <button type="button" class="btn btn-link btn-sm text-primary p-0 hint">Save for
                                            later</button>
                                        <button type="button"
                                            class="btn btn-link btn-sm text-muted p-0 hint d-block">Remove</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                    <!-- Small add-on suggestions -->

                </div>

                <!-- ========== PRICE SUMMARY ========== -->
                <div class="col-12 col-xl-4">
                    <div class="card store-card cart-summary">
                        <div class="card-body p-4">
                            <h2 class="h6 fw-bold mb-3">Price Details</h2>

                            <div class="summary-row hint mb-2">
                                <span class="text-muted">Subtotal (4 items)</span><span>&#8377;9,096</span>
                            </div>
                            <div class="summary-row hint mb-2">
                                <span class="text-muted">Product discount</span><span
                                    class="text-primary">&minus;&#8377;3,600</span>
                            </div>
                            <div class="summary-row hint mb-2">
                                <span class="text-muted">Coupon WELCOME10</span><span
                                    class="text-primary">&minus;&#8377;500</span>
                            </div>
                            <div class="summary-row hint mb-3">
                                <span class="text-muted">Delivery charges</span><span>Free</span>
                            </div>
                            <div class="summary-row summary-total fw-bold">
                                <span>Total Amount</span><span>&#8377;8,596</span>
                            </div>

                            <div class="alert alert-primary border-0 hint mt-3 mb-3 py-2">
                                <i class="fa-solid fa-piggy-bank me-1"></i>You save &#8377;4,100 on this order.
                            </div>

                            <label for="couponCode" class="form-label small fw-semibold">Have a coupon?</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fa-solid fa-ticket"></i></span>
                                <input type="text" class="form-control" id="couponCode" name="couponCode"
                                       placeholder="Enter code" data-validation="max" data-max="20">
                                <button class="btn btn-outline-primary" type="button">Apply</button>
                            </div>
                            <div class="invalid-feedback" id="couponCodeError"></div>

                            <a href="checkout.php" class="btn btn-primary w-100 py-2 fw-semibold">
                                <i class="fa-solid fa-arrow-right me-2"></i>Proceed to Checkout
                            </a>
                            <a href="product.php" class="btn btn-outline-primary w-100 mt-2">
                                <i class="fa-solid fa-layer-group me-1"></i>Shop More
                            </a>

                            <p class="hint text-muted text-center mt-3 mb-0">
                                <i class="fa-solid fa-lock me-1"></i>Safe and secure checkout
                            </p>
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