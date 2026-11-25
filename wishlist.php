<?php
/*
 * wishlist.php - MY WISHLIST
 * Dynamic saved products and move-to-cart actions connected to MySQL.
 */

require_once __DIR__ . '/check_auth.php';
require_once __DIR__ . '/db.php';

$user    = current_user();
$user_id = (int)$user['id'];

$flash = $_SESSION['wishlist_flash'] ?? null;
unset($_SESSION['wishlist_flash']);

// -------------------------------------------------------------
// 1. HANDLE POST & GET ACTIONS (Remove, Move to Cart, Add All, Clear)
// -------------------------------------------------------------
$action = $_POST['action'] ?? ($_GET['action'] ?? '');

if (!empty($action)) {
    try {
        if ($action === 'remove') {
            $wishlist_id = (int)($_POST['wishlist_id'] ?? ($_GET['wishlist_id'] ?? 0));
            db_exec("DELETE FROM wishlists WHERE id = ? AND user_id = ?", [$wishlist_id, $user_id]);
            $_SESSION['wishlist_flash'] = ['type' => 'success', 'message' => 'Item removed from your wishlist.'];
            header('Location: wishlist.php');
            exit;
        }

        if ($action === 'add_to_cart') {
            $product_id  = (int)($_POST['product_id'] ?? ($_GET['product_id'] ?? 0));
            $wishlist_id = (int)($_POST['wishlist_id'] ?? ($_GET['wishlist_id'] ?? 0));

            if ($product_id > 0) {
                db_exec(
                    "INSERT INTO carts (user_id, product_id, qty, status) VALUES (?, ?, 1, 'active')
                     ON DUPLICATE KEY UPDATE qty = qty + 1, status = 'active'",
                    [$user_id, $product_id]
                );

                if ($wishlist_id > 0) {
                    db_exec("DELETE FROM wishlists WHERE id = ? AND user_id = ?", [$wishlist_id, $user_id]);
                }

                $_SESSION['wishlist_flash'] = [
                    'type'    => 'success',
                    'message' => 'Product moved to your shopping cart! <a href="cart.php" class="alert-link fw-bold">View Cart</a>'
                ];
            }
            header('Location: wishlist.php');
            exit;
        }

        if ($action === 'add_all') {
            $items = db_all("SELECT product_id FROM wishlists WHERE user_id = ? AND status = 'active'", [$user_id]);
            foreach ($items as $it) {
                db_exec(
                    "INSERT INTO carts (user_id, product_id, qty, status) VALUES (?, ?, 1, 'active')
                     ON DUPLICATE KEY UPDATE qty = qty + 1, status = 'active'",
                    [$user_id, (int)$it['product_id']]
                );
            }
            db_exec("DELETE FROM wishlists WHERE user_id = ?", [$user_id]);
            $_SESSION['wishlist_flash'] = [
                'type'    => 'success',
                'message' => 'All wishlist items have been moved to your cart! <a href="cart.php" class="alert-link fw-bold">View Cart</a>'
            ];
            header('Location: wishlist.php');
            exit;
        }

        if ($action === 'clear_wishlist') {
            db_exec("DELETE FROM wishlists WHERE user_id = ?", [$user_id]);
            $_SESSION['wishlist_flash'] = ['type' => 'info', 'message' => 'Your wishlist has been cleared.'];
            header('Location: wishlist.php');
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['wishlist_flash'] = ['type' => 'danger', 'message' => 'Error: ' . $e->getMessage()];
        header('Location: wishlist.php');
        exit;
    }
}

// -------------------------------------------------------------
// 2. SORTING & FETCH DATA
// -------------------------------------------------------------
$sort = $_GET['sort'] ?? 'recent';
switch ($sort) {
    case 'price_asc':
        $order_clause = 'p.price_paise ASC';
        break;
    case 'price_desc':
        $order_clause = 'p.price_paise DESC';
        break;
    case 'discount':
        $order_clause = '(COALESCE(p.old_price_paise, 0) - p.price_paise) DESC';
        break;
    case 'recent':
    default:
        $sort = 'recent';
        $order_clause = 'w.id DESC';
        break;
}

try {
    $wishlist_items = db_all(
        "SELECT w.id AS wishlist_id, w.product_id, w.created_at,
                p.name, p.slug, p.price_paise, p.old_price_paise, p.stock_qty, p.image_icon, p.image_url,
                p.rating_average, p.review_count,
                cat.name AS category_name
         FROM wishlists w
         JOIN products p ON p.id = w.product_id
         LEFT JOIN categories cat ON cat.id = p.category_id
         WHERE w.user_id = ? AND w.status = 'active'
         ORDER BY {$order_clause}",
        [$user_id]
    );
} catch (PDOException $e) {
    $wishlist_items = [];
}

$items_count = count($wishlist_items);

// STEP 1: Settings used by the shared layout.
$page_title     = 'My Wishlist | TrendCart';
$active_page    = 'wishlist';
$account_active = 'wishlist';

// STEP 2: Start capturing this page's HTML.
ob_start();
?>

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="account.php" class="text-decoration-none">My Account</a></li>
            <li class="breadcrumb-item active" aria-current="page">Wishlist</li>
        </ol>
    </nav>

    <div class="page-header">
        <span class="badge text-bg-primary mb-3 px-3 py-2">
            <i class="fa-regular fa-heart me-1"></i>MY WISHLIST
        </span>
        <h1 class="h2 fw-bold mb-3">Your Saved Picks</h1>
        <p class="text-muted">Keep an eye on prices and move favourites to your cart whenever you are ready.</p>
    </div>

    <?php if ($flash): ?>
        <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show mb-4" role="alert">
            <?= $flash['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- ========== SHARED SIDEBAR ========== -->
        <aside class="col-12 col-lg-3">
            <?php require 'account-nav.php'; ?>
        </aside>

        <div class="col-12 col-lg-9">
            <!-- Toolbar -->
            <div class="card store-card mb-4">
                <div class="card-body p-3 d-flex flex-wrap align-items-center gap-3">
                    <div class="feature-icon"><i class="fa-solid fa-heart-circle-check"></i></div>
                    <div class="me-auto">
                        <p class="fw-bold mb-0"><?= $items_count ?> product<?= $items_count === 1 ? '' : 's' ?> saved</p>
                        <p class="hint text-muted mb-0">Prices checked in real-time. Saved securely to your account.</p>
                    </div>
                    <?php if (!empty($wishlist_items)): ?>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <label class="visually-hidden" for="wishlistSort">Sort wishlist</label>
                            <select class="form-select form-select-sm sort-select" id="wishlistSort"
                                    onchange="location.href='wishlist.php?sort=' + this.value">
                                <option value="recent" <?= $sort === 'recent' ? 'selected' : '' ?>>Recently saved</option>
                                <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Price: low to high</option>
                                <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price: high to low</option>
                                <option value="discount" <?= $sort === 'discount' ? 'selected' : '' ?>>Biggest discount</option>
                            </select>

                            <form action="wishlist.php" method="post" class="d-inline">
                                <input type="hidden" name="action" value="add_all">
                                <button type="submit" class="btn btn-primary btn-sm px-3">
                                    <i class="fa-solid fa-cart-shopping me-1"></i>Add All to Cart
                                </button>
                            </form>

                            <form action="wishlist.php" method="post" class="d-inline" onsubmit="return confirm('Clear your entire wishlist?');">
                                <input type="hidden" name="action" value="clear_wishlist">
                                <button type="submit" class="btn btn-outline-danger btn-sm px-3">
                                    <i class="fa-solid fa-trash-can me-1"></i>Clear
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ========== SAVED PRODUCTS ========== -->
            <?php if (empty($wishlist_items)): ?>
                <div class="card store-card text-center p-5 mb-4">
                    <div class="feature-icon mx-auto mb-3 text-muted opacity-75">
                        <i class="fa-regular fa-heart fa-2x"></i>
                    </div>
                    <h3 class="h5 fw-bold mb-2">Your Wishlist is Empty</h3>
                    <p class="text-muted hint mb-4">Explore our catalogue and click the heart icon on any product to save it here.</p>
                    <div>
                        <a href="product.php" class="btn btn-primary px-4">
                            <i class="fa-solid fa-bag-shopping me-1"></i>Explore Products
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($wishlist_items as $item): ?>
                        <?php
                            $wishlist_id  = (int)$item['wishlist_id'];
                            $prod_id      = (int)$item['product_id'];
                            $price        = (int)$item['price_paise'];
                            $old          = (int)($item['old_price_paise'] ?? 0);
                            $in_stock     = (int)($item['stock_qty'] ?? 0) > 0;
                            $discount_pct = ($old > $price) ? round((($old - $price) / $old) * 100) : 0;
                            $time_saved   = !empty($item['created_at']) ? date('M j, Y', strtotime($item['created_at'])) : '';
                        ?>
                        <div class="col-12 col-sm-6 col-xl-4">
                            <div class="card store-card wishlist-card h-100 overflow-hidden d-flex flex-column">
                                <div class="product-img d-flex align-items-center justify-content-center position-relative">
                                    <?php if ($discount_pct > 0): ?>
                                        <span class="badge discount-badge text-bg-primary position-absolute top-0 start-0 m-2">-<?= $discount_pct ?>%</span>
                                    <?php endif; ?>

                                    <div class="position-absolute top-0 end-0 m-2 d-flex gap-1">
                                        <a href="product-detail.php?product=<?= urlencode($item['slug']) ?>"
                                            class="product-icon-btn btn btn-sm btn-light rounded-circle shadow-sm" title="View product">
                                            <i class="fa-regular fa-eye text-primary"></i>
                                        </a>

                                        <form action="wishlist.php" method="post" class="d-inline">
                                            <input type="hidden" name="action" value="add_to_cart">
                                            <input type="hidden" name="product_id" value="<?= $prod_id ?>">
                                            <input type="hidden" name="wishlist_id" value="<?= $wishlist_id ?>">
                                            <button type="submit" class="product-icon-btn btn btn-sm btn-light rounded-circle shadow-sm" title="Move to cart">
                                                <i class="fa-solid fa-cart-shopping text-primary"></i>
                                            </button>
                                        </form>

                                        <form action="wishlist.php" method="post" class="d-inline">
                                            <input type="hidden" name="action" value="remove">
                                            <input type="hidden" name="wishlist_id" value="<?= $wishlist_id ?>">
                                            <button type="submit" class="product-icon-btn btn btn-sm btn-light rounded-circle shadow-sm" title="Remove from wishlist">
                                                <i class="fa-solid fa-trash-can text-danger"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <?php if (!empty($item['image_url'])): ?>
                                        <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="img-fluid">
                                    <?php else: ?>
                                        <i class="fa-solid <?= htmlspecialchars($item['image_icon'] ?: 'fa-box') ?> text-primary fa-3x"></i>
                                    <?php endif; ?>
                                </div>

                                <div class="card-body p-3 d-flex flex-column flex-grow-1">
                                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-1">
                                        <small class="text-muted"><?= htmlspecialchars($item['category_name'] ?? 'Product') ?></small>
                                        <?php if ($time_saved): ?>
                                            <span class="saved-date small text-muted"><i class="fa-regular fa-clock me-1"></i><?= $time_saved ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <h2 class="h6 fw-bold mt-1 mb-1">
                                        <a href="product-detail.php?product=<?= urlencode($item['slug']) ?>" class="text-decoration-none text-dark">
                                            <?= htmlspecialchars($item['name']) ?>
                                        </a>
                                    </h2>

                                    <div class="rating-stars small mb-2">
                                        <?php
                                            $rating = (float)($item['rating_average'] ?? 4.5);
                                            for ($i = 1; $i <= 5; $i++) {
                                                echo ($i <= round($rating)) ? '<i class="fa-solid fa-star text-warning"></i>' : '<i class="fa-regular fa-star text-muted"></i>';
                                            }
                                        ?>
                                        <span class="text-muted ms-1">(<?= (int)($item['review_count'] ?? 10) ?>)</span>
                                    </div>

                                    <div class="d-flex align-items-baseline gap-2 mb-2">
                                        <span class="fs-5 fw-bold"><?= money($price) ?></span>
                                        <?php if ($old > $price): ?>
                                            <span class="price-old hint"><?= money($old) ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <p class="hint text-muted mb-3">
                                        <span class="stock-pill <?= $in_stock ? '' : 'bg-danger-subtle text-danger' ?>"><?= $in_stock ? 'In stock' : 'Out of stock' ?></span>
                                        Free delivery
                                    </p>

                                    <div class="mt-auto">
                                        <form action="wishlist.php" method="post">
                                            <input type="hidden" name="action" value="add_to_cart">
                                            <input type="hidden" name="product_id" value="<?= $prod_id ?>">
                                            <input type="hidden" name="wishlist_id" value="<?= $wishlist_id ?>">
                                            <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                                                <i class="fa-solid fa-cart-shopping me-1"></i>Move to Cart
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
// STEP 3: Save the captured HTML and stop buffering.
$page_content = ob_get_clean();

// STEP 4: Render it inside the shared layout.
require 'layout.php';
