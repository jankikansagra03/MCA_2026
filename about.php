<?php
/*
 * about.php - ABOUT US
 */

// STEP 1: Settings used by the shared layout.
$page_title  = 'About Us | TrendCart';
$active_page = 'about';

// STEP 2: Start capturing this page's HTML.
ob_start();
?>

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">About Us</li>
        </ol>
    </nav>

    <!-- ========== PAGE HEADING ========== -->
    <div class="page-header">
        <span class="badge text-bg-primary mb-3 px-3 py-2"><i class="fa-solid fa-heart me-1"></i>SINCE 2021</span>
        <h1 class="h2 fw-bold mb-3">About TrendCart</h1>
        <p class="text-muted text-narrow mx-auto">
            We started with one simple belief: shopping online should be easy, fair and
            enjoyable. Today, thousands of customers across India trust us for the things
            they love.
        </p>
    </div>

    <!-- ========== STORY & MISSION ========== -->
    <div class="row g-4 mb-5">
        <div class="col-12 col-md-6">
            <div class="card store-card h-100">
                <div class="card-body p-4 p-lg-5">
                    <div class="fs-2 text-primary mb-3"><i class="fa-solid fa-book-open"></i></div>
                    <h2 class="h4 fw-bold">Our Story</h2>
                    <p class="text-muted">
                        TrendCart began in 2021 as a small shop in Ahmedabad with a handful of
                        products and a lot of curiosity. We listened closely to what customers
                        wanted &mdash; honest descriptions, fair prices and delivery that actually
                        arrives on time.
                    </p>
                    <p class="text-muted mb-0">
                        That focus still guides us. We grow slowly, choose our products carefully
                        and keep our promise: no surprises at checkout.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="card store-card h-100">
                <div class="card-body p-4 p-lg-5">
                    <div class="fs-2 text-danger mb-3"><i class="fa-solid fa-bullseye"></i></div>
                    <h2 class="h4 fw-bold">Our Mission</h2>
                    <p class="text-muted">
                        To make good-quality products affordable for everyone, and to back every
                        order with service we would want for ourselves. We believe a great shopping
                        experience doesn't have to be complicated.
                    </p>
                    <p class="text-muted mb-0">
                        From the first click to the day your parcel arrives, we're working to make
                        it feel effortless.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== NUMBERS ========== -->
    <div class="row g-3 text-center mb-5">
        <div class="col-6 col-lg-3">
            <div class="border rounded p-4 h-100">
                <div class="h2 fw-bold text-primary mb-1">50K+</div>
                <div class="text-muted hint">Happy customers</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="border rounded p-4 h-100">
                <div class="h2 fw-bold text-success mb-1">2,000+</div>
                <div class="text-muted hint">Products delivered</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="border rounded p-4 h-100">
                <div class="h2 fw-bold text-warning mb-1">4.8/5</div>
                <div class="text-muted hint">Average rating</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="border rounded p-4 h-100">
                <div class="h2 fw-bold text-danger mb-1">24/7</div>
                <div class="text-muted hint">Order tracking</div>
            </div>
        </div>
    </div>

    <!-- ========== WHY CHOOSE US ========== -->
    <h2 class="h3 fw-bold text-center mb-1">Why Shop With Us</h2>
    <p class="text-muted text-center hint mb-4">A few reasons customers keep coming back.</p>

    <div class="row g-4 text-center mb-5">
        <div class="col-6 col-lg-3">
            <div class="fs-1 text-primary mb-2"><i class="fa-solid fa-truck-fast"></i></div>
            <h6 class="fw-bold">Quick Dispatch</h6>
            <p class="text-muted hint">Orders leave our warehouse within 24 hours.</p>
        </div>
        <div class="col-6 col-lg-3">
            <div class="fs-1 text-success mb-2"><i class="fa-solid fa-shield-halved"></i></div>
            <h6 class="fw-bold">Quality Checked</h6>
            <p class="text-muted hint">Every item is inspected before it ships.</p>
        </div>
        <div class="col-6 col-lg-3">
            <div class="fs-1 text-warning mb-2"><i class="fa-solid fa-rotate-left"></i></div>
            <h6 class="fw-bold">7-Day Returns</h6>
            <p class="text-muted hint">Not happy? Send it back with no questions asked.</p>
        </div>
        <div class="col-6 col-lg-3">
            <div class="fs-1 text-danger mb-2"><i class="fa-solid fa-headset"></i></div>
            <h6 class="fw-bold">Real People</h6>
            <p class="text-muted hint">Our support team answers within a few hours.</p>
        </div>
    </div>

    <!-- ========== TEAM ========== -->
    <h2 class="h3 fw-bold text-center mb-1">Meet the Team</h2>
    <p class="text-muted text-center hint mb-4">The people behind your orders.</p>

    <div class="row g-4 mb-5">
        <div class="col-12 col-md-4">
            <div class="card store-card h-100 text-center">
                <div class="card-body py-4">
                    <div class="avatar bg-primary-subtle text-primary mx-auto mb-3">AK</div>
                    <h5 class="h6 fw-bold mb-1">Aarav Kapoor</h5>
                    <p class="text-muted hint mb-2">Founder &amp; CEO</p>
                    <p class="text-muted hint mb-0">Keeps the big picture in focus and the team smiling.</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card store-card h-100 text-center">
                <div class="card-body py-4">
                    <div class="avatar bg-success-subtle text-success mx-auto mb-3">RS</div>
                    <h5 class="h6 fw-bold mb-1">Riya Sharma</h5>
                    <p class="text-muted hint mb-2">Head of Customer Care</p>
                    <p class="text-muted hint mb-0">Makes sure every question gets a helpful answer.</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card store-card h-100 text-center">
                <div class="card-body py-4">
                    <div class="avatar bg-warning-subtle text-warning-emphasis mx-auto mb-3">MJ</div>
                    <h5 class="h6 fw-bold mb-1">Manav Joshi</h5>
                    <p class="text-muted hint mb-2">Operations Lead</p>
                    <p class="text-muted hint mb-0">Gets your parcel packed, checked and on its way.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== CTA ========== -->
    <div class="bg-light rounded-3 p-4 p-lg-5 text-center">
        <h2 class="h3 fw-bold">Ready to find your next favourite?</h2>
        <p class="text-muted mb-4">Browse our collection and enjoy free delivery over &#8377;999.</p>
        <a href="product.php" class="btn btn-primary btn-lg px-4">
            <i class="fa-solid fa-cart-shopping me-2"></i>Start Shopping
        </a>
    </div>
</div>

<?php
// STEP 3: Save the captured HTML and stop buffering.
$page_content = ob_get_clean();

// STEP 4: Render it inside the shared layout.
require 'layout.php';
