-- =============================================================
-- MCA_2026 Data Dictionary / Database Schema
-- Version 1.0 - 20 November 2026
-- Blueprint for the admin-panel redesign. The current site is static.
-- Money is stored as INTEGER Paise. Example: 49900 = Rs. 499.00
-- =============================================================

CREATE DATABASE IF NOT EXISTS MCA_2026
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE MCA_2026;

SET FOREIGN_KEY_CHECKS = 0;

-- -------------------------------------------------------------
-- 1. Catalogue
-- -------------------------------------------------------------

CREATE TABLE categories (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL,
    parent_id BIGINT UNSIGNED NULL DEFAULT NULL,
    icon_class VARCHAR(100) NOT NULL DEFAULT 'fa-box',
    description TEXT NULL DEFAULT NULL,
    show_on_home TINYINT(1) NOT NULL DEFAULT 0,
    show_in_menu TINYINT(1) NOT NULL DEFAULT 1,
    display_order INT UNSIGNED NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'draft' COMMENT 'draft, active, inactive, archived',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_categories_slug (slug),
    KEY idx_categories_status (status),
    KEY idx_categories_parent (parent_id),
    KEY idx_categories_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Product collections shown in menu, filters and home tiles';

CREATE TABLE tax_classes (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    rate_percent DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    description TEXT NULL DEFAULT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_tax_classes_name (name),
    KEY idx_tax_classes_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='GST and other tax classes';

CREATE TABLE tags (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(80) NOT NULL,
    slug VARCHAR(100) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_tags_slug (slug),
    KEY idx_tags_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Reusable product tags';
CREATE TABLE products (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    category_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(160) NOT NULL,
    sku VARCHAR(60) NOT NULL,
    short_description VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price_paise INT UNSIGNED NOT NULL,
    compare_at_price_paise INT UNSIGNED NULL DEFAULT NULL,
    discount_percent TINYINT UNSIGNED NOT NULL DEFAULT 0,
    tax_class_id BIGINT UNSIGNED NULL DEFAULT NULL,
    stock_qty INT UNSIGNED NOT NULL DEFAULT 0,
    low_stock_threshold INT UNSIGNED NOT NULL DEFAULT 10,
    brand VARCHAR(100) NULL DEFAULT NULL,
    colour_name VARCHAR(60) NULL DEFAULT NULL,
    main_icon_class VARCHAR(100) NOT NULL DEFAULT 'fa-box',
    image_url VARCHAR(255) NULL DEFAULT NULL,
    rating_average DECIMAL(2,1) NOT NULL DEFAULT 0.0,
    review_count INT UNSIGNED NOT NULL DEFAULT 0,
    meta_title VARCHAR(180) NULL DEFAULT NULL,
    meta_description VARCHAR(300) NULL DEFAULT NULL,
    meta_keywords VARCHAR(300) NULL DEFAULT NULL,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    is_new TINYINT(1) NOT NULL DEFAULT 0,
    is_returnable TINYINT(1) NOT NULL DEFAULT 1,
    status VARCHAR(20) NOT NULL DEFAULT 'draft' COMMENT 'draft, active, inactive, archived',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_products_slug (slug),
    UNIQUE KEY uq_products_sku (sku),
    KEY idx_products_category (category_id),
    KEY idx_products_tax_class (tax_class_id),
    KEY idx_products_status (status),
    KEY idx_products_price (price_paise),
    KEY idx_products_stock (stock_qty),
    KEY idx_products_rating (rating_average),
    KEY idx_products_featured (is_featured),
    CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_products_tax_class FOREIGN KEY (tax_class_id) REFERENCES tax_classes (id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT chk_products_rating CHECK (rating_average BETWEEN 0 AND 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Storefront products and product-detail data';

CREATE TABLE product_images (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    product_id BIGINT UNSIGNED NOT NULL,
    icon_class VARCHAR(100) NOT NULL,
    image_url VARCHAR(255) NULL DEFAULT NULL,
    label VARCHAR(100) NOT NULL,
    alt_text VARCHAR(180) NULL DEFAULT NULL,
    display_order INT UNSIGNED NOT NULL DEFAULT 0,
    is_primary TINYINT(1) NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_product_images_product (product_id),
    KEY idx_product_images_order (display_order),
    KEY idx_product_images_status (status),
    CONSTRAINT fk_product_images_product FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Four gallery views per product';

CREATE TABLE product_features (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    product_id BIGINT UNSIGNED NOT NULL,
    feature_text VARCHAR(200) NOT NULL,
    display_order INT UNSIGNED NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_product_features_product (product_id),
    KEY idx_product_features_order (display_order),
    CONSTRAINT fk_product_features_product FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='What you get bullets on the detail page';

CREATE TABLE product_variants (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    product_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(100) NOT NULL,
    sku VARCHAR(60) NOT NULL,
    colour VARCHAR(50) NULL DEFAULT NULL,
    size VARCHAR(30) NULL DEFAULT NULL,
    price_paise INT UNSIGNED NOT NULL DEFAULT 0,
    stock_qty INT UNSIGNED NOT NULL DEFAULT 0,
    display_order INT UNSIGNED NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_product_variants_sku (sku),
    KEY idx_product_variants_product (product_id),
    KEY idx_product_variants_status (status),
    CONSTRAINT fk_product_variants_product FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Optional colour, size and model options';

CREATE TABLE product_tags (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    product_id BIGINT UNSIGNED NOT NULL,
    tag_id BIGINT UNSIGNED NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_product_tags_pair (product_id, tag_id),
    KEY idx_product_tags_tag (tag_id),
    CONSTRAINT fk_product_tags_product FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_product_tags_tag FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Many-to-many link between products and tags';
-- -------------------------------------------------------------
-- 2. Users, addresses, access and audit
-- -------------------------------------------------------------

CREATE TABLE users (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(80) NOT NULL,
    last_name VARCHAR(80) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(30) NULL DEFAULT NULL,
    password_hash VARCHAR(255) NOT NULL,
    date_of_birth DATE NULL DEFAULT NULL,
    gender VARCHAR(30) NULL DEFAULT NULL COMMENT 'Male, Female, Prefer not to say',
    avatar_url VARCHAR(255) NULL DEFAULT NULL,
    loyalty_tier VARCHAR(20) NOT NULL DEFAULT 'standard' COMMENT 'standard, silver, gold',
    reward_points INT UNSIGNED NOT NULL DEFAULT 0,
    email_verified_at DATETIME NULL DEFAULT NULL,
    last_login_at DATETIME NULL DEFAULT NULL,
    remember_token VARCHAR(100) NULL DEFAULT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pending' COMMENT 'pending, active, inactive, blocked',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_email (email),
    UNIQUE KEY uq_users_phone (phone),
    KEY idx_users_status (status),
    KEY idx_users_tier (loyalty_tier),
    KEY idx_users_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Customer accounts and profile details';

CREATE TABLE admin_roles (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(60) NOT NULL,
    description VARCHAR(255) NULL DEFAULT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_admin_roles_name (name),
    KEY idx_admin_roles_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Admin roles such as Super Admin and Support';

CREATE TABLE admin_users (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    role_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    avatar_url VARCHAR(255) NULL DEFAULT NULL,
    phone VARCHAR(30) NULL DEFAULT NULL,
    two_factor_enabled TINYINT(1) NOT NULL DEFAULT 0,
    last_login_at DATETIME NULL DEFAULT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pending' COMMENT 'pending, active, inactive, blocked',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_admin_users_email (email),
    KEY idx_admin_users_role (role_id),
    KEY idx_admin_users_status (status),
    CONSTRAINT fk_admin_users_role FOREIGN KEY (role_id) REFERENCES admin_roles (id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Admin panel users';

CREATE TABLE admin_permissions (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    role_id BIGINT UNSIGNED NOT NULL,
    module_key VARCHAR(80) NOT NULL COMMENT 'products, orders, users, content, settings',
    can_view TINYINT(1) NOT NULL DEFAULT 1,
    can_edit TINYINT(1) NOT NULL DEFAULT 0,
    can_delete TINYINT(1) NOT NULL DEFAULT 0,
    can_activate TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_admin_permissions_pair (role_id, module_key),
    CONSTRAINT fk_admin_permissions_role FOREIGN KEY (role_id) REFERENCES admin_roles (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Per-module admin permissions';

CREATE TABLE admin_activity_logs (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    admin_user_id BIGINT UNSIGNED NOT NULL,
    action VARCHAR(100) NOT NULL COMMENT 'product.updated, order.status_changed',
    module VARCHAR(60) NOT NULL,
    record_id BIGINT UNSIGNED NULL DEFAULT NULL,
    description VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45) NULL DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_admin_logs_admin (admin_user_id),
    KEY idx_admin_logs_module (module),
    KEY idx_admin_logs_created (created_at),
    CONSTRAINT fk_admin_logs_admin FOREIGN KEY (admin_user_id) REFERENCES admin_users (id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Immutable admin audit trail';

CREATE TABLE user_addresses (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    label VARCHAR(30) NOT NULL COMMENT 'Home, Office, Other',
    icon_class VARCHAR(100) NOT NULL DEFAULT 'fa-house',
    is_default TINYINT(1) NOT NULL DEFAULT 0,
    name VARCHAR(120) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    line1 VARCHAR(180) NOT NULL,
    line2 VARCHAR(180) NULL DEFAULT NULL,
    city VARCHAR(80) NOT NULL,
    state VARCHAR(80) NOT NULL,
    pin VARCHAR(10) NOT NULL,
    country VARCHAR(60) NOT NULL DEFAULT 'India',
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (id),
    KEY idx_user_addresses_user (user_id),
    KEY idx_user_addresses_status (status),
    KEY idx_user_addresses_pin (pin),
    CONSTRAINT fk_user_addresses_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Saved address book entries';

CREATE TABLE user_sessions (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    token_hash VARCHAR(255) NOT NULL,
    device VARCHAR(150) NULL DEFAULT NULL,
    ip_address VARCHAR(45) NULL DEFAULT NULL,
    last_activity_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    expires_at DATETIME NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_user_sessions_user (user_id),
    KEY idx_user_sessions_expiry (expires_at),
    CONSTRAINT fk_user_sessions_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Active login sessions';

CREATE TABLE password_reset_tokens (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    token_hash VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    used_at DATETIME NULL DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_password_tokens_user (user_id),
    KEY idx_password_tokens_expiry (expires_at),
    CONSTRAINT fk_password_tokens_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Password reset links from forgot-password.php';

-- -------------------------------------------------------------
-- 3. Inventory logs
-- -------------------------------------------------------------

CREATE TABLE inventory_logs (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    product_id BIGINT UNSIGNED NOT NULL,
    variant_id BIGINT UNSIGNED NULL DEFAULT NULL,
    change_qty INT NOT NULL COMMENT 'Positive = stock in, negative = stock out',
    reason VARCHAR(100) NOT NULL COMMENT 'purchase, sale, return, adjustment, damage',
    reference VARCHAR(100) NULL DEFAULT NULL,
    changed_by_admin_id BIGINT UNSIGNED NULL DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_inventory_product (product_id),
    KEY idx_inventory_variant (variant_id),
    KEY idx_inventory_reason (reason),
    KEY idx_inventory_created (created_at),
    CONSTRAINT fk_inventory_product FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_inventory_variant FOREIGN KEY (variant_id) REFERENCES product_variants (id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_inventory_admin FOREIGN KEY (changed_by_admin_id) REFERENCES admin_users (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stock movement history';
-- -------------------------------------------------------------
-- 4. Cart and wishlist
-- -------------------------------------------------------------

CREATE TABLE carts (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NULL DEFAULT NULL,
    guest_token VARCHAR(100) NULL DEFAULT NULL,
    coupon_code VARCHAR(50) NULL DEFAULT NULL COMMENT 'Snapshot code from checkout',
    currency CHAR(3) NOT NULL DEFAULT 'INR',
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, converted, abandoned',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_carts_user (user_id),
    KEY idx_carts_guest (guest_token),
    KEY idx_carts_status (status),
    KEY idx_carts_updated (updated_at),
    CONSTRAINT fk_carts_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Shopping cart header';

CREATE TABLE wishlists (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NULL DEFAULT NULL,
    guest_token VARCHAR(100) NULL DEFAULT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, cleared, converted',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_wishlists_user (user_id),
    KEY idx_wishlists_guest (guest_token),
    KEY idx_wishlists_status (status),
    CONSTRAINT fk_wishlists_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Wishlist header';

CREATE TABLE cart_items (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    cart_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    variant_id BIGINT UNSIGNED NULL DEFAULT NULL,
    qty INT UNSIGNED NOT NULL DEFAULT 1,
    unit_price_paise INT UNSIGNED NOT NULL,
    added_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_cart_items_cart (cart_id),
    KEY idx_cart_items_product (product_id),
    KEY idx_cart_items_variant (variant_id),
    CONSTRAINT fk_cart_items_cart FOREIGN KEY (cart_id) REFERENCES carts (id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_cart_items_product FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_cart_items_variant FOREIGN KEY (variant_id) REFERENCES product_variants (id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT chk_cart_items_qty CHECK (qty > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Products currently in a cart';

CREATE TABLE wishlist_items (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    wishlist_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    added_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    notified_price_drop TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    UNIQUE KEY uq_wishlist_items_pair (wishlist_id, product_id),
    KEY idx_wishlist_items_product (product_id),
    KEY idx_wishlist_items_added (added_at),
    CONSTRAINT fk_wishlist_items_wishlist FOREIGN KEY (wishlist_id) REFERENCES wishlists (id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_wishlist_items_product FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Saved products in a wishlist';

-- -------------------------------------------------------------
-- 5. Coupons, offers and promotions
-- -------------------------------------------------------------

CREATE TABLE coupons (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    code VARCHAR(50) NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT NULL DEFAULT NULL,
    type VARCHAR(30) NOT NULL COMMENT 'percentage, fixed, shipping',
    value DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    min_order_paise INT UNSIGNED NOT NULL DEFAULT 0,
    max_discount_paise INT UNSIGNED NULL DEFAULT NULL,
    usage_limit INT UNSIGNED NULL DEFAULT NULL,
    per_customer_limit INT UNSIGNED NOT NULL DEFAULT 1,
    used_count INT UNSIGNED NOT NULL DEFAULT 0,
    first_order_only TINYINT(1) NOT NULL DEFAULT 0,
    applicable_categories VARCHAR(255) NULL DEFAULT NULL COMMENT 'Comma-separated category IDs or all',
    applicable_products VARCHAR(255) NULL DEFAULT NULL,
    starts_at DATETIME NULL DEFAULT NULL,
    ends_at DATETIME NULL DEFAULT NULL,
    terms_note VARCHAR(255) NULL DEFAULT NULL,
    show_on_home TINYINT(1) NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'draft' COMMENT 'draft, active, inactive, expired',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_coupons_code (code),
    KEY idx_coupons_status (status),
    KEY idx_coupons_type (type),
    KEY idx_coupons_end (ends_at),
    KEY idx_coupons_home (show_on_home)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Coupon codes used on offers, cart and checkout';

CREATE TABLE deals (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(150) NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    deal_price_paise INT UNSIGNED NOT NULL,
    discount_percent TINYINT UNSIGNED NOT NULL DEFAULT 0,
    starts_at DATETIME NULL DEFAULT NULL,
    ends_at DATETIME NULL DEFAULT NULL,
    is_daily TINYINT(1) NOT NULL DEFAULT 0,
    display_order INT UNSIGNED NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'scheduled' COMMENT 'scheduled, active, inactive, expired',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_deals_product (product_id),
    KEY idx_deals_status (status),
    KEY idx_deals_order (display_order),
    CONSTRAINT fk_deals_product FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Deals of the day';

CREATE TABLE promo_banners (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(150) NOT NULL,
    text VARCHAR(255) NOT NULL,
    cta_label VARCHAR(80) NULL DEFAULT NULL,
    cta_url VARCHAR(255) NULL DEFAULT NULL,
    icon_class VARCHAR(100) NULL DEFAULT NULL,
    image_url VARCHAR(255) NULL DEFAULT NULL,
    starts_at DATETIME NULL DEFAULT NULL,
    ends_at DATETIME NULL DEFAULT NULL,
    display_order INT UNSIGNED NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'draft' COMMENT 'draft, active, inactive, expired',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_promo_banners_status (status),
    KEY idx_promo_banners_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Promotional banners';

CREATE TABLE payment_offers (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(150) NOT NULL,
    description VARCHAR(255) NOT NULL,
    provider VARCHAR(80) NOT NULL,
    icon_class VARCHAR(100) NOT NULL,
    terms_note VARCHAR(255) NULL DEFAULT NULL,
    display_order INT UNSIGNED NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_payment_offers_provider (provider),
    KEY idx_payment_offers_status (status),
    KEY idx_payment_offers_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bank and wallet offers on offers.php';

CREATE TABLE payment_method_options (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    code VARCHAR(30) NOT NULL COMMENT 'upi, card, netbanking, wallet, cod',
    label VARCHAR(100) NOT NULL,
    description VARCHAR(180) NULL DEFAULT NULL,
    icon_class VARCHAR(100) NOT NULL,
    display_order INT UNSIGNED NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_payment_methods_code (code),
    KEY idx_payment_methods_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Checkout payment radio options';
-- -------------------------------------------------------------
-- 6. Orders, returns and transactions
-- -------------------------------------------------------------

CREATE TABLE orders (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    order_number VARCHAR(30) NOT NULL,
    user_id BIGINT UNSIGNED NULL DEFAULT NULL,
    guest_email VARCHAR(150) NULL DEFAULT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'pending' COMMENT 'pending, processing, packed, shipped, out_for_delivery, delivered, cancelled, returned',
    payment_status VARCHAR(30) NOT NULL DEFAULT 'pending' COMMENT 'pending, authorized, captured, failed, refunded, partially_refunded',
    coupon_id BIGINT UNSIGNED NULL DEFAULT NULL,
    coupon_code VARCHAR(50) NULL DEFAULT NULL COMMENT 'Code snapshot, kept even if coupon is removed',
    subtotal_paise INT UNSIGNED NOT NULL DEFAULT 0,
    product_discount_paise INT UNSIGNED NOT NULL DEFAULT 0,
    coupon_discount_paise INT UNSIGNED NOT NULL DEFAULT 0,
    shipping_paise INT UNSIGNED NOT NULL DEFAULT 0,
    tax_paise INT UNSIGNED NOT NULL DEFAULT 0,
    cod_fee_paise INT UNSIGNED NOT NULL DEFAULT 0,
    gift_wrap_paise INT UNSIGNED NOT NULL DEFAULT 0,
    total_paise INT UNSIGNED NOT NULL DEFAULT 0,
    saved_paise INT UNSIGNED NOT NULL DEFAULT 0,
    currency CHAR(3) NOT NULL DEFAULT 'INR',
    delivery_note TEXT NULL DEFAULT NULL,
    shipping_name VARCHAR(120) NOT NULL,
    shipping_phone VARCHAR(30) NOT NULL,
    shipping_line1 VARCHAR(180) NOT NULL,
    shipping_line2 VARCHAR(180) NULL DEFAULT NULL,
    shipping_city VARCHAR(80) NOT NULL,
    shipping_state VARCHAR(80) NOT NULL,
    shipping_pin VARCHAR(10) NOT NULL,
    shipping_country VARCHAR(60) NOT NULL DEFAULT 'India',
    tracking_id VARCHAR(100) NULL DEFAULT NULL,
    courier_name VARCHAR(100) NULL DEFAULT NULL,
    placed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    delivered_at DATETIME NULL DEFAULT NULL,
    cancelled_at DATETIME NULL DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_orders_number (order_number),
    KEY idx_orders_user (user_id),
    KEY idx_orders_status (status),
    KEY idx_orders_payment_status (payment_status),
    KEY idx_orders_coupon (coupon_id),
    KEY idx_orders_tracking (tracking_id),
    KEY idx_orders_placed (placed_at),
    CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_orders_coupon FOREIGN KEY (coupon_id) REFERENCES coupons (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Order headers and delivery snapshots';

CREATE TABLE order_items (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    order_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NULL DEFAULT NULL,
    variant_id BIGINT UNSIGNED NULL DEFAULT NULL,
    product_name_snapshot VARCHAR(150) NOT NULL,
    sku_snapshot VARCHAR(60) NOT NULL,
    icon_class VARCHAR(100) NOT NULL,
    colour_snapshot VARCHAR(60) NULL DEFAULT NULL,
    qty INT UNSIGNED NOT NULL DEFAULT 1,
    unit_price_paise INT UNSIGNED NOT NULL,
    line_total_paise INT UNSIGNED NOT NULL,
    return_status VARCHAR(30) NOT NULL DEFAULT 'none' COMMENT 'none, requested, approved, rejected, returned',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_order_items_order (order_id),
    KEY idx_order_items_product (product_id),
    KEY idx_order_items_variant (variant_id),
    KEY idx_order_items_return (return_status),
    CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_order_items_product FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_order_items_variant FOREIGN KEY (variant_id) REFERENCES product_variants (id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT chk_order_items_qty CHECK (qty > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Snapshot of each product inside an order';

CREATE TABLE order_status_history (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    order_id BIGINT UNSIGNED NOT NULL,
    status VARCHAR(30) NOT NULL,
    note VARCHAR(255) NULL DEFAULT NULL,
    changed_by_admin_id BIGINT UNSIGNED NULL DEFAULT NULL,
    changed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_order_history_order (order_id),
    KEY idx_order_history_status (status),
    KEY idx_order_history_changed (changed_at),
    CONSTRAINT fk_order_history_order FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_order_history_admin FOREIGN KEY (changed_by_admin_id) REFERENCES admin_users (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Immutable delivery timeline';

CREATE TABLE returns (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    return_number VARCHAR(30) NOT NULL,
    order_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NULL DEFAULT NULL,
    reason VARCHAR(255) NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'requested' COMMENT 'requested, approved, rejected, picked_up, refunded, closed',
    handled_by_admin_id BIGINT UNSIGNED NULL DEFAULT NULL,
    admin_note TEXT NULL DEFAULT NULL,
    requested_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    resolved_at DATETIME NULL DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_returns_number (return_number),
    KEY idx_returns_order (order_id),
    KEY idx_returns_status (status),
    KEY idx_returns_requested (requested_at),
    CONSTRAINT fk_returns_order FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_returns_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_returns_admin FOREIGN KEY (handled_by_admin_id) REFERENCES admin_users (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Return requests raised from My Orders or support';

CREATE TABLE return_items (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    return_id BIGINT UNSIGNED NOT NULL,
    order_item_id BIGINT UNSIGNED NOT NULL,
    qty INT UNSIGNED NOT NULL DEFAULT 1,
    item_condition VARCHAR(50) NULL DEFAULT NULL,
    refund_amount_paise INT UNSIGNED NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_return_items_return (return_id),
    KEY idx_return_items_order_item (order_item_id),
    CONSTRAINT fk_return_items_return FOREIGN KEY (return_id) REFERENCES returns (id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_return_items_order_item FOREIGN KEY (order_item_id) REFERENCES order_items (id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Products included in a return request';

CREATE TABLE transactions (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    order_id BIGINT UNSIGNED NOT NULL,
    gateway VARCHAR(60) NOT NULL,
    method VARCHAR(30) NOT NULL COMMENT 'upi, card, netbanking, wallet, cod',
    gateway_transaction_id VARCHAR(120) NULL DEFAULT NULL,
    bank_reference VARCHAR(120) NULL DEFAULT NULL,
    amount_paise INT UNSIGNED NOT NULL,
    currency CHAR(3) NOT NULL DEFAULT 'INR',
    status VARCHAR(30) NOT NULL DEFAULT 'pending' COMMENT 'pending, authorized, captured, failed, refunded, partially_refunded',
    is_cod TINYINT(1) NOT NULL DEFAULT 0,
    failure_reason VARCHAR(255) NULL DEFAULT NULL,
    paid_at DATETIME NULL DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_transactions_gateway_id (gateway_transaction_id),
    KEY idx_transactions_order (order_id),
    KEY idx_transactions_method (method),
    KEY idx_transactions_status (status),
    KEY idx_transactions_paid (paid_at),
    CONSTRAINT fk_transactions_order FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Payment gateway transaction details';

CREATE TABLE refunds (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    transaction_id BIGINT UNSIGNED NOT NULL,
    order_id BIGINT UNSIGNED NOT NULL,
    amount_paise INT UNSIGNED NOT NULL,
    reason VARCHAR(255) NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'requested' COMMENT 'requested, approved, processed, failed',
    requested_by_admin_id BIGINT UNSIGNED NULL DEFAULT NULL,
    processed_by_admin_id BIGINT UNSIGNED NULL DEFAULT NULL,
    processed_at DATETIME NULL DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_refunds_transaction (transaction_id),
    KEY idx_refunds_order (order_id),
    KEY idx_refunds_status (status),
    CONSTRAINT fk_refunds_transaction FOREIGN KEY (transaction_id) REFERENCES transactions (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_refunds_order FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_refunds_requested_by FOREIGN KEY (requested_by_admin_id) REFERENCES admin_users (id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_refunds_processed_by FOREIGN KEY (processed_by_admin_id) REFERENCES admin_users (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Refund workflow and amounts';

CREATE TABLE coupon_usages (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    coupon_id BIGINT UNSIGNED NOT NULL,
    order_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NULL DEFAULT NULL,
    discount_paise INT UNSIGNED NOT NULL DEFAULT 0,
    used_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_coupon_usages_order (order_id),
    KEY idx_coupon_usages_coupon (coupon_id),
    KEY idx_coupon_usages_user (user_id),
    KEY idx_coupon_usages_used (used_at),
    CONSTRAINT fk_coupon_usages_coupon FOREIGN KEY (coupon_id) REFERENCES coupons (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_coupon_usages_order FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_coupon_usages_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='One coupon usage per order';
-- -------------------------------------------------------------
-- 7. Ratings, reviews and inquiries
-- -------------------------------------------------------------

CREATE TABLE product_reviews (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    product_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NULL DEFAULT NULL,
    order_item_id BIGINT UNSIGNED NULL DEFAULT NULL,
    reviewer_name VARCHAR(120) NOT NULL,
    reviewer_email VARCHAR(150) NULL DEFAULT NULL,
    rating TINYINT UNSIGNED NOT NULL,
    title VARCHAR(150) NULL DEFAULT NULL,
    body TEXT NOT NULL,
    is_verified_purchase TINYINT(1) NOT NULL DEFAULT 0,
    helpful_count INT UNSIGNED NOT NULL DEFAULT 0,
    admin_reply TEXT NULL DEFAULT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pending' COMMENT 'pending, active, inactive, rejected, archived',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (id),
    KEY idx_product_reviews_product (product_id),
    KEY idx_product_reviews_user (user_id),
    KEY idx_product_reviews_rating (rating),
    KEY idx_product_reviews_status (status),
    KEY idx_product_reviews_created (created_at),
    CONSTRAINT fk_product_reviews_product FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_product_reviews_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_product_reviews_order_item FOREIGN KEY (order_item_id) REFERENCES order_items (id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT chk_product_reviews_rating CHECK (rating BETWEEN 1 AND 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Individual product ratings and reviews';

CREATE TABLE review_votes (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    review_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NULL DEFAULT NULL,
    vote TINYINT NOT NULL COMMENT '1 = helpful, -1 = not helpful',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_review_votes_pair (review_id, user_id),
    KEY idx_review_votes_review (review_id),
    CONSTRAINT fk_review_votes_review FOREIGN KEY (review_id) REFERENCES product_reviews (id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_review_votes_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT chk_review_votes_value CHECK (vote IN (-1, 1))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Helpful votes on reviews';

CREATE TABLE inquiries (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    reference_no VARCHAR(30) NOT NULL,
    user_id BIGINT UNSIGNED NULL DEFAULT NULL,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(30) NULL DEFAULT NULL,
    order_number VARCHAR(30) NULL DEFAULT NULL COMMENT 'Optional guest-provided order number',
    subject VARCHAR(30) NOT NULL COMMENT 'order, return, product, payment, other',
    message TEXT NOT NULL,
    priority VARCHAR(20) NOT NULL DEFAULT 'normal' COMMENT 'low, normal, high, urgent',
    status VARCHAR(20) NOT NULL DEFAULT 'open' COMMENT 'open, in_progress, resolved, closed, spam',
    assigned_admin_id BIGINT UNSIGNED NULL DEFAULT NULL,
    admin_reply TEXT NULL DEFAULT NULL,
    replied_at DATETIME NULL DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_inquiries_reference (reference_no),
    KEY idx_inquiries_email (email),
    KEY idx_inquiries_order_number (order_number),
    KEY idx_inquiries_subject (subject),
    KEY idx_inquiries_status (status),
    KEY idx_inquiries_priority (priority),
    KEY idx_inquiries_created (created_at),
    CONSTRAINT fk_inquiries_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_inquiries_admin FOREIGN KEY (assigned_admin_id) REFERENCES admin_users (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Contact form messages and support inbox';

-- -------------------------------------------------------------
-- 8. FAQ and help centre
-- -------------------------------------------------------------

CREATE TABLE faq_topics (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL,
    icon_class VARCHAR(100) NOT NULL,
    button_style VARCHAR(40) NOT NULL DEFAULT 'outline-primary',
    display_order INT UNSIGNED NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_faq_topics_slug (slug),
    KEY idx_faq_topics_order (display_order),
    KEY idx_faq_topics_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='FAQ groups on faq.php';

CREATE TABLE faqs (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    topic_id BIGINT UNSIGNED NOT NULL,
    question VARCHAR(255) NOT NULL,
    answer TEXT NOT NULL,
    display_order INT UNSIGNED NOT NULL DEFAULT 0,
    helpful_count INT UNSIGNED NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'draft' COMMENT 'draft, published, inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (id),
    KEY idx_faqs_topic (topic_id),
    KEY idx_faqs_status (status),
    KEY idx_faqs_order (display_order),
    CONSTRAINT fk_faqs_topic FOREIGN KEY (topic_id) REFERENCES faq_topics (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Accordion questions and answers';
-- -------------------------------------------------------------
-- 9. Site settings and content blocks
-- -------------------------------------------------------------

CREATE TABLE site_settings (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    setting_key VARCHAR(100) NOT NULL COMMENT 'Dot notation, for example store.support_email',
    setting_group VARCHAR(60) NOT NULL COMMENT 'store, currency, delivery, payment, notification, seo, social',
    setting_value TEXT NOT NULL,
    value_type VARCHAR(30) NOT NULL DEFAULT 'string' COMMENT 'string, int, decimal, boolean, json',
    is_public TINYINT(1) NOT NULL DEFAULT 1,
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
    updated_by_admin_id BIGINT UNSIGNED NULL DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_site_settings_key (setting_key),
    KEY idx_site_settings_group (setting_group),
    KEY idx_site_settings_status (status),
    CONSTRAINT fk_site_settings_admin FOREIGN KEY (updated_by_admin_id) REFERENCES admin_users (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Global store configuration';

CREATE TABLE content_blocks (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    block_key VARCHAR(100) NOT NULL COMMENT 'For example home.hero.title or about.story',
    page VARCHAR(60) NOT NULL COMMENT 'home, shop, product, offers, about, contact, faq, global',
    section VARCHAR(80) NOT NULL COMMENT 'hero, trust_strip, story, footer',
    eyebrow VARCHAR(120) NULL DEFAULT NULL,
    title VARCHAR(255) NULL DEFAULT NULL,
    body TEXT NULL DEFAULT NULL,
    cta_label VARCHAR(100) NULL DEFAULT NULL,
    cta_url VARCHAR(255) NULL DEFAULT NULL,
    secondary_cta_label VARCHAR(100) NULL DEFAULT NULL,
    secondary_cta_url VARCHAR(255) NULL DEFAULT NULL,
    meta TEXT NULL DEFAULT NULL COMMENT 'JSON extras such as coupon code or rating text',
    display_order INT UNSIGNED NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'published' COMMENT 'draft, published, inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_content_blocks_key (block_key),
    KEY idx_content_blocks_page (page),
    KEY idx_content_blocks_status (status),
    KEY idx_content_blocks_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Editable text blocks for every public page';

CREATE TABLE content_block_items (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    block_id BIGINT UNSIGNED NOT NULL,
    icon_class VARCHAR(100) NOT NULL,
    title VARCHAR(120) NOT NULL,
    text VARCHAR(255) NULL DEFAULT NULL,
    link_url VARCHAR(255) NULL DEFAULT NULL,
    colour_class VARCHAR(60) NULL DEFAULT NULL,
    display_order INT UNSIGNED NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_content_block_items_block (block_id),
    KEY idx_content_block_items_order (display_order),
    KEY idx_content_block_items_status (status),
    CONSTRAINT fk_content_block_items_block FOREIGN KEY (block_id) REFERENCES content_blocks (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Repeated small cards inside content blocks';

CREATE TABLE about_stats (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    value VARCHAR(30) NOT NULL COMMENT '50K+, 2,000+, 4.8/5, 24/7',
    label VARCHAR(120) NOT NULL COMMENT 'Happy customers, Products delivered',
    icon_class VARCHAR(100) NULL DEFAULT NULL,
    colour_class VARCHAR(60) NULL DEFAULT NULL,
    display_order INT UNSIGNED NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_about_stats_order (display_order),
    KEY idx_about_stats_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Four number cards on about.php';

CREATE TABLE team_members (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(120) NOT NULL,
    role VARCHAR(120) NOT NULL,
    bio VARCHAR(255) NULL DEFAULT NULL,
    initials VARCHAR(10) NOT NULL,
    avatar_url VARCHAR(255) NULL DEFAULT NULL,
    display_order INT UNSIGNED NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_team_members_order (display_order),
    KEY idx_team_members_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Meet the Team cards on about.php';

CREATE TABLE home_category_tiles (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    icon_class VARCHAR(100) NOT NULL,
    colour_class VARCHAR(60) NOT NULL DEFAULT 'text-primary',
    category_id BIGINT UNSIGNED NULL DEFAULT NULL,
    target_url VARCHAR(255) NOT NULL DEFAULT 'product.php',
    display_order INT UNSIGNED NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_home_tiles_order (display_order),
    KEY idx_home_tiles_status (status),
    CONSTRAINT fk_home_tiles_category FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Six Shop by Category tiles on the home page';

CREATE TABLE footer_link_groups (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL COMMENT 'Shop, Customer Care, Company, Stay Connected',
    display_order INT UNSIGNED NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_footer_groups_order (display_order),
    KEY idx_footer_groups_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Footer column headings';

CREATE TABLE footer_links (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    group_id BIGINT UNSIGNED NOT NULL,
    label VARCHAR(120) NOT NULL,
    url VARCHAR(255) NOT NULL,
    icon_class VARCHAR(100) NULL DEFAULT NULL,
    display_order INT UNSIGNED NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_footer_links_group (group_id),
    KEY idx_footer_links_order (display_order),
    KEY idx_footer_links_status (status),
    CONSTRAINT fk_footer_links_group FOREIGN KEY (group_id) REFERENCES footer_link_groups (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Footer links from layout.php';

CREATE TABLE newsletter_subscribers (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    email VARCHAR(150) NOT NULL,
    source VARCHAR(50) NOT NULL DEFAULT 'footer',
    status VARCHAR(20) NOT NULL DEFAULT 'subscribed' COMMENT 'subscribed, unsubscribed',
    subscribed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    unsubscribed_at DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_newsletter_email (email),
    KEY idx_newsletter_status (status),
    KEY idx_newsletter_source (source)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Footer newsletter signups';

CREATE TABLE policies (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    slug VARCHAR(100) NOT NULL COMMENT 'terms, privacy, returns, shipping',
    title VARCHAR(150) NOT NULL,
    body LONGTEXT NOT NULL,
    version VARCHAR(20) NOT NULL DEFAULT '1.0',
    effective_from DATE NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'draft' COMMENT 'draft, published, inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_policies_slug (slug),
    KEY idx_policies_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Terms, privacy, returns and shipping pages';

CREATE TABLE notifications (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    audience VARCHAR(20) NOT NULL COMMENT 'user, admin',
    recipient_id BIGINT UNSIGNED NOT NULL COMMENT 'users.id or admin_users.id depending on audience',
    title VARCHAR(150) NOT NULL,
    body VARCHAR(255) NOT NULL,
    type VARCHAR(30) NOT NULL COMMENT 'order, stock, offer, inquiry, system',
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    link_url VARCHAR(255) NULL DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_notifications_recipient (audience, recipient_id),
    KEY idx_notifications_type (type),
    KEY idx_notifications_read (is_read),
    KEY idx_notifications_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='In-app notification feed';

-- -------------------------------------------------------------
-- 10. Self-reference added after all base tables exist
-- -------------------------------------------------------------

ALTER TABLE categories
    ADD CONSTRAINT fk_categories_parent
    FOREIGN KEY (parent_id) REFERENCES categories (id)
    ON DELETE SET NULL
    ON UPDATE CASCADE;

SET FOREIGN_KEY_CHECKS = 1;

-- End of MCA_2026 data dictionary schema.