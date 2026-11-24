<?php
/*
 * addresses.php - ADDRESS BOOK
 * Saved delivery addresses with Bootstrap modals for adding and editing.
 */

// STEP 1: Saved addresses used to build the cards and edit modals.
$address_labels = ['Home', 'Office', 'Other'];

$addresses = [
    'home' => [
        'label'   => 'Home',
        'icon'    => 'fa-house',
        'default' => true,
        'name'    => 'Aarav Mehta',
        'phone'   => '+91 98765 43210',
        'line1'   => '402, Sunrise Residency',
        'line2'   => '12 Palm Grove Road, Indiranagar',
        'city'    => 'Bengaluru',
        'state'   => 'Karnataka',
        'pin'     => '560038',
    ],
    'office' => [
        'label'   => 'Office',
        'icon'    => 'fa-building',
        'default' => false,
        'name'    => 'Aarav Mehta',
        'phone'   => '+91 98765 43210',
        'line1'   => '7th Floor, Tech Park One',
        'line2'   => 'Whitefield Main Road',
        'city'    => 'Bengaluru',
        'state'   => 'Karnataka',
        'pin'     => '560066',
    ],
];

// STEP 2: Settings used by the shared layout.
$page_title     = 'Address Book | TrendCart';
$active_page    = 'addresses';
$account_active = 'addresses';

// STEP 3: Start capturing this page's HTML.
ob_start();
?>

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="account.php" class="text-decoration-none">My Account</a></li>
            <li class="breadcrumb-item active" aria-current="page">Address Book</li>
        </ol>
    </nav>

    <div class="page-header">
        <span class="badge text-bg-primary mb-3 px-3 py-2">
            <i class="fa-solid fa-location-dot me-1"></i>ADDRESS BOOK
        </span>
        <h1 class="h2 fw-bold mb-3">Saved Addresses</h1>
        <p class="text-muted">Choose where your orders arrive and keep your favourite addresses handy.</p>
    </div>

    <div class="row g-4">
        <!-- ========== SHARED SIDEBAR ========== -->
        <aside class="col-12 col-lg-3">
            <?php require 'account-nav.php'; ?>
        </aside>

        <div class="col-12 col-lg-9">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                <p class="hint text-muted mb-0">
                    <?= count($addresses) ?> saved addresses &middot; Your default is used at checkout
                </p>
                <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal"
                        data-bs-target="#addAddressModal">
                    <i class="fa-solid fa-plus me-1"></i>Add New Address
                </button>
            </div>

            <!-- ========== SAVED ADDRESS CARDS ========== -->
            <div class="row g-3 mb-4">
                <?php foreach ($addresses as $key => $address) : ?>
                    <div class="col-12 col-xl-6">
                        <div class="card store-card address-card h-100 <?= $address['default'] ? 'is-default' : '' ?>">
                            <div class="card-body p-4">
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                                    <h2 class="h6 fw-bold mb-0">
                                        <i class="fa-solid <?= $address['icon'] ?> text-primary me-2"></i>
                                        <?= htmlspecialchars($address['label'], ENT_QUOTES, 'UTF-8') ?>
                                        <?php if ($address['default']) : ?>
                                            <span class="badge address-default-flag ms-2">DEFAULT</span>
                                        <?php endif; ?>
                                    </h2>
                                    <button type="button" class="btn btn-outline-primary btn-sm px-3"
                                            data-bs-toggle="modal" data-bs-target="#editAddress-<?= $key ?>">
                                        <i class="fa-solid fa-pen-to-square me-1"></i>Edit
                                    </button>
                                </div>
                                <p class="fw-bold mb-1"><?= htmlspecialchars($address['name'], ENT_QUOTES, 'UTF-8') ?></p>
                                <p class="hint text-muted mb-2"><?= htmlspecialchars($address['phone'], ENT_QUOTES, 'UTF-8') ?></p>
                                <p class="hint mb-3">
                                    <?= htmlspecialchars($address['line1'], ENT_QUOTES, 'UTF-8') ?><br>
                                    <?= htmlspecialchars($address['line2'], ENT_QUOTES, 'UTF-8') ?><br>
                                    <?= htmlspecialchars($address['city'], ENT_QUOTES, 'UTF-8') ?>,
                                    <?= htmlspecialchars($address['state'], ENT_QUOTES, 'UTF-8') ?>
                                    <?= htmlspecialchars($address['pin'], ENT_QUOTES, 'UTF-8') ?>
                                </p>
                                <div class="d-flex flex-wrap gap-2 border-top pt-3">
                                    <button type="button" class="btn btn-primary btn-sm px-3">
                                        <i class="fa-solid <?= $address['default'] ? 'fa-circle-check' : 'fa-star' ?> me-1"></i>
                                        <?= $address['default'] ? 'Deliver Here' : 'Set as Default' ?>
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm px-3">
                                        <i class="fa-solid fa-trash-can me-1"></i>Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="card store-card">
                <div class="card-body p-4 d-flex flex-wrap align-items-center gap-3">
                    <div class="feature-icon"><i class="fa-solid fa-location-crosshairs"></i></div>
                    <div class="me-auto">
                        <h2 class="h6 fw-bold mb-1">Shopping somewhere new?</h2>
                        <p class="hint text-muted mb-0">Add another delivery location for faster checkout next time.</p>
                    </div>
                    <button type="button" class="btn btn-outline-primary px-4" data-bs-toggle="modal"
                            data-bs-target="#addAddressModal">
                        <i class="fa-solid fa-plus me-1"></i>Add Address
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========== ADD ADDRESS MODAL ========== -->
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title h5 fw-bold" id="addAddressModalLabel">
                    <i class="fa-solid fa-plus me-2"></i>Add New Address
                </h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="post" novalidate>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="addLabel" class="form-label small fw-semibold">Address Label</label>
                            <select class="form-select" id="addLabel" name="addressLabel" data-validation="required">
                                <?php foreach ($address_labels as $label) : ?>
                                    <option value="<?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>"><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback" id="addLabelError"></div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="addPhone" class="form-label small fw-semibold">Mobile Number</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                                <input type="tel" class="form-control" id="addPhone" name="addPhone"
                                       placeholder="+91 98765 43210" data-validation="required max" data-max="15">
                            </div>
                            <div class="invalid-feedback" id="addPhoneError"></div>
                        </div>
                        <div class="col-12">
                            <label for="addName" class="form-label small fw-semibold">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                <input type="text" class="form-control" id="addName" name="addName"
                                       placeholder="Receiver's name" data-validation="required max" data-max="50">
                            </div>
                            <div class="invalid-feedback" id="addNameError"></div>
                        </div>
                        <div class="col-12">
                            <label for="addLine1" class="form-label small fw-semibold">Flat, House No. &amp; Building</label>
                            <input type="text" class="form-control" id="addLine1" name="addLine1"
                                   placeholder="Flat 402, Sunrise Residency" data-validation="required max" data-max="100">
                            <div class="invalid-feedback" id="addLine1Error"></div>
                        </div>
                        <div class="col-12">
                            <label for="addLine2" class="form-label small fw-semibold">Street &amp; Area</label>
                            <input type="text" class="form-control" id="addLine2" name="addLine2"
                                   placeholder="12 Palm Grove Road, Indiranagar" data-validation="max" data-max="100">
                            <div class="invalid-feedback" id="addLine2Error"></div>
                        </div>
                        <div class="col-12 col-md-5">
                            <label for="addCity" class="form-label small fw-semibold">City</label>
                            <input type="text" class="form-control" id="addCity" name="addCity"
                                   placeholder="Bengaluru" data-validation="required max" data-max="50">
                            <div class="invalid-feedback" id="addCityError"></div>
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="addState" class="form-label small fw-semibold">State</label>
                            <input type="text" class="form-control" id="addState" name="addState"
                                   placeholder="Karnataka" data-validation="required max" data-max="50">
                            <div class="invalid-feedback" id="addStateError"></div>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="addPin" class="form-label small fw-semibold">PIN Code</label>
                            <input type="text" class="form-control" id="addPin" name="addPin"
                                   placeholder="560038" data-validation="required numeric min max"
                                   data-min="6" data-max="6">
                            <div class="invalid-feedback" id="addPinError"></div>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="addDefault" name="addDefault"
                                       data-validation="max-items" data-max-items="1">
                                <label class="form-check-label hint" for="addDefault">
                                    Make this my default delivery address
                                </label>
                                <div class="invalid-feedback" id="addDefaultError"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fa-solid fa-plus me-1"></i>Save Address
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========== EDIT ADDRESS MODALS ========== -->
<?php foreach ($addresses as $key => $address) : ?>
    <div class="modal fade" id="editAddress-<?= $key ?>" tabindex="-1"
         aria-labelledby="editAddressLabel-<?= $key ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5 fw-bold" id="editAddressLabel-<?= $key ?>">
                        <i class="fa-solid fa-pen-to-square me-2"></i>Edit
                        <?= htmlspecialchars($address['label'], ENT_QUOTES, 'UTF-8') ?> Address
                    </h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="post" novalidate>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="editLabel-<?= $key ?>" class="form-label small fw-semibold">Address Label</label>
                                <select class="form-select" id="editLabel-<?= $key ?>" name="editLabel-<?= $key ?>" data-validation="required">
                                    <?php foreach ($address_labels as $label) : ?>
                                        <option value="<?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>" <?= $label === $address['label'] ? 'selected' : '' ?>><?= $label ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback" id="editLabel-<?= $key ?>Error"></div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="editPhone-<?= $key ?>" class="form-label small fw-semibold">Mobile Number</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                                    <input type="tel" class="form-control" id="editPhone-<?= $key ?>"
                                           name="editPhone-<?= $key ?>"
                                           value="<?= htmlspecialchars($address['phone'], ENT_QUOTES, 'UTF-8') ?>"
                                           data-validation="required max" data-max="15">
                                </div>
                                <div class="invalid-feedback" id="editPhone-<?= $key ?>Error"></div>
                            </div>
                            <div class="col-12">
                                <label for="editName-<?= $key ?>" class="form-label small fw-semibold">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                    <input type="text" class="form-control" id="editName-<?= $key ?>"
                                           name="editName-<?= $key ?>"
                                           value="<?= htmlspecialchars($address['name'], ENT_QUOTES, 'UTF-8') ?>"
                                           data-validation="required max" data-max="50">
                                </div>
                                <div class="invalid-feedback" id="editName-<?= $key ?>Error"></div>
                            </div>
                            <div class="col-12">
                                <label for="editLine1-<?= $key ?>" class="form-label small fw-semibold">Flat, House No. &amp; Building</label>
                                <input type="text" class="form-control" id="editLine1-<?= $key ?>"
                                       name="editLine1-<?= $key ?>"
                                       value="<?= htmlspecialchars($address['line1'], ENT_QUOTES, 'UTF-8') ?>"
                                       data-validation="required max" data-max="100">
                                <div class="invalid-feedback" id="editLine1-<?= $key ?>Error"></div>
                            </div>
                            <div class="col-12">
                                <label for="editLine2-<?= $key ?>" class="form-label small fw-semibold">Street &amp; Area</label>
                                <input type="text" class="form-control" id="editLine2-<?= $key ?>"
                                       name="editLine2-<?= $key ?>"
                                       value="<?= htmlspecialchars($address['line2'], ENT_QUOTES, 'UTF-8') ?>"
                                       data-validation="max" data-max="100">
                                <div class="invalid-feedback" id="editLine2-<?= $key ?>Error"></div>
                            </div>
                            <div class="col-12 col-md-5">
                                <label for="editCity-<?= $key ?>" class="form-label small fw-semibold">City</label>
                                <input type="text" class="form-control" id="editCity-<?= $key ?>"
                                       name="editCity-<?= $key ?>"
                                       value="<?= htmlspecialchars($address['city'], ENT_QUOTES, 'UTF-8') ?>"
                                       data-validation="required max" data-max="50">
                                <div class="invalid-feedback" id="editCity-<?= $key ?>Error"></div>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="editState-<?= $key ?>" class="form-label small fw-semibold">State</label>
                                <input type="text" class="form-control" id="editState-<?= $key ?>"
                                       name="editState-<?= $key ?>"
                                       value="<?= htmlspecialchars($address['state'], ENT_QUOTES, 'UTF-8') ?>"
                                       data-validation="required max" data-max="50">
                                <div class="invalid-feedback" id="editState-<?= $key ?>Error"></div>
                            </div>
                            <div class="col-12 col-md-3">
                                <label for="editPin-<?= $key ?>" class="form-label small fw-semibold">PIN Code</label>
                                <input type="text" class="form-control" id="editPin-<?= $key ?>"
                                       name="editPin-<?= $key ?>"
                                       value="<?= htmlspecialchars($address['pin'], ENT_QUOTES, 'UTF-8') ?>"
                                       data-validation="required numeric min max" data-min="6" data-max="6">
                                <div class="invalid-feedback" id="editPin-<?= $key ?>Error"></div>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="editDefault-<?= $key ?>"
                                        name="editDefault-<?= $key ?>" data-validation="max-items" data-max-items="1"
                                        <?= $address['default'] ? 'checked' : '' ?>>
                                    <label class="form-check-label hint" for="editDefault-<?= $key ?>">
                                        Make this my default delivery address
                                    </label>
                                    <div class="invalid-feedback" id="editDefault-<?= $key ?>Error"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fa-solid fa-check me-1"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php
// STEP 4: Save the captured HTML and stop buffering.
$page_content = ob_get_clean();

// STEP 5: Render it inside the shared layout.
require 'layout.php';
