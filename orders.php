<?php
/*
 * orders.php - MY ORDERS
 * Dynamic order history linked to MySQL orders and order_items tables.
 */

require_once __DIR__ . '/check_auth.php';
require_once __DIR__ . '/db.php';

$user    = current_user();
$user_id = $user['id'];

// Status tabs filter
$tab = trim($_GET['tab'] ?? 'all');

// Helper to determine status style, icon, and progress steps
function get_order_status_meta($status) {
    $status = strtolower(trim((string)$status));
    switch ($status) {
        case 'delivered':
            return [
                'class'     => 'badge bg-success-subtle text-success border border-success-subtle px-3 py-2',
                'icon'      => 'fa-circle-check',
                'steps'     => 4,
                'label'     => 'Delivered',
                'delivered' => true,
            ];
        case 'shipped':
            return [
                'class'     => 'badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2',
                'icon'      => 'fa-truck',
                'steps'     => 3,
                'label'     => 'Shipped',
                'delivered' => false,
            ];
        case 'processing':
            return [
                'class'     => 'badge bg-info-subtle text-info border border-info-subtle px-3 py-2',
                'icon'      => 'fa-box-open',
                'steps'     => 2,
                'label'     => 'Processing',
                'delivered' => false,
            ];
        case 'cancelled':
            return [
                'class'     => 'badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2',
                'icon'      => 'fa-circle-xmark',
                'steps'     => 0,
                'label'     => 'Cancelled',
                'delivered' => false,
            ];
        default: // 'pending'
            return [
                'class'     => 'badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2',
                'icon'      => 'fa-clock',
                'steps'     => 1,
                'label'     => ucfirst($status ?: 'Pending'),
                'delivered' => false,
            ];
    }
}

// Counts for filter pills
try {
    $count_all        = (int) (db_one("SELECT COUNT(*) as c FROM orders WHERE user_id = ?", [$user_id])['c'] ?? 0);
    $count_processing = (int) (db_one("SELECT COUNT(*) as c FROM orders WHERE user_id = ? AND status IN ('pending', 'processing')", [$user_id])['c'] ?? 0);
    $count_shipped    = (int) (db_one("SELECT COUNT(*) as c FROM orders WHERE user_id = ? AND status = 'shipped'", [$user_id])['c'] ?? 0);
    $count_delivered  = (int) (db_one("SELECT COUNT(*) as c FROM orders WHERE user_id = ? AND status = 'delivered'", [$user_id])['c'] ?? 0);

    // Build filter query
    $where = "WHERE user_id = ?";
    $params = [$user_id];

    if ($tab === 'processing') {
        $where .= " AND status IN ('pending', 'processing')";
    } elseif ($tab === 'shipped') {
        $where .= " AND status = 'shipped'";
    } elseif ($tab === 'delivered') {
        $where .= " AND status = 'delivered'";
    }

    $orders = db_all("SELECT * FROM orders {$where} ORDER BY id DESC", $params);
} catch (PDOException $e) {
    $count_all = $count_processing = $count_shipped = $count_delivered = 0;
    $orders = [];
}

// STEP 2: Settings used by the shared layout.
$page_title     = 'My Orders | TrendCart';
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
            <li class="breadcrumb-item active" aria-current="page">My Orders</li>
        </ol>
    </nav>

    <div class="page-header">
        <span class="badge text-bg-primary mb-3 px-3 py-2">
            <i class="fa-solid fa-box-archive me-1"></i>MY ORDERS
        </span>
        <h1 class="h2 fw-bold mb-3">Your Order History</h1>
        <p class="text-muted">Track deliveries, check invoices and revisit anything you have bought.</p>
    </div>

    <div class="row g-4">
        <!-- ========== SHARED SIDEBAR ========== -->
        <aside class="col-12 col-lg-3">
            <?php require 'account-nav.php'; ?>
        </aside>

        <!-- ========== ORDER LIST ========== -->
        <div class="col-12 col-lg-9">
            <!-- Stat overview -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-4">
                    <div class="card store-card account-stat p-3 h-100">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon text-primary"><i class="fa-solid fa-box-archive"></i></div>
                            <div>
                                <p class="hint text-muted mb-0">Total orders</p>
                                <p class="h5 fw-bold mb-0"><?= $count_all ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="card store-card account-stat p-3 h-100">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon text-primary"><i class="fa-solid fa-truck"></i></div>
                            <div>
                                <p class="hint text-muted mb-0">In progress</p>
                                <p class="h5 fw-bold mb-0"><?= $count_processing + $count_shipped ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card store-card account-stat p-3 h-100">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon text-primary"><i class="fa-solid fa-circle-check"></i></div>
                            <div>
                                <p class="hint text-muted mb-0">Delivered</p>
                                <p class="h5 fw-bold mb-0"><?= $count_delivered ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter tabs -->
            <div class="card store-card mb-4">
                <div class="card-body p-2">
                    <ul class="nav nav-pills account-tabs small">
                        <li class="nav-item">
                            <a class="nav-link <?= $tab === 'all' ? 'active' : '' ?>" href="orders.php?tab=all">
                                All Orders <span class="badge bg-primary ms-1"><?= $count_all ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $tab === 'processing' ? 'active' : '' ?>" href="orders.php?tab=processing">
                                Processing <span class="badge bg-primary ms-1"><?= $count_processing ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $tab === 'shipped' ? 'active' : '' ?>" href="orders.php?tab=shipped">
                                Shipped <span class="badge bg-primary ms-1"><?= $count_shipped ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $tab === 'delivered' ? 'active' : '' ?>" href="orders.php?tab=delivered">
                                Delivered <span class="badge bg-primary ms-1"><?= $count_delivered ?></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Orders Cards -->
            <?php if (empty($orders)): ?>
                <div class="card store-card text-center p-5 mb-4">
                    <div class="feature-icon mx-auto mb-3 text-muted opacity-75">
                        <i class="fa-solid fa-box-open fa-2x"></i>
                    </div>
                    <h3 class="h5 fw-bold mb-2">No Orders Found</h3>
                    <p class="text-muted hint mb-4">
                        <?= $tab !== 'all' ? 'No orders match this status filter.' : 'You have not placed any orders yet.' ?>
                    </p>
                    <div>
                        <a href="product.php" class="btn btn-primary px-4">
                            <i class="fa-solid fa-bag-shopping me-1"></i>Start Shopping
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($orders as $order) : ?>
                    <?php
                        $order_id   = (int) $order['id'];
                        $meta       = get_order_status_meta($order['status']);
                        $order_num  = !empty($order['order_number']) ? $order['order_number'] : ('TC-' . str_pad((string)$order_id, 5, '0', STR_PAD_LEFT));
                        $order_date = date('d M Y', strtotime($order['created_at']));
                        
                        // Total price formatting
                        $total_formatted = function_exists('money') 
                            ? money($order['total_paise']) 
                            : '&#8377;' . number_format(((float)$order['total_paise']) / 100, 2);

                        // Fetch items for this order
                        try {
                            $items = db_all("SELECT * FROM order_items WHERE order_id = ?", [$order_id]);
                        } catch (PDOException $e) {
                            $items = [];
                        }

                        $items_count = count($items);
                        if ($items_count > 0) {
                            $first_item_name = htmlspecialchars($items[0]['name_snapshot'], ENT_QUOTES, 'UTF-8');
                            $order_title = $items_count > 1 ? "{$first_item_name} &amp; " . ($items_count - 1) . " more item" . ($items_count > 2 ? 's' : '') : $first_item_name;
                        } else {
                            $order_title = "Order items details";
                        }
                    ?>
                    <div class="card store-card mb-3">
                        <div class="card-body p-3 p-sm-4">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                                <div>
                                    <p class="hint text-muted mb-1">ORDER #<?= htmlspecialchars($order_num) ?></p>
                                    <h2 class="h6 fw-bold mb-0">Placed on <?= $order_date ?></h2>
                                </div>
                                <span class="<?= $meta['class'] ?>">
                                    <i class="fa-solid <?= $meta['icon'] ?> me-1"></i><?= $meta['label'] ?>
                                </span>
                            </div>

                            <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                                <div class="product-img order-thumb rounded d-flex align-items-center justify-content-center bg-light">
                                    <i class="fa-solid fa-box text-primary fa-lg"></i>
                                </div>
                                <div class="flex-fill">
                                    <p class="fw-bold mb-1"><?= $order_title ?></p>
                                    <p class="hint text-muted mb-2">
                                        <?= $items_count ?> item<?= $items_count === 1 ? '' : 's' ?> &middot; 
                                        Payment: <?= ucfirst(htmlspecialchars($order['payment_status'] ?? 'Paid')) ?>
                                    </p>
                                    <div class="mini-progress" aria-label="Delivery progress">
                                        <?php for ($i = 1; $i <= 4; $i++) : ?>
                                            <span class="<?= $i <= $meta['steps'] ? 'on' : '' ?>"></span>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <span class="fw-bold fs-5"><?= $total_formatted ?></span>
                            </div>

                            <div class="d-flex flex-wrap gap-2 border-top pt-3">
                                <a href="order-detail.php?id=<?= $order_id ?>" class="btn btn-primary btn-sm px-3">
                                    <i class="fa-regular fa-eye me-1"></i>View Details
                                </a>
                                <?php if ($meta['delivered']) : ?>
                                    <a href="order-detail.php?id=<?= $order_id ?>" class="btn btn-outline-primary btn-sm px-3">
                                        <i class="fa-solid fa-file-invoice me-1"></i>Invoice
                                    </a>
                                <?php else : ?>
                                    <a href="order-detail.php?id=<?= $order_id ?>" class="btn btn-outline-primary btn-sm px-3">
                                        <i class="fa-solid fa-truck me-1"></i>Track Order
                                    </a>
                                <?php endif; ?>
                                <a href="contact.php" class="btn btn-outline-secondary btn-sm px-3 ms-auto">
                                    <i class="fa-solid fa-headset me-1"></i>Need Help?
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="card store-card">
                <div class="card-body p-4 d-flex flex-wrap align-items-center gap-3">
                    <div class="feature-icon"><i class="fa-solid fa-headset"></i></div>
                    <div class="me-auto">
                        <p class="fw-bold mb-0">Need help with an order?</p>
                        <p class="hint text-muted mb-0">Our support team can help with tracking, returns and invoices.</p>
                    </div>
                    <a href="contact.php" class="btn btn-outline-primary btn-sm px-3">Contact Support</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// STEP 4: Save the captured HTML and stop buffering.
$page_content = ob_get_clean();

// STEP 5: Render it inside the shared layout.
require 'layout.php';
