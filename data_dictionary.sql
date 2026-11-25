-- =============================================================
-- MCA_2026 Simple Data Dictionary
-- 16 tables only. No logs, permissions or audit tables.
-- Money is stored in paise. 49900 = Rs. 499.00
--
-- Admin listing rule for every table:
-- Search + filter + sort + pagination.
-- Actions: View / Edit / Delete / Activate or Deactivate.
-- =============================================================

CREATE DATABASE IF NOT EXISTS MCA_2026
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE MCA_2026;

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Categories
CREATE TABLE categories (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL,
    icon_class VARCHAR(100) NOT NULL DEFAULT 'fa-box',
    display_order INT UNSIGNED NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_categories_slug (slug),
    KEY idx_categories_status (status),
    KEY idx_categories_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Products
CREATE TABLE products (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    category_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(160) NOT NULL,
    sku VARCHAR(60) NOT NULL,
    short_description VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price_paise INT UNSIGNED NOT NULL,
    old_price_paise INT UNSIGNED NULL DEFAULT NULL,
    stock_qty INT UNSIGNED NOT NULL DEFAULT 0,
    image_url VARCHAR(255) NULL DEFAULT NULL,
    image_icon VARCHAR(100) NOT NULL DEFAULT 'fa-box',
    rating_average DECIMAL(2,1) NOT NULL DEFAULT 0.0,
    review_count INT UNSIGNED NOT NULL DEFAULT 0,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_products_slug (slug),
    UNIQUE KEY uq_products_sku (sku),
    KEY idx_products_category (category_id),
    KEY idx_products_status (status),
    KEY idx_products_price (price_paise),
    KEY idx_products_stock (stock_qty),
    CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT chk_products_rating CHECK (rating_average BETWEEN 0 AND 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Users
CREATE TABLE users (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    fullname VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(30) NULL DEFAULT NULL,
    password_hash VARCHAR(255) NOT NULL,
    profile_picture VARCHAR(255) NULL DEFAULT NULL COMMENT 'File name saved under uploads/profile_pictures/',
    dob DATE NULL DEFAULT NULL,
    gender VARCHAR(20) NULL DEFAULT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'customer' COMMENT 'customer, admin',
    status VARCHAR(20) NOT NULL DEFAULT 'inactive' COMMENT 'active, inactive, blocked',
    token VARCHAR(255) NULL DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_email (email),
    UNIQUE KEY uq_users_phone (phone),
    KEY idx_users_role (role),
    KEY idx_users_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Addresses
CREATE TABLE addresses (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(100) NULL DEFAULT NULL,
    label VARCHAR(30) NOT NULL COMMENT 'Home, Office, Other',
    line1 VARCHAR(180) NOT NULL,
    line2 VARCHAR(180) NULL DEFAULT NULL,
    city VARCHAR(80) NOT NULL,
    state VARCHAR(80) NOT NULL,
    pin VARCHAR(10) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    is_default TINYINT(1) NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_addresses_user (user_id),
    KEY idx_addresses_status (status),
    CONSTRAINT fk_addresses_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Cart (one row per product)
CREATE TABLE carts (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NULL DEFAULT NULL,
    guest_token VARCHAR(100) NULL DEFAULT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    qty INT UNSIGNED NOT NULL DEFAULT 1,
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_carts_user_product (user_id, product_id),
    KEY idx_carts_guest (guest_token),
    KEY idx_carts_product (product_id),
    KEY idx_carts_status (status),
    CONSTRAINT fk_carts_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_carts_product FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Wishlist (one row per product)
CREATE TABLE wishlists (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NULL DEFAULT NULL,
    guest_token VARCHAR(100) NULL DEFAULT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_wishlists_user_product (user_id, product_id),
    KEY idx_wishlists_guest (guest_token),
    KEY idx_wishlists_product (product_id),
    KEY idx_wishlists_status (status),
    CONSTRAINT fk_wishlists_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_wishlists_product FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Coupons / offers / discounts
CREATE TABLE coupons (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    code VARCHAR(50) NOT NULL,
    title VARCHAR(150) NOT NULL,
    type VARCHAR(20) NOT NULL COMMENT 'percentage, fixed',
    value DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    min_order_paise INT UNSIGNED NOT NULL DEFAULT 0,
    starts_at DATETIME NULL DEFAULT NULL,
    ends_at DATETIME NULL DEFAULT NULL,
    usage_limit INT UNSIGNED NULL DEFAULT NULL,
    used_count INT UNSIGNED NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_coupons_code (code),
    KEY idx_coupons_status (status),
    KEY idx_coupons_end (ends_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Orders
CREATE TABLE orders (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    order_number VARCHAR(30) NOT NULL,
    user_id BIGINT UNSIGNED NULL DEFAULT NULL,
    coupon_code VARCHAR(50) NULL DEFAULT NULL,
    subtotal_paise INT UNSIGNED NOT NULL DEFAULT 0,
    discount_paise INT UNSIGNED NOT NULL DEFAULT 0,
    shipping_paise INT UNSIGNED NOT NULL DEFAULT 0,
    tax_paise INT UNSIGNED NOT NULL DEFAULT 0,
    total_paise INT UNSIGNED NOT NULL DEFAULT 0,
    payment_method VARCHAR(30) NOT NULL COMMENT 'upi, card, netbanking, wallet, cod',
    payment_status VARCHAR(20) NOT NULL DEFAULT 'pending' COMMENT 'pending, paid, failed, refunded',
    address_text TEXT NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'pending' COMMENT 'pending, processing, shipped, delivered, cancelled, returned',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_orders_number (order_number),
    KEY idx_orders_user (user_id),
    KEY idx_orders_status (status),
    KEY idx_orders_payment_status (payment_status),
    KEY idx_orders_created (created_at),
    CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Order items
CREATE TABLE order_items (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    order_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NULL DEFAULT NULL,
    name_snapshot VARCHAR(150) NOT NULL,
    qty INT UNSIGNED NOT NULL DEFAULT 1,
    price_paise INT UNSIGNED NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_order_items_order (order_id),
    KEY idx_order_items_product (product_id),
    KEY idx_order_items_status (status),
    CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_order_items_product FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Transactions
CREATE TABLE transactions (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    order_id BIGINT UNSIGNED NOT NULL,
    gateway VARCHAR(60) NOT NULL,
    method VARCHAR(30) NOT NULL,
    transaction_id VARCHAR(120) NULL DEFAULT NULL,
    amount_paise INT UNSIGNED NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pending' COMMENT 'pending, success, failed, refunded',
    paid_at DATETIME NULL DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_transactions_order (order_id),
    KEY idx_transactions_status (status),
    KEY idx_transactions_method (method),
    CONSTRAINT fk_transactions_order FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Product ratings and reviews
CREATE TABLE product_reviews (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    product_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NULL DEFAULT NULL,
    name VARCHAR(120) NOT NULL,
    rating TINYINT UNSIGNED NOT NULL,
    title VARCHAR(150) NULL DEFAULT NULL,
    body TEXT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pending' COMMENT 'pending, active, inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_reviews_product (product_id),
    KEY idx_reviews_user (user_id),
    KEY idx_reviews_rating (rating),
    KEY idx_reviews_status (status),
    CONSTRAINT fk_reviews_product FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_reviews_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT chk_reviews_rating CHECK (rating BETWEEN 1 AND 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Inquiries / contact messages
CREATE TABLE inquiries (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NULL DEFAULT NULL,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(30) NULL DEFAULT NULL,
    subject VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'open' COMMENT 'open, closed',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_inquiries_user (user_id),
    KEY idx_inquiries_status (status),
    KEY idx_inquiries_created (created_at),
    CONSTRAINT fk_inquiries_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. FAQs
CREATE TABLE faqs (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    topic VARCHAR(100) NOT NULL COMMENT 'Orders, Returns, Payment, Products',
    question VARCHAR(255) NOT NULL,
    answer TEXT NOT NULL,
    display_order INT UNSIGNED NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_faqs_topic (topic),
    KEY idx_faqs_status (status),
    KEY idx_faqs_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. Site settings / editable website details
CREATE TABLE site_settings (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    setting_key VARCHAR(100) NOT NULL COMMENT 'store_name, support_email, footer_text, hero_title',
    setting_value TEXT NOT NULL,
    setting_group VARCHAR(50) NOT NULL DEFAULT 'general' COMMENT 'general, contact, footer, home',
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_site_settings_key (setting_key),
    KEY idx_settings_group (setting_group),
    KEY idx_settings_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 15. Guest pages / static page content
CREATE TABLE pages (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    page_key VARCHAR(60) NOT NULL COMMENT 'home, about, contact, faq, offers, shop',
    page_name VARCHAR(120) NOT NULL COMMENT 'Home, About, Contact',
    title VARCHAR(180) NOT NULL,
    subtitle VARCHAR(255) NULL DEFAULT NULL,
    body TEXT NULL DEFAULT NULL,
    cta_label VARCHAR(80) NULL DEFAULT NULL,
    cta_url VARCHAR(255) NULL DEFAULT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_pages_key (page_key),
    KEY idx_pages_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Editable hero/intro content for every guest page';

-- 16. Reusable sections inside guest pages
CREATE TABLE page_sections (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    page_id BIGINT UNSIGNED NOT NULL,
    section_key VARCHAR(80) NOT NULL COMMENT 'trust, story, team, contact_info, hours',
    heading VARCHAR(180) NULL DEFAULT NULL,
    body TEXT NULL DEFAULT NULL,
    icon_class VARCHAR(100) NULL DEFAULT NULL,
    display_order INT UNSIGNED NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_page_sections_page_section (page_id, section_key),
    KEY idx_page_sections_page (page_id),
    KEY idx_page_sections_status (status),
    KEY idx_page_sections_order (display_order),
    CONSTRAINT fk_page_sections_page FOREIGN KEY (page_id) REFERENCES pages (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Repeatable content blocks such as About stats and Contact details';

-- 17. Password Reset OTP management
CREATE TABLE password_reset_otps (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(180) NOT NULL,
    otp VARCHAR(10) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_expires (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Active 2-minute OTPs for password recovery';

-- 18. Password reset attempt logs for 24-hour rate limiting
CREATE TABLE password_reset_attempts (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(180) NOT NULL,
    ip_address VARCHAR(45) NULL DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email_time (email, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tracks OTP generation attempts for 24-hour rate limiting';

SET FOREIGN_KEY_CHECKS = 1;

-- End of simplified MCA_2026 data dictionary.