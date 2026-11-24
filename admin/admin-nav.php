<?php
/**
 * admin-nav.php - Shared sidebar navigation for all admin pages.
 */

$admin_nav_groups = [
    'Overview' => [
        ['key' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'fa-gauge-high', 'href' => 'admin.php'],
    ],
    'Catalogue' => [
        ['key' => 'products', 'label' => 'Products', 'icon' => 'fa-box', 'href' => 'admin-products.php'],
        ['key' => 'categories', 'label' => 'Categories', 'icon' => 'fa-layer-group', 'href' => 'admin-categories.php'],
        ['key' => 'offers', 'label' => 'Offers & Coupons', 'icon' => 'fa-ticket', 'href' => 'admin-offers.php'],
    ],
    'Sales' => [
        ['key' => 'orders', 'label' => 'Orders', 'icon' => 'fa-box-archive', 'href' => 'admin-orders.php'],
        ['key' => 'order-items', 'label' => 'Order Items', 'icon' => 'fa-list-check', 'href' => 'admin-order-items.php'],
        ['key' => 'transactions', 'label' => 'Transactions', 'icon' => 'fa-credit-card', 'href' => 'admin-transactions.php'],
    ],
    'Customers' => [
        ['key' => 'customers', 'label' => 'Customers', 'icon' => 'fa-users', 'href' => 'admin-customers.php'],
        ['key' => 'addresses', 'label' => 'Addresses', 'icon' => 'fa-location-dot', 'href' => 'admin-addresses.php'],
        ['key' => 'carts', 'label' => 'Carts', 'icon' => 'fa-cart-shopping', 'href' => 'admin-carts.php'],
        ['key' => 'wishlists', 'label' => 'Wishlists', 'icon' => 'fa-heart', 'href' => 'admin-wishlists.php'],
    ],
    'Website' => [
        ['key' => 'pages', 'label' => 'Guest Pages', 'icon' => 'fa-file-lines', 'href' => 'admin-pages.php'],
        ['key' => 'page-sections', 'label' => 'Page Sections', 'icon' => 'fa-grip', 'href' => 'admin-page-sections.php'],
        ['key' => 'faqs', 'label' => 'FAQs', 'icon' => 'fa-circle-question', 'href' => 'admin-faqs.php'],
        ['key' => 'reviews', 'label' => 'Reviews', 'icon' => 'fa-star', 'href' => 'admin-reviews.php'],
        ['key' => 'inquiries', 'label' => 'Inquiries', 'icon' => 'fa-envelope-open-text', 'href' => 'admin-inquiries.php'],
        ['key' => 'settings', 'label' => 'Settings', 'icon' => 'fa-gear', 'href' => 'admin-settings.php'],
    ],
    'Account' => [
        ['key' => 'profile', 'label' => 'Admin Profile', 'icon' => 'fa-user-gear', 'href' => 'admin-profile.php'],
    ],
];
?>
<nav class="admin-nav" aria-label="Admin navigation">
    <?php foreach ($admin_nav_groups as $group => $links) : ?>
        <p class="admin-nav-heading"><?= h($group) ?></p>
        <ul class="admin-nav-list">
            <?php foreach ($links as $link) : ?>
                <li>
                    <a href="<?= h($link['href']) ?>"
                       class="admin-nav-link <?= $admin_active === $link['key'] ? 'active' : '' ?>">
                        <i class="fa-solid <?= h($link['icon']) ?>"></i>
                        <span><?= h($link['label']) ?></span>
                        <?php if ($admin_active === $link['key']) : ?><i class="fa-solid fa-chevron-right admin-chevron"></i><?php endif; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>

    <div class="admin-nav-bottom">
        <a href="index.php" class="admin-nav-link"><i class="fa-solid fa-arrow-left"></i><span>Back to Store</span></a>
    </div>
</nav>