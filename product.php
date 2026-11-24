<?php
/*
 * product.php - SHOP / PRODUCT LISTING
 */

// STEP 1: Settings used by the shared layout.
$page_title  = 'Shop All Products | TrendCart';
$active_page = 'product';

// STEP 2: Start capturing this page's HTML.
ob_start();
?>

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Shop</li>
        </ol>
    </nav>

    <div class="page-header">
        <span class="badge text-bg-primary mb-3 px-3 py-2">
            <i class="fa-solid fa-layer-group me-1"></i>SHOP
        </span>
        <h1 class="h2 fw-bold mb-3">Shop All Products</h1>
        <p class="text-muted">
            Browse gadgets, fashion, books and gifts — all with clear prices and easy returns.
        </p>
    </div>

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <p class="text-muted hint mb-0">Showing 1&ndash;8 of 8 products</p>
        <div class="d-flex align-items-center gap-2">
            <label class="text-muted hint mb-0" for="sort">Sort by</label>
            <select class="form-select form-select-sm sort-select" id="sort" name="sort" data-validation="required">
                <option selected>Popularity</option>
                <option>Price: Low to High</option>
                <option>Price: High to Low</option>
                <option>Newest Arrivals</option>
                <option>Customer Rating</option>
            </select>
            <div class="invalid-feedback" id="sortError"></div>
        </div>
    </div>

    <div class="row g-4">
        <!-- ========== FILTER SIDEBAR ========== -->
        <aside class="col-12 col-lg-3">
            <div class="card store-card">
                <div class="card-body">
                    <h2 class="h6 fw-bold mb-3"><i class="fa-solid fa-sliders me-2"></i>Filters</h2>
                    <hr>

                    <h3 class="hint fw-bold text-uppercase">Category</h3>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="category" data-validation="required" id="catAll" checked>
                        <label class="form-check-label hint" for="catAll">All products (8)</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="category" data-validation="required" id="catElect">
                        <label class="form-check-label hint" for="catElect">Electronics (4)</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="category" data-validation="required" id="catFashion">
                        <label class="form-check-label hint" for="catFashion">Fashion (1)</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="category" data-validation="required" id="catGaming">
                        <label class="form-check-label hint" for="catGaming">Gaming (1)</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="category" data-validation="required" id="catBooks">
                        <label class="form-check-label hint" for="catBooks">Books (1)</label>
                    </div>

                    <hr>
                    <h3 class="hint fw-bold text-uppercase">Price</h3>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="price" data-validation="required" id="priceAll" checked>
                        <label class="form-check-label hint" for="priceAll">Any price</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="price" data-validation="required" id="priceLow">
                        <label class="form-check-label hint" for="priceLow">Under &#8377;1,000</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="price" data-validation="required" id="priceMid">
                        <label class="form-check-label hint" for="priceMid">&#8377;1,000 &ndash; &#8377;10,000</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="price" data-validation="required" id="priceHigh">
                        <label class="form-check-label hint" for="priceHigh">Above &#8377;10,000</label>
                    </div>

                    <hr>
                    <h3 class="hint fw-bold text-uppercase">Availability</h3>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="inStock" name="inStock"
                                           data-validation="max-items" data-max-items="1">
                        <label class="form-check-label hint" for="inStock">In stock only</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="onSale" name="onSale"
                                           data-validation="max-items" data-max-items="1">
                        <label class="form-check-label hint" for="onSale">On sale</label>
                    </div>

                    <button type="button" class="btn btn-outline-primary btn-sm w-100 mt-4">
                        <i class="fa-solid fa-rotate-left me-1"></i>Reset Filters
                    </button>
                </div>
            </div>
        </aside>

        <!-- ========== PRODUCT GRID ========== -->
        <div class="col-12 col-lg-9">
            <div class="row g-4">

                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="card store-card h-100">
                        <div
                            class="product-img d-flex align-items-center justify-content-center bg-light text-primary position-relative">
                            <span
                                class="badge discount-badge text-bg-danger position-absolute top-0 start-0 m-2">-25%</span>
                            <div class="position-absolute top-0 end-0 m-2 d-flex gap-1">
                                <a href="product-detail.php?product=headphones"
                                    class="product-icon-btn btn btn-sm btn-light rounded-circle" title="View product">
                                    <i class="fa-regular fa-eye text-secondary"></i>
                                </a>
                                <button class="product-icon-btn btn btn-sm btn-light rounded-circle"
                                    title="Add to wishlist">
                                    <i class="fa-regular fa-heart text-danger"></i>
                                </button>
                                <button class="product-icon-btn btn btn-sm btn-light rounded-circle"
                                    title="Add to cart">
                                    <i class="fa-solid fa-cart-shopping text-primary"></i>
                                </button>
                            </div>
                            <i class="fa-solid fa-headphones"></i>
                        </div>
                        <div class="card-body">
                            <small class="text-muted">Electronics</small>
                            <h5 class="h6 fw-bold mt-1">Pulse Wireless Headphones</h5>
                            <div class="rating-stars small">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i>
                                <span class="text-muted">(128)</span>
                            </div>
                            <div class="d-flex align-items-baseline gap-2 mt-2">
                                <span class="fs-5 fw-bold">&#8377;2,499</span>
                                <span class="price-old hint">&#8377;3,299</span>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="card store-card h-100">
                        <div
                            class="product-img d-flex align-items-center justify-content-center bg-light text-success position-relative">
                            <span
                                class="badge discount-badge text-bg-danger position-absolute top-0 start-0 m-2">-28%</span>
                            <div class="position-absolute top-0 end-0 m-2 d-flex gap-1">
                                <a href="product-detail.php?product=smartwatch"
                                    class="product-icon-btn btn btn-sm btn-light rounded-circle" title="View product">
                                    <i class="fa-regular fa-eye text-secondary"></i>
                                </a>
                                <button class="product-icon-btn btn btn-sm btn-light rounded-circle"
                                    title="Add to wishlist">
                                    <i class="fa-regular fa-heart text-danger"></i>
                                </button>
                                <button class="product-icon-btn btn btn-sm btn-light rounded-circle"
                                    title="Add to cart">
                                    <i class="fa-solid fa-cart-shopping text-primary"></i>
                                </button>
                            </div>
                            <i class="fa-solid fa-stopwatch"></i>
                        </div>
                        <div class="card-body">
                            <small class="text-muted">Electronics</small>
                            <h5 class="h6 fw-bold mt-1">Nova Smartwatch 2</h5>
                            <div class="rating-stars small">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star-half-stroke"></i>
                                <span class="text-muted">(86)</span>
                            </div>
                            <div class="d-flex align-items-baseline gap-2 mt-2">
                                <span class="fs-5 fw-bold">&#8377;4,999</span>
                                <span class="price-old hint">&#8377;6,999</span>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="card store-card h-100">
                        <div
                            class="product-img d-flex align-items-center justify-content-center bg-light text-warning-emphasis position-relative">
                            <span
                                class="badge discount-badge text-bg-danger position-absolute top-0 start-0 m-2">-12%</span>
                            <div class="position-absolute top-0 end-0 m-2 d-flex gap-1">
                                <a href="product-detail.php?product=laptop"
                                    class="product-icon-btn btn btn-sm btn-light rounded-circle" title="View product">
                                    <i class="fa-regular fa-eye text-secondary"></i>
                                </a>
                                <button class="product-icon-btn btn btn-sm btn-light rounded-circle"
                                    title="Add to wishlist">
                                    <i class="fa-regular fa-heart text-danger"></i>
                                </button>
                                <button class="product-icon-btn btn btn-sm btn-light rounded-circle"
                                    title="Add to cart">
                                    <i class="fa-solid fa-cart-shopping text-primary"></i>
                                </button>
                            </div>
                            <i class="fa-solid fa-laptop"></i>
                        </div>
                        <div class="card-body">
                            <small class="text-muted">Electronics</small>
                            <h5 class="h6 fw-bold mt-1">Zenith 14 Laptop</h5>
                            <div class="rating-stars small">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i>
                                <span class="text-muted">(54)</span>
                            </div>
                            <div class="d-flex align-items-baseline gap-2 mt-2">
                                <span class="fs-5 fw-bold">&#8377;54,990</span>
                                <span class="price-old hint">&#8377;62,990</span>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="card store-card h-100">
                        <div
                            class="product-img d-flex align-items-center justify-content-center bg-light text-danger position-relative">
                            <span
                                class="badge discount-badge text-bg-danger position-absolute top-0 start-0 m-2">-25%</span>
                            <div class="position-absolute top-0 end-0 m-2 d-flex gap-1">
                                <a href="product-detail.php?product=controller"
                                    class="product-icon-btn btn btn-sm btn-light rounded-circle" title="View product">
                                    <i class="fa-regular fa-eye text-secondary"></i>
                                </a>
                                <button class="product-icon-btn btn btn-sm btn-light rounded-circle"
                                    title="Add to wishlist">
                                    <i class="fa-regular fa-heart text-danger"></i>
                                </button>
                                <button class="product-icon-btn btn btn-sm btn-light rounded-circle"
                                    title="Add to cart">
                                    <i class="fa-solid fa-cart-shopping text-primary"></i>
                                </button>
                            </div>
                            <i class="fa-solid fa-gamepad"></i>
                        </div>
                        <div class="card-body">
                            <small class="text-muted">Gaming</small>
                            <h5 class="h6 fw-bold mt-1">ProPlay Wireless Controller</h5>
                            <div class="rating-stars small">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star-half-stroke"></i>
                                <span class="text-muted">(97)</span>
                            </div>
                            <div class="d-flex align-items-baseline gap-2 mt-2">
                                <span class="fs-5 fw-bold">&#8377;2,999</span>
                                <span class="price-old hint">&#8377;3,999</span>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="card store-card h-100">
                        <div
                            class="product-img d-flex align-items-center justify-content-center bg-light text-danger position-relative">
                            <span
                                class="badge discount-badge text-bg-danger position-absolute top-0 start-0 m-2">-33%</span>
                            <div class="position-absolute top-0 end-0 m-2 d-flex gap-1">
                                <a href="product-detail.php?product=tshirt"
                                    class="product-icon-btn btn btn-sm btn-light rounded-circle" title="View product">
                                    <i class="fa-regular fa-eye text-secondary"></i>
                                </a>
                                <button class="product-icon-btn btn btn-sm btn-light rounded-circle"
                                    title="Add to wishlist">
                                    <i class="fa-regular fa-heart text-danger"></i>
                                </button>
                                <button class="product-icon-btn btn btn-sm btn-light rounded-circle"
                                    title="Add to cart">
                                    <i class="fa-solid fa-cart-shopping text-primary"></i>
                                </button>
                            </div>
                            <i class="fa-solid fa-shirt"></i>
                        </div>
                        <div class="card-body">
                            <small class="text-muted">Fashion</small>
                            <h5 class="h6 fw-bold mt-1">Everywear Cotton T-Shirt</h5>
                            <div class="rating-stars small">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i>
                                <span class="text-muted">(213)</span>
                            </div>
                            <div class="d-flex align-items-baseline gap-2 mt-2">
                                <span class="fs-5 fw-bold">&#8377;799</span>
                                <span class="price-old hint">&#8377;1,199</span>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="card store-card h-100">
                        <div
                            class="product-img d-flex align-items-center justify-content-center bg-light text-info-emphasis position-relative">
                            <span
                                class="badge discount-badge text-bg-danger position-absolute top-0 start-0 m-2">-11%</span>
                            <div class="position-absolute top-0 end-0 m-2 d-flex gap-1">
                                <a href="product-detail.php?product=camera"
                                    class="product-icon-btn btn btn-sm btn-light rounded-circle" title="View product">
                                    <i class="fa-regular fa-eye text-secondary"></i>
                                </a>
                                <button class="product-icon-btn btn btn-sm btn-light rounded-circle"
                                    title="Add to wishlist">
                                    <i class="fa-regular fa-heart text-danger"></i>
                                </button>
                                <button class="product-icon-btn btn btn-sm btn-light rounded-circle"
                                    title="Add to cart">
                                    <i class="fa-solid fa-cart-shopping text-primary"></i>
                                </button>
                            </div>
                            <i class="fa-solid fa-camera"></i>
                        </div>
                        <div class="card-body">
                            <small class="text-muted">Electronics</small>
                            <h5 class="h6 fw-bold mt-1">SnapShot Digital Camera</h5>
                            <div class="rating-stars small">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star-half-stroke"></i>
                                <span class="text-muted">(41)</span>
                            </div>
                            <div class="d-flex align-items-baseline gap-2 mt-2">
                                <span class="fs-5 fw-bold">&#8377;28,500</span>
                                <span class="price-old hint">&#8377;32,000</span>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="card store-card h-100">
                        <div
                            class="product-img d-flex align-items-center justify-content-center bg-light text-primary position-relative">
                            <span
                                class="badge discount-badge text-bg-danger position-absolute top-0 start-0 m-2">-28%</span>
                            <div class="position-absolute top-0 end-0 m-2 d-flex gap-1">
                                <a href="product-detail.php?product=book"
                                    class="product-icon-btn btn btn-sm btn-light rounded-circle" title="View product">
                                    <i class="fa-regular fa-eye text-secondary"></i>
                                </a>
                                <button class="product-icon-btn btn btn-sm btn-light rounded-circle"
                                    title="Add to wishlist">
                                    <i class="fa-regular fa-heart text-danger"></i>
                                </button>
                                <button class="product-icon-btn btn btn-sm btn-light rounded-circle"
                                    title="Add to cart">
                                    <i class="fa-solid fa-cart-shopping text-primary"></i>
                                </button>
                            </div>
                            <i class="fa-solid fa-book"></i>
                        </div>
                        <div class="card-body">
                            <small class="text-muted">Books</small>
                            <h5 class="h6 fw-bold mt-1">PHP: From Zero to Hero</h5>
                            <div class="rating-stars small">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i>
                                <span class="text-muted">(312)</span>
                            </div>
                            <div class="d-flex align-items-baseline gap-2 mt-2">
                                <span class="fs-5 fw-bold">&#8377;499</span>
                                <span class="price-old hint">&#8377;699</span>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="card store-card h-100">
                        <div
                            class="product-img d-flex align-items-center justify-content-center bg-light text-danger position-relative">
                            <span
                                class="badge discount-badge text-bg-danger position-absolute top-0 start-0 m-2">-27%</span>
                            <div class="position-absolute top-0 end-0 m-2 d-flex gap-1">
                                <a href="product-detail.php?product=hamper"
                                    class="product-icon-btn btn btn-sm btn-light rounded-circle" title="View product">
                                    <i class="fa-regular fa-eye text-secondary"></i>
                                </a>
                                <button class="product-icon-btn btn btn-sm btn-light rounded-circle"
                                    title="Add to wishlist">
                                    <i class="fa-regular fa-heart text-danger"></i>
                                </button>
                                <button class="product-icon-btn btn btn-sm btn-light rounded-circle"
                                    title="Add to cart">
                                    <i class="fa-solid fa-cart-shopping text-primary"></i>
                                </button>
                            </div>
                            <i class="fa-solid fa-gem"></i>
                        </div>
                        <div class="card-body">
                            <small class="text-muted">Gifts</small>
                            <h5 class="h6 fw-bold mt-1">Aura Gift Hamper</h5>
                            <div class="rating-stars small">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star-half-stroke"></i>
                                <span class="text-muted">(68)</span>
                            </div>
                            <div class="d-flex align-items-baseline gap-2 mt-2">
                                <span class="fs-5 fw-bold">&#8377;1,299</span>
                                <span class="price-old hint">&#8377;1,799</span>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            <nav class="mt-5" aria-label="Product pages">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<?php
// STEP 3: Save the captured HTML and stop buffering.
$page_content = ob_get_clean();

// STEP 4: Render it inside the shared layout.
require 'layout.php';