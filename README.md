# TrendCart — PHP Storefront with a Shared Layout

A complete-looking online store built with plain PHP, Bootstrap 5 and FontAwesome.
The visible pages read like a real shop (products, prices, offers, policies, account
pages), while the PHP underneath stays small and beginner-friendly.

## How to run

1. Keep this folder inside Laragon's `www` directory.
2. Start Laragon (Apache + PHP).
3. Open **http://localhost/MCA_2026/**

Bootstrap, FontAwesome and the webfonts are all local, so the site works offline.
The storefront pages are static; the admin panel uses a local MySQL database.
No Composer or build step is needed.

## Pages

| File | Page |
| --- | --- |
| `index.php` | Home — hero, categories, bestsellers, testimonials |
| `product.php` | Shop listing with filters, ratings, prices and pagination |
| `product-detail.php` | One product's details, opened by a view icon with `?product=slug` |
| `offers.php` | Coupon codes, deals of the day and bank/wallet offers |
| `about.php` | Company story, numbers, team and CTA |
| `contact.php` | Contact form, store details and help-centre link |
| `faq.php` | Help centre — 24 questions across 5 topics |
| `login.php` | Sign in |
| `register.php` | Create account |
| `forgot-password.php` | Password reset request |
| `account.php` | Dashboard — gradient welcome banner, stat tiles, recent order and quick links |
| `orders.php` | My Orders — status cards, tabs, progress bars and detail links |
| `order-detail.php` | One order's delivery banner, tracking timeline, items and price summary |
| `profile.php` | Read-only profile summary |
| `edit-profile.php` | Grouped profile form with preferences and account shortcuts |
| `change-password.php` | Security banner, password form, strength checklist and signed-in devices |
| `addresses.php` | Saved addresses with add/edit Bootstrap modals |
| `cart.php` | Shopping cart with quantity controls and price summary |
| `wishlist.php` | Saved products using the same product tiles |
| `checkout.php` | Secure preview with address/payment cards and a sticky order summary |
| `account-nav.php` | Shared sidebar used by every account page |
| `admin.php` | Admin dashboard — stats, sales chart, recent orders and low stock |
| `admin-layout.php` | Separate admin shell with a fixed dark sidebar and Bootstrap JS |
| `admin-nav.php` | Shared admin sidebar with links to every database table |
| `db.php` | PDO connection to the `MCA_2026` MySQL database |
| `data_dictionary.sql` | Schema for all 16 admin-managed tables |
| `guest_pages_seed.sql` | Guest-page/section copy copied from the static HTML pages |
| `admin-data.php` | Column definitions for every CRUD table |
| `admin-crud.php` | Generic list, search, sort, paginate and CRUD engine |
| `admin-products.php` | Products CRUD |
| `admin-categories.php` | Categories CRUD |
| `admin-customers.php` | Users / customers CRUD |
| `admin-addresses.php` | Addresses CRUD |
| `admin-carts.php` | Carts CRUD |
| `admin-wishlists.php` | Wishlists CRUD |
| `admin-offers.php` | Coupons / offers CRUD |
| `admin-orders.php` | Orders CRUD |
| `admin-order-items.php` | Order items CRUD |
| `admin-transactions.php` | Transactions CRUD |
| `admin-reviews.php` | Ratings and reviews CRUD |
| `admin-inquiries.php` | Contact inquiries CRUD |
| `admin-faqs.php` | FAQs CRUD |
| `admin-pages.php` | Guest page content CRUD |
| `admin-page-sections.php` | Repeatable page sections CRUD |
| `admin-settings.php` | Site settings CRUD |
| `admin-content.php` | Alias of `admin-pages.php` |
| `admin-profile.php` | Admin profile, password and permissions |
| `layout.php` | Shared announcement bar, navbar and footer |
| `css/style.css` | Theme colours and store-specific styling |
| `fontawesome/` | Local FontAwesome CSS and webfonts |
| `js/bootstrap.bundle.min.js` | The only JavaScript file (Bootstrap's local bundle) |

## The PHP idea underneath

Every page follows the same four steps. The visible result is a normal storefront,
but there is only one copy of the header and footer in the whole project.

```php
<?php
$page_title  = 'Shop All Products | TrendCart';  // 1. settings for the layout
$active_page = 'product';

ob_start();                                      // 2. capture all output from here
?>
<!-- this page's HTML -->
<?php
$page_content = ob_get_clean();                   // 3. get the HTML as a string
require 'layout.php';                             // 4. layout prints header + content + footer
```

`ob_start()` asks PHP to remember everything that would normally be printed.
`ob_get_clean()` returns that remembered HTML and clears the buffer.
`layout.php` prints it with `<?= $page_content ?>`. Because the menu and footer live
in one file, a single edit updates every page.

In `layout.php`, `<?= $page_content ?>` is short for `<?php echo $page_content ?>`,
and `??` means "use this value if it exists, otherwise use the default".

## Adding a page

1. Copy `about.php` to `newpage.php`.
2. Change `$page_title` and `$active_page`.
3. Replace the HTML between `ob_start()` and `ob_get_clean()`.
4. Add a link for it in `layout.php`.

## Behaviour notes

- The storefront content, prices, offers, policies and testimonials are illustrative.
- Storefront forms, filters, sorting, wishlist buttons, "Add to Cart" and pagination remain
  visual only. The admin panel, however, is fully backed by the local MySQL database.
- Password reset is linked from the sign-in page; it is intentionally not repeated in
  the Account dropdown.
- The Account dropdown links to the dashboard, orders, wishlist, cart, address book and
  profile settings. The navbar heart and cart icons now open `wishlist.php` and `cart.php`.
- All account pages share one sidebar from `account-nav.php`. Each page sets
  `$account_active` to its own key, and the matching link is highlighted automatically.
  The order pages use `$active_page = 'orders'` so My Orders stays active in the navbar.
- Account forms, quantity buttons, coupon boxes, checkout radios and "Place Order" are
  visual only. Checkout is a preview; it never takes a payment or creates an order.
- Open `admin.php` for the admin panel. It uses a completely separate layout
  (`admin-layout.php`) with a dark sidebar and dense tables, but reuses the same teal
  theme. Every admin page captures HTML into `$admin_page_content` and sets
  `$admin_active`; `admin-nav.php` highlights the matching section.
- Every admin table is driven by `admin-data.php` through the shared `admin-crud.php`
  engine. Each screen has a Search button, status filter, sortable columns, pagination
  and icon-only View / Edit / Delete / Activate or Deactivate actions. Add, edit and view
  details open in Bootstrap modals, and all changes are saved to MySQL. The Customers
  screen also has cart and wishlist icons that open modals showing that customer's items.
  The local Bootstrap bundle is loaded only for these modals and dismissible alerts;
  there is no custom JS file.
- View details modals use a record banner, a status/timestamp summary and full-width
  text cards instead of a plain definition list. Guest Pages and Page Sections are seeded
  from the actual static page copy and can be re-synced with `guest_pages_seed.sql`.
- The FAQ page contains 24 accordion questions grouped into orders, returns, payments,
  account and products. The contact page links to it instead of duplicating questions.
- Every page uses one brand colour (`#0f766e`) for buttons, badges, links, borders and
  icons. `css/style.css` points all Bootstrap colour names (`primary`, `success`,
  `warning`, `danger`, etc.) at that single colour, so the HTML classes stay simple.
  Lighter and darker tints of the same teal are used only on teal panels where white
  or dark text would otherwise be hard to read. Every solid teal surface — announcement
  bar, hero, promo banner, footer, page-header badges, buttons, auth strips and the
  sign-in brand panel — uses the same dark-to-light gradient defined once as
  `--tc-brand-gradient`. The navbar has `fixed-top`, so it stays visible while you scroll.
- Every page starts with a breadcrumb and a centred page header. Product tiles use a small
  `.discount-badge` and always show three circular icons: view, wishlist and add-to-cart.
  The view icon opens `product-detail.php?product=slug`, where the details come from one
  simple PHP array in that file. Each product defines four icon-based views; the detail
  page shows one large main image with four thumbnails underneath so you can switch views.
  The cart uses a delivery strip, divided item rows and a sticky price summary; the
  wishlist uses taller saved-product tiles with price alerts and move-to-cart actions.
- The dashboard, order history, order detail and checkout pages follow the same card
  and section-heading pattern as the profile/security pages. Order cards use compact
  progress bars, and checkout uses payment option cards with a sticky price summary.
- The sign-in page uses a split card: a teal gradient brand panel beside the form. The
  registration form uses a single **Full Name** field and sits in a wider `.register-card`.
  Profile photo upload lives in `edit-profile.php`, not registration.
- Interactive parts use the local Bootstrap JavaScript bundle: the FAQ accordion, mobile
  menu, product gallery, address-book modals and every admin CRUD modal. There is no
  jQuery and no custom JS file.
- Product "images" are FontAwesome icons on coloured tiles so the project needs no
  image downloads. Swap the icons in each product's `images` array for `<img>` tags
  when real product photos are available.
