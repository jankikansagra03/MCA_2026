<?php
/*
 * order-detail.php - ORDER DETAILS & INVOICE
 * Dynamic view of a specific customer order and its purchased items.
 */

require_once __DIR__ . '/check_auth.php';
require_once __DIR__ . '/db.php';

$user     = current_user();
$user_id  = $user['id'];
$order_id = (int) ($_GET['id'] ?? 0);

// Fetch order
$order = null;
if ($order_id > 0) {
    try {
        $order = db_one("SELECT * FROM orders WHERE id = ? AND user_id = ? LIMIT 1", [$order_id, $user_id]);
    } catch (PDOException $e) {
        $order = null;
    }
}

// Fetch order items if order exists
$order_items = [];
if ($order) {
    try {
        $order_items = db_all("SELECT oi.*, p.slug FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?", [$order['id']]);
    } catch (PDOException $e) {
        $order_items = [];
    }
}

// Helper for status details
$status = strtolower($order['status'] ?? 'pending');
$is_delivered = ($status === 'delivered');
$is_shipped   = in_array($status, ['shipped', 'delivered'], true);
$is_processed = in_array($status, ['processing', 'shipped', 'delivered'], true);

// STEP 2: Settings used by the shared layout.
$order_num      = $order ? (!empty($order['order_number']) ? $order['order_number'] : ('TC-' . str_pad((string)$order['id'], 5, '0', STR_PAD_LEFT))) : 'Order Details';
$page_title     = "Order #{$order_num} | TrendCart";
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

    <?php if (!$order): ?>
        <div class="card store-card text-center p-5 my-4">
            <div class="feature-icon mx-auto mb-3 text-danger">
                <i class="fa-solid fa-circle-exclamation fa-2x"></i>
            </div>
            <h2 class="h4 fw-bold mb-2">Order Not Found</h2>
            <p class="text-muted hint mb-4">The order you are looking for does not exist or belongs to another account.</p>
            <div>
                <a href="orders.php" class="btn btn-primary px-4">
                    <i class="fa-solid fa-arrow-left me-2"></i>Back to My Orders
                </a>
            </div>
        </div>
    <?php else: ?>
        <?php
            $order_date = date('d M Y, h:i A', strtotime($order['created_at']));
            $total_paid = function_exists('money') ? money($order['total_paise']) : ('&#8377;' . number_format(((float)$order['total_paise']) / 100, 2));
            $subtotal   = function_exists('money') ? money($order['subtotal_paise']) : ('&#8377;' . number_format(((float)$order['subtotal_paise']) / 100, 2));
            $discount   = function_exists('money') ? money($order['discount_paise']) : ('&#8377;' . number_format(((float)$order['discount_paise']) / 100, 2));
            $shipping   = ($order['shipping_paise'] > 0) ? (function_exists('money') ? money($order['shipping_paise']) : ('&#8377;' . number_format(((float)$order['shipping_paise']) / 100, 2))) : 'Free';
        ?>

        <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <span class="badge text-bg-primary mb-2 px-3 py-2">
                    <i class="fa-solid fa-file-invoice me-1"></i>ORDER #<?= htmlspecialchars($order_num) ?>
                </span>
                <h1 class="h2 fw-bold mb-1">Order Details</h1>
                <p class="text-muted mb-0">Placed on <?= $order_date ?></p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-primary btn-sm px-3" onclick="window.print();">
                    <i class="fa-solid fa-print me-1"></i>Print Invoice
                </button>
                <a href="orders.php" class="btn btn-primary btn-sm px-3">
                    <i class="fa-solid fa-arrow-left me-1"></i>All Orders
                </a>
            </div>
        </div>

        <div class="row g-4 mt-1">
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
                                <i class="fa-solid <?= $is_delivered ? 'fa-circle-check text-success' : 'fa-truck text-primary' ?>"></i>
                            </div>
                            <div>
                                <p class="hint mb-1">ORDER #<?= htmlspecialchars($order_num) ?></p>
                                <h2 class="h5 fw-bold mb-1"><?= ucfirst(htmlspecialchars($order['status'])) ?></h2>
                                <p class="hint mb-0"><?= count($order_items) ?> item<?= count($order_items) === 1 ? '' : 's' ?> &middot; Status: <?= ucfirst(htmlspecialchars($order['status'])) ?></p>
                            </div>
                        </div>
                        <div class="text-end">
                            <p class="hint mb-1">Total Paid</p>
                            <p class="h4 fw-bold mb-0 text-primary"><?= $total_paid ?></p>
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
                                <p class="hint text-muted mb-0">Every step of your delivery progress.</p>
                            </div>
                            <span class="badge text-bg-primary px-3 py-2">
                                <i class="fa-solid fa-circle-check me-1"></i><?= ucfirst(htmlspecialchars($order['status'])) ?>
                            </span>
                        </div>

                        <div class="track">
                            <div class="track-step done">
                                <div class="track-dot"><i class="fa-solid fa-check"></i></div>
                                <p class="hint fw-semibold mt-2 mb-0">Order Placed</p>
                                <p class="hint text-muted mb-0"><?= date('d M', strtotime($order['created_at'])) ?></p>
                            </div>
                            <div class="track-step <?= $is_processed ? 'done' : '' ?>">
                                <div class="track-dot"><i class="fa-solid fa-box-open"></i></div>
                                <p class="hint fw-semibold mt-2 mb-0">Processing</p>
                                <p class="hint text-muted mb-0"><?= $is_processed ? 'Confirmed' : 'Pending' ?></p>
                            </div>
                            <div class="track-step <?= $is_shipped ? 'done' : '' ?>">
                                <div class="track-dot"><i class="fa-solid fa-truck"></i></div>
                                <p class="hint fw-semibold mt-2 mb-0">Shipped</p>
                                <p class="hint text-muted mb-0"><?= $is_shipped ? 'On the way' : 'Pending' ?></p>
                            </div>
                            <div class="track-step <?= $is_delivered ? 'done' : '' ?>">
                                <div class="track-dot"><i class="fa-solid fa-house"></i></div>
                                <p class="hint fw-semibold mt-2 mb-0">Delivered</p>
                                <p class="hint text-muted mb-0"><?= $is_delivered ? 'Completed' : 'Pending' ?></p>
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
                                <p class="hint text-muted mb-0">Products purchased in this order.</p>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table account-table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Price</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($order_items)): ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">
                                                No item snapshots available for this order.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($order_items as $item) : ?>
                                            <?php
                                                $item_price = function_exists('money') ? money($item['price_paise']) : ('&#8377;' . number_format(((float)$item['price_paise']) / 100, 2));
                                                $item_sub   = function_exists('money') ? money($item['price_paise'] * $item['qty']) : ('&#8377;' . number_format(((float)($item['price_paise'] * $item['qty'])) / 100, 2));
                                            ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="product-img order-thumb rounded d-flex align-items-center justify-content-center bg-light">
                                                            <i class="fa-solid fa-bag-shopping text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <p class="fw-bold mb-0"><?= htmlspecialchars($item['name_snapshot'], ENT_QUOTES, 'UTF-8') ?></p>
                                                            <?php if (!empty($item['product_id'])): ?>
                                                                <a href="product-detail.php?id=<?= $item['product_id'] ?>" class="hint text-primary text-decoration-none">View Product</a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center fw-semibold"><?= (int) $item['qty'] ?></td>
                                                <td class="text-end text-muted"><?= $item_price ?></td>
                                                <td class="text-end fw-bold"><?= $item_sub ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Delivery & Payment -->
                    <div class="col-12 col-xl-7">
                        <div class="card store-card h-100">
                            <div class="card-body p-4">
                                <div class="section-heading mb-4">
                                    <div class="feature-icon"><i class="fa-solid fa-location-dot"></i></div>
                                    <div>
                                        <h2 class="h6 fw-bold mb-0">Delivery &amp; Payment</h2>
                                        <p class="hint text-muted mb-0">Shipping address and payment method.</p>
                                    </div>
                                </div>

                                <p class="hint text-uppercase text-muted fw-bold mb-1">Delivery Address</p>
                                <div class="bg-light p-3 rounded mb-3">
                                    <p class="fw-semibold mb-1"><?= htmlspecialchars($user['fullname']) ?></p>
                                    <p class="hint text-muted mb-0" style="white-space: pre-line;">
                                        <?= !empty($order['address_text']) ? htmlspecialchars($order['address_text']) : 'Address on file' ?>
                                    </p>
                                </div>

                                <hr>
                                <p class="hint text-uppercase text-muted fw-bold mb-1">Payment Method</p>
                                <p class="mb-0">
                                    <i class="fa-solid fa-credit-card text-primary me-2"></i>
                                    <?= strtoupper(htmlspecialchars($order['payment_method'] ?? 'COD')) ?> &middot; 
                                    Status: <strong><?= ucfirst(htmlspecialchars($order['payment_status'] ?? 'Paid')) ?></strong>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Price Details -->
                    <div class="col-12 col-xl-5">
                        <div class="card store-card h-100">
                            <div class="card-body p-4">
                                <div class="section-heading mb-4">
                                    <div class="feature-icon"><i class="fa-solid fa-file-invoice"></i></div>
                                    <div>
                                        <h2 class="h6 fw-bold mb-0">Price Details</h2>
                                        <p class="hint text-muted mb-0">Final amount breakdown.</p>
                                    </div>
                                </div>

                                <div class="summary-row hint mb-2 d-flex justify-content-between">
                                    <span class="text-muted">Subtotal</span>
                                    <span><?= $subtotal ?></span>
                                </div>
                                <div class="summary-row hint mb-2 d-flex justify-content-between">
                                    <span class="text-muted">Delivery</span>
                                    <span><?= $shipping ?></span>
                                </div>
                                <?php if ($order['discount_paise'] > 0): ?>
                                    <div class="summary-row hint mb-3 d-flex justify-content-between text-success">
                                        <span>Discount <?= !empty($order['coupon_code']) ? ('(' . htmlspecialchars($order['coupon_code']) . ')') : '' ?></span>
                                        <span>&minus;<?= $discount ?></span>
                                    </div>
                                <?php endif; ?>
                                <hr>
                                <div class="summary-row summary-total fw-bold fs-5 d-flex justify-content-between">
                                    <span>Total Paid</span>
                                    <span class="text-primary"><?= $total_paid ?></span>
                                </div>

                                <div class="d-flex flex-wrap gap-2 mt-4">
                                    <button type="button" class="btn btn-outline-primary btn-sm px-3" onclick="window.print();">
                                        <i class="fa-solid fa-file-invoice me-1"></i>Print Invoice
                                    </button>
                                    <a href="contact.php" class="btn btn-outline-secondary btn-sm px-3 ms-auto">
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
    <?php endif; ?>
</div>

<?php
// STEP 4: Save the captured HTML and stop buffering.
$page_content = ob_get_clean();

// STEP 5: Render it inside the shared layout.
require 'layout.php';
