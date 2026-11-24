<?php
/*
 * admin-customer-edit.php - EDIT CUSTOMER
 * Prefilled customer record with profile, addresses and preferences.
 * Design only — no data is changed.
 */

$admin_page_title = 'Edit Customer | TrendCart Admin';
$admin_active     = 'customers';

$addresses = [
    ['label' => 'Home', 'icon' => 'fa-house', 'line' => '402, Sunrise Residency, 12 Palm Grove Road, Indiranagar, Bengaluru 560038', 'default' => true],
    ['label' => 'Office', 'icon' => 'fa-building', 'line' => '7th Floor, Orion Tech Park, MG Road, Bengaluru 560001', 'default' => false],
];

ob_start();
?>

<div class="admin-page-heading">
    <div>
        <nav aria-label="breadcrumb" class="admin-breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="admin.php" class="text-decoration-none">Admin</a></li>
                <li class="breadcrumb-item"><a href="admin-customers.php" class="text-decoration-none">Customers</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Customer</li>
            </ol>
        </nav>
        <p class="admin-eyebrow mb-0">Sales</p>
        <h1>Aarav Mehta</h1>
        <p class="text-muted mb-0">Customer since 14 Jan 2024 &middot; 6 orders</p>
    </div>
    <a href="admin-orders.php" class="btn btn-outline-primary btn-sm px-3">
        <i class="fa-solid fa-box-archive me-1"></i>View Orders
    </a>
</div>

<form action="#" method="post" novalidate>
    <div class="row g-4">
        <div class="col-12 col-xl-8">
            <div class="card admin-card mb-4">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">Personal information</h2>
                    <span class="admin-section-note">Customer ID #TC-102493</span>
                </div>
                <div class="card-body">
                    <div class="admin-form-grid">
                        <div class="full">
                            <label for="fullname" class="form-label small fw-semibold">Full name</label>
                            <input type="text" class="form-control" id="fullname" name="fullname" value="Aarav Mehta"
                                   data-validation="required max" data-max="50">
                            <div class="invalid-feedback" id="fullnameError"></div>
                        </div>
                        <div>
                            <label for="customerEmail" class="form-label small fw-semibold">Email address</label>
                            <input type="email" class="form-control" id="customerEmail" name="customerEmail"
                                   value="aarav.mehta@example.com" data-validation="required email">
                            <div class="invalid-feedback" id="customerEmailError"></div>
                        </div>
                        <div>
                            <label for="customerPhone" class="form-label small fw-semibold">Phone number</label>
                            <input type="tel" class="form-control" id="customerPhone" name="customerPhone"
                                   value="+91 98765 43210" data-validation="required max" data-max="15">
                            <div class="invalid-feedback" id="customerPhoneError"></div>
                        </div>
                        <div>
                            <label for="customerDob" class="form-label small fw-semibold">Date of birth</label>
                            <input type="date" class="form-control" id="customerDob" name="customerDob"
                                   value="1997-08-12" data-validation="required">
                            <div class="invalid-feedback" id="customerDobError"></div>
                        </div>
                        <div>
                            <label for="customerTier" class="form-label small fw-semibold">Loyalty tier</label>
                            <select class="form-select" id="customerTier" name="customerTier" data-validation="required">
                                <option>Standard</option>
                                <option selected>Silver</option>
                                <option>Gold</option>
                            </select>
                            <div class="invalid-feedback" id="customerTierError"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card admin-card mb-4">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">Saved addresses</h2>
                    <button type="button" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-plus me-1"></i>Add address</button>
                </div>
                <div class="card-body">
                    <?php foreach ($addresses as $index => $address) : ?>
                        <div class="border rounded p-3 mb-3 <?= $address['default'] ? 'border-primary' : '' ?>">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <span class="admin-mini-icon"><i class="fa-solid <?= $address['icon'] ?>"></i></span>
                                <strong class="small me-auto"><?= $address['label'] ?></strong>
                                <?php if ($address['default']) : ?><span class="admin-status">Default</span><?php endif; ?>
                            </div>
                            <label for="addressLine<?= $index ?>" class="visually-hidden"><?= $address['label'] ?> address</label>
                            <textarea class="form-control form-control-sm" id="addressLine<?= $index ?>"
                                      name="addressLine<?= $index ?>" rows="2"
                                      data-validation="required max" data-max="200"><?= $address['line'] ?></textarea>
                            <div class="invalid-feedback" id="addressLine<?= $index ?>Error"></div>
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                <button type="button" class="btn btn-outline-primary btn-sm">Save address</button>
                                <button type="button" class="btn btn-outline-primary btn-sm">Set as default</button>
                                <button type="button" class="btn btn-outline-primary btn-sm">Remove</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="card admin-card">
                <div class="admin-card-header"><h2 class="admin-card-title">Communication preferences</h2></div>
                <div class="card-body">
                    <div class="admin-switch-card">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="prefOffers" name="prefOffers" checked
                                   data-validation="max-items" data-max-items="1">
                            <label class="form-check-label small fw-semibold" for="prefOffers">Email me offers and coupons</label>
                            <div class="invalid-feedback" id="prefOffersError"></div>
                        </div>
                    </div>
                    <div class="admin-switch-card">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="prefOrders" name="prefOrders" checked
                                   data-validation="max-items" data-max-items="1">
                            <label class="form-check-label small fw-semibold" for="prefOrders">Order and delivery updates</label>
                            <div class="invalid-feedback" id="prefOrdersError"></div>
                        </div>
                    </div>
                    <div class="admin-switch-card">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="prefSms" name="prefSms"
                                   data-validation="max-items" data-max-items="1">
                            <label class="form-check-label small fw-semibold" for="prefSms">SMS notifications</label>
                            <div class="invalid-feedback" id="prefSmsError"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card admin-card mb-4">
                <div class="admin-card-header"><h2 class="admin-card-title">Account status</h2></div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="admin-user-avatar">AM</span>
                        <div><p class="fw-bold mb-0">Aarav Mehta</p><span class="admin-status">Active</span></div>
                    </div>
                    <label for="accountStatus" class="form-label small fw-semibold">Status</label>
                    <select class="form-select mb-3" id="accountStatus" name="accountStatus" data-validation="required">
                        <option selected>Active</option>
                        <option>Pending</option>
                        <option>Blocked</option>
                    </select>
                    <div class="invalid-feedback" id="accountStatusError"></div>
                    <label for="adminNote" class="form-label small fw-semibold">Internal note</label>
                    <textarea class="form-control" id="adminNote" name="adminNote" rows="3"
                              placeholder="Only admins can see this note."
                              data-validation="max" data-max="500"></textarea>
                    <div class="invalid-feedback" id="adminNoteError"></div>
                </div>
            </div>

            <div class="card admin-card mb-4">
                <div class="admin-card-header"><h2 class="admin-card-title">Customer snapshot</h2></div>
                <div class="card-body">
                    <div class="d-flex justify-content-between small mb-2"><span class="text-muted">Total orders</span><strong>6</strong></div>
                    <div class="d-flex justify-content-between small mb-2"><span class="text-muted">Total spent</span><strong>&#8377;24,180</strong></div>
                    <div class="d-flex justify-content-between small mb-3"><span class="text-muted">Reward points</span><strong>1,240</strong></div>
                    <div class="admin-progress mb-2"><span style="width:68%"></span></div>
                    <p class="admin-section-note mb-0">68% toward Gold tier</p>
                </div>
            </div>

            <div class="admin-save-bar">
                <span class="admin-section-note">Design preview only</span>
                <div class="d-flex flex-wrap gap-2">
                    <a href="admin-customers.php" class="btn btn-outline-primary btn-sm px-3">Cancel</a>
                    <button type="submit" class="btn btn-primary btn-sm px-3">
                        <i class="fa-solid fa-check me-1"></i>Save Customer
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<?php
$admin_page_content = ob_get_clean();
require 'admin-layout.php';
