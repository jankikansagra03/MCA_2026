<?php
/*
 * admin-product-edit.php - ADD / EDIT PRODUCT
 * Prefilled product editor. Every field is visual only — nothing is saved.
 */

$admin_page_title = 'Edit Product | TrendCart Admin';
$admin_active     = 'products';

$gallery_rows = [
    ['icon' => 'fa-headphones', 'label' => 'Front view', 'file' => 'headphones-front.png'],
    ['icon' => 'fa-volume-high', 'label' => 'Sound detail', 'file' => 'headphones-detail.png'],
    ['icon' => 'fa-bluetooth', 'label' => 'Wireless pairing', 'file' => 'headphones-bluetooth.png'],
    ['icon' => 'fa-battery-full', 'label' => 'Battery life', 'file' => 'headphones-battery.png'],
];

ob_start();
?>

<div class="admin-page-heading">
    <div>
        <nav aria-label="breadcrumb" class="admin-breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="admin.php" class="text-decoration-none">Admin</a></li>
                <li class="breadcrumb-item"><a href="admin-products.php" class="text-decoration-none">Products</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Product</li>
            </ol>
        </nav>
        <p class="admin-eyebrow mb-0">Catalogue</p>
        <h1>Edit Product</h1>
        <p class="text-muted mb-0">Pulse Wireless Headphones &middot; SKU TC-AUD-001</p>
    </div>
    <a href="product-detail.php?product=headphones" class="btn btn-outline-primary btn-sm px-3">
        <i class="fa-regular fa-eye me-1"></i>View in Store
    </a>
</div>

<form action="#" method="post" novalidate>
    <div class="row g-4">
        <div class="col-12 col-xl-8">
            <div class="card admin-card mb-4">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">General details</h2>
                    <span class="admin-section-note">What customers see first</span>
                </div>
                <div class="card-body">
                    <div class="admin-form-grid">
                        <div class="full">
                            <label for="productName" class="form-label small fw-semibold">Product name</label>
                            <input type="text" class="form-control" id="productName" name="productName" value="Pulse Wireless Headphones"
                                   data-validation="required max" data-max="100">
                            <div class="invalid-feedback" id="productNameError"></div>
                        </div>
                        <div>
                            <label for="productSlug" class="form-label small fw-semibold">URL slug</label>
                            <input type="text" class="form-control" id="productSlug" name="productSlug" value="headphones"
                                   data-validation="required max" data-max="100">
                            <div class="invalid-feedback" id="productSlugError"></div>
                        </div>
                        <div>
                            <label for="productCategory" class="form-label small fw-semibold">Category</label>
                            <select class="form-select" id="productCategory" name="productCategory" data-validation="required">
                                <option selected>Electronics</option>
                                <option>Gaming</option>
                                <option>Fashion</option>
                                <option>Books</option>
                                <option>Gifts</option>
                            </select>
                            <div class="invalid-feedback" id="productCategoryError"></div>
                        </div>
                        <div class="full">
                            <label for="productDescription" class="form-label small fw-semibold">Short description</label>
                            <textarea class="form-control" id="productDescription" name="productDescription" rows="3"
                                      data-validation="required max" data-max="500">Rich sound, all-day comfort and reliable wireless freedom in one lightweight design.</textarea>
                            <div class="invalid-feedback" id="productDescriptionError"></div>
                        </div>
                        <div class="full">
                            <label for="productTags" class="form-label small fw-semibold">Tags</label>
                            <input type="text" class="form-control" id="productTags" name="productTags" value="audio, wireless, bluetooth, bestseller"
                                   data-validation="required max" data-max="200">
                            <div class="invalid-feedback" id="productTagsError"></div>
                            <div class="admin-section-note mt-1">Separate tags with commas.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card admin-card mb-4">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">Pricing &amp; inventory</h2>
                    <span class="admin-section-note">Margin and availability</span>
                </div>
                <div class="card-body">
                    <div class="admin-form-grid">
                        <div>
                            <label for="productPrice" class="form-label small fw-semibold">Selling price (&#8377;)</label>
                            <input type="number" class="form-control" id="productPrice" name="productPrice" value="2499"
                                   data-validation="required numeric">
                            <div class="invalid-feedback" id="productPriceError"></div>
                        </div>
                        <div>
                            <label for="productOldPrice" class="form-label small fw-semibold">Compare-at price (&#8377;)</label>
                            <input type="number" class="form-control" id="productOldPrice" name="productOldPrice" value="3299"
                                   data-validation="required numeric">
                            <div class="invalid-feedback" id="productOldPriceError"></div>
                        </div>
                        <div>
                            <label for="productSku" class="form-label small fw-semibold">SKU</label>
                            <input type="text" class="form-control" id="productSku" name="productSku" value="TC-AUD-001"
                                   data-validation="required max" data-max="50">
                            <div class="invalid-feedback" id="productSkuError"></div>
                        </div>
                        <div>
                            <label for="productStock" class="form-label small fw-semibold">Stock quantity</label>
                            <input type="number" class="form-control" id="productStock" name="productStock" value="24"
                                   data-validation="required numeric">
                            <div class="invalid-feedback" id="productStockError"></div>
                        </div>
                        <div>
                            <label for="productThreshold" class="form-label small fw-semibold">Low-stock alert at</label>
                            <input type="number" class="form-control" id="productThreshold" name="productThreshold" value="10"
                                   data-validation="required numeric">
                            <div class="invalid-feedback" id="productThresholdError"></div>
                        </div>
                        <div>
                            <label for="productTax" class="form-label small fw-semibold">Tax class</label>
                            <select class="form-select" id="productTax" name="productTax" data-validation="required">
                                <option selected>Standard 18% GST</option>
                                <option>Reduced 5% GST</option>
                                <option>Exempt</option>
                            </select>
                            <div class="invalid-feedback" id="productTaxError"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card admin-card mb-4">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">Images &amp; gallery</h2>
                    <span class="admin-section-note">Four views shown on the product page</span>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <?php foreach ($gallery_rows as $image) : ?>
                            <div class="col-6 col-md-3">
                                <div class="admin-gallery-tile">
                                    <i class="fa-solid <?= $image['icon'] ?>"></i>
                                    <span class="small mt-1"><?= $image['label'] ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php foreach ($gallery_rows as $index => $image) : ?>
                        <div class="row g-2 align-items-center mb-2">
                            <div class="col-auto"><span class="admin-mini-icon"><i class="fa-solid <?= $image['icon'] ?>"></i></span></div>
                            <div class="col">
                                <label for="galleryLabel<?= $index ?>" class="visually-hidden">View label <?= $index + 1 ?></label>
                                <input type="text" class="form-control form-control-sm" id="galleryLabel<?= $index ?>"
                                       name="galleryLabel<?= $index ?>" value="<?= $image['label'] ?>"
                                       data-validation="required max" data-max="100">
                                <div class="invalid-feedback" id="galleryLabel<?= $index ?>Error"></div>
                            </div>
                            <div class="col">
                                <label for="galleryFile<?= $index ?>" class="visually-hidden">Image file <?= $index + 1 ?></label>
                                <input type="text" class="form-control form-control-sm" id="galleryFile<?= $index ?>"
                                       name="galleryFile<?= $index ?>" value="<?= $image['file'] ?>"
                                       data-validation="required max" data-max="150">
                                <div class="invalid-feedback" id="galleryFile<?= $index ?>Error"></div>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-outline-primary btn-sm admin-action" title="Remove view"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-2">
                        <i class="fa-solid fa-plus me-1"></i>Add another view
                    </button>
                </div>
            </div>

            <div class="card admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">Search engine details</h2>
                </div>
                <div class="card-body">
                    <div class="admin-form-grid">
                        <div class="full">
                            <label for="metaTitle" class="form-label small fw-semibold">Meta title</label>
                            <input type="text" class="form-control" id="metaTitle" name="metaTitle"
                                   value="Pulse Wireless Headphones | TrendCart"
                                   data-validation="required max" data-max="100">
                            <div class="invalid-feedback" id="metaTitleError"></div>
                        </div>
                        <div class="full">
                            <label for="metaDescription" class="form-label small fw-semibold">Meta description</label>
                            <textarea class="form-control" id="metaDescription" name="metaDescription" rows="2"
                                      data-validation="required max" data-max="200">Shop Pulse Wireless Headphones with 30-hour battery life and quick pairing.</textarea>
                            <div class="invalid-feedback" id="metaDescriptionError"></div>
                        </div>
                        <div class="full">
                            <label for="metaKeywords" class="form-label small fw-semibold">Keywords</label>
                            <input type="text" class="form-control" id="metaKeywords" name="metaKeywords"
                                   value="wireless headphones, bluetooth audio, over ear"
                                   data-validation="required max" data-max="200">
                            <div class="invalid-feedback" id="metaKeywordsError"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card admin-card mb-4">
                <div class="admin-card-header"><h2 class="admin-card-title">Preview</h2></div>
                <div class="card-body">
                    <div class="admin-preview-card">
                        <div class="admin-preview-head"><i class="fa-solid fa-headphones"></i></div>
                        <div class="p-3">
                            <p class="fw-bold mb-1">Pulse Wireless Headphones</p>
                            <p class="admin-section-note mb-2">Electronics &middot; 24 in stock</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <strong>&#8377;2,499</strong>
                                <span class="admin-status">Active</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card admin-card mb-4">
                <div class="admin-card-header"><h2 class="admin-card-title">Visibility</h2></div>
                <div class="card-body">
                    <div class="admin-switch-card">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="productActive" name="productActive" checked
                                   data-validation="max-items" data-max-items="1">
                            <label class="form-check-label small fw-semibold" for="productActive">Active</label>
                            <div class="invalid-feedback" id="productActiveError"></div>
                        </div>
                    </div>
                    <div class="admin-switch-card">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="productFeatured" name="productFeatured" checked
                                   data-validation="max-items" data-max-items="1">
                            <label class="form-check-label small fw-semibold" for="productFeatured">Featured product</label>
                            <div class="invalid-feedback" id="productFeaturedError"></div>
                        </div>
                    </div>
                    <div class="admin-switch-card">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="productNew" name="productNew"
                                   data-validation="max-items" data-max-items="1">
                            <label class="form-check-label small fw-semibold" for="productNew">Mark as new arrival</label>
                            <div class="invalid-feedback" id="productNewError"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-save-bar">
                <span class="admin-section-note">Design preview only</span>
                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-outline-primary btn-sm px-3">Save &amp; New</button>
                    <button type="submit" class="btn btn-primary btn-sm px-3">
                        <i class="fa-solid fa-check me-1"></i>Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<?php
$admin_page_content = ob_get_clean();
require 'admin-layout.php';
