<?php
/*
 * orders.php - MY ORDERS
 * Static order history with status tabs and links to the detail view.
 */

// STEP 1: Sample orders used to build the tabs and order cards.
$orders = [
    [
        'id'           => 'TC-48291',
        'date'         => '18 Nov 2026',
        'status'       => 'Delivered',
        'status_class' => 'badge text-bg-primary px-3 py-2',
        'status_icon'  => 'fa-circle-check',
        'icons'        => ['fa-headphones', 'fa-stopwatch'],
        'title'        => 'Pulse Wireless Headphones & Nova Smartwatch 2',
        'meta'         => '2 items &middot; Delivered on 21 Nov 2026',
        'total'        => '6,998',
        'delivered'    => true,
        'steps'        => 4,
    ],
    [
        'id'           => 'TC-48176',
        'date'         => '02 Nov 2026',
        'status'       => 'Out for delivery',
        'status_class' => 'badge border border-primary text-primary px-3 py-2',
        'status_icon'  => 'fa-truck',
        'icons'        => ['fa-shirt'],
        'title'        => 'Everywear Cotton T-Shirt',
        'meta'         => '1 item &middot; Expected delivery 22 Nov 2026',
        'total'        => '799',
        'delivered'    => false,
        'steps'        => 3,
    ],
    [
        'id'           => 'TC-47984',
        'date'         => '21 Oct 2026',
        'status'       => 'Delivered',
        'status_class' => 'badge text-bg-primary px-3 py-2',
        'status_icon'  => 'fa-circle-check',
        'icons'        => ['fa-book', 'fa-gem', 'fa-camera'],
        'title'        => 'PHP: From Zero to Hero, Aura Gift Hamper & more',
        'meta'         => '3 items &middot; Delivered on 24 Oct 2026',
        'total'        => '30,299',
        'delivered'    => true,
        'steps'        => 4,
    ],
];

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
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-4">
                    <div class="card store-card account-stat p-3 h-100">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon text-primary"><i class="fa-solid fa-box-archive"></i></div>
                            <div>
                                <p class="hint text-muted mb-0">Total orders</p>
                                <p class="h5 fw-bold mb-0"><?= count($orders) ?></p>
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
                                <p class="h5 fw-bold mb-0">1</p>
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
                                <p class="h5 fw-bold mb-0">2</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card store-card mb-4">
                <div class="card-body p-2">
                    <ul class="nav nav-pills account-tabs small">
                        <li class="nav-item"><a class="nav-link active" href="#">All Orders <span
                                            class="badge bg-primary">3</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Processing <span
                                            class="badge bg-primary">1</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Shipped <span
                                            class="badge bg-primary">1</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Delivered <span
                                            class="badge bg-primary">2</span></a></li>
                    </ul>
                </div>
            </div>

            <?php foreach ($orders as $order) : ?>
                <div class="card store-card mb-3">
                    <div class="card-body p-3 p-sm-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <div>
                                <p class="hint text-muted mb-1">ORDER #TC-<?= htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8') ?></p>
                                <h2 class="h6 fw-bold mb-0">Placed on <?= htmlspecialchars($order['date'], ENT_QUOTES, 'UTF-8') ?></h2>
                            </div>
                            <span class="<?= $order['status_class'] ?>">
                                <i class="fa-solid <?= $order['status_icon'] ?> me-1"></i><?= htmlspecialchars($order['status'], ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </div>

                        <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                            <div class="d-flex flex-wrap gap-1">
                                <?php foreach ($order['icons'] as $icon) : ?>
                                    <div class="product-img order-thumb rounded d-flex align-items-center justify-content-center">
                                        <i class="fa-solid <?= $icon ?> text-primary"></i>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="flex-fill">
                                <p class="fw-bold mb-1"><?= htmlspecialchars($order['title'], ENT_QUOTES, 'UTF-8') ?></p>
                                <p class="hint text-muted mb-2"><?= $order['meta'] ?></p>
                                <div class="mini-progress" aria-label="Delivery progress">
                                    <?php for ($i = 1; $i <= 4; $i++) : ?>
                                        <span class="<?= $i <= $order['steps'] ? 'on' : '' ?>"></span>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <span class="fw-bold">&#8377;<?= htmlspecialchars($order['total'], ENT_QUOTES, 'UTF-8') ?></span>
                        </div>

                        <div class="d-flex flex-wrap gap-2 border-top pt-3">
                            <?php if ($order['delivered']) : ?>
                                <a href="order-detail.php" class="btn btn-primary btn-sm px-3">
                                    <i class="fa-regular fa-eye me-1"></i>View Details
                                </a>
                                <a href="order-detail.php" class="btn btn-outline-primary btn-sm px-3">
                                    <i class="fa-solid fa-file-invoice me-1"></i>Invoice
                                </a>
                            <?php else : ?>
                                <a href="order-detail.php" class="btn btn-primary btn-sm px-3">
                                    <i class="fa-solid fa-truck me-1"></i>Track Order
                                </a>
                                <a href="contact.php" class="btn btn-outline-primary btn-sm px-3">
                                    <i class="fa-solid fa-headset me-1"></i>Need Help?
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="card store-card">
                <div class="card-body p-4 d-flex flex-wrap align-items-center gap-3">
                    <div class="feature-icon"><i class="fa-solid fa-headset"></i></div>
                    <div class="me-auto">
                        <p class="fw-bold mb-0">Need help with an order?</p>
                        <p class="hint text-muted mb-0">Our support team can help with tracking, returns and invoices.</p>
                    </div>
                    <a href="contact.php" class="btn btn-outline-primary btn-sm px-3">Contact support</a>
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
