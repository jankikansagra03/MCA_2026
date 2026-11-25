<?php
/**
 * account-nav.php
 * ---------------
 * Shared sidebar for every account page with dynamic user details and avatar.
 */

require_once __DIR__ . '/auth.php';

$account_active = $account_active ?? '';
$sidebar_user   = current_user();
$sidebar_avatar = user_avatar_url($sidebar_user);

// Two small groups keep the menu easy to scan.
$account_groups = [
    'My Account'  => [
        ['key' => 'account',   'label' => 'Dashboard',     'href' => 'account.php',   'icon' => 'fa-solid fa-gauge-high'],
        ['key' => 'orders',    'label' => 'My Orders',     'href' => 'orders.php',    'icon' => 'fa-solid fa-box-archive'],
        ['key' => 'wishlist',  'label' => 'Wishlist',      'href' => 'wishlist.php',  'icon' => 'fa-regular fa-heart'],
        ['key' => 'cart',      'label' => 'Shopping Cart', 'href' => 'cart.php',      'icon' => 'fa-solid fa-cart-shopping'],
        ['key' => 'addresses', 'label' => 'Address Book',  'href' => 'addresses.php', 'icon' => 'fa-solid fa-location-dot'],
    ],
    'Preferences' => [
        ['key' => 'profile',         'label' => 'My Profile',      'href' => 'profile.php',         'icon' => 'fa-solid fa-user'],
        ['key' => 'edit-profile',    'label' => 'Edit Profile',    'href' => 'edit-profile.php',    'icon' => 'fa-solid fa-user-pen'],
        ['key' => 'change-password', 'label' => 'Change Password', 'href' => 'change-password.php', 'icon' => 'fa-solid fa-key'],
    ],
];
?>
<div class="card store-card account-sidebar mb-4 mb-lg-0">
    <div class="card-body p-3">
        <div class="d-flex align-items-center gap-3 px-2 py-2 mb-3 border-bottom">
            <?php if ($sidebar_avatar): ?>
                <img src="<?= $sidebar_avatar ?>" alt="Avatar" class="avatar-sm rounded-circle object-fit-cover border" style="width: 42px; height: 42px;">
            <?php else: ?>
                <div class="avatar-sm bg-primary text-white d-flex align-items-center justify-content-center rounded-circle" style="width: 42px; height: 42px;">
                    <i class="fa-regular fa-user"></i>
                </div>
            <?php endif; ?>
            <div class="overflow-hidden">
                <p class="small fw-bold mb-0 text-truncate"><?= htmlspecialchars($sidebar_user['fullname'] ?? 'TrendCart Member') ?></p>
                <p class="hint text-muted mb-0 text-truncate"><?= htmlspecialchars($sidebar_user['email'] ?? 'Member') ?></p>
            </div>
        </div>

        <?php foreach ($account_groups as $group_name => $links) : ?>
            <p class="hint text-uppercase text-muted fw-bold px-2 mb-2"><?= htmlspecialchars($group_name) ?></p>
            <div class="nav flex-column account-nav">
                <?php foreach ($links as $link) : ?>
                    <?php $is_active = $account_active === $link['key'] ? ' active' : ''; ?>
                    <a class="nav-link<?= $is_active ?>" href="<?= $link['href'] ?>">
                        <i class="<?= $link['icon'] ?> me-2"></i><?= htmlspecialchars($link['label']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <hr class="my-3">
        <?php endforeach; ?>

        <div class="nav flex-column account-nav">
            <a class="nav-link text-danger" href="logout.php">
                <i class="fa-solid fa-right-from-bracket me-2"></i>Sign Out
            </a>
        </div>
    </div>
</div>
