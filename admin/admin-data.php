<?php
/**
 * admin-data.php - Table definitions for the generic admin CRUD engine.
 * Only columns listed here can be searched, sorted, inserted or updated.
 */

$admin_status_options = [
    'active'   => 'Active',
    'inactive' => 'Inactive',
];

$admin_tables = [
    'categories' => [
        'table' => 'categories', 'title' => 'Categories', 'singular' => 'Category',
        'icon' => 'fa-layer-group', 'nav_key' => 'categories',
        'columns' => [
            'id' => ['label' => 'ID', 'type' => 'number', 'auto' => true, 'list' => true],
            'name' => ['label' => 'Name', 'type' => 'text', 'required' => true, 'list' => true],
            'slug' => ['label' => 'Slug', 'type' => 'text', 'required' => true, 'list' => true],
            'icon_class' => ['label' => 'Icon class', 'type' => 'text'],
            'display_order' => ['label' => 'Display order', 'type' => 'number', 'list' => true],
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => $admin_status_options, 'list' => true],
        ],
        'search' => ['name', 'slug'],
    ],

    'products' => [
        'table' => 'products', 'title' => 'Products', 'singular' => 'Product',
        'icon' => 'fa-box', 'nav_key' => 'products',
        'columns' => [
            'id' => ['label' => 'ID', 'type' => 'number', 'auto' => true, 'list' => true],
            'category_id' => ['label' => 'Category', 'type' => 'lookup', 'required' => true, 'list' => true,
                'lookup' => ['table' => 'categories', 'value' => 'id', 'label' => 'name']],
            'name' => ['label' => 'Name', 'type' => 'text', 'required' => true, 'list' => true],
            'slug' => ['label' => 'Slug', 'type' => 'text', 'required' => true],
            'sku' => ['label' => 'SKU', 'type' => 'text', 'required' => true, 'list' => true],
            'short_description' => ['label' => 'Short description', 'type' => 'text', 'required' => true],
            'description' => ['label' => 'Description', 'type' => 'textarea', 'required' => true],
            'price_paise' => ['label' => 'Price (paise)', 'type' => 'number', 'format' => 'money', 'required' => true, 'list' => true],
            'old_price_paise' => ['label' => 'Old price (paise)', 'type' => 'number', 'format' => 'money'],
            'stock_qty' => ['label' => 'Stock', 'type' => 'number', 'required' => true, 'list' => true],
            'image_url' => ['label' => 'Image URL', 'type' => 'text'],
            'image_icon' => ['label' => 'Icon class', 'type' => 'text'],
            'rating_average' => ['label' => 'Rating', 'type' => 'number'],
            'review_count' => ['label' => 'Review count', 'type' => 'number'],
            'is_featured' => ['label' => 'Featured', 'type' => 'select', 'options' => ['1' => 'Yes', '0' => 'No']],
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => $admin_status_options, 'list' => true],
        ],
        'search' => ['name', 'sku', 'slug'],
    ],

    'users' => [
        'table' => 'users', 'title' => 'Customers', 'singular' => 'Customer',
        'icon' => 'fa-users', 'nav_key' => 'customers',
        'columns' => [
            'id' => ['label' => 'ID', 'type' => 'number', 'auto' => true, 'list' => true],
            'fullname' => ['label' => 'Full name', 'type' => 'text', 'required' => true, 'list' => true],
            'email' => ['label' => 'Email', 'type' => 'email', 'required' => true, 'list' => true],
            'phone' => ['label' => 'Phone', 'type' => 'text'],
            'password_hash' => ['label' => 'Password hash', 'type' => 'password', 'required' => true],
            'profile_picture' => ['label' => 'Profile picture file', 'type' => 'text'],
            'role' => ['label' => 'Role', 'type' => 'select', 'options' => ['customer' => 'Customer', 'admin' => 'Admin'], 'list' => true],
            'status' => ['label' => 'Status', 'type' => 'select',
                'options' => ['active' => 'Active', 'inactive' => 'Inactive', 'blocked' => 'Blocked'], 'list' => true],
        ],
        'search' => ['fullname', 'email', 'phone'],
        'row_actions' => ['cart', 'wishlist'],
    ],

    'addresses' => [
        'table' => 'addresses', 'title' => 'Addresses', 'singular' => 'Address',
        'icon' => 'fa-location-dot', 'nav_key' => 'addresses',
        'columns' => [
            'id' => ['label' => 'ID', 'type' => 'number', 'auto' => true, 'list' => true],
            'user_id' => ['label' => 'Customer', 'type' => 'lookup', 'required' => true, 'list' => true,
                'lookup' => ['table' => 'users', 'value' => 'id', 'label' => 'fullname']],
            'label' => ['label' => 'Label', 'type' => 'text', 'required' => true, 'list' => true],
            'line1' => ['label' => 'Address line', 'type' => 'text', 'required' => true],
            'city' => ['label' => 'City', 'type' => 'text', 'required' => true, 'list' => true],
            'state' => ['label' => 'State', 'type' => 'text', 'required' => true],
            'pin' => ['label' => 'PIN', 'type' => 'text', 'required' => true, 'list' => true],
            'phone' => ['label' => 'Phone', 'type' => 'text', 'required' => true],
            'is_default' => ['label' => 'Default', 'type' => 'select', 'options' => ['1' => 'Yes', '0' => 'No']],
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => $admin_status_options, 'list' => true],
        ],
        'search' => ['label', 'city', 'pin', 'phone'],
    ],

    'carts' => [
        'table' => 'carts', 'title' => 'Carts', 'singular' => 'Cart item',
        'icon' => 'fa-cart-shopping', 'nav_key' => 'carts',
        'columns' => [
            'id' => ['label' => 'ID', 'type' => 'number', 'auto' => true, 'list' => true],
            'user_id' => ['label' => 'Customer', 'type' => 'lookup',
                'lookup' => ['table' => 'users', 'value' => 'id', 'label' => 'fullname'], 'list' => true],
            'guest_token' => ['label' => 'Guest token', 'type' => 'text'],
            'product_id' => ['label' => 'Product', 'type' => 'lookup', 'required' => true, 'list' => true,
                'lookup' => ['table' => 'products', 'value' => 'id', 'label' => 'name']],
            'qty' => ['label' => 'Quantity', 'type' => 'number', 'required' => true, 'list' => true],
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => $admin_status_options, 'list' => true],
        ],
        'search' => ['guest_token'],
    ],

    'wishlists' => [
        'table' => 'wishlists', 'title' => 'Wishlists', 'singular' => 'Wishlist item',
        'icon' => 'fa-heart', 'nav_key' => 'wishlists',
        'columns' => [
            'id' => ['label' => 'ID', 'type' => 'number', 'auto' => true, 'list' => true],
            'user_id' => ['label' => 'Customer', 'type' => 'lookup',
                'lookup' => ['table' => 'users', 'value' => 'id', 'label' => 'fullname'], 'list' => true],
            'guest_token' => ['label' => 'Guest token', 'type' => 'text'],
            'product_id' => ['label' => 'Product', 'type' => 'lookup', 'required' => true, 'list' => true,
                'lookup' => ['table' => 'products', 'value' => 'id', 'label' => 'name']],
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => $admin_status_options, 'list' => true],
        ],
        'search' => ['guest_token'],
    ],

    'coupons' => [
        'table' => 'coupons', 'title' => 'Offers & Coupons', 'singular' => 'Coupon',
        'icon' => 'fa-ticket', 'nav_key' => 'offers',
        'columns' => [
            'id' => ['label' => 'ID', 'type' => 'number', 'auto' => true, 'list' => true],
            'code' => ['label' => 'Code', 'type' => 'text', 'required' => true, 'list' => true],
            'title' => ['label' => 'Title', 'type' => 'text', 'required' => true, 'list' => true],
            'type' => ['label' => 'Type', 'type' => 'select', 'options' => ['percentage' => 'Percentage', 'fixed' => 'Fixed amount']],
            'value' => ['label' => 'Value', 'type' => 'number', 'required' => true, 'list' => true],
            'min_order_paise' => ['label' => 'Min order (paise)', 'type' => 'number', 'format' => 'money'],
            'starts_at' => ['label' => 'Starts at', 'type' => 'text'],
            'ends_at' => ['label' => 'Ends at', 'type' => 'text'],
            'usage_limit' => ['label' => 'Usage limit', 'type' => 'number'],
            'used_count' => ['label' => 'Used', 'type' => 'number', 'list' => true],
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => $admin_status_options, 'list' => true],
        ],
        'search' => ['code', 'title'],
    ],

    'orders' => [
        'table' => 'orders', 'title' => 'Orders', 'singular' => 'Order',
        'icon' => 'fa-box-archive', 'nav_key' => 'orders',
        'columns' => [
            'id' => ['label' => 'ID', 'type' => 'number', 'auto' => true, 'list' => true],
            'order_number' => ['label' => 'Order no.', 'type' => 'text', 'required' => true, 'list' => true],
            'user_id' => ['label' => 'Customer', 'type' => 'lookup',
                'lookup' => ['table' => 'users', 'value' => 'id', 'label' => 'fullname'], 'list' => true],
            'coupon_code' => ['label' => 'Coupon', 'type' => 'text'],
            'subtotal_paise' => ['label' => 'Subtotal (paise)', 'type' => 'number', 'format' => 'money'],
            'discount_paise' => ['label' => 'Discount (paise)', 'type' => 'number', 'format' => 'money'],
            'shipping_paise' => ['label' => 'Shipping (paise)', 'type' => 'number', 'format' => 'money'],
            'tax_paise' => ['label' => 'Tax (paise)', 'type' => 'number', 'format' => 'money'],
            'total_paise' => ['label' => 'Total', 'type' => 'number', 'format' => 'money', 'required' => true, 'list' => true],
            'payment_method' => ['label' => 'Payment method', 'type' => 'select',
                'options' => ['upi' => 'UPI', 'card' => 'Card', 'netbanking' => 'Netbanking', 'wallet' => 'Wallet', 'cod' => 'COD']],
            'payment_status' => ['label' => 'Payment status', 'type' => 'select',
                'options' => ['pending' => 'Pending', 'paid' => 'Paid', 'failed' => 'Failed', 'refunded' => 'Refunded'], 'list' => true],
            'address_text' => ['label' => 'Delivery address', 'type' => 'textarea', 'required' => true],
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => [
                'pending' => 'Pending', 'processing' => 'Processing', 'shipped' => 'Shipped',
                'delivered' => 'Delivered', 'cancelled' => 'Cancelled', 'returned' => 'Returned',
            ], 'list' => true],
        ],
        'search' => ['order_number', 'coupon_code'],
        'active_value' => 'delivered', 'inactive_value' => 'cancelled',
    ],

    'order_items' => [
        'table' => 'order_items', 'title' => 'Order Items', 'singular' => 'Order item',
        'icon' => 'fa-list-check', 'nav_key' => 'order-items',
        'columns' => [
            'id' => ['label' => 'ID', 'type' => 'number', 'auto' => true, 'list' => true],
            'order_id' => ['label' => 'Order', 'type' => 'lookup', 'required' => true, 'list' => true,
                'lookup' => ['table' => 'orders', 'value' => 'id', 'label' => 'order_number']],
            'product_id' => ['label' => 'Product', 'type' => 'lookup',
                'lookup' => ['table' => 'products', 'value' => 'id', 'label' => 'name'], 'list' => true],
            'name_snapshot' => ['label' => 'Product name', 'type' => 'text', 'required' => true, 'list' => true],
            'qty' => ['label' => 'Qty', 'type' => 'number', 'required' => true, 'list' => true],
            'price_paise' => ['label' => 'Price', 'type' => 'number', 'format' => 'money', 'required' => true, 'list' => true],
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => $admin_status_options, 'list' => true],
        ],
        'search' => ['name_snapshot'],
    ],

    'transactions' => [
        'table' => 'transactions', 'title' => 'Transactions', 'singular' => 'Transaction',
        'icon' => 'fa-credit-card', 'nav_key' => 'transactions',
        'columns' => [
            'id' => ['label' => 'ID', 'type' => 'number', 'auto' => true, 'list' => true],
            'order_id' => ['label' => 'Order', 'type' => 'lookup', 'required' => true, 'list' => true,
                'lookup' => ['table' => 'orders', 'value' => 'id', 'label' => 'order_number']],
            'gateway' => ['label' => 'Gateway', 'type' => 'text', 'required' => true],
            'method' => ['label' => 'Method', 'type' => 'text', 'required' => true, 'list' => true],
            'transaction_id' => ['label' => 'Transaction ID', 'type' => 'text'],
            'amount_paise' => ['label' => 'Amount', 'type' => 'number', 'format' => 'money', 'required' => true, 'list' => true],
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => [
                'pending' => 'Pending', 'success' => 'Success', 'failed' => 'Failed', 'refunded' => 'Refunded',
            ], 'list' => true],
            'paid_at' => ['label' => 'Paid at', 'type' => 'text'],
        ],
        'search' => ['transaction_id', 'gateway', 'method'],
        'active_value' => 'success', 'inactive_value' => 'failed',
    ],

    'product_reviews' => [
        'table' => 'product_reviews', 'title' => 'Ratings & Reviews', 'singular' => 'Review',
        'icon' => 'fa-star', 'nav_key' => 'reviews',
        'columns' => [
            'id' => ['label' => 'ID', 'type' => 'number', 'auto' => true, 'list' => true],
            'product_id' => ['label' => 'Product', 'type' => 'lookup', 'required' => true, 'list' => true,
                'lookup' => ['table' => 'products', 'value' => 'id', 'label' => 'name']],
            'user_id' => ['label' => 'Customer', 'type' => 'lookup',
                'lookup' => ['table' => 'users', 'value' => 'id', 'label' => 'fullname']],
            'name' => ['label' => 'Reviewer', 'type' => 'text', 'required' => true, 'list' => true],
            'rating' => ['label' => 'Rating', 'type' => 'select', 'options' => [
                '1' => '1 star', '2' => '2 stars', '3' => '3 stars', '4' => '4 stars', '5' => '5 stars',
            ], 'required' => true, 'list' => true],
            'title' => ['label' => 'Title', 'type' => 'text'],
            'body' => ['label' => 'Review', 'type' => 'textarea', 'required' => true],
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => [
                'pending' => 'Pending', 'active' => 'Active', 'inactive' => 'Inactive',
            ], 'list' => true],
        ],
        'search' => ['name', 'title', 'body'],
        'sort' => 'id', 'dir' => 'DESC',
    ],

    'inquiries' => [
        'table' => 'inquiries', 'title' => 'Inquiries', 'singular' => 'Inquiry',
        'icon' => 'fa-envelope-open-text', 'nav_key' => 'inquiries',
        'columns' => [
            'id' => ['label' => 'ID', 'type' => 'number', 'auto' => true, 'list' => true],
            'user_id' => ['label' => 'Customer', 'type' => 'lookup',
                'lookup' => ['table' => 'users', 'value' => 'id', 'label' => 'fullname'], 'list' => true],
            'name' => ['label' => 'Name', 'type' => 'text', 'required' => true, 'list' => true],
            'email' => ['label' => 'Email', 'type' => 'email', 'required' => true],
            'phone' => ['label' => 'Phone', 'type' => 'text'],
            'subject' => ['label' => 'Subject', 'type' => 'text', 'required' => true, 'list' => true],
            'message' => ['label' => 'Message', 'type' => 'textarea', 'required' => true],
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['open' => 'Open', 'closed' => 'Closed'], 'list' => true],
        ],
        'search' => ['name', 'email', 'subject'],
        'active_value' => 'closed', 'inactive_value' => 'open',
        'dir' => 'DESC',
    ],

    'faqs' => [
        'table' => 'faqs', 'title' => 'FAQs', 'singular' => 'FAQ',
        'icon' => 'fa-circle-question', 'nav_key' => 'faqs',
        'columns' => [
            'id' => ['label' => 'ID', 'type' => 'number', 'auto' => true, 'list' => true],
            'topic' => ['label' => 'Topic', 'type' => 'text', 'required' => true, 'list' => true],
            'question' => ['label' => 'Question', 'type' => 'text', 'required' => true, 'list' => true],
            'answer' => ['label' => 'Answer', 'type' => 'textarea', 'required' => true],
            'display_order' => ['label' => 'Display order', 'type' => 'number', 'list' => true],
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => $admin_status_options, 'list' => true],
        ],
        'search' => ['topic', 'question', 'answer'],
    ],

    'site_settings' => [
        'table' => 'site_settings', 'title' => 'Site Settings', 'singular' => 'Setting',
        'icon' => 'fa-gear', 'nav_key' => 'settings',
        'columns' => [
            'id' => ['label' => 'ID', 'type' => 'number', 'auto' => true, 'list' => true],
            'setting_key' => ['label' => 'Key', 'type' => 'text', 'required' => true, 'list' => true],
            'setting_value' => ['label' => 'Value', 'type' => 'textarea', 'required' => true],
            'setting_group' => ['label' => 'Group', 'type' => 'text', 'required' => true, 'list' => true],
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => $admin_status_options, 'list' => true],
        ],
        'search' => ['setting_key', 'setting_value'],
    ],

    'pages' => [
        'table' => 'pages', 'title' => 'Guest Pages', 'singular' => 'Page',
        'icon' => 'fa-file-lines', 'nav_key' => 'pages',
        'columns' => [
            'id' => ['label' => 'ID', 'type' => 'number', 'auto' => true, 'list' => true],
            'page_key' => ['label' => 'Page key', 'type' => 'text', 'required' => true, 'list' => true],
            'page_name' => ['label' => 'Page name', 'type' => 'text', 'required' => true, 'list' => true],
            'title' => ['label' => 'Title', 'type' => 'text', 'required' => true, 'list' => true],
            'subtitle' => ['label' => 'Subtitle', 'type' => 'text'],
            'body' => ['label' => 'Body', 'type' => 'textarea'],
            'cta_label' => ['label' => 'Button label', 'type' => 'text'],
            'cta_url' => ['label' => 'Button URL', 'type' => 'text'],
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => $admin_status_options, 'list' => true],
        ],
        'search' => ['page_key', 'page_name', 'title'],
    ],

    'page_sections' => [
        'table' => 'page_sections', 'title' => 'Page Sections', 'singular' => 'Section',
        'icon' => 'fa-grip', 'nav_key' => 'page-sections',
        'columns' => [
            'id' => ['label' => 'ID', 'type' => 'number', 'auto' => true, 'list' => true],
            'page_id' => ['label' => 'Page', 'type' => 'lookup', 'required' => true, 'list' => true,
                'lookup' => ['table' => 'pages', 'value' => 'id', 'label' => 'page_name']],
            'section_key' => ['label' => 'Section key', 'type' => 'text', 'required' => true, 'list' => true],
            'heading' => ['label' => 'Heading', 'type' => 'text', 'list' => true],
            'body' => ['label' => 'Body', 'type' => 'textarea'],
            'icon_class' => ['label' => 'Icon class', 'type' => 'text'],
            'display_order' => ['label' => 'Display order', 'type' => 'number', 'list' => true],
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => $admin_status_options, 'list' => true],
        ],
        'search' => ['section_key', 'heading', 'body'],
    ],
];

/** Default active/inactive values used by the status toggle button. */
$admin_default_active_value   = 'active';
$admin_default_inactive_value = 'inactive';