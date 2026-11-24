<?php
/*
 * faq.php - FREQUENTLY ASKED QUESTIONS
 */

// STEP 1: Settings used by the shared layout.
$page_title  = 'FAQs | TrendCart';
$active_page = 'faq';

// STEP 2: Start capturing this page's HTML.
ob_start();
?>

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">FAQs</li>
        </ol>
    </nav>

    <!-- ========== PAGE HEADING ========== -->
    <div class="page-header">
        <span class="badge text-bg-primary mb-3 px-3 py-2">
            <i class="fa-solid fa-circle-question me-1"></i>HELP CENTRE
        </span>
        <h1 class="h2 fw-bold mb-3">Frequently Asked Questions</h1>
        <p class="text-muted">
            Everything you need to know about ordering, delivery, returns and your account.
            Can't find an answer? Our team is one message away.
        </p>
    </div>

    <div class="d-flex flex-wrap justify-content-center gap-2 mb-5">
        <a href="#orders" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-truck me-1"></i>Orders &amp; Delivery</a>
        <a href="#returns" class="btn btn-outline-success btn-sm"><i class="fa-solid fa-rotate-left me-1"></i>Returns &amp; Refunds</a>
        <a href="#payments" class="btn btn-outline-warning btn-sm"><i class="fa-solid fa-credit-card me-1"></i>Payments</a>
        <a href="#account" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-user-lock me-1"></i>Account</a>
        <a href="#products" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-box me-1"></i>Products</a>
    </div>

    <!-- ========== ORDERS & DELIVERY ========== -->
    <section id="orders" class="mb-5">
        <h2 class="h4 fw-bold mb-3">
            <i class="fa-solid fa-truck text-primary me-2"></i>Orders &amp; Delivery
        </h2>
        <div class="accordion" id="ordersAccordion">
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#ordOne" aria-expanded="true" aria-controls="ordOne">
                        How long does delivery take?
                    </button>
                </h3>
                <div id="ordOne" class="accordion-collapse collapse show" data-bs-parent="#ordersAccordion">
                    <div class="accordion-body text-muted">
                        Orders to metro cities usually arrive in 2&ndash;3 working days. Other locations
                        take 4&ndash;6 working days. You will see an estimated date at checkout and receive
                        a tracking link by email once your parcel is dispatched.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#ordTwo" aria-expanded="false" aria-controls="ordTwo">
                        How can I track my order?
                    </button>
                </h3>
                <div id="ordTwo" class="accordion-collapse collapse" data-bs-parent="#ordersAccordion">
                    <div class="accordion-body text-muted">
                        Sign in and open <strong>My Orders</strong> to see live status. You can also use
                        the tracking link in your dispatch email, or call us with your order number.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#ordThree" aria-expanded="false" aria-controls="ordThree">
                        What are the delivery charges?
                    </button>
                </h3>
                <div id="ordThree" class="accordion-collapse collapse" data-bs-parent="#ordersAccordion">
                    <div class="accordion-body text-muted">
                        Delivery is free on all orders above &#8377;999. For smaller orders we charge a
                        flat &#8377;49. The final amount is shown before you place the order.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#ordFour" aria-expanded="false" aria-controls="ordFour">
                        Can I change my delivery address after ordering?
                    </button>
                </h3>
                <div id="ordFour" class="accordion-collapse collapse" data-bs-parent="#ordersAccordion">
                    <div class="accordion-body text-muted">
                        Yes, if the parcel has not been dispatched yet. Contact our support team with
                        your order number within 12 hours of placing the order and we will update it.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#ordFive" aria-expanded="false" aria-controls="ordFive">
                        Do you deliver outside India?
                    </button>
                </h3>
                <div id="ordFive" class="accordion-collapse collapse" data-bs-parent="#ordersAccordion">
                    <div class="accordion-body text-muted">
                        At the moment we deliver across India only. International shipping is on our
                        roadmap &mdash; write to us at support@trendcart.in and we'll let you know when it launches.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== RETURNS & REFUNDS ========== -->
    <section id="returns" class="mb-5">
        <h2 class="h4 fw-bold mb-3">
            <i class="fa-solid fa-rotate-left text-success me-2"></i>Returns &amp; Refunds
        </h2>
        <div class="accordion" id="returnsAccordion">
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#retOne" aria-expanded="false" aria-controls="retOne">
                        What is your return policy?
                    </button>
                </h3>
                <div id="retOne" class="accordion-collapse collapse" data-bs-parent="#returnsAccordion">
                    <div class="accordion-body text-muted">
                        You can return most items within 7 days of delivery, as long as they are unused,
                        undamaged and in their original packaging with all accessories included.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#retTwo" aria-expanded="false" aria-controls="retTwo">
                        How do I return a product?
                    </button>
                </h3>
                <div id="retTwo" class="accordion-collapse collapse" data-bs-parent="#returnsAccordion">
                    <div class="accordion-body text-muted">
                        Raise a request from <strong>My Orders</strong>, or email us with your order number.
                        We arrange a free pickup from your address within 2 working days.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#retThree" aria-expanded="false" aria-controls="retThree">
                        When will I receive my refund?
                    </button>
                </h3>
                <div id="retThree" class="accordion-collapse collapse" data-bs-parent="#returnsAccordion">
                    <div class="accordion-body text-muted">
                        Refunds are started after the returned item passes our quality check, usually
                        within 5 working days of pickup. Money reaches your original payment method in
                        3&ndash;5 additional working days.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#retFour" aria-expanded="false" aria-controls="retFour">
                        What if I receive a damaged or wrong item?
                    </button>
                </h3>
                <div id="retFour" class="accordion-collapse collapse" data-bs-parent="#returnsAccordion">
                    <div class="accordion-body text-muted">
                        Email photos of the product and the packaging within 48 hours of delivery.
                        We will send a replacement at no cost, or arrange a free return and full refund.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#retFive" aria-expanded="false" aria-controls="retFive">
                        Can I return items bought on sale?
                    </button>
                </h3>
                <div id="retFive" class="accordion-collapse collapse" data-bs-parent="#returnsAccordion">
                    <div class="accordion-body text-muted">
                        Yes, sale items can be returned within the same 7-day window. Only final-clearance
                        products and personal-care items marked "non-returnable" are excluded.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== PAYMENTS & PRICING ========== -->
    <section id="payments" class="mb-5">
        <h2 class="h4 fw-bold mb-3">
            <i class="fa-solid fa-credit-card text-warning me-2"></i>Payments &amp; Pricing
        </h2>
        <div class="accordion" id="paymentsAccordion">
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#payOne" aria-expanded="false" aria-controls="payOne">
                        Which payment methods do you accept?
                    </button>
                </h3>
                <div id="payOne" class="accordion-collapse collapse" data-bs-parent="#paymentsAccordion">
                    <div class="accordion-body text-muted">
                        UPI, all major credit and debit cards, net banking, popular wallets and Cash on
                        Delivery. Every online payment goes through our encrypted payment gateway.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#payTwo" aria-expanded="false" aria-controls="payTwo">
                        Is Cash on Delivery available?
                    </button>
                </h3>
                <div id="payTwo" class="accordion-collapse collapse" data-bs-parent="#paymentsAccordion">
                    <div class="accordion-body text-muted">
                        Yes, on orders below &#8377;10,000. A &#8377;20 handling fee is added at checkout.
                        Please keep the exact amount ready when our delivery partner arrives.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#payThree" aria-expanded="false" aria-controls="payThree">
                        How do I use a coupon code?
                    </button>
                </h3>
                <div id="payThree" class="accordion-collapse collapse" data-bs-parent="#paymentsAccordion">
                    <div class="accordion-body text-muted">
                        Enter the code in the coupon box on the checkout page and click Apply. For example,
                        <strong>WELCOME10</strong> gives new customers 10% off. One coupon can be used per order.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#payFour" aria-expanded="false" aria-controls="payFour">
                        Are the displayed prices inclusive of tax?
                    </button>
                </h3>
                <div id="payFour" class="accordion-collapse collapse" data-bs-parent="#paymentsAccordion">
                    <div class="accordion-body text-muted">
                        Yes. All prices include GST. Delivery charges, if any, are shown separately before
                        you confirm the order &mdash; there are no hidden charges.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#payFive" aria-expanded="false" aria-controls="payFive">
                        Can I cancel my order?
                    </button>
                </h3>
                <div id="payFive" class="accordion-collapse collapse" data-bs-parent="#paymentsAccordion">
                    <div class="accordion-body text-muted">
                        You can cancel for free until your order is dispatched. Contact support with your
                        order number, and we will cancel it and start the refund right away.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== ACCOUNT & SECURITY ========== -->
    <section id="account" class="mb-5">
        <h2 class="h4 fw-bold mb-3">
            <i class="fa-solid fa-user-lock text-danger me-2"></i>Account &amp; Security
        </h2>
        <div class="accordion" id="accountAccordion">
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#accOne" aria-expanded="false" aria-controls="accOne">
                        Do I need an account to shop?
                    </button>
                </h3>
                <div id="accOne" class="accordion-collapse collapse" data-bs-parent="#accountAccordion">
                    <div class="accordion-body text-muted">
                        No, you can check out as a guest. An account lets you track orders, save items to
                        your wishlist and checkout faster next time.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#accTwo" aria-expanded="false" aria-controls="accTwo">
                        I forgot my password. What should I do?
                    </button>
                </h3>
                <div id="accTwo" class="accordion-collapse collapse" data-bs-parent="#accountAccordion">
                    <div class="accordion-body text-muted">
                        On the <a href="login.php" class="text-decoration-none">sign-in page</a>, click
                        "Forgot password?", enter your registered email and we will send you a reset link.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#accThree" aria-expanded="false" aria-controls="accThree">
                        How do I change my email address or phone number?
                    </button>
                </h3>
                <div id="accThree" class="accordion-collapse collapse" data-bs-parent="#accountAccordion">
                    <div class="accordion-body text-muted">
                        Sign in and update your details under Account Settings. If you no longer have access
                        to your old email, contact support with proof of identity and we will help you.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#accFour" aria-expanded="false" aria-controls="accFour">
                        Is my payment information safe?
                    </button>
                </h3>
                <div id="accFour" class="accordion-collapse collapse" data-bs-parent="#accountAccordion">
                    <div class="accordion-body text-muted">
                        Yes. Card details are handled by our PCI-compliant payment gateway and encrypted in
                        transit. We never store your full card number or CVV on our servers.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#accFive" aria-expanded="false" aria-controls="accFive">
                        How do I unsubscribe from the newsletter?
                    </button>
                </h3>
                <div id="accFive" class="accordion-collapse collapse" data-bs-parent="#accountAccordion">
                    <div class="accordion-body text-muted">
                        Every newsletter email includes an Unsubscribe link at the bottom. One click removes
                        your address from the list immediately.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== PRODUCTS & STOCK ========== -->
    <section id="products" class="mb-5">
        <h2 class="h4 fw-bold mb-3">
            <i class="fa-solid fa-box text-secondary me-2"></i>Products &amp; Stock
        </h2>
        <div class="accordion" id="productsAccordion">
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#prodOne" aria-expanded="false" aria-controls="prodOne">
                        Are your products genuine?
                    </button>
                </h3>
                <div id="prodOne" class="accordion-collapse collapse" data-bs-parent="#productsAccordion">
                    <div class="accordion-body text-muted">
                        Yes. We source from authorised distributors and brand partners, and every item is
                        quality checked before it leaves our warehouse.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#prodTwo" aria-expanded="false" aria-controls="prodTwo">
                        What happens if a product is out of stock?
                    </button>
                </h3>
                <div id="prodTwo" class="accordion-collapse collapse" data-bs-parent="#productsAccordion">
                    <div class="accordion-body text-muted">
                        Email us with the product name and we will tell you the expected restock date,
                        usually 5&ndash;7 working days. We can also suggest a similar alternative.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#prodThree" aria-expanded="false" aria-controls="prodThree">
                        Do the product photos match the real item?
                    </button>
                </h3>
                <div id="prodThree" class="accordion-collapse collapse" data-bs-parent="#productsAccordion">
                    <div class="accordion-body text-muted">
                        Our photos show the actual product. Colours may look slightly different on screen
                        because of display settings, but the description lists the exact shade and model.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#prodFour" aria-expanded="false" aria-controls="prodFour">
                        Do you offer gift wrapping?
                    </button>
                </h3>
                <div id="prodFour" class="accordion-collapse collapse" data-bs-parent="#productsAccordion">
                    <div class="accordion-body text-muted">
                        Yes. Select gift wrapping at checkout for &#8377;99 and add a free message card.
                        Prices are hidden on the packing slip for all gift orders.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== CTA ========== -->
    <div class="bg-light rounded-3 p-4 p-lg-5 text-center">
        <div class="fs-1 text-primary mb-3"><i class="fa-solid fa-life-ring"></i></div>
        <h2 class="h3 fw-bold">Still need help?</h2>
        <p class="text-muted mb-4">Send our support team a message and we'll reply within 24 hours.</p>
        <a href="contact.php" class="btn btn-primary btn-lg px-4">
            <i class="fa-solid fa-envelope me-2"></i>Contact Us
        </a>
    </div>
</div>

<?php
// STEP 3: Save the captured HTML and stop buffering.
$page_content = ob_get_clean();

// STEP 4: Render it inside the shared layout.
require 'layout.php';
