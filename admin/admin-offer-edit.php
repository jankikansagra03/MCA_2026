<?php
/*
 * admin-offer-edit.php - ADD / EDIT COUPON
 * Prefilled coupon editor with discount rules and a live-looking preview.
 * Design only — nothing is saved.
 */

$admin_page_title = 'Edit Coupon | TrendCart Admin';
$admin_active     = 'offers';

ob_start();
?>

<div class="admin-page-heading">
    <div>
        <nav aria-label="breadcrumb" class="admin-breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="admin.php" class="text-decoration-none">Admin</a></li>
                <li class="breadcrumb-item"><a href="admin-offers.php" class="text-decoration-none">Offers &amp; Coupons</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Coupon</li>
            </ol>
        </nav>
        <p class="admin-eyebrow mb-0">Catalogue</p>
        <h1>Edit Coupon</h1>
        <p class="text-muted mb-0">WELCOME10 &middot; 10% off first order</p>
    </div>
    <a href="offers.php" class="btn btn-outline-primary btn-sm px-3">
        <i class="fa-regular fa-eye me-1"></i>View in Store
    </a>
</div>

<form action="#" method="post" novalidate>
    <div class="row g-4">
        <div class="col-12 col-xl-8">
            <div class="card admin-card mb-4">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">Coupon details</h2>
                    <span class="admin-section-note">What customers see</span>
                </div>
                <div class="card-body">
                    <div class="admin-form-grid">
                        <div>
                            <label for="couponCode" class="form-label small fw-semibold">Coupon code</label>
                            <input type="text" class="form-control" id="couponCode" name="couponCode" value="WELCOME10"
                                   data-validation="required max" data-max="30">
                            <div class="invalid-feedback" id="couponCodeError"></div>
                        </div>
                        <div>
                            <label for="couponLabel" class="form-label small fw-semibold">Display label</label>
                            <input type="text" class="form-control" id="couponLabel" name="couponLabel" value="10% off first order"
                                   data-validation="required max" data-max="100">
                            <div class="invalid-feedback" id="couponLabelError"></div>
                        </div>
                        <div class="full">
                            <label for="couponDescription" class="form-label small fw-semibold">Description</label>
                            <textarea class="form-control" id="couponDescription" name="couponDescription" rows="2"
                                      data-validation="required max" data-max="500">Get 10% off your first TrendCart order. Valid on all categories.</textarea>
                            <div class="invalid-feedback" id="couponDescriptionError"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card admin-card mb-4">
                <div class="admin-card-header"><h2 class="admin-card-title">Discount rules</h2></div>
                <div class="card-body">
                    <div class="admin-form-grid">
                        <div>
                            <label for="discountType" class="form-label small fw-semibold">Discount type</label>
                            <select class="form-select" id="discountType" name="discountType" data-validation="required">
                                <option selected>Percentage</option>
                                <option>Fixed amount</option>
                                <option>Free shipping</option>
                            </select>
                            <div class="invalid-feedback" id="discountTypeError"></div>
                        </div>
                        <div>
                            <label for="discountValue" class="form-label small fw-semibold">Value</label>
                            <input type="text" class="form-control" id="discountValue" name="discountValue" value="10%"
                                   data-validation="required max" data-max="20">
                            <div class="invalid-feedback" id="discountValueError"></div>
                        </div>
                        <div>
                            <label for="minOrder" class="form-label small fw-semibold">Minimum order (&#8377;)</label>
                            <input type="number" class="form-control" id="minOrder" name="minOrder" value="999"
                                   data-validation="required numeric">
                            <div class="invalid-feedback" id="minOrderError"></div>
                        </div>
                        <div>
                            <label for="maxDiscount" class="form-label small fw-semibold">Maximum discount (&#8377;)</label>
                            <input type="number" class="form-control" id="maxDiscount" name="maxDiscount" value="500"
                                   data-validation="required numeric">
                            <div class="invalid-feedback" id="maxDiscountError"></div>
                        </div>
                        <div>
                            <label for="usageLimit" class="form-label small fw-semibold">Total usage limit</label>
                            <input type="number" class="form-control" id="usageLimit" name="usageLimit" value="500"
                                   data-validation="required numeric">
                            <div class="invalid-feedback" id="usageLimitError"></div>
                        </div>
                        <div>
                            <label for="perCustomer" class="form-label small fw-semibold">Uses per customer</label>
                            <input type="number" class="form-control" id="perCustomer" name="perCustomer" value="1"
                                   data-validation="required numeric">
                            <div class="invalid-feedback" id="perCustomerError"></div>
                        </div>
                        <div class="full">
                            <label for="applicableCategories" class="form-label small fw-semibold">Applicable categories</label>
                            <select class="form-select" id="applicableCategories" name="applicableCategories" data-validation="required">
                                <option selected>All categories</option>
                                <option>Electronics only</option>
                                <option>Fashion only</option>
                            </select>
                            <div class="invalid-feedback" id="applicableCategoriesError"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card admin-card">
                <div class="admin-card-header"><h2 class="admin-card-title">Schedule &amp; display</h2></div>
                <div class="card-body">
                    <div class="admin-form-grid">
                        <div>
                            <label for="couponStart" class="form-label small fw-semibold">Starts</label>
                            <input type="datetime-local" class="form-control" id="couponStart" name="couponStart"
                                   value="2026-01-01T00:00" data-validation="required">
                            <div class="invalid-feedback" id="couponStartError"></div>
                        </div>
                        <div>
                            <label for="couponEnd" class="form-label small fw-semibold">Expires</label>
                            <input type="datetime-local" class="form-control" id="couponEnd" name="couponEnd"
                                   value="2026-12-31T23:59" data-validation="required">
                            <div class="invalid-feedback" id="couponEndError"></div>
                        </div>
                        <div class="full">
                            <label for="couponNote" class="form-label small fw-semibold">Terms note</label>
                            <input type="text" class="form-control" id="couponNote" name="couponNote"
                                   value="Cannot be combined with other offers."
                                   data-validation="required max" data-max="200">
                            <div class="invalid-feedback" id="couponNoteError"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card admin-card mb-4">
                <div class="admin-card-header"><h2 class="admin-card-title">Coupon preview</h2></div>
                <div class="card-body">
                    <div class="admin-preview-card">
                        <div class="admin-preview-head"><i class="fa-solid fa-ticket"></i></div>
                        <div class="p-3">
                            <span class="admin-status">WELCOME10</span>
                            <p class="fw-bold mt-2 mb-1">10% off first order</p>
                            <p class="admin-section-note mb-0">Valid on orders above &#8377;999</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card admin-card mb-4">
                <div class="admin-card-header"><h2 class="admin-card-title">Availability</h2></div>
                <div class="card-body">
                    <div class="admin-switch-card">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="couponActive" name="couponActive" checked
                                   data-validation="max-items" data-max-items="1">
                            <label class="form-check-label small fw-semibold" for="couponActive">Coupon active</label>
                            <div class="invalid-feedback" id="couponActiveError"></div>
                        </div>
                    </div>
                    <div class="admin-switch-card">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="couponHome" name="couponHome" checked
                                   data-validation="max-items" data-max-items="1">
                            <label class="form-check-label small fw-semibold" for="couponHome">Show on home page</label>
                            <div class="invalid-feedback" id="couponHomeError"></div>
                        </div>
                    </div>
                    <div class="admin-switch-card">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="couponEmail" name="couponEmail"
                                   data-validation="max-items" data-max-items="1">
                            <label class="form-check-label small fw-semibold" for="couponEmail">Include in email campaigns</label>
                            <div class="invalid-feedback" id="couponEmailError"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-save-bar">
                <span class="admin-section-note">Design preview only</span>
                <div class="d-flex flex-wrap gap-2">
                    <a href="admin-offers.php" class="btn btn-outline-primary btn-sm px-3">Cancel</a>
                    <button type="submit" class="btn btn-primary btn-sm px-3">
                        <i class="fa-solid fa-check me-1"></i>Save Coupon
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<?php
$admin_page_content = ob_get_clean();
require 'admin-layout.php';
