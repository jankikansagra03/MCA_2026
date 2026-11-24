<?php
/*
 * order-detail.php - ONE ORDER'S DETAILS
 * A static example order with items, tracking steps and a price summary.
 */

// STEP 1: Sample order data used by the table and summary.
$order_items = [
    [
        'slug'    => 'headphones',
        'icon'    => 'fa-headphones',
        'name'    => 'Pulse Wireless Headphones',
        'meta'    => 'Electronics &middot; Black',
        'qty'     => 1,
        'price'   => '2,499',
    ],
    [
        'slug'    => 'smartwatch',
        'icon'    => 'fa-stopwatch',
        'name'    => 'Nova Smartwatch 2',
        'meta'    => 'Electronics &middot; Graphite',
        'qty'     => 1,
        'price'   => '4,999',
    ],
];

// STEP 2: Settings used by the shared layout.
$page_title     = 'Order Details | TrendCart';
$active_page    = 'orders';
$account_active = 'orders';

// STEP 3: Start capturing this page's HTML.
ob_start();
?>

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="account.php" class="text-decoration-none">My Account</a></li>
            <li class="breadcrumb-item"><a href="orders.php" class="text-decoration-none">My Orders</a></li>
            <li class="breadcrumb-item active" aria-current="page">Order Details</li>
        </ol>
    </nav>

    <div class="page-header">
        <span class="badge text-bg-primary mb-3 px-3 py-2">
            <i class="fa-solid fa-file-invoice me-1"></i>ORDER #TC-48291
        </span>
        <h1 class="h2 fw-bold mb-3">Order Details</h1>
        <p class="text-muted">Placed on 18 Nov 2026 &middot; Delivered on 21 Nov 2026</p>
    </div>

    <div class="row g-4">
        <!-- ========== SHARED SIDEBAR ========== -->
        <aside class="col-12 col-lg-3">
            <?php require 'account-nav.php'; ?>
        </aside>

        <div class="col-12 col-lg-9">
            <!-- Order banner -->
            <div class="card store-card overflow-hidden mb-4">
                <div class="order-banner p-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="feature-icon bg-white text-primary">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <div>
                            <p class="hint mb-1">ORDER #TC-48291</p>
                            <h2 class="h5 fw-bold mb-1">Delivered successfully</h2>
                            <p class="hint mb-0">2 items &middot; Delivered on 21 Nov 2026</p>
                        </div>
                    </div>
                    <div class="text-end">
                        <p class="hint mb-1">Total paid</p>
                        <p class="h4 fw-bold mb-2">&#8377;6,998</p>
                        <button type="button" class="btn btn-light btn-sm px-3 text-primary fw-semibold">
                            <i class="fa-solid fa-file-invoice me-1"></i>Download Invoice
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tracking timeline -->
            <div class="card store-card mb-4">
                <div class="card-body p-4">
                    <div class="section-heading mb-4">
                        <div class="feature-icon"><i class="fa-solid fa-truck"></i></div>
                        <div class="me-auto">
                            <h2 class="h6 fw-bold mb-0">Delivery Status</h2>
                            <p class="hint text-muted mb-0">Every step of your delivery, in one place.</p>
                        </div>
                        <span class="badge text-bg-primary px-3 py-2">
                            <i class="fa-solid fa-circle-check me-1"></i>Delivered
                        </span>
                    </div>

                    <div class="track">
                        <div class="track-step done">
                            <div class="track-dot"><i class="fa-solid fa-check"></i></div>
                            <p class="hint fw-semibold mt-2 mb-0">Order Placed</p>
                            <p class="hint text-muted mb-0">18 Nov, 10:24 AM</p>
                        </div>
                        <div class="track-step done">
                            <div class="track-dot"><i class="fa-solid fa-check"></i></div>
                            <p class="hint fw-semibold mt-2 mb-0">Packed</p>
                            <p class="hint text-muted mb-0">18 Nov, 04:10 PM</p>
                        </div>
                        <div class="track-step done">
                            <div class="track-dot"><i class="fa-solid fa-check"></i></div>
                            <p class="hint fw-semibold mt-2 mb-0">Shipped</p>
                            <p class="hint text-muted mb-0">19 Nov, 09:05 AM</p>
                        </div>
                        <div class="track-step done">
                            <div class="track-dot"><i class="fa-solid fa-check"></i></div>
                            <p class="hint fw-semibold mt-2 mb-0">Out for Delivery</p>
                            <p class="hint text-muted mb-0">21 Nov, 08:30 AM</p>
                        </div>
                        <div class="track-step current">
                            <div class="track-dot"><i class="fa-solid fa-house"></i></div>
                            <p class="hint fw-semibold mt-2 mb-0">Delivered</p>
                            <p class="hint text-muted mb-0">21 Nov, 02:45 PM</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items in this order -->
            <div class="card store-card mb-4">
                <div class="card-body p-4">
                    <div class="section-heading mb-4">
                        <div class="feature-icon"><i class="fa-solid fa-box-archive"></i></div>
                        <div>
                            <h2 class="h6 fw-bold mb-0">Items in this Order</h2>
                            <p class="hint text-muted mb-0">Products delivered in this shipment.</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table account-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order_items as $item) : ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div
                                                    class="product-img order-thumb rounded d-flex align-items-center justify-content-center">
                                                    <i class="fa-solid <?= $item['icon'] ?> text-primary"></i>
                                                </div>
                                                <div>
                                                    <p class="fw-bold mb-1"><?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8') ?></p>
                                                    <p class="hint text-muted mb-2"><?= $item['meta'] ?></p>
                                                    <div class="d-flex gap-1">
                                                        <a href="product-detail.php?product=<?= $item['slug'] ?>"
                                                           class="product-icon-btn btn btn-sm btn-outline-primary rounded-circle"
                                                           title="View product"><i class="fa-regular fa-eye"></i></a>
                                                        <button class="product-icon-btn btn btn-sm btn-outline-primary rounded-circle"
                                                                title="Add to wishlist"><i class="fa-regular fa-heart"></i></button>
                                                        <a href="cart.php"
                                                           class="product-icon-btn btn btn-sm btn-outline-primary rounded-circle"
                                                           title="Add to cart"><i class="fa-solid fa-cart-shopping"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center"><?= $item['qty'] ?></td>
                                        <td class="text-end fw-bold">&#8377;<?= $item['price'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-12 col-xl-7">
                    <div class="card store-card h-100">
                        <div class="card-body p-4">
                            <div class="section-heading mb-4">
                                <div class="feature-icon"><i class="fa-solid fa-location-dot"></i></div>
                                <div>
                                    <h2 class="h6 fw-bold mb-0">Delivery &amp; Payment</h2>
                                    <p class="hint text-muted mb-0">Where it arrived and how you paid.</p>
                                </div>
                            </div>

                            <p class="hint text-uppercase text-muted fw-bold mb-1">Delivery address</p>
                            <p class="fw-bold mb-1">Aarav Mehta</p>
                            <p class="hint text-muted mb-1">+91 98765 43210</p>
                            <p class="hint mb-3">
                                402, Sunrise Residency<br>
                                12 Palm Grove Road, Indiranagar<br>
                                Bengaluru, Karnataka 560038
                            </p>
                            <hr>
                            <p class="hint text-uppercase text-muted fw-bold mb-1">Payment method</p>
                            <p class="mb-0"><i class="fa-solid fa-wallet text-primary me-2"></i>UPI &middot; Paid on 18 Nov 2026</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-5">
                    <div class="card store-card h-100">
                        <div class="card-body p-4">
                            <div class="section-heading mb-4">
                                <div class="feature-icon"><i class="fa-solid fa-file-invoice"></i></div>
                                <div>
                                    <h2 class="h6 fw-bold mb-0">Price Details</h2>
                                    <p class="hint text-muted mb-0">Final amount paid for this order.</p>
                                </div>
                            </div>

                            <div class="summary-row hint mb-2"><span class="text-muted">Subtotal</span><span>&#8377;7,498</span></div>
                            <div class="summary-row hint mb-2"><span class="text-muted">Delivery</span><span>Free</span></div>
                            <div class="summary-row hint mb-3"><span class="text-muted">Coupon WELCOME10</span><span>&minus;&#8377;500</span></div>
                            <div class="summary-row summary-total fw-bold"><span>Total Paid</span><span>&#8377;6,998</span></div>

                            <div class="alert alert-primary border-0 hint mt-3 mb-0 py-2">
                                <i class="fa-solid fa-piggy-bank me-1"></i>You saved &#8377;800 on this order.
                            </div>

                            <div class="d-flex flex-wrap gap-2 mt-4">
                                <button type="button" class="btn btn-primary btn-sm px-3">
                                    <i class="fa-solid fa-file-invoice me-1"></i>Download Invoice
                                </button>
                                <a href="contact.php" class="btn btn-outline-primary btn-sm px-3">
                                    <i class="fa-solid fa-headset me-1"></i>Need Help?
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="orders.php" class="btn btn-outline-primary btn-sm px-3">
                    <i class="fa-solid fa-arrow-left me-1"></i>Back to My Orders
                </a>
            </div>
        </div>
    </div>
</div>

<?php
// STEP 4: Save the captured HTML and stop buffering.
$page_content = ob_get_clean();

// STEP 5: Render it inside the shared layout.
require 'layout.php';
