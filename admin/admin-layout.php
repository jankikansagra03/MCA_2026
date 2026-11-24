<?php
/**
 * admin-layout.php
 * ----------------
 * A separate shell for the admin area. It deliberately does not use the shop
 * navbar or footer, so the admin panel has its own dashboard-style layout.
 * Every admin page captures HTML into $admin_page_content and loads this file.
 * Bootstrap bundle is included for modal dialogs used by the CRUD screens.
 */

require_once 'db.php';

$admin_page_title   = $admin_page_title   ?? 'Dashboard | TrendCart Admin';
$admin_active       = $admin_active       ?? 'dashboard';
$admin_page_content = $admin_page_content ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="TrendCart admin panel — manage products, orders, customers, offers and site content.">
    <title><?= htmlspecialchars($admin_page_title) ?></title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="admin-body">
    <div class="admin-shell">
        <!-- ========== FIXED ADMIN SIDEBAR ========== -->
        <aside class="admin-sidebar">
            <a href="admin.php" class="admin-brand">
                <span class="admin-brand-icon"><i class="fa-solid fa-bag-shopping"></i></span>
                <span>Trend<span>Cart</span><small>Admin Panel</small></span>
            </a>
            <?php require 'admin-nav.php'; ?>
        </aside>

        <!-- ========== MAIN ADMIN AREA ========== -->
        <div class="admin-main">
            <header class="admin-topbar">
                <div class="admin-topbar-inner">
                    <div class="d-flex align-items-center gap-2">
                        <span class="admin-mobile-brand d-lg-none">
                            <i class="fa-solid fa-bag-shopping me-1"></i>TrendCart Admin
                        </span>
                        <form action="<?= h(basename($_SERVER['PHP_SELF'])) ?>" method="get" class="admin-search d-none d-sm-flex"
                              role="search" novalidate>
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <label for="adminSearch" class="visually-hidden">Search this table</label>
                            <input type="search" id="adminSearch" name="q" placeholder="Search this table…"
                                   data-validation="max" data-max="100">
                            <div class="invalid-feedback" id="adminSearchError"></div>
                        </form>
                    </div>

                    <div class="d-flex align-items-center gap-2 gap-sm-3">
                        <a href="index.php" class="admin-top-link d-none d-md-inline">
                            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>View Store
                        </a>
                        <a href="admin-orders.php" class="admin-icon-link" aria-label="Notifications">
                            <i class="fa-regular fa-bell"></i><span class="admin-dot"></span>
                        </a>
                        <div class="admin-user-chip">
                            <span class="admin-user-avatar">RK</span>
                            <span class="d-none d-sm-block">
                                <strong>Riya Kapoor</strong>
                                <small>Super Admin</small>
                            </span>
                        </div>
                    </div>
                </div>
            </header>

            <main class="admin-content container-fluid py-4">
                <?= $admin_page_content ?>
            </main>

            <footer class="admin-footer">
                <span>&copy; 2026 TrendCart Admin. Database-backed CRUD panel.</span>
                <span class="d-none d-sm-inline">Local demo &middot; Laragon MySQL</span>
            </footer>
        </div>
    </div>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/validator.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>
