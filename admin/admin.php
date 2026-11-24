<?php
/*
 * admin.php - ADMIN DASHBOARD
 * A separate admin layout with a compact, database-backed store overview.
 */

require_once 'db.php';

$admin_page_title = 'Dashboard | TrendCart Admin';
$admin_active     = 'dashboard';

/* Headline numbers */
$revenuePaise  = (int) (db_one("SELECT COALESCE(SUM(total_paise), 0) AS v FROM orders WHERE payment_status = 'paid'")['v'] ?? 0);
$orderCount    = (int) (db_one('SELECT COUNT(*) AS v FROM orders')['v'] ?? 0);
$customerCount = (int) (db_one("SELECT COUNT(*) AS v FROM users WHERE role = 'customer'")['v'] ?? 0);
$productCount  = (int) (db_one('SELECT COUNT(*) AS v FROM products')['v'] ?? 0);
$lowStockCount = (int) (db_one('SELECT COUNT(*) AS v FROM products WHERE stock_qty < 20')['v'] ?? 0);

/* Recent five orders */
$recent_orders = db_all('
   SELECT o.order_number, o.total_paise, o.status, o.created_at,
          COALESCE(u.fullname, "Guest") AS customer
    FROM orders o
    LEFT JOIN users u ON u.id = o.user_id
    ORDER BY o.id DESC
    LIMIT 5
');

/* Low-stock products */
$low_stock = db_all('
    SELECT name, sku, image_icon, stock_qty
    FROM products
    WHERE stock_qty < 20
    ORDER BY stock_qty ASC, name ASC
    LIMIT 3
');

/* Order status counts */
$statusCounts = [];
foreach (db_all('SELECT status, COUNT(*) AS v FROM orders GROUP BY status') as $row) {
    $statusCounts[$row['status']] = (int) $row['v'];
}
$maxStatus = max(1, max($statusCounts ?: [0]));
$status_rows = [];
foreach (['delivered', 'shipped', 'processing', 'cancelled'] as $status) {
    $count = $statusCounts[$status] ?? 0;
    $status_rows[] = [
        ucfirst($status),
        $count,
        $count ? max(10, (int) round($count / $maxStatus * 100)) : 0,
    ];
}

/* Weekly revenue bars, grouped by weekday */
$weekdays  = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
$chartData = [];
foreach (db_all('SELECT DAYNAME(created_at) AS day, SUM(total_paise) AS v FROM orders GROUP BY DAYNAME(created_at)') as $row) {
    $chartData[substr($row['day'], 0, 3)] = (float) $row['v'];
}
$maxChart = max(1, max($chartData ?: [0]));
$chart_bars = [];
foreach ($weekdays as $day) {
    $value = $chartData[$day] ?? 0;
    $chart_bars[] = ['label' => $day, 'height' => max(6, (int) round($value / $maxChart * 100))];
}

ob_start();
?>

<div class="admin-page-heading">
    <div>
        <p class="admin-eyebrow mb-0">Admin Overview</p>
        <h1>Store Dashboard</h1>
        <p class="text-muted mb-0">Here is what is happening across TrendCart today.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="index.php" class="btn btn-outline-primary btn-sm px-3">
            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>View Store
        </a>
        <a href="admin-products.php" class="btn btn-primary btn-sm px-3">
            <i class="fa-solid fa-plus me-1"></i>Manage Products
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card admin-card admin-stat p-3 h-100">
            <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                <span class="admin-mini-icon"><i class="fa-solid fa-chart-line"></i></span>
                <span class="stat-delta">Paid orders</span>
            </div>
            <p class="admin-eyebrow mb-1">Revenue</p>
            <p class="stat-value mb-0"><?= money($revenuePaise) ?></p>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card admin-card admin-stat p-3 h-100">
            <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                <span class="admin-mini-icon"><i class="fa-solid fa-box-archive"></i></span>
                <span class="stat-delta">All orders</span>
            </div>
            <p class="admin-eyebrow mb-1">Orders</p>
            <p class="stat-value mb-0"><?= (int) $orderCount ?></p>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card admin-card admin-stat p-3 h-100">
            <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                <span class="admin-mini-icon"><i class="fa-solid fa-users"></i></span>
                <span class="stat-delta">Registered</span>
            </div>
            <p class="admin-eyebrow mb-1">Customers</p>
            <p class="stat-value mb-0"><?= (int) $customerCount ?></p>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card admin-card admin-stat p-3 h-100">
            <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                <span class="admin-mini-icon"><i class="fa-solid fa-box"></i></span>
                <span class="stat-delta"><?= (int) $lowStockCount ?> low stock</span>
            </div>
            <p class="admin-eyebrow mb-1">Products</p>
            <p class="stat-value mb-0"><?= (int) $productCount ?></p>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-xl-8">
        <div class="card admin-card h-100">
            <div class="admin-card-header">
                <div>
                    <h2 class="admin-card-title">Sales overview</h2>
                    <span class="admin-section-note">Revenue for the last seven days</span>
                </div>
                <span class="admin-status">This week</span>
            </div>
            <div class="card-body">
                <div class="admin-bar-chart">
                    <?php foreach ($chart_bars as $bar) : ?>
                        <div class="admin-bar-item">
                            <span class="admin-bar" style="height:<?= (int) $bar['height'] ?>%"></span>
                            <span class="admin-bar-label"><?= h($bar['label']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="card admin-card h-100">
            <div class="admin-card-header">
                <h2 class="admin-card-title">Order status</h2>
            </div>
            <div class="card-body">
                <?php foreach ($status_rows as $row) : ?>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small mb-1">
                            <span><?= $row[0] ?></span><strong><?= $row[1] ?></strong>
                        </div>
                        <div class="admin-progress"><span style="width:<?= $row[2] ?>%"></span></div>
                    </div>
                <?php endforeach; ?>
                <a href="admin-orders.php" class="small text-decoration-none fw-semibold">
                    Manage all orders <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-xl-7">
        <div class="card admin-card h-100">
            <div class="admin-card-header">
                <div>
                    <h2 class="admin-card-title">Recent orders</h2>
                    <span class="admin-section-note">Latest five orders placed in the store</span>
                </div>
                <a href="admin-orders.php" class="small text-decoration-none fw-semibold">View all</a>
            </div>
            <div class="table-responsive">
                <table class="table admin-table mb-0">
                    <thead>
                        <tr><th>Order</th><th>Customer</th><th>Total</th><th>Status</th><th></th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_orders as $order) : ?>
                            <?php
                            $status = (string) $order['status'];
                            $badgeClass = in_array($status, ['delivered', 'success'], true)
                                ? ''
                                : (in_array($status, ['pending', 'processing', 'shipped'], true) ? 'warn' : 'muted');
                            ?>
                            <tr>
                                <td>
                                    <strong>#<?= h($order['order_number']) ?></strong><br>
                                    <span class="admin-section-note"><?= h(date('d M Y', strtotime($order['created_at']))) ?></span>
                                </td>
                                <td><?= h($order['customer']) ?></td>
                                <td><?= money($order['total_paise']) ?></td>
                                <td><span class="admin-status <?= $badgeClass ?>"><?= h(ucfirst($status)) ?></span></td>
                                <td class="text-end"><a href="admin-orders.php" class="btn btn-sm btn-outline-primary admin-action" title="Manage order"><i class="fa-solid fa-pen-to-square"></i></a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-5">
        <div class="card admin-card h-100">
            <div class="admin-card-header">
                <div>
                    <h2 class="admin-card-title">Low stock items</h2>
                    <span class="admin-section-note">Restock these products soon</span>
                </div>
                <a href="admin-products.php" class="small text-decoration-none fw-semibold">Inventory</a>
            </div>
            <div class="card-body">
                <?php foreach ($low_stock as $item) : ?>
                    <div class="d-flex align-items-center gap-3 py-2">
                        <span class="admin-thumb"><i class="fa-solid <?= h($item['image_icon'] ?: 'fa-box') ?>"></i></span>
                        <div class="me-auto">
                            <p class="small fw-bold mb-0"><?= h($item['name']) ?></p>
                            <span class="admin-section-note"><?= h($item['sku']) ?></span>
                        </div>
                        <span class="admin-status warn"><?= (int) $item['stock_qty'] ?> left</span>
                        <a href="admin-products.php" class="btn btn-sm btn-outline-primary admin-action" title="Manage product"><i class="fa-solid fa-pen-to-square"></i></a>
                    </div>
                <?php endforeach; ?>
                <div class="mt-3 p-3 rounded" style="background:var(--tc-brand-pale)">
                    <p class="small fw-bold mb-1"><i class="fa-solid fa-lightbulb text-primary me-1"></i>Quick tip</p>
                    <p class="admin-section-note mb-0">Open Products to update stock, price and status from the database.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$admin_page_content = ob_get_clean();
require 'admin-layout.php';
