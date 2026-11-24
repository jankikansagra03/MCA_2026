<?php
/*
 * checkout.php - CHECKOUT PREVIEW
 * Delivery address, payment choice and an order summary. Nothing is processed.
 */

// STEP 1: Settings used by the shared layout.
$page_title     = 'Checkout | TrendCart';
$active_page    = 'checkout';
$account_active = 'cart';

// STEP 2: Start capturing this page's HTML.
ob_start();
?>

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="cart.php" class="text-decoration-none">Shopping Cart</a></li>
            <li class="breadcrumb-item active" aria-current="page">Checkout</li>
        </ol>
    </nav>

    <div class="page-header">
        <span class="badge text-bg-primary mb-3 px-3 py-2">
            <i class="fa-solid fa-credit-card me-1"></i>CHECKOUT
        </span>
        <h1 class="h2 fw-bold mb-3">Complete Your Order</h1>
        <p class="text-muted">Confirm your delivery address and pick a payment method.</p>
    </div>

    <div class="row g-4">
        <!-- ========== SHARED SIDEBAR ========== -->
        <aside class="col-12 col-lg-3">
            <?php require 'account-nav.php'; ?>
        </aside>

        <div class="col-12 col-lg-9">
            <!-- Secure preview strip -->
            <div class="card store-card mb-4">
                <div class="card-body p-3 d-flex flex-wrap align-items-center gap-3">
                    <div class="feature-icon"><i class="fa-solid fa-lock"></i></div>
                    <div class="me-auto">
                        <p class="fw-bold mb-0">Secure checkout preview</p>
                        <p class="hint text-muted mb-0">This demo does not take payment or create a real order.</p>
                    </div>
                    <span class="stock-pill">No payment taken</span>
                </div>
            </div>

            <!-- Checkout progress -->
            <div class="card store-card mb-4">
                <div class="card-body p-4">
                    <div class="section-heading mb-4">
                        <div class="feature-icon"><i class="fa-solid fa-route"></i></div>
                        <div>
                            <h2 class="h6 fw-bold mb-0">Checkout Progress</h2>
                            <p class="hint text-muted mb-0">You are one step away from confirming your order.</p>
                        </div>
                    </div>
                    <div class="track">
                        <div class="track-step done">
                            <div class="track-dot"><i class="fa-solid fa-check"></i></div>
                            <p class="hint fw-semibold mt-2 mb-0">Cart</p>
                        </div>
                        <div class="track-step done">
                            <div class="track-dot"><i class="fa-solid fa-check"></i></div>
                            <p class="hint fw-semibold mt-2 mb-0">Address</p>
                        </div>
                        <div class="track-step current">
                            <div class="track-dot"><i class="fa-solid fa-credit-card"></i></div>
                            <p class="hint fw-semibold mt-2 mb-0">Payment</p>
                        </div>
                        <div class="track-step">
                            <div class="track-dot"><i class="fa-solid fa-flag-checkered"></i></div>
                            <p class="hint fw-semibold mt-2 mb-0">Confirmation</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-12 col-xl-7">
                    <form action="#" method="post" novalidate>
                        <!-- Delivery address -->
                        <div class="card store-card mb-4">
                            <div class="card-body p-4">
                                <div class="section-heading mb-3">
                                    <div class="feature-icon"><i class="fa-solid fa-location-dot"></i></div>
                                    <div class="me-auto">
                                        <h2 class="h6 fw-bold mb-0">Delivery Address</h2>
                                        <p class="hint text-muted mb-0">Where should we send your order?</p>
                                    </div>
                                    <a href="addresses.php" class="hint text-decoration-none fw-semibold">
                                        <i class="fa-solid fa-pen-to-square me-1"></i>Change
                                    </a>
                                </div>

                                <div class="form-check p-3 address-choice">
                                    <input class="form-check-input" type="radio" name="address" id="homeAddress" checked
                                           data-validation="required">
                                    <label class="form-check-label w-100" for="homeAddress">
                                        <span class="fw-bold">Home <span class="badge address-default-flag ms-2">DEFAULT</span></span>
                                        <span class="hint text-muted d-block mt-1">
                                            Aarav Mehta &middot; +91 98765 43210<br>
                                            402, Sunrise Residency, 12 Palm Grove Road,<br>
                                            Indiranagar, Bengaluru, Karnataka 560038
                                        </span>
                                    </label>
                                </div>
                                <div class="invalid-feedback" id="addressError"></div>
                            </div>
                        </div>

                        <!-- Payment method -->
                        <div class="card store-card mb-4">
                            <div class="card-body p-4">
                                <div class="section-heading mb-3">
                                    <div class="feature-icon"><i class="fa-solid fa-wallet"></i></div>
                                    <div>
                                        <h2 class="h6 fw-bold mb-0">Payment Method</h2>
                                        <p class="hint text-muted mb-0">Choose how you would like to pay.</p>
                                    </div>
                                </div>

                                <div class="form-check payment-option is-selected mb-2">
                                    <input class="form-check-input" type="radio" name="payment" id="paymentUpi" checked
                                           data-validation="required">
                                    <label class="form-check-label w-100" for="paymentUpi">
                                        <span class="pref-icon text-primary"><i class="fa-solid fa-wallet"></i></span>
                                        <span class="me-auto">
                                            <strong class="d-block small">UPI</strong>
                                            <span class="hint text-muted">Pay with any UPI app</span>
                                        </span>
                                    </label>
                                </div>

                                <div class="form-check payment-option mb-2">
                                    <input class="form-check-input" type="radio" name="payment" id="paymentCard"
                                           data-validation="required">
                                    <label class="form-check-label w-100" for="paymentCard">
                                        <span class="pref-icon text-primary"><i class="fa-solid fa-credit-card"></i></span>
                                        <span class="me-auto">
                                            <strong class="d-block small">Credit / Debit Card</strong>
                                            <span class="hint text-muted">Visa, Mastercard or RuPay</span>
                                        </span>
                                    </label>
                                </div>

                                <div class="form-check payment-option">
                                    <input class="form-check-input" type="radio" name="payment" id="paymentCod"
                                           data-validation="required">
                                    <label class="form-check-label w-100" for="paymentCod">
                                        <span class="pref-icon text-primary"><i class="fa-solid fa-money-bill-wave"></i></span>
                                        <span class="me-auto">
                                            <strong class="d-block small">Cash on Delivery</strong>
                                            <span class="hint text-muted">Pay when your order arrives</span>
                                        </span>
                                    </label>
                                </div>
                                <div class="invalid-feedback" id="paymentError"></div>
                            </div>
                        </div>

                        <!-- Instructions and confirmation -->
                        <div class="card store-card">
                            <div class="card-body p-4">
                                <div class="section-heading mb-3">
                                    <div class="feature-icon"><i class="fa-solid fa-pen-to-square"></i></div>
                                    <div>
                                        <h2 class="h6 fw-bold mb-0">Delivery Instructions</h2>
                                        <p class="hint text-muted mb-0">Optional note for our delivery partner.</p>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="deliveryNote" class="visually-hidden">Delivery instructions</label>
                                    <textarea class="form-control" id="deliveryNote" name="deliveryNote" rows="2"
                                              placeholder="Example: Leave at the door, ring the bell twice."
                                              data-validation="max" data-max="200"></textarea>
                                    <div class="invalid-feedback" id="deliveryNoteError"></div>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="placeOrderTerms" name="terms"
                                           data-validation="required">
                                    <label class="form-check-label hint" for="placeOrderTerms">
                                        I agree to the <a href="about.php" class="text-decoration-none">Terms of Service</a>
                                        and <a href="faq.php" class="text-decoration-none">Returns Policy</a>.
                                    </label>
                                    <div class="invalid-feedback" id="termsError"></div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                                    <i class="fa-solid fa-shield-halved me-2"></i>Place Order
                                </button>
                                <p class="hint text-muted text-center mt-3 mb-0">Preview only — no payment is taken.</p>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Order summary -->
                <div class="col-12 col-xl-5">
                    <div class="card store-card checkout-summary">
                        <div class="card-body p-4">
                            <div class="section-heading mb-4">
                                <div class="feature-icon"><i class="fa-solid fa-file-invoice"></i></div>
                                <div>
                                    <h2 class="h6 fw-bold mb-0">Order Summary</h2>
                                    <p class="hint text-muted mb-0">3 items in this order.</p>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="product-img order-thumb rounded d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-headphones text-primary"></i>
                                </div>
                                <div class="flex-fill">
                                    <p class="small fw-bold mb-0">Pulse Wireless Headphones</p>
                                    <p class="hint text-muted mb-0">Qty 1</p>
                                </div>
                                <span class="fw-bold">&#8377;2,499</span>
                            </div>

                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="product-img order-thumb rounded d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-stopwatch text-primary"></i>
                                </div>
                                <div class="flex-fill">
                                    <p class="small fw-bold mb-0">Nova Smartwatch 2</p>
                                    <p class="hint text-muted mb-0">Qty 1</p>
                                </div>
                                <span class="fw-bold">&#8377;4,999</span>
                            </div>

                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="product-img order-thumb rounded d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-shirt text-primary"></i>
                                </div>
                                <div class="flex-fill">
                                    <p class="small fw-bold mb-0">Everywear Cotton T-Shirt</p>
                                    <p class="hint text-muted mb-0">Qty 2</p>
                                </div>
                                <span class="fw-bold">&#8377;1,598</span>
                            </div>

                            <div class="summary-row hint mb-2"><span class="text-muted">Subtotal</span><span>&#8377;9,096</span></div>
                            <div class="summary-row hint mb-2"><span class="text-muted">Product discount</span><span class="text-primary">&minus;&#8377;3,600</span></div>
                            <div class="summary-row hint mb-2"><span class="text-muted">Coupon WELCOME10</span><span class="text-primary">&minus;&#8377;500</span></div>
                            <div class="summary-row hint mb-3"><span class="text-muted">Delivery</span><span>Free</span></div>
                            <div class="summary-row summary-total fw-bold"><span>Total</span><span>&#8377;8,596</span></div>

                            <div class="alert alert-primary border-0 hint mt-3 mb-0 py-2">
                                <i class="fa-solid fa-piggy-bank me-1"></i>You save &#8377;4,100 on this order.
                            </div>

                            <a href="cart.php" class="btn btn-outline-primary w-100 mt-4">
                                <i class="fa-solid fa-arrow-left me-1"></i>Back to Cart
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
