<?php
/*
 * admin-profile.php - ADMIN ACCOUNT
 * Visual-only profile, password and role settings for the signed-in admin.
 */

$admin_page_title = 'Admin Profile | TrendCart Admin';
$admin_active     = 'profile';

$activity = [
    ['icon' => 'fa-right-to-bracket', 'text' => 'Signed in from Chrome on Windows', 'time' => 'Today, 09:12 AM'],
    ['icon' => 'fa-pen-to-square', 'text' => 'Updated product: Pulse Wireless Headphones', 'time' => 'Yesterday, 04:38 PM'],
    ['icon' => 'fa-truck', 'text' => 'Changed order status #TC-48276', 'time' => '18 Nov 2026, 11:20 AM'],
];

ob_start();
?>

<div class="admin-page-heading">
    <div>
        <p class="admin-eyebrow mb-0">Account</p>
        <h1>Admin Profile</h1>
        <p class="text-muted mb-0">Manage your admin details, password and access level.</p>
    </div>
    <span class="admin-status"><i class="fa-solid fa-shield-halved me-1"></i>Super Admin</span>
</div>

<div class="row g-4">
    <div class="col-12 col-xl-8">
        <form action="#" method="post" id="adminProfileForm" novalidate>
            <div class="card admin-card mb-4">
                <div class="admin-card-header"><h2 class="admin-card-title">Profile details</h2></div>
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
                        <span class="admin-user-avatar" style="width:64px;height:64px;font-size:1.1rem">RK</span>
                        <div class="me-auto">
                            <p class="fw-bold mb-0">Riya Kapoor</p>
                            <p class="admin-section-note mb-0">riya.kapoor@trendcart.example</p>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-camera me-1"></i>Change photo</button>
                    </div>
                    <div class="admin-form-grid">
                        <div>
                            <label for="adminFirst" class="form-label small fw-semibold">First name</label>
                            <input type="text" class="form-control" id="adminFirst" name="adminFirst" value="Riya"
                                   data-validation="required max" data-max="50">
                            <div class="invalid-feedback" id="adminFirstError"></div>
                        </div>
                        <div>
                            <label for="adminLast" class="form-label small fw-semibold">Last name</label>
                            <input type="text" class="form-control" id="adminLast" name="adminLast" value="Kapoor"
                                   data-validation="required max" data-max="50">
                            <div class="invalid-feedback" id="adminLastError"></div>
                        </div>
                        <div>
                            <label for="adminEmail" class="form-label small fw-semibold">Email</label>
                            <input type="email" class="form-control" id="adminEmail" name="adminEmail"
                                   value="riya.kapoor@trendcart.example" data-validation="required email">
                            <div class="invalid-feedback" id="adminEmailError"></div>
                        </div>
                        <div>
                            <label for="adminPhone" class="form-label small fw-semibold">Phone</label>
                            <input type="tel" class="form-control" id="adminPhone" name="adminPhone"
                                   value="+91 99000 11223" data-validation="required max" data-max="15">
                            <div class="invalid-feedback" id="adminPhoneError"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card admin-card mb-4">
                <div class="admin-card-header"><h2 class="admin-card-title">Change password</h2></div>
                <div class="card-body">
                    <div class="admin-form-grid">
                        <div>
                            <label for="adminCurrentPass" class="form-label small fw-semibold">Current password</label>
                            <input type="password" class="form-control" id="adminCurrentPass" name="adminCurrentPass"
                                   placeholder="Enter current password" data-validation="max" data-max="255">
                            <div class="invalid-feedback" id="adminCurrentPassError"></div>
                        </div>
                        <div>
                            <label for="adminNewPass" class="form-label small fw-semibold">New password</label>
                            <input type="password" class="form-control" id="adminNewPass" name="adminNewPass"
                                   placeholder="At least 8 characters" data-validation="strongPassword">
                            <div class="invalid-feedback" id="adminNewPassError"></div>
                        </div>
                        <div class="full">
                            <label for="adminConfirmPass" class="form-label small fw-semibold">Confirm new password</label>
                            <input type="password" class="form-control" id="adminConfirmPass" name="adminConfirmPass"
                                   placeholder="Repeat new password" data-validation="confirmPassword"
                                   data-password-id="adminNewPass">
                            <div class="invalid-feedback" id="adminConfirmPassError"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card admin-card">
                <div class="admin-card-header"><h2 class="admin-card-title">Permissions</h2></div>
                <div class="card-body">
                    <div class="admin-switch-card">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="permCatalogue" name="permCatalogue" checked
                                   data-validation="max-items" data-max-items="1">
                            <label class="form-check-label small fw-semibold" for="permCatalogue">Manage catalogue</label>
                            <div class="invalid-feedback" id="permCatalogueError"></div>
                        </div>
                    </div>
                    <div class="admin-switch-card">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="permOrders" name="permOrders" checked
                                   data-validation="max-items" data-max-items="1">
                            <label class="form-check-label small fw-semibold" for="permOrders">Manage orders and customers</label>
                            <div class="invalid-feedback" id="permOrdersError"></div>
                        </div>
                    </div>
                    <div class="admin-switch-card">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="permContent" name="permContent" checked
                                   data-validation="max-items" data-max-items="1">
                            <label class="form-check-label small fw-semibold" for="permContent">Edit site content</label>
                            <div class="invalid-feedback" id="permContentError"></div>
                        </div>
                    </div>
                    <div class="admin-switch-card">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="permSettings" name="permSettings"
                                   data-validation="max-items" data-max-items="1">
                            <label class="form-check-label small fw-semibold" for="permSettings">Change system settings</label>
                            <div class="invalid-feedback" id="permSettingsError"></div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="col-12 col-xl-4">
        <div class="card admin-card mb-4">
            <div class="admin-card-header"><h2 class="admin-card-title">Account snapshot</h2></div>
            <div class="card-body">
                <div class="d-flex justify-content-between small mb-2"><span class="text-muted">Role</span><strong>Super Admin</strong></div>
                <div class="d-flex justify-content-between small mb-2"><span class="text-muted">Last login</span><strong>Today, 09:12 AM</strong></div>
                <div class="d-flex justify-content-between small mb-3"><span class="text-muted">2-factor auth</span><strong>Enabled</strong></div>
                <span class="admin-status"><i class="fa-solid fa-circle-check me-1"></i>Account secure</span>
            </div>
        </div>

        <div class="card admin-card mb-4">
            <div class="admin-card-header"><h2 class="admin-card-title">Recent activity</h2></div>
            <div class="card-body">
                <?php foreach ($activity as $item) : ?>
                    <div class="d-flex align-items-start gap-3 py-2">
                        <span class="admin-mini-icon"><i class="fa-solid <?= $item['icon'] ?>"></i></span>
                        <div>
                            <p class="small fw-bold mb-0"><?= $item['text'] ?></p>
                            <span class="admin-section-note"><?= $item['time'] ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="admin-save-bar">
            <span class="admin-section-note">Design preview only</span>
            <button type="submit" form="adminProfileForm" class="btn btn-primary btn-sm px-3">
                <i class="fa-solid fa-check me-1"></i>Save Profile
            </button>
        </div>
    </div>
</div>

<?php
$admin_page_content = ob_get_clean();
require 'admin-layout.php';
