<?php
/*
 * wishlist.php - MY WISHLIST
 * Saved products reuse the same three-icon tile pattern as the shop page.
 */

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
                        <p class="fw-bold mb-0">3 products saved</p>
                        <p class="hint text-muted mb-0">Prices checked today. Your list is saved to your account.</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <label class="visually-hidden" for="wishlistSort">Sort wishlist</label>
                        <select class="form-select form-select-sm sort-select" id="wishlistSort" name="wishlistSort" data-validation="required">
                            <option>Recently saved</option>
                            <option>Price: low to high</option>
                            <option>Price: high to low</option>
                            <option>Biggest discount</option>
                        </select>
                        <div class="invalid-feedback" id="wishlistSortError"></div>
                        <a href="cart.php" class="btn btn-primary btn-sm px-3">
                            <i class="fa-solid fa-cart-shopping me-1"></i>Add All to Cart
                        </a>
                        <button type="button" class="btn btn-outline-primary btn-sm px-3">
                            <i class="fa-solid fa-trash-can me-1"></i>Clear Wishlist
                        </button>
                    </div>
                </div>
            </div>



            <!-- ========== SAVED PRODUCTS ========== -->
            <div class="row g-4">
                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="card store-card wishlist-card h-100 overflow-hidden">
                        <div class="product-img d-flex align-items-center justify-content-center position-relative">
                            <span
                                class="badge discount-badge text-bg-primary position-absolute top-0 start-0 m-2">-24%</span>
                            <div class="position-absolute top-0 end-0 m-2 d-flex gap-1">
                                <a href="product-detail.php?product=headphones"
                                    class="product-icon-btn btn btn-sm btn-light rounded-circle" title="View product"><i
                                        class="fa-regular fa-eye text-primary"></i></a>
                                <a href="cart.php" class="product-icon-btn btn btn-sm btn-light rounded-circle"
                                    title="Add to cart"><i class="fa-solid fa-cart-shopping text-primary"></i></a>
                                <button type="button" class="product-icon-btn btn btn-sm btn-light rounded-circle"
                                    title="Remove from wishlist"><i
                                        class="fa-solid fa-trash-can text-primary"></i></button>
                            </div>
                            <i class="fa-solid fa-headphones text-primary"></i>
                        </div>
                        <div class="card-body p-3 d-flex flex-column">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                <small class="text-muted">Electronics</small>
                                <span class="saved-date"><i class="fa-regular fa-clock me-1"></i>2 days ago</span>
                            </div>
                            <h2 class="h6 fw-bold mt-1 mb-1">Pulse Wireless Headphones</h2>
                            <div class="rating-stars small mb-2">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i> <span class="text-muted">(128)</span>
                            </div>
                            <div class="d-flex align-items-baseline gap-2 mb-2">
                                <span class="fs-5 fw-bold">&#8377;2,499</span>
                                <span class="price-old hint">&#8377;3,299</span>
                            </div>
                            <p class="hint text-muted mb-3">
                                <span class="stock-pill">In stock</span> Free delivery
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="card store-card wishlist-card h-100 overflow-hidden">
                        <div class="product-img d-flex align-items-center justify-content-center position-relative">
                            <span
                                class="badge discount-badge text-bg-primary position-absolute top-0 start-0 m-2">-12%</span>
                            <div class="position-absolute top-0 end-0 m-2 d-flex gap-1">
                                <a href="product-detail.php?product=laptop"
                                    class="product-icon-btn btn btn-sm btn-light rounded-circle" title="View product"><i
                                        class="fa-regular fa-eye text-primary"></i></a>
                                <a href="cart.php" class="product-icon-btn btn btn-sm btn-light rounded-circle"
                                    title="Add to cart"><i class="fa-solid fa-cart-shopping text-primary"></i></a>
                                <button type="button" class="product-icon-btn btn btn-sm btn-light rounded-circle"
                                    title="Remove from wishlist"><i
                                        class="fa-solid fa-trash-can text-primary"></i></button>
                            </div>
                            <i class="fa-solid fa-laptop text-primary"></i>
                        </div>
                        <div class="card-body p-3 d-flex flex-column">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                <small class="text-muted">Electronics</small>
                                <span class="saved-date"><i class="fa-regular fa-clock me-1"></i>5 days ago</span>
                            </div>
                            <h2 class="h6 fw-bold mt-1 mb-1">Zenith 14 Laptop</h2>
                            <div class="rating-stars small mb-2">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i> <span class="text-muted">(54)</span>
                            </div>
                            <div class="d-flex align-items-baseline gap-2 mb-2">
                                <span class="fs-5 fw-bold">&#8377;54,990</span>
                                <span class="price-old hint">&#8377;62,990</span>
                            </div>
                            <p class="hint text-muted mb-3">
                                <span class="stock-pill">In stock</span> Free delivery
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="card store-card wishlist-card h-100 overflow-hidden">
                        <div class="product-img d-flex align-items-center justify-content-center position-relative">
                            <span
                                class="badge discount-badge text-bg-primary position-absolute top-0 start-0 m-2">-28%</span>
                            <div class="position-absolute top-0 end-0 m-2 d-flex gap-1">
                                <a href="product-detail.php?product=book"
                                    class="product-icon-btn btn btn-sm btn-light rounded-circle" title="View product"><i
                                        class="fa-regular fa-eye text-primary"></i></a>
                                <a href="cart.php" class="product-icon-btn btn btn-sm btn-light rounded-circle"
                                    title="Add to cart"><i class="fa-solid fa-cart-shopping text-primary"></i></a>
                                <button type="button" class="product-icon-btn btn btn-sm btn-light rounded-circle"
                                    title="Remove from wishlist"><i
                                        class="fa-solid fa-trash-can text-primary"></i></button>
                            </div>
                            <i class="fa-solid fa-book text-primary"></i>
                        </div>
                        <div class="card-body p-3 d-flex flex-column">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                <small class="text-muted">Books</small>
                                <span class="saved-date"><i class="fa-regular fa-clock me-1"></i>1 week ago</span>
                            </div>
                            <h2 class="h6 fw-bold mt-1 mb-1">PHP: From Zero to Hero</h2>
                            <div class="rating-stars small mb-2">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i> <span class="text-muted">(312)</span>
                            </div>
                            <div class="d-flex align-items-baseline gap-2 mb-2">
                                <span class="fs-5 fw-bold">&#8377;499</span>
                                <span class="price-old hint">&#8377;699</span>
                            </div>
                            <p class="hint text-muted mb-3">
                                <span class="stock-pill">In stock</span> Free delivery
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wishlist tips -->

        </div>
    </div>
</div>

<?php
// STEP 3: Save the captured HTML and stop buffering.
$page_content = ob_get_clean();

// STEP 4: Render it inside the shared layout.
require 'layout.php';