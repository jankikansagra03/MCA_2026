<?php
/*
 * cart.php - SHOPPING CART
 * Dynamic cart rows, quantity controls and price summary connected to MySQL.
 */

require_once __DIR__ . '/check_auth.php';
require_once __DIR__ . '/db.php';

$user    = current_user();
$user_id = (int)$user['id'];

$flash = $_SESSION['cart_flash'] ?? null;
unset($_SESSION['cart_flash']);

// -------------------------------------------------------------
// 1. HANDLE POST & GET ACTIONS (Quantity, Remove, Wishlist, Coupon)
// -------------------------------------------------------------
$action = $_POST['action'] ?? ($_GET['action'] ?? '');

if (!empty($action)) {
    try {
        if ($action === 'update_qty') {
            $cart_id = (int)($_POST['cart_id'] ?? 0);
            $qty     = (int)($_POST['qty'] ?? 1);
            if ($qty <= 0) {
                db_exec("DELETE FROM carts WHERE id = ? AND user_id = ?", [$cart_id, $user_id]);
                $_SESSION['cart_flash'] = ['type' => 'success', 'message' => 'Item removed from your cart.'];
            } else {
                db_exec("UPDATE carts SET qty = ? WHERE id = ? AND user_id = ?", [$qty, $cart_id, $user_id]);
            }
            header('Location: cart.php');
            exit;
        }

        if ($action === 'increase') {
            $cart_id = (int)($_POST['cart_id'] ?? ($_GET['cart_id'] ?? 0));
            db_exec("UPDATE carts SET qty = qty + 1 WHERE id = ? AND user_id = ?", [$cart_id, $user_id]);
            header('Location: cart.php');
            exit;
        }

        if ($action === 'decrease') {
            $cart_id = (int)($_POST['cart_id'] ?? ($_GET['cart_id'] ?? 0));
            $item = db_one("SELECT qty FROM carts WHERE id = ? AND user_id = ?", [$cart_id, $user_id]);
            if ($item && (int)$item['qty'] > 1) {
                db_exec("UPDATE carts SET qty = qty - 1 WHERE id = ? AND user_id = ?", [$cart_id, $user_id]);
            } else {
                db_exec("DELETE FROM carts WHERE id = ? AND user_id = ?", [$cart_id, $user_id]);
                $_SESSION['cart_flash'] = ['type' => 'success', 'message' => 'Item removed from your cart.'];
            }
            header('Location: cart.php');
            exit;
        }

        if ($action === 'remove') {
            $cart_id = (int)($_POST['cart_id'] ?? ($_GET['cart_id'] ?? 0));
            db_exec("DELETE FROM carts WHERE id = ? AND user_id = ?", [$cart_id, $user_id]);
            $_SESSION['cart_flash'] = ['type' => 'success', 'message' => 'Item removed from your cart.'];
            header('Location: cart.php');
            exit;
        }

        if ($action === 'save_for_later') {
            $cart_id = (int)($_POST['cart_id'] ?? 0);
            $cart_item = db_one("SELECT product_id FROM carts WHERE id = ? AND user_id = ?", [$cart_id, $user_id]);
            if ($cart_item) {
                db_exec(
                    "INSERT INTO wishlists (user_id, product_id, status) VALUES (?, ?, 'active') 
                     ON DUPLICATE KEY UPDATE status = 'active'",
                    [$user_id, $cart_item['product_id']]
                );
                db_exec("DELETE FROM carts WHERE id = ? AND user_id = ?", [$cart_id, $user_id]);
                $_SESSION['cart_flash'] = ['type' => 'success', 'message' => 'Item saved to your wishlist!'];
            }
            header('Location: cart.php');
            exit;
        }

        if ($action === 'apply_coupon') {
            $coupon = strtoupper(trim($_POST['couponCode'] ?? ''));
            if ($coupon === 'WELCOME10') {
                $_SESSION['cart_coupon'] = 'WELCOME10';
                $_SESSION['cart_flash'] = ['type' => 'success', 'message' => 'Coupon WELCOME10 applied! You get 10% off.'];
            } else {
                $_SESSION['cart_flash'] = ['type' => 'danger', 'message' => 'Invalid or expired coupon code.'];
            }
            header('Location: cart.php');
            exit;
        }

        if ($action === 'remove_coupon') {
            unset($_SESSION['cart_coupon']);
            $_SESSION['cart_flash'] = ['type' => 'info', 'message' => 'Coupon removed.'];
            header('Location: cart.php');
            exit;
        }

        if ($action === 'clear_cart') {
            db_exec("DELETE FROM carts WHERE user_id = ?", [$user_id]);
            $_SESSION['cart_flash'] = ['type' => 'info', 'message' => 'Your shopping cart has been cleared.'];
            header('Location: cart.php');
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['cart_flash'] = ['type' => 'danger', 'message' => 'Error: ' . $e->getMessage()];
        header('Location: cart.php');
        exit;
    }
}

// -------------------------------------------------------------
// 2. FETCH CART DATA & ADDRESS
// -------------------------------------------------------------
try {
    $cart_items = db_all(
        "SELECT c.id AS cart_id, c.qty, c.product_id,
                p.name, p.slug, p.price_paise, p.old_price_paise, p.stock_qty, p.image_icon, p.image_url,
                cat.name AS category_name
         FROM carts c
         JOIN products p ON p.id = c.product_id
         LEFT JOIN categories cat ON cat.id = p.category_id
         WHERE c.user_id = ? AND c.status = 'active'
         ORDER BY c.id DESC",
        [$user_id]
    );

    $default_address = db_one(
        "SELECT * FROM addresses WHERE user_id = ? AND status = 'active' ORDER BY is_default DESC, id DESC LIMIT 1",
        [$user_id]
    );
} catch (PDOException $e) {
    $cart_items = [];
    $default_address = null;
}

// -------------------------------------------------------------
// 3. FINANCIAL CALCULATIONS
// -------------------------------------------------------------
$total_qty               = 0;
$subtotal_paise          = 0;
$product_discount_paise  = 0;

foreach ($cart_items as $item) {
    $item_qty = (int)$item['qty'];
    $total_qty += $item_qty;
    $subtotal_paise += ((int)$item['price_paise'] * $item_qty);

    if (!empty($item['old_price_paise']) && (int)$item['old_price_paise'] > (int)$item['price_paise']) {
        $product_discount_paise += (((int)$item['old_price_paise'] - (int)$item['price_paise']) * $item_qty);
    }
}

// Delivery fee: Free for orders >= ₹999 (99900 paise) or empty cart, else ₹50 (5000 paise)
$free_delivery_threshold = 99900;
$delivery_fee_paise      = (!empty($cart_items) && $subtotal_paise < $free_delivery_threshold) ? 5000 : 0;

// Coupon discount
$applied_coupon = $_SESSION['cart_coupon'] ?? null;
$coupon_discount_paise = 0;
if ($applied_coupon === 'WELCOME10' && $subtotal_paise > 0) {
    $coupon_discount_paise = (int)round($subtotal_paise * 0.10);
}

$grand_total_paise = max(0, $subtotal_paise + $delivery_fee_paise - $coupon_discount_paise);
$total_savings_paise = $product_discount_paise + $coupon_discount_paise;

// STEP 1: Settings used by the shared layout.
$page_title     = 'Shopping Cart | TrendCart';
$active_page    = 'cart';
$account_active = 'cart';

// STEP 2: Start capturing this page's HTML.
ob_start();
?>

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="account.php" class="text-decoration-none">My Account</a></li>
            <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
        </ol>
    </nav>

    <div class="page-header">
        <span class="badge text-bg-primary mb-3 px-3 py-2">
            <i class="fa-solid fa-cart-shopping me-1"></i>YOUR CART
        </span>
        <h1 class="h2 fw-bold mb-3">Shopping Cart</h1>
        <p class="text-muted">Review your picks, adjust quantities and check out when you are ready.</p>
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
            <!-- Delivery strip -->
            <div class="card store-card mb-4">
                <div class="card-body p-3 d-flex flex-wrap align-items-center gap-3">
                    <div class="feature-icon"><i class="fa-solid fa-truck"></i></div>
                    <div class="me-auto">
                        <?php if ($default_address): ?>
                            <p class="fw-bold mb-0">Delivering to <?= htmlspecialchars($default_address['name'] ?? $user['fullname']) ?> &middot; <?= htmlspecialchars($default_address['pin']) ?></p>
                            <p class="hint text-muted mb-0">
                                <?= $delivery_fee_paise === 0 ? 'Free delivery on this order.' : 'Standard delivery charge applied.' ?>
                                Estimated arrival: <strong>2&ndash;4 business days</strong>.
                            </p>
                        <?php else: ?>
                            <p class="fw-bold mb-0">No default delivery address selected</p>
                            <p class="hint text-muted mb-0">Add your delivery address to see accurate arrival dates.</p>
                        <?php endif; ?>
                    </div>
                    <a href="addresses.php" class="btn btn-sm btn-outline-primary"><?= $default_address ? 'Change address' : 'Add address' ?></a>
                </div>
            </div>

            <div class="row g-4">
                <!-- ========== CART ITEMS ========== -->
                <div class="col-12 col-xl-8">
                    <div class="card store-card">
                        <div class="card-body p-3 p-sm-4 border-bottom">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                <h2 class="h6 fw-bold mb-0">
                                    Items in your cart <span class="badge text-bg-primary ms-1"><?= $total_qty ?></span>
                                </h2>
                                <div class="d-flex align-items-center gap-3">
                                    <?php if (!empty($cart_items)): ?>
                                        <form action="cart.php" method="post" class="d-inline" onsubmit="return confirm('Clear your entire cart?');">
                                            <input type="hidden" name="action" value="clear_cart">
                                            <button type="submit" class="btn btn-link text-danger p-0 hint text-decoration-none">
                                                <i class="fa-solid fa-trash-can me-1"></i>Clear cart
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <a href="product.php" class="hint text-decoration-none fw-semibold">
                                        <i class="fa-solid fa-arrow-left me-1"></i>Continue shopping
                                    </a>
                                </div>
                            </div>
                        </div>

                        <?php if (empty($cart_items)): ?>
                            <div class="card-body p-5 text-center">
                                <div class="feature-icon mx-auto mb-3 text-muted opacity-75">
                                    <i class="fa-solid fa-cart-shopping fa-2x"></i>
                                </div>
                                <h3 class="h5 fw-bold mb-2">Your Shopping Cart is Empty</h3>
                                <p class="text-muted hint mb-4">Looks like you haven't added anything to your cart yet.</p>
                                <a href="product.php" class="btn btn-primary px-4">
                                    <i class="fa-solid fa-bag-shopping me-1"></i>Explore Products
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="cart-items">
                                <?php foreach ($cart_items as $item): ?>
                                    <?php
                                        $cart_id  = (int)$item['cart_id'];
                                        $qty      = (int)$item['qty'];
                                        $price    = (int)$item['price_paise'];
                                        $old      = (int)($item['old_price_paise'] ?? 0);
                                        $in_stock = (int)($item['stock_qty'] ?? 0) > 0;
                                        $discount_pct = ($old > $price) ? round((($old - $price) / $old) * 100) : 0;
                                    ?>
                                    <div class="cart-item p-3 border-bottom">
                                        <div class="row g-3 align-items-center">
                                            <div class="col-auto">
                                                <div class="product-img order-thumb rounded d-flex align-items-center justify-content-center position-relative">
                                                    <?php if ($discount_pct > 0): ?>
                                                        <span class="badge discount-badge text-bg-primary position-absolute top-0 start-0 m-1">-<?= $discount_pct ?>%</span>
                                                    <?php endif; ?>
                                                    <?php if (!empty($item['image_url'])): ?>
                                                        <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="img-fluid rounded">
                                                    <?php else: ?>
                                                        <i class="fa-solid <?= htmlspecialchars($item['image_icon'] ?: 'fa-box') ?> text-primary fa-lg"></i>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                                    <span class="stock-pill <?= $in_stock ? '' : 'bg-danger-subtle text-danger' ?>"><?= $in_stock ? 'In stock' : 'Out of stock' ?></span>
                                                    <small class="text-muted"><?= htmlspecialchars($item['category_name'] ?? 'Product') ?></small>
                                                </div>
                                                <h3 class="h6 fw-bold mb-1">
                                                    <a href="product-detail.php?product=<?= urlencode($item['slug']) ?>" class="text-decoration-none text-dark">
                                                        <?= htmlspecialchars($item['name']) ?>
                                                    </a>
                                                </h3>
                                                <p class="hint text-muted mb-2"><?= money($price) ?> each &middot; Sold by TrendCart</p>
                                                <div class="d-flex gap-2">
                                                    <a href="product-detail.php?product=<?= urlencode($item['slug']) ?>"
                                                        class="product-icon-btn btn btn-sm btn-outline-primary rounded-circle"
                                                        title="View product"><i class="fa-regular fa-eye"></i></a>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-auto">
                                                <div class="qty-stepper d-flex align-items-center gap-1">
                                                    <form action="cart.php" method="post" class="d-inline">
                                                        <input type="hidden" name="action" value="decrease">
                                                        <input type="hidden" name="cart_id" value="<?= $cart_id ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-primary" aria-label="Decrease quantity">
                                                            <i class="fa-solid fa-minus"></i>
                                                        </button>
                                                    </form>
                                                    <form action="cart.php" method="post" class="d-inline">
                                                        <input type="hidden" name="action" value="update_qty">
                                                        <input type="hidden" name="cart_id" value="<?= $cart_id ?>">
                                                        <input type="number" name="qty" class="form-control form-control-sm text-center" 
                                                               value="<?= $qty ?>" min="1" max="99" style="width: 55px;"
                                                               onchange="this.form.submit()">
                                                    </form>
                                                    <form action="cart.php" method="post" class="d-inline">
                                                        <input type="hidden" name="action" value="increase">
                                                        <input type="hidden" name="cart_id" value="<?= $cart_id ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-primary" aria-label="Increase quantity">
                                                            <i class="fa-solid fa-plus"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="col-auto text-end">
                                                <p class="fw-bold mb-0"><?= money($price * $qty) ?></p>
                                                <?php if ($old > $price): ?>
                                                    <p class="price-old hint mb-1"><?= money($old * $qty) ?></p>
                                                <?php endif; ?>
                                                <form action="cart.php" method="post" class="d-inline">
                                                    <input type="hidden" name="action" value="save_for_later">
                                                    <input type="hidden" name="cart_id" value="<?= $cart_id ?>">
                                                    <button type="submit" class="btn btn-link btn-sm text-primary p-0 hint">Save for later</button>
                                                </form>
                                                <form action="cart.php" method="post" class="d-inline">
                                                    <input type="hidden" name="action" value="remove">
                                                    <input type="hidden" name="cart_id" value="<?= $cart_id ?>">
                                                    <button type="submit" class="btn btn-link btn-sm text-muted p-0 hint d-block ms-auto">Remove</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- ========== PRICE SUMMARY ========== -->
                <div class="col-12 col-xl-4">
                    <div class="card store-card cart-summary">
                        <div class="card-body p-4">
                            <h2 class="h6 fw-bold mb-3">Price Details</h2>

                            <div class="summary-row hint mb-2 d-flex justify-content-between">
                                <span class="text-muted">Subtotal (<?= $total_qty ?> item<?= $total_qty === 1 ? '' : 's' ?>)</span>
                                <span><?= money($subtotal_paise) ?></span>
                            </div>

                            <?php if ($product_discount_paise > 0): ?>
                                <div class="summary-row hint mb-2 d-flex justify-content-between">
                                    <span class="text-muted">Product discount</span>
                                    <span class="text-success">&minus;<?= money($product_discount_paise) ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if ($coupon_discount_paise > 0): ?>
                                <div class="summary-row hint mb-2 d-flex justify-content-between align-items-center">
                                    <span class="text-muted">
                                        Coupon <strong class="text-primary"><?= htmlspecialchars($applied_coupon) ?></strong>
                                        <a href="cart.php?action=remove_coupon" class="text-danger small ms-1" title="Remove coupon"><i class="fa-solid fa-xmark"></i></a>
                                    </span>
                                    <span class="text-success">&minus;<?= money($coupon_discount_paise) ?></span>
                                </div>
                            <?php endif; ?>

                            <div class="summary-row hint mb-3 d-flex justify-content-between">
                                <span class="text-muted">Delivery charges</span>
                                <span><?= $delivery_fee_paise === 0 ? '<span class="text-success">Free</span>' : money($delivery_fee_paise) ?></span>
                            </div>

                            <hr class="my-3">

                            <div class="summary-row summary-total fw-bold d-flex justify-content-between fs-5 mb-3">
                                <span>Total Amount</span>
                                <span><?= money($grand_total_paise) ?></span>
                            </div>

                            <?php if ($total_savings_paise > 0): ?>
                                <div class="alert alert-success border-0 hint mb-3 py-2 text-center">
                                    <i class="fa-solid fa-piggy-bank me-1"></i>You save <?= money($total_savings_paise) ?> on this order!
                                </div>
                            <?php endif; ?>

                            <form action="cart.php" method="post" class="mb-3">
                                <input type="hidden" name="action" value="apply_coupon">
                                <label for="couponCode" class="form-label small fw-semibold">Have a coupon?</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-ticket"></i></span>
                                    <input type="text" class="form-control" id="couponCode" name="couponCode"
                                           placeholder="e.g. WELCOME10" value="<?= htmlspecialchars($applied_coupon ?? '') ?>"
                                           data-validation="max" data-max="20">
                                    <button class="btn btn-outline-primary" type="submit">Apply</button>
                                </div>
                                <div class="invalid-feedback" id="couponCodeError"></div>
                                <div class="form-text small">Use code <strong>WELCOME10</strong> for 10% off.</div>
                            </form>

                            <?php if (!empty($cart_items)): ?>
                                <a href="checkout.php" class="btn btn-primary w-100 py-2 fw-semibold">
                                    <i class="fa-solid fa-arrow-right me-2"></i>Proceed to Checkout
                                </a>
                            <?php else: ?>
                                <button class="btn btn-primary w-100 py-2 fw-semibold" disabled>
                                    <i class="fa-solid fa-arrow-right me-2"></i>Proceed to Checkout
                                </button>
                            <?php endif; ?>

                            <a href="product.php" class="btn btn-outline-primary w-100 mt-2">
                                <i class="fa-solid fa-layer-group me-1"></i>Shop More
                            </a>

                            <p class="hint text-muted text-center mt-3 mb-0">
                                <i class="fa-solid fa-lock me-1"></i>Safe and secure checkout
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// STEP 3: Save the captured HTML and stop buffering.
$page_content = ob_get_clean();

// STEP 4: Render it inside the shared layout.
require 'layout.php';
