<?php
/*
 * addresses.php - ADDRESS BOOK
 * Dynamic address management (Add, Edit, Set as Default, Delete) linked to MySQL.
 */

require_once __DIR__ . '/check_auth.php';
require_once __DIR__ . '/db.php';

$user    = current_user();
$user_id = $user['id'];

$address_labels = ['Home', 'Office', 'Other'];

// Auto-check and create missing columns if table already exists in MySQL
try {
    $pdo->query("SELECT name, line2 FROM addresses LIMIT 1");
} catch (PDOException $e) {
    try {
        $pdo->exec("ALTER TABLE addresses ADD COLUMN name VARCHAR(100) NULL DEFAULT NULL AFTER user_id, ADD COLUMN line2 VARCHAR(180) NULL DEFAULT NULL AFTER line1");
    } catch (PDOException $ex) {}
}

$flash = $_SESSION['address_flash'] ?? null;
unset($_SESSION['address_flash']);

// -------------------------------------------------------------
// 1. HANDLE POST ACTIONS (Add / Edit)
// -------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // ADD ADDRESS
    if ($action === 'add') {
        $label      = trim($_POST['addressLabel'] ?? 'Home');
        $name       = trim($_POST['addName'] ?? '');
        $phone      = trim($_POST['addPhone'] ?? '');
        $line1      = trim($_POST['addLine1'] ?? '');
        $line2      = trim($_POST['addLine2'] ?? '');
        $city       = trim($_POST['addCity'] ?? '');
        $state      = trim($_POST['addState'] ?? '');
        $pin        = trim($_POST['addPin'] ?? '');
        $is_default = !empty($_POST['addDefault']) ? 1 : 0;

        if ($line1 === '' || $city === '' || $state === '' || $pin === '' || $phone === '') {
            $_SESSION['address_flash'] = ['type' => 'danger', 'message' => 'Please fill in all required address fields.'];
        } else {
            try {
                // If this is set as default or is user's first address, update previous defaults
                $existingCount = (int) (db_one("SELECT COUNT(*) as c FROM addresses WHERE user_id = ? AND status = 'active'", [$user_id])['c'] ?? 0);
                if ($existingCount === 0) {
                    $is_default = 1; // First address is automatically default
                } elseif ($is_default === 1) {
                    db_exec("UPDATE addresses SET is_default = 0 WHERE user_id = ?", [$user_id]);
                }

                $sql = "INSERT INTO addresses (user_id, name, label, line1, line2, city, state, pin, phone, is_default, status)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')";
                db_exec($sql, [
                    $user_id,
                    $name !== '' ? $name : $user['fullname'],
                    $label,
                    $line1,
                    $line2,
                    $city,
                    $state,
                    $pin,
                    $phone !== '' ? $phone : ($user['phone'] ?? ''),
                    $is_default
                ]);

                $_SESSION['address_flash'] = ['type' => 'success', 'message' => 'New address added successfully!'];
            } catch (PDOException $e) {
                $_SESSION['address_flash'] = ['type' => 'danger', 'message' => 'Failed to save address: ' . $e->getMessage()];
            }
        }
        header('Location: addresses.php');
        exit;
    }

    // EDIT ADDRESS
    if ($action === 'edit') {
        $address_id = (int) ($_POST['address_id'] ?? 0);
        $label      = trim($_POST['editLabel'] ?? 'Home');
        $name       = trim($_POST['editName'] ?? '');
        $phone      = trim($_POST['editPhone'] ?? '');
        $line1      = trim($_POST['editLine1'] ?? '');
        $line2      = trim($_POST['editLine2'] ?? '');
        $city       = trim($_POST['editCity'] ?? '');
        $state      = trim($_POST['editState'] ?? '');
        $pin        = trim($_POST['editPin'] ?? '');
        $is_default = !empty($_POST['editDefault']) ? 1 : 0;

        if ($address_id > 0) {
            try {
                // Verify address belongs to user
                $check = db_one("SELECT id FROM addresses WHERE id = ? AND user_id = ? LIMIT 1", [$address_id, $user_id]);
                if ($check) {
                    if ($is_default === 1) {
                        db_exec("UPDATE addresses SET is_default = 0 WHERE user_id = ?", [$user_id]);
                    }

                    $sql = "UPDATE addresses 
                            SET name = ?, label = ?, line1 = ?, line2 = ?, city = ?, state = ?, pin = ?, phone = ?, is_default = ?
                            WHERE id = ? AND user_id = ?";
                    db_exec($sql, [
                        $name !== '' ? $name : $user['fullname'],
                        $label,
                        $line1,
                        $line2,
                        $city,
                        $state,
                        $pin,
                        $phone,
                        $is_default,
                        $address_id,
                        $user_id
                    ]);

                    $_SESSION['address_flash'] = ['type' => 'success', 'message' => 'Address updated successfully!'];
                }
            } catch (PDOException $e) {
                $_SESSION['address_flash'] = ['type' => 'danger', 'message' => 'Failed to update address: ' . $e->getMessage()];
            }
        }
        header('Location: addresses.php');
        exit;
    }
}

// -------------------------------------------------------------
// 2. HANDLE GET ACTIONS (Set Default / Delete)
// -------------------------------------------------------------
if (isset($_GET['action'])) {
    $action     = $_GET['action'];
    $address_id = (int) ($_GET['id'] ?? 0);

    if ($address_id > 0) {
        try {
            if ($action === 'set_default') {
                db_exec("UPDATE addresses SET is_default = 0 WHERE user_id = ?", [$user_id]);
                db_exec("UPDATE addresses SET is_default = 1 WHERE id = ? AND user_id = ?", [$address_id, $user_id]);
                $_SESSION['address_flash'] = ['type' => 'success', 'message' => 'Default delivery address updated!'];
            } elseif ($action === 'delete') {
                db_exec("DELETE FROM addresses WHERE id = ? AND user_id = ?", [$address_id, $user_id]);
                $_SESSION['address_flash'] = ['type' => 'info', 'message' => 'Address removed.'];
            }
        } catch (PDOException $e) {
            $_SESSION['address_flash'] = ['type' => 'danger', 'message' => 'Error: ' . $e->getMessage()];
        }
    }
    header('Location: addresses.php');
    exit;
}

// -------------------------------------------------------------
// 3. FETCH DYNAMIC SAVED ADDRESSES FOR CURRENT USER
// -------------------------------------------------------------
try {
    $addresses = db_all(
        "SELECT * FROM addresses WHERE user_id = ? AND status = 'active' ORDER BY is_default DESC, id DESC",
        [$user_id]
    );
} catch (PDOException $e) {
    $addresses = [];
}

// Helper icon mapping
function get_address_icon($label) {
    $map = [
        'Home'   => 'fa-house',
        'Office' => 'fa-building',
        'Other'  => 'fa-location-dot',
    ];
    return $map[$label] ?? 'fa-location-dot';
}

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

    <?php if ($flash): ?>
        <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show mb-4" role="alert">
            <?= htmlspecialchars($flash['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- ========== SHARED SIDEBAR ========== -->
        <aside class="col-12 col-lg-3">
            <?php require 'account-nav.php'; ?>
        </aside>

        <div class="col-12 col-lg-9">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                <p class="hint text-muted mb-0">
                    <?= count($addresses) ?> saved address<?= count($addresses) === 1 ? '' : 'es' ?> &middot; Your default address is preselected at checkout
                </p>
                <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                    <i class="fa-solid fa-plus me-1"></i>Add New Address
                </button>
            </div>

            <!-- ========== SAVED ADDRESS CARDS ========== -->
            <?php if (empty($addresses)): ?>
                <div class="card store-card text-center p-5 mb-4">
                    <div class="feature-icon mx-auto mb-3 text-muted opacity-75">
                        <i class="fa-solid fa-location-dot fa-2x"></i>
                    </div>
                    <h3 class="h5 fw-bold mb-2">No Saved Addresses</h3>
                    <p class="text-muted hint mb-3">You have not added any delivery addresses yet.</p>
                    <div>
                        <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                            <i class="fa-solid fa-plus me-1"></i>Add Your First Address
                        </button>
                    </div>
                </div>
            <?php else: ?>
                <div class="row g-3 mb-4">
                    <?php foreach ($addresses as $address) : ?>
                        <?php 
                            $isDef = (int)($address['is_default'] ?? 0) === 1; 
                            $addrId = (int)$address['id'];
                            $icon   = get_address_icon($address['label']);
                        ?>
                        <div class="col-12 col-xl-6">
                            <div class="card store-card address-card h-100 <?= $isDef ? 'is-default border-primary' : '' ?>">
                                <div class="card-body p-4 d-flex flex-column">
                                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                                        <h2 class="h6 fw-bold mb-0">
                                            <i class="fa-solid <?= $icon ?> text-primary me-2"></i>
                                            <?= htmlspecialchars($address['label'], ENT_QUOTES, 'UTF-8') ?>
                                            <?php if ($isDef) : ?>
                                                <span class="badge text-bg-primary ms-2">DEFAULT</span>
                                            <?php endif; ?>
                                        </h2>
                                        <button type="button" class="btn btn-outline-primary btn-sm px-3"
                                                data-bs-toggle="modal" data-bs-target="#editAddress-<?= $addrId ?>">
                                            <i class="fa-solid fa-pen-to-square me-1"></i>Edit
                                        </button>
                                    </div>

                                    <p class="fw-bold mb-1"><?= htmlspecialchars($address['name'] ?? $user['fullname'], ENT_QUOTES, 'UTF-8') ?></p>
                                    <p class="hint text-muted mb-2"><i class="fa-solid fa-phone me-1 small"></i><?= htmlspecialchars($address['phone'], ENT_QUOTES, 'UTF-8') ?></p>
                                    <p class="hint mb-3 flex-grow-1">
                                        <?= htmlspecialchars($address['line1'], ENT_QUOTES, 'UTF-8') ?>
                                        <?php if (!empty($address['line2'])): ?>
                                            <br><?= htmlspecialchars($address['line2'], ENT_QUOTES, 'UTF-8') ?>
                                        <?php endif; ?>
                                        <br><?= htmlspecialchars($address['city'], ENT_QUOTES, 'UTF-8') ?>, <?= htmlspecialchars($address['state'], ENT_QUOTES, 'UTF-8') ?> - <?= htmlspecialchars($address['pin'], ENT_QUOTES, 'UTF-8') ?>
                                    </p>

                                    <div class="d-flex flex-wrap gap-2 border-top pt-3 mt-auto">
                                        <?php if (!$isDef): ?>
                                            <a href="addresses.php?action=set_default&id=<?= $addrId ?>" class="btn btn-outline-primary btn-sm px-3">
                                                <i class="fa-regular fa-star me-1"></i>Set as Default
                                            </a>
                                        <?php else: ?>
                                            <span class="btn btn-light btn-sm px-3 text-success fw-semibold disabled">
                                                <i class="fa-solid fa-circle-check me-1"></i>Default Address
                                            </span>
                                        <?php endif; ?>

                                        <a href="addresses.php?action=delete&id=<?= $addrId ?>" class="btn btn-outline-danger btn-sm px-3 ms-auto"
                                           onclick="return confirm('Are you sure you want to delete this address?');">
                                            <i class="fa-solid fa-trash-can me-1"></i>Remove
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="card store-card">
                <div class="card-body p-4 d-flex flex-wrap align-items-center gap-3">
                    <div class="feature-icon"><i class="fa-solid fa-location-crosshairs"></i></div>
                    <div class="me-auto">
                        <h2 class="h6 fw-bold mb-1">Shopping somewhere new?</h2>
                        <p class="hint text-muted mb-0">Add another delivery location for faster checkout next time.</p>
                    </div>
                    <button type="button" class="btn btn-outline-primary px-4" data-bs-toggle="modal" data-bs-target="#addAddressModal">
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
                    <i class="fa-solid fa-plus me-2 text-primary"></i>Add New Address
                </h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="addresses.php" method="post" novalidate>
                <input type="hidden" name="action" value="add">
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
                                       value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                                       placeholder="+91 98765 43210" data-validation="required numeric min max"
                                       data-min="10" data-max="15">
                            </div>
                            <div class="invalid-feedback" id="addPhoneError"></div>
                        </div>
                        <div class="col-12">
                            <label for="addName" class="form-label small fw-semibold">Recipient Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                <input type="text" class="form-control" id="addName" name="addName"
                                       value="<?= htmlspecialchars($user['fullname']) ?>"
                                       placeholder="Recipient's name" data-validation="required min max"
                                       data-min="2" data-max="50">
                            </div>
                            <div class="invalid-feedback" id="addNameError"></div>
                        </div>
                        <div class="col-12">
                            <label for="addLine1" class="form-label small fw-semibold">Flat, House No., Building</label>
                            <input type="text" class="form-control" id="addLine1" name="addLine1"
                                   placeholder="e.g. Flat 402, Sunrise Residency" data-validation="required">
                            <div class="invalid-feedback" id="addLine1Error"></div>
                        </div>
                        <div class="col-12">
                            <label for="addLine2" class="form-label small fw-semibold">Street, Area, Landmark</label>
                            <input type="text" class="form-control" id="addLine2" name="addLine2"
                                   placeholder="e.g. 12 Palm Grove Road, Near Law Garden">
                        </div>
                        <div class="col-12 col-md-5">
                            <label for="addCity" class="form-label small fw-semibold">City</label>
                            <input type="text" class="form-control" id="addCity" name="addCity"
                                   placeholder="e.g. Ahmedabad" data-validation="required alpha">
                            <div class="invalid-feedback" id="addCityError"></div>
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="addState" class="form-label small fw-semibold">State</label>
                            <input type="text" class="form-control" id="addState" name="addState"
                                   placeholder="e.g. Gujarat" data-validation="required alpha">
                            <div class="invalid-feedback" id="addStateError"></div>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="addPin" class="form-label small fw-semibold">PIN Code</label>
                            <input type="text" class="form-control" id="addPin" name="addPin"
                                   placeholder="e.g. 380006" data-validation="required numeric min max"
                                   data-min="6" data-max="10">
                            <div class="invalid-feedback" id="addPinError"></div>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="addDefault" name="addDefault" value="1"
                                       <?= empty($addresses) ? 'checked' : '' ?>>
                                <label class="form-check-label hint" for="addDefault">
                                    Make this my default delivery address
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fa-solid fa-plus me-1"></i>Save Address
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========== EDIT ADDRESS MODALS ========== -->
<?php foreach ($addresses as $address) : ?>
    <?php 
        $addrId = (int)$address['id'];
        $isDef  = (int)($address['is_default'] ?? 0) === 1;
    ?>
    <div class="modal fade" id="editAddress-<?= $addrId ?>" tabindex="-1"
         aria-labelledby="editAddressLabel-<?= $addrId ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5 fw-bold" id="editAddressLabel-<?= $addrId ?>">
                        <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>Edit
                        <?= htmlspecialchars($address['label'], ENT_QUOTES, 'UTF-8') ?> Address
                    </h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="addresses.php" method="post" novalidate>
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="address_id" value="<?= $addrId ?>">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="editLabel-<?= $addrId ?>" class="form-label small fw-semibold">Address Label</label>
                                <select class="form-select" id="editLabel-<?= $addrId ?>" name="editLabel" data-validation="required">
                                    <?php foreach ($address_labels as $label) : ?>
                                        <option value="<?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>" <?= $label === $address['label'] ? 'selected' : '' ?>><?= $label ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback" id="editLabel-<?= $addrId ?>Error"></div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="editPhone-<?= $addrId ?>" class="form-label small fw-semibold">Mobile Number</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                                    <input type="tel" class="form-control" id="editPhone-<?= $addrId ?>"
                                           name="editPhone"
                                           value="<?= htmlspecialchars($address['phone'], ENT_QUOTES, 'UTF-8') ?>"
                                           data-validation="required numeric min max" data-min="10" data-max="15">
                                </div>
                                <div class="invalid-feedback" id="editPhone-<?= $addrId ?>Error"></div>
                            </div>
                            <div class="col-12">
                                <label for="editName-<?= $addrId ?>" class="form-label small fw-semibold">Recipient Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                    <input type="text" class="form-control" id="editName-<?= $addrId ?>"
                                           name="editName"
                                           value="<?= htmlspecialchars($address['name'] ?? $user['fullname'], ENT_QUOTES, 'UTF-8') ?>"
                                           data-validation="required min max" data-min="2" data-max="50">
                                </div>
                                <div class="invalid-feedback" id="editName-<?= $addrId ?>Error"></div>
                            </div>
                            <div class="col-12">
                                <label for="editLine1-<?= $addrId ?>" class="form-label small fw-semibold">Flat, House No., Building</label>
                                <input type="text" class="form-control" id="editLine1-<?= $addrId ?>"
                                       name="editLine1"
                                       value="<?= htmlspecialchars($address['line1'], ENT_QUOTES, 'UTF-8') ?>"
                                       data-validation="required">
                                <div class="invalid-feedback" id="editLine1-<?= $addrId ?>Error"></div>
                            </div>
                            <div class="col-12">
                                <label for="editLine2-<?= $addrId ?>" class="form-label small fw-semibold">Street, Area, Landmark</label>
                                <input type="text" class="form-control" id="editLine2-<?= $addrId ?>"
                                       name="editLine2"
                                       value="<?= htmlspecialchars($address['line2'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                            <div class="col-12 col-md-5">
                                <label for="editCity-<?= $addrId ?>" class="form-label small fw-semibold">City</label>
                                <input type="text" class="form-control" id="editCity-<?= $addrId ?>"
                                       name="editCity"
                                       value="<?= htmlspecialchars($address['city'], ENT_QUOTES, 'UTF-8') ?>"
                                       data-validation="required alpha">
                                <div class="invalid-feedback" id="editCity-<?= $addrId ?>Error"></div>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="editState-<?= $addrId ?>" class="form-label small fw-semibold">State</label>
                                <input type="text" class="form-control" id="editState-<?= $addrId ?>"
                                       name="editState"
                                       value="<?= htmlspecialchars($address['state'], ENT_QUOTES, 'UTF-8') ?>"
                                       data-validation="required alpha">
                                <div class="invalid-feedback" id="editState-<?= $addrId ?>Error"></div>
                            </div>
                            <div class="col-12 col-md-3">
                                <label for="editPin-<?= $addrId ?>" class="form-label small fw-semibold">PIN Code</label>
                                <input type="text" class="form-control" id="editPin-<?= $addrId ?>"
                                       name="editPin"
                                       value="<?= htmlspecialchars($address['pin'], ENT_QUOTES, 'UTF-8') ?>"
                                       data-validation="required numeric min max" data-min="6" data-max="10">
                                <div class="invalid-feedback" id="editPin-<?= $addrId ?>Error"></div>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="editDefault-<?= $addrId ?>"
                                           name="editDefault" value="1"
                                           <?= $isDef ? 'checked' : '' ?>>
                                    <label class="form-check-label hint" for="editDefault-<?= $addrId ?>">
                                        Make this my default delivery address
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
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
