<?php
/*
 * product-detail.php - SINGLE PRODUCT VIEW
 * The view icons on the listing pages link here with ?product=slug.
 * A small array holds the details for each product (no database needed).
 */

// STEP 1: All the product information in one simple array.
$products = [
    'headphones' => [
        'name'        => 'Pulse Wireless Headphones',
        'category'    => 'Electronics',
        'price'       => '2,499',
        'old_price'   => '3,299',
        'discount'    => '-25%',
        'reviews'     => 128,
        'icon'        => 'fa-headphones',
        'images'      => [
            ['icon' => 'fa-headphones',   'label' => 'Front view'],
            ['icon' => 'fa-volume-high',  'label' => 'Sound detail'],
            ['icon' => 'fa-bluetooth',    'label' => 'Wireless pairing'],
            ['icon' => 'fa-battery-full', 'label' => 'Battery life'],
        ],
        'colour'      => 'text-primary',
        'short'       => 'Deep bass, all-day comfort and 30 hours of battery life.',
        'description' => 'Our best-selling wireless headphones combine rich sound with a light, comfortable fit. Switch easily between your phone and laptop, and enjoy up to 30 hours of playtime on a single charge.',
        'features'    => ['Bluetooth 5.3 with quick pairing', 'Up to 30 hours of battery life', 'Soft memory-foam ear cushions', 'Built-in microphone for calls'],
    ],
    'smartwatch' => [
        'name'        => 'Nova Smartwatch 2',
        'category'    => 'Electronics',
        'price'       => '4,999',
        'old_price'   => '6,999',
        'discount'    => '-28%',
        'reviews'     => 86,
        'icon'        => 'fa-stopwatch',
        'images'      => [
            ['icon' => 'fa-stopwatch',    'label' => 'Front view'],
            ['icon' => 'fa-heart-pulse',  'label' => 'Health tracking'],
            ['icon' => 'fa-mobile-screen','label' => 'Notifications'],
            ['icon' => 'fa-battery-full', 'label' => 'Battery life'],
        ],
        'colour'      => 'text-info',
        'short'       => 'Track your day, your workouts and your sleep in style.',
        'description' => 'The Nova Smartwatch 2 keeps your health numbers and notifications on your wrist. Its bright display stays easy to read outdoors, and the battery comfortably lasts through a busy week.',
        'features'    => ['Heart-rate and sleep tracking', '1.9 inch AMOLED display', 'Up to 7 days of battery life', 'Water resistant to 5 ATM'],
    ],
    'laptop' => [
        'name'        => 'Zenith 14 Laptop',
        'category'    => 'Electronics',
        'price'       => '54,990',
        'old_price'   => '62,990',
        'discount'    => '-12%',
        'reviews'     => 54,
        'icon'        => 'fa-laptop',
        'images'      => [
            ['icon' => 'fa-laptop',   'label' => 'Open view'],
            ['icon' => 'fa-keyboard', 'label' => 'Keyboard'],
            ['icon' => 'fa-microchip','label' => 'Performance'],
            ['icon' => 'fa-plug',     'label' => 'Ports'],
        ],
        'colour'      => 'text-primary',
        'short'       => 'A slim, fast laptop for work, study and everything in between.',
        'description' => 'Built around a crisp 14-inch screen and a fast processor, the Zenith 14 is made for long work sessions without weighing you down. It boots quickly and stays cool under pressure.',
        'features'    => ['14-inch full-HD anti-glare display', '16 GB RAM and 512 GB SSD', 'Backlit keyboard and fingerprint login', 'Up to 10 hours of battery life'],
    ],
    'controller' => [
        'name'        => 'ProPlay Wireless Controller',
        'category'    => 'Gaming',
        'price'       => '2,999',
        'old_price'   => '3,999',
        'discount'    => '-25%',
        'reviews'     => 97,
        'icon'        => 'fa-gamepad',
        'images'      => [
            ['icon' => 'fa-gamepad',     'label' => 'Front view'],
            ['icon' => 'fa-bluetooth',   'label' => 'Wireless'],
            ['icon' => 'fa-battery-half','label' => 'Battery'],
            ['icon' => 'fa-bolt',        'label' => 'Fast response'],
        ],
        'colour'      => 'text-success',
        'short'       => 'Responsive controls and a comfortable grip for long sessions.',
        'description' => 'Take your gaming to the next level with precise controls, customisable buttons and a grip that stays comfortable through long matches. Connect wirelessly or with a cable whenever you need.',
        'features'    => ['Low-latency wireless connection', 'Customisable button mapping', 'Built-in vibration feedback', 'Works with PC and consoles'],
    ],
    'tshirt' => [
        'name'        => 'Everywear Cotton T-Shirt',
        'category'    => 'Fashion',
        'price'       => '799',
        'old_price'   => '1,199',
        'discount'    => '-33%',
        'reviews'     => 213,
        'icon'        => 'fa-shirt',
        'images'      => [
            ['icon' => 'fa-shirt', 'label' => 'Front view'],
            ['icon' => 'fa-leaf',  'label' => 'Soft cotton'],
            ['icon' => 'fa-ruler', 'label' => 'True fit'],
            ['icon' => 'fa-star',  'label' => 'Customer favourite'],
        ],
        'colour'      => 'text-danger',
        'short'       => 'Soft, breathable cotton with a fit that works every day.',
        'description' => 'A wardrobe essential made from breathable combed cotton. The fabric holds its shape wash after wash, while the relaxed cut keeps you comfortable from morning to night.',
        'features'    => ['100% combed cotton', 'Available in seven colours', 'Pre-shrunk to keep its fit', 'Machine washable'],
    ],
    'camera' => [
        'name'        => 'SnapShot Digital Camera',
        'category'    => 'Electronics',
        'price'       => '28,500',
        'old_price'   => '32,000',
        'discount'    => '-11%',
        'reviews'     => 41,
        'icon'        => 'fa-camera',
        'images'      => [
            ['icon' => 'fa-camera', 'label' => 'Front view'],
            ['icon' => 'fa-video',  'label' => 'Video mode'],
            ['icon' => 'fa-image',  'label' => 'Photo samples'],
            ['icon' => 'fa-bolt',   'label' => 'Fast capture'],
        ],
        'colour'      => 'text-warning',
        'short'       => 'Sharp photos and smooth video in a compact body.',
        'description' => 'Capture holidays, events and everyday moments with a compact camera that is easy to carry. Quick autofocus and helpful shooting modes make it simple to get great results.',
        'features'    => ['24 MP sensor', '4K video recording', 'Fast autofocus with face detection', 'Includes 32 GB memory card'],
    ],
    'book' => [
        'name'        => 'PHP: From Zero to Hero',
        'category'    => 'Books',
        'price'       => '499',
        'old_price'   => '699',
        'discount'    => '-28%',
        'reviews'     => 312,
        'icon'        => 'fa-book',
        'images'      => [
            ['icon' => 'fa-book',           'label' => 'Cover'],
            ['icon' => 'fa-book-open',      'label' => 'Inside pages'],
            ['icon' => 'fa-file-lines',     'label' => 'Sample chapter'],
            ['icon' => 'fa-graduation-cap', 'label' => 'Learn PHP'],
        ],
        'colour'      => 'text-primary',
        'short'       => 'A friendly, practical guide to learning PHP step by step.',
        'description' => 'Start with the basics and build real confidence through clear explanations and small projects. This beginner-friendly guide is written for readers with no previous programming experience.',
        'features'    => ['Covers PHP 8 from the ground up', 'Hands-on examples in every chapter', 'Simple, beginner-friendly language', 'Printed and ebook editions'],
    ],
    'hamper' => [
        'name'        => 'Aura Gift Hamper',
        'category'    => 'Gifts',
        'price'       => '1,299',
        'old_price'   => '1,799',
        'discount'    => '-27%',
        'reviews'     => 68,
        'icon'        => 'fa-gem',
        'images'      => [
            ['icon' => 'fa-gem',  'label' => 'Gift box'],
            ['icon' => 'fa-gift', 'label' => 'Ready to gift'],
            ['icon' => 'fa-box',  'label' => 'What is inside'],
            ['icon' => 'fa-heart','label' => 'Thoughtful surprise'],
        ],
        'colour'      => 'text-danger',
        'short'       => 'A thoughtful gift box for birthdays, festivals and thank-yous.',
        'description' => 'Bring a little joy to someone special with a carefully packed hamper of sweet treats and small surprises. It arrives ready to gift, with a personal message card included.',
        'features'    => ['Assorted premium snacks', 'Reusable gift box and ribbon', 'Personal message card included', 'Free gift wrapping'],
    ],
];

// STEP 2: Read the requested product. Fall back to the first one if it is unknown.
$slug = isset($_GET['product']) ? $_GET['product'] : 'headphones';
if (!isset($products[$slug])) {
    $slug = 'headphones';
}
$product = $products[$slug];

// STEP 3: Settings used by the shared layout.
$page_title  = $product['name'] . ' | TrendCart';
$active_page = 'product';

// STEP 4: Start capturing this page's HTML.
ob_start();
?>

<div class="container py-4 py-lg-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="product.php" class="text-decoration-none">Shop</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $product['name'] ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- ========== PRODUCT IMAGE AREA ========== -->
        <div class="col-lg-5">
            <div class="card store-card p-4 h-100">
                <div class="position-relative">
                    <span
                        class="badge discount-badge text-bg-danger position-absolute top-0 start-0 m-3 gallery-floating"><?= $product['discount'] ?></span>
                    <div class="position-absolute top-0 end-0 m-3 d-flex gap-1 gallery-floating">
                        <button class="product-icon-btn btn btn-sm btn-light rounded-circle" title="Add to wishlist">
                            <i class="fa-regular fa-heart text-danger"></i>
                        </button>
                        <button class="product-icon-btn btn btn-sm btn-light rounded-circle" title="Add to cart">
                            <i class="fa-solid fa-cart-shopping text-primary"></i>
                        </button>
                    </div>

                    <!-- One large image plus thumbnails for the other views. -->
                    <div id="productGallery" class="carousel slide product-gallery" data-bs-ride="false">
                        <div class="carousel-inner">
                            <?php foreach ($product['images'] as $index => $image) : ?>
                                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                    <div
                                        class="product-img product-detail-img d-flex align-items-center justify-content-center rounded <?= $product['colour'] ?>">
                                        <i class="fa-solid <?= $image['icon'] ?>"></i>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="carousel-indicators gallery-indicators">
                            <?php foreach ($product['images'] as $index => $image) : ?>
                                <button type="button" data-bs-target="#productGallery"
                                        data-bs-slide-to="<?= $index ?>"
                                        class="<?= $index === 0 ? 'active' : '' ?>"
                                        <?= $index === 0 ? 'aria-current="true"' : '' ?>
                                        aria-label="View <?= $index + 1 ?>: <?= htmlspecialchars($image['label']) ?>"
                                        title="<?= htmlspecialchars($image['label']) ?>">
                                    <i class="fa-solid <?= $image['icon'] ?>"></i>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <p class="hint text-muted text-center mt-3 mb-0">
                    <i class="fa-solid fa-image me-1"></i><?= count($product['images']) ?> views available &mdash;
                    select a thumbnail to see more.
                </p>
            </div>
        </div>

        <!-- ========== PRODUCT DETAILS ========== -->
        <div class="col-lg-7">
            <div class="card store-card p-4 p-lg-5 h-100">
                <small class="text-uppercase text-muted fw-semibold"><?= $product['category'] ?></small>
                <h1 class="h2 fw-bold mt-1 mb-2"><?= $product['name'] ?></h1>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="rating-stars">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                            class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                            class="fa-solid fa-star"></i>
                    </span>
                    <span class="text-muted hint">(<?= $product['reviews'] ?> reviews)</span>
                </div>

                <div class="d-flex align-items-baseline gap-3 flex-wrap mb-3">
                    <span class="display-6 fw-bold text-primary">&#8377;<?= $product['price'] ?></span>
                    <span class="price-old fs-6">&#8377;<?= $product['old_price'] ?></span>
                    <span class="badge discount-badge text-bg-danger"><?= $product['discount'] ?> off</span>
                </div>

                <p class="fs-6"><?= $product['short'] ?></p>
                <p class="text-muted hint mb-4"><?= $product['description'] ?></p>

                <h2 class="h6 fw-bold mb-3">What you get</h2>
                <ul class="list-unstyled mb-4">
                    <?php foreach ($product['features'] as $feature) : ?>
                    <li class="mb-2 hint"><i class="fa-solid fa-circle-check text-success me-2"></i><?= $feature ?></li>
                    <?php endforeach; ?>
                </ul>

                <div id="buy-now" class="d-flex flex-wrap align-items-center gap-3 mb-4">
                    <div class="input-group" style="width: 130px;">
                        <button class="btn btn-outline-secondary" type="button" title="Decrease quantity"><i
                                class="fa-solid fa-minus"></i></button>
                        <input type="text" class="form-control text-center" value="1" aria-label="Quantity" readonly
                               data-validation="required numeric min" data-min="1">
                        <button class="btn btn-outline-secondary" type="button" title="Increase quantity"><i
                                class="fa-solid fa-plus"></i></button>
                    </div>
                    <button class="btn btn-primary px-4 py-2 fw-semibold" type="button">
                        <i class="fa-solid fa-cart-shopping me-2"></i>Add to Cart
                    </button>

                </div>

                <div class="row g-3 border-top pt-4">
                    <div class="col-sm-4">
                        <div class="d-flex gap-2 align-items-start">
                            <i class="fa-solid fa-truck text-primary mt-1"></i>
                            <div><strong class="d-block hint">Free delivery</strong><span class="text-muted hint">On
                                    orders above &#8377;999</span></div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="d-flex gap-2 align-items-start">
                            <i class="fa-solid fa-rotate-left text-primary mt-1"></i>
                            <div><strong class="d-block hint">Easy returns</strong><span class="text-muted hint">Within
                                    7 days</span></div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="d-flex gap-2 align-items-start">
                            <i class="fa-solid fa-shield-halved text-primary mt-1"></i>
                            <div><strong class="d-block hint">Secure shopping</strong><span class="text-muted hint">Safe
                                    checkout</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

<?php
// STEP 5: Save the captured HTML and stop buffering.
$page_content = ob_get_clean();

// STEP 6: Render it inside the shared layout.
require 'layout.php';