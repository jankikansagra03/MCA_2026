<?php
/*
 * contact.php - CONTACT US
 */

// STEP 1: Settings used by the shared layout.
$page_title  = 'Contact Us | TrendCart';
$active_page = 'contact';

// STEP 2: Start capturing this page's HTML.
ob_start();
?>

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
        </ol>
    </nav>

    <div class="page-header">
        <span class="badge text-bg-primary mb-3 px-3 py-2">
            <i class="fa-solid fa-headset me-1"></i>SUPPORT
        </span>
        <h1 class="h2 fw-bold mb-3">We're Here to Help</h1>
        <p class="text-muted">
            Questions about an order, a product or a return? Send us a message and our
            support team will get back to you within 24 hours.
        </p>
    </div>

    <div class="row g-4 mb-5">
        <!-- ========== CONTACT FORM ========== -->
        <div class="col-12 col-lg-7">
            <div class="card store-card h-100">
                <div class="card-body p-4 p-lg-5">
                    <h2 class="h4 fw-bold mb-4">Send Us a Message</h2>

                    <form action="#" method="post" novalidate>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="name" class="form-label small fw-semibold">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Your full name"
                                           data-validation="required max" data-max="50">
                                </div>
                                <div class="invalid-feedback" id="nameError"></div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="email" class="form-label small fw-semibold">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com"
                                           data-validation="required email">
                                </div>
                                <div class="invalid-feedback" id="emailError"></div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="order" class="form-label small fw-semibold">Order Number <span class="text-muted">(optional)</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-box"></i></span>
                                    <input type="text" class="form-control" id="order" name="order" placeholder="e.g. TC-2026-1042"
                                           data-validation="max" data-max="30">
                                </div>
                                <div class="invalid-feedback" id="orderError"></div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="phone" class="form-label small fw-semibold">Phone Number <span class="text-muted">(optional)</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="+91 98765 43210"
                                           data-validation="max" data-max="15">
                                </div>
                                <div class="invalid-feedback" id="phoneError"></div>
                            </div>

                            <div class="col-12">
                                <label for="subject" class="form-label small fw-semibold">What is this about?</label>
                                <select class="form-select" id="subject" name="subject" data-validation="required">
                                    <option value="" selected>Choose a topic...</option>
                                    <option value="order">My order / delivery</option>
                                    <option value="return">Return or refund</option>
                                    <option value="product">Product question</option>
                                    <option value="payment">Payment issue</option>
                                    <option value="other">Something else</option>
                                </select>
                                <div class="invalid-feedback" id="subjectError"></div>
                            </div>

                            <div class="col-12">
                                <label for="message" class="form-label small fw-semibold">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="5"
                                          placeholder="Tell us a little more about how we can help..."
                                          data-validation="required max" data-max="500"></textarea>
                                <div class="invalid-feedback" id="messageError"></div>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fa-solid fa-paper-plane me-2"></i>Send Message
                                </button>
                            </div>
                        </div>
                    </form>

                    <p class="text-muted hint mt-3 mb-0">
                        <i class="fa-solid fa-clock me-1"></i>
                        We usually reply within one business day, Monday to Saturday.
                    </p>
                </div>
            </div>
        </div>

        <!-- ========== CONTACT DETAILS ========== -->
        <div class="col-12 col-lg-5">
            <div class="card store-card h-100">
                <div class="card-body p-4 p-lg-5">
                    <h2 class="h4 fw-bold mb-4">Reach Us Directly</h2>

                    <div class="d-flex mb-4">
                        <div class="fs-4 text-primary me-3"><i class="fa-solid fa-location-dot"></i></div>
                        <div>
                            <h3 class="h6 fw-bold mb-1">Visit Our Store</h3>
                            <p class="text-muted hint mb-0">204, MG Road, Near Law Garden,<br>Ahmedabad, Gujarat 380006</p>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <div class="fs-4 text-success me-3"><i class="fa-solid fa-phone"></i></div>
                        <div>
                            <h3 class="h6 fw-bold mb-1">Call Us</h3>
                            <p class="text-muted hint mb-0">1800 123 4567 (toll free)<br>+91 79 4000 2244</p>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <div class="fs-4 text-warning me-3"><i class="fa-solid fa-envelope"></i></div>
                        <div>
                            <h3 class="h6 fw-bold mb-1">Email Us</h3>
                            <p class="text-muted hint mb-0">support@trendcart.in<br>orders@trendcart.in</p>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <div class="fs-4 text-danger me-3"><i class="fa-solid fa-clock"></i></div>
                        <div>
                            <h3 class="h6 fw-bold mb-1">Store Hours</h3>
                            <p class="text-muted hint mb-0">Monday &ndash; Saturday: 10:00 AM &ndash; 7:00 PM<br>Sunday: Closed</p>
                        </div>
                    </div>

                    <hr>

                    <p class="text-muted hint mb-2">Follow us for new arrivals and offers:</p>
                    <div class="d-flex gap-3 fs-5">
                        <a href="#" class="text-primary"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="text-info"><i class="fa-brands fa-square-x-twitter"></i></a>
                        <a href="#" class="text-danger"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="text-danger"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== FAQ LINK ========== -->
    <div class="bg-light rounded-3 p-4 p-lg-5 text-center">
        <div class="fs-1 text-primary mb-3"><i class="fa-solid fa-circle-question"></i></div>
        <h2 class="h4 fw-bold">Looking for a quick answer?</h2>
        <p class="text-muted mb-4">Browse our FAQs for answers about delivery, returns, payments and more.</p>
        <a href="faq.php" class="btn btn-outline-primary px-4">
            <i class="fa-solid fa-circle-question me-2"></i>Visit Help Centre
        </a>
    </div>
</div>

<?php
// STEP 3: Save the captured HTML and stop buffering.
$page_content = ob_get_clean();

// STEP 4: Render it inside the shared layout.
require 'layout.php';
