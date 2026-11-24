-- =============================================================
-- Guest page content copied from the static HTML pages.
-- Safe to re-run: pages are upserted, page sections are rebuilt.
-- =============================================================

USE MCA_2026;

INSERT INTO pages (id, page_key, page_name, title, subtitle, body, cta_label, cta_url, status) VALUES
(1, 'home', 'Home',
 'Everyday picks. Exceptional prices.',
 'Smart gadgets, easy fashion and home essentials — handpicked so you spend less time searching and less money checking out.',
 'NEW SEASON · FRESH FINDS\nCoupon WELCOME10 — 10% off your first order\n4.8/5 from 12,000+ shoppers · Free delivery over ₹999',
 'Shop all products', 'product.php', 'active'),

(2, 'about', 'About Us',
 'About TrendCart',
 'We started with one simple belief: shopping online should be easy, fair and enjoyable. Today, thousands of customers across India trust us for the things they love.',
 'SINCE 2021 — trusted by thousands of shoppers across India.',
 'Start Shopping', 'product.php', 'active'),

(3, 'contact', 'Contact Us',
 'We''re Here to Help',
 'Questions about an order, a product or a return? Send us a message and our support team will get back to you within 24 hours.',
 'SUPPORT — Questions about an order, a product or a return? Send us a message.',
 'Visit Help Centre', 'faq.php', 'active'),

(4, 'faq', 'FAQs',
 'Frequently Asked Questions',
 'Everything you need to know about ordering, delivery, returns and your account. Can''t find an answer? Our team is one message away.',
 'HELP CENTRE — Orders & Delivery, Returns & Refunds, Payments, Account, Products.',
 'Contact Us', 'contact.php', 'active'),

(5, 'offers', 'Offers & Deals',
 'Offers & Deals',
 'Good prices get better. Pick a coupon, shop the deal and enjoy free delivery on orders above ₹999.',
 'SAVE MORE — Pick a coupon, shop the deal and enjoy free delivery on orders above ₹999.',
 'Shop All Products', 'product.php', 'active'),

(6, 'shop', 'Shop',
 'Shop All Products',
 'Browse gadgets, fashion, books and gifts — all with clear prices and easy returns.',
 'SHOP — Showing 1–8 of 8 products. Sort by popularity, price, newest arrivals or customer rating.',
 '', '', 'active')
ON DUPLICATE KEY UPDATE
    page_key    = VALUES(page_key),
    page_name   = VALUES(page_name),
    title       = VALUES(title),
    subtitle    = VALUES(subtitle),
    body        = VALUES(body),
    cta_label   = VALUES(cta_label),
    cta_url     = VALUES(cta_url),
    status      = VALUES(status);

DELETE FROM page_sections WHERE page_id BETWEEN 1 AND 6;

INSERT INTO page_sections (page_id, section_key, heading, body, icon_class, display_order, status) VALUES
-- ---------------- HOME ----------------
(1,'hero_badge','NEW SEASON · FRESH FINDS','Handpicked new arrivals for the season.','fa-solid fa-wand-magic-sparkles',1,'active'),
(1,'hero_coupon','WELCOME10','10% off your first order · NEW','fa-solid fa-ticket',2,'active'),
(1,'hero_featured_product','Pulse Wireless Headphones','FEATURED PICK\n₹2,499 / ₹4,299\n-42% · 4.9 rating','fa-solid fa-headphones',3,'active'),
(1,'trust_delivery','Fast Delivery','Dispatched within 24 hours','fa-solid fa-truck-fast',4,'active'),
(1,'trust_returns','Easy Returns','7-day hassle-free returns','fa-solid fa-rotate-left',5,'active'),
(1,'trust_payments','Secure Payments','100% safe checkout','fa-solid fa-shield-halved',6,'active'),
(1,'trust_support','Help When You Need','Support 6 days a week','fa-solid fa-headset',7,'active'),
(1,'categories','Shop by Category','Find exactly what you''re looking for.\nElectronics · Fashion · Gaming · Books · Home & Living · Sports','fa-solid fa-layer-group',8,'active'),
(1,'bestsellers','Bestsellers This Week','The picks our customers keep coming back for.\nPulse Wireless Headphones · Nova Smartwatch 2 · Zenith 14 Laptop · ProPlay Wireless Controller','fa-solid fa-star',9,'active'),
(1,'promo_banner','Flat ₹500 off on your first laptop','TODAY ONLY\nUse code LAPTOP500 at checkout. Offer valid while stocks last.','fa-solid fa-fire',10,'active'),
(1,'testimonials','Loved by 50,000+ Shoppers','Real words from our customers.\nRiya S.: Ordered on Monday, delivered on Wednesday. The headphones sound great and the packaging was lovely.\nArjun K.: The smartwatch was exactly as described. Customer support answered my question within a few hours.\nMeera P.: Great prices and an easy return process. I''ve recommended TrendCart to all my classmates.','fa-solid fa-quote-left',11,'active'),

-- ---------------- ABOUT ----------------
(2,'story','Our Story','TrendCart began in 2021 as a small shop in Ahmedabad with a handful of products and a lot of curiosity. We listened closely to what customers wanted — honest descriptions, fair prices and delivery that actually arrives on time.\n\nThat focus still guides us. We grow slowly, choose our products carefully and keep our promise: no surprises at checkout.','fa-solid fa-book-open',1,'active'),
(2,'mission','Our Mission','To make good-quality products affordable for everyone, and to back every order with service we would want for ourselves. We believe a great shopping experience doesn''t have to be complicated.\n\nFrom the first click to the day your parcel arrives, we''re working to make it feel effortless.','fa-solid fa-bullseye',2,'active'),
(2,'stats','By the Numbers','50K+ Happy customers\n2,000+ Products delivered\n4.8/5 Average rating\n24/7 Order tracking','fa-solid fa-chart-bar',3,'active'),
(2,'why_choose_us','Why Shop With Us','A few reasons customers keep coming back.\nQuick Dispatch · Orders leave our warehouse within 24 hours.\nQuality Checked · Every item is inspected before it ships.\n7-Day Returns · Not happy? Send it back with no questions asked.\nReal People · Our support team answers within a few hours.','fa-solid fa-shield-halved',4,'active'),
(2,'team','Meet the Team','The people behind your orders.\nAarav Kapoor — Founder & CEO — Keeps the big picture in focus and the team smiling.\nRiya Sharma — Head of Customer Care — Makes sure every question gets a helpful answer.\nManav Joshi — Operations Lead — Gets your parcel packed, checked and on its way.','fa-solid fa-users',5,'active'),
(2,'about_cta','Ready to find your next favourite?','Browse our collection and enjoy free delivery over ₹999.','fa-solid fa-cart-shopping',6,'active'),

-- ---------------- CONTACT ----------------
(3,'contact_form','Send Us a Message','Full Name · Email Address · Order Number (optional) · Phone Number (optional)\nWhat is this about?: My order / delivery, Return or refund, Product question, Payment issue, Something else\nMessage · Send Message\nWe usually reply within one business day, Monday to Saturday.','fa-solid fa-paper-plane',1,'active'),
(3,'contact_details','Reach Us Directly','Visit Our Store — 204, MG Road, Near Law Garden, Ahmedabad, Gujarat 380006\nCall Us — 1800 123 4567 (toll free), +91 79 4000 2244\nEmail Us — support@trendcart.in, orders@trendcart.in\nStore Hours — Monday – Saturday: 10:00 AM – 7:00 PM, Sunday: Closed','fa-solid fa-location-dot',2,'active'),
(3,'contact_social','Follow Us','Follow us for new arrivals and offers: Facebook · X · Instagram · YouTube','fa-solid fa-share-nodes',3,'active'),
(3,'contact_faq_cta','Looking for a quick answer?','Browse our FAQs for answers about delivery, returns, payments and more. CTA: Visit Help Centre','fa-solid fa-circle-question',4,'active'),

-- ---------------- FAQ ----------------
(4,'faq_orders','Orders & Delivery','How long does delivery take? — Orders to metro cities usually arrive in 2–3 working days. Other locations take 4–6 working days. You will see an estimated date at checkout and receive a tracking link by email once your parcel is dispatched.\nHow can I track my order? — Sign in and open My Orders to see live status. You can also use the tracking link in your dispatch email, or call us with your order number.\nWhat are the delivery charges? — Delivery is free on all orders above ₹999. For smaller orders we charge a flat ₹49. The final amount is shown before you place the order.\nCan I change my delivery address after ordering? — Yes, if the parcel has not been dispatched yet. Contact our support team with your order number within 12 hours of placing the order and we will update it.\nDo you deliver outside India? — At the moment we deliver across India only. International shipping is on our roadmap — write to us at support@trendcart.in and we''ll let you know when it launches.','fa-solid fa-truck',1,'active'),
(4,'faq_returns','Returns & Refunds','What is your return policy? — You can return most items within 7 days of delivery, as long as they are unused, undamaged and in their original packaging with all accessories included.\nHow do I return a product? — Raise a request from My Orders, or email us with your order number. We arrange a free pickup from your address within 2 working days.\nWhen will I receive my refund? — Refunds are started after the returned item passes our quality check, usually within 5 working days of pickup. Money reaches your original payment method in 3–5 additional working days.\nWhat if I receive a damaged or wrong item? — Email photos of the product and the packaging within 48 hours of delivery. We will send a replacement at no cost, or arrange a free return and full refund.\nCan I return items bought on sale? — Yes, sale items can be returned within the same 7-day window. Only final-clearance products and personal-care items marked "non-returnable" are excluded.','fa-solid fa-rotate-left',2,'active'),
(4,'faq_payments','Payments & Pricing','Which payment methods do you accept? — UPI, all major credit and debit cards, net banking, popular wallets and Cash on Delivery. Every online payment goes through our encrypted payment gateway.\nIs Cash on Delivery available? — Yes, on orders below ₹10,000. A ₹20 handling fee is added at checkout. Please keep the exact amount ready when our delivery partner arrives.\nHow do I use a coupon code? — Enter the code in the coupon box on the checkout page and click Apply. For example, WELCOME10 gives new customers 10% off. One coupon can be used per order.\nAre the displayed prices inclusive of tax? — Yes. All prices include GST. Delivery charges, if any, are shown separately before you confirm the order — there are no hidden charges.\nCan I cancel my order? — You can cancel for free until your order is dispatched. Contact support with your order number, and we will cancel it and start the refund right away.','fa-solid fa-credit-card',3,'active'),
(4,'faq_account','Account & Security','Do I need an account to shop? — No, you can check out as a guest. An account lets you track orders, save items to your wishlist and checkout faster next time.\nI forgot my password. What should I do? — On the sign-in page, click "Forgot password?", enter your registered email and we will send you a reset link.\nHow do I change my email address or phone number? — Sign in and update your details under Account Settings. If you no longer have access to your old email, contact support with proof of identity and we will help you.\nIs my payment information safe? — Yes. Card details are handled by our PCI-compliant payment gateway and encrypted in transit. We never store your full card number or CVV on our servers.\nHow do I unsubscribe from the newsletter? — Every newsletter email includes an Unsubscribe link at the bottom. One click removes your address from the list immediately.','fa-solid fa-user-lock',4,'active'),
(4,'faq_products','Products & Stock','Are your products genuine? — Yes. We source from authorised distributors and brand partners, and every item is quality checked before it leaves our warehouse.\nWhat happens if a product is out of stock? — Email us with the product name and we will tell you the expected restock date, usually 5–7 working days. We can also suggest a similar alternative.\nDo the product photos match the real item? — Our photos show the actual product. Colours may look slightly different on screen because of display settings, but the description lists the exact shade and model.\nDo you offer gift wrapping? — Yes. Select gift wrapping at checkout for ₹99 and add a free message card. Prices are hidden on the packing slip for all gift orders.','fa-solid fa-box',5,'active'),
(4,'faq_cta','Still need help?','Send our support team a message and we''ll reply within 24 hours. CTA: Contact Us','fa-solid fa-life-ring',6,'active'),

-- ---------------- OFFERS ----------------
(5,'coupons','Coupon Codes','Copy a code and paste it at checkout.','fa-solid fa-ticket',1,'active'),
(5,'coupon_welcome10','10% Off Your First Order','New customers save on everything in the store.\nValid for new accounts','fa-solid fa-gift',2,'active'),
(5,'coupon_laptop500','Flat ₹500 Off Laptops','A little extra off our biggest gadget category.\nValid while stocks last','fa-solid fa-laptop',3,'active'),
(5,'coupon_freeship','Free Delivery','No delivery charge on orders above ₹499.\nValid on all addresses in India','fa-solid fa-truck-fast',4,'active'),
(5,'deals_of_day','Deals of the Day','Popular products at their lowest prices this week.','fa-solid fa-bolt',5,'active'),
(5,'bank_wallet_offers','Bank & Wallet Offers','Save more with the payment method you already use.','fa-solid fa-credit-card',6,'active'),
(5,'bank_hdfc','10% Instant Discount','On HDFC Bank credit cards. Maximum saving ₹1,000.','fa-solid fa-credit-card',7,'active'),
(5,'bank_upi','UPI Cashback','Get up to ₹200 cashback on your first UPI payment.','fa-solid fa-mobile-screen-button',8,'active'),
(5,'bank_app','App-Only Extra 5%','Additional 5% off when you shop through the TrendCart app.','fa-solid fa-mobile',9,'active'),
(5,'offers_terms','Offer Terms','Only one coupon can be used per order. Offers cannot be combined with each other.','fa-solid fa-circle-info',10,'active'),
(5,'offers_cta','Ready to grab a deal?','Browse the full collection and find something you''ll love.','fa-solid fa-bolt',11,'active'),

-- ---------------- SHOP ----------------
(6,'filters','Filters','Category · Price · Availability\nAll products (8), Electronics (4), Fashion (1), Gaming (1), Books (1)\nAny price, Under ₹1,000, ₹1,000 – ₹10,000, Above ₹10,000\nIn stock only, On sale, Reset Filters','fa-solid fa-sliders',1,'active'),
(6,'sorting','Sort By','Popularity · Price: Low to High · Price: High to Low · Newest Arrivals · Customer Rating','fa-solid fa-arrow-down-wide-short',2,'active'),
(6,'products','All Products','Showing 1–8 of 8 products.\nPulse Wireless Headphones · Nova Smartwatch 2 · Zenith 14 Laptop · ProPlay Wireless Controller · Everywear Cotton T-Shirt · SnapShot Digital Camera · PHP: From Zero to Hero · Aura Gift Hamper','fa-solid fa-box',3,'active'),
(6,'pagination','Pagination','Previous 1 2 3 Next','fa-solid fa-angles-right',4,'active');