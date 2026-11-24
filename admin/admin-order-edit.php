<?php
/*
 * admin-order-edit.php - EDIT ONE ORDER
 * Prefilled order editor: status, items, address, payment and timeline.
 * Design only — no changes are stored.
 */

$admin_page_title = 'Edit Order | TrendCart Admin';
$admin_active     = 'orders';

$order_items = [
    ['icon' => 'fa-headphones', 'name' => 'Pulse Wireless Headphones', 'sku' => 'TC-AUD-001', 'qty' => 1, 'price' => '2,499'],
    ['icon' => 'fa-stopwatch', 'name' => 'Nova Smartwatch 2', 'sku' => 'TC-WAT-002', 'qty' => 1, 'price' => '4,999'],
];

$timeline = [
    ['icon' => 'fa-check', 'label' => 'Order placed', 'date' => '2026-11-18T10:24'],
    ['icon' => 'fa-box', 'label' => 'Packed', 'date' => '2026-11-18T16:10'],
    ['icon' => 'fa-truck', 'label' => 'Shipped', 'date' => '2026-11-19T09:05'],
    ['icon' => 'fa-house', 'label' => 'Delivered', 'date' => '2026-11-21T14:45'],
];

ob_start();
?>

<div class="admin-page-heading">
    <div>
        <nav aria-label="breadcrumb" class="admin-breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="admin.php" class="text-decoration-none">Admin</a></li>
                <li class="breadcrumb-item"><a href="admin-orders.php" class="text-decoration-none">Orders</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Order</li>
            </ol>
        </nav>
        <p class="admin-eyebrow mb-0">Sales</p>
        <h1>Order #TC-48291</h1>
        <p class="text-muted mb-0">Placed on 18 Nov 2026 &middot; Aarav Mehta</p>
    </div>
    <a href="order-detail.php" class="btn btn-outline-primary btn-sm px-3">
        <i class="fa-regular fa-eye me-1"></i>View in Store
    </a>
</div>

<form action="#" method="post" novalidate>
    <div class="row g-4">
        <div class="col-12 col-xl-8">
            <div class="card admin-card mb-4">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">Fulfilment status</h2>
                    <span class="admin-status">Paid</span>
                </div>
                <div class="card-body">
                    <div class="admin-form-grid">
                        <div>
                            <label for="fulfilmentStatus" class="form-label small fw-semibold">Order status</label>
                            <select class="form-select" id="fulfilmentStatus" name="fulfilmentStatus" data-validation="required">
                                <option>Processing</option>
                                <option>Shipped</option>
                                <option>Out for delivery</option>
                                <option selected>Delivered</option>
                                <option>Cancelled</option>
                            </select>
                            <div class="invalid-feedback" id="fulfilmentStatusError"></div>
                        </div>
                        <div>
                            <label for="paymentStatus" class="form-label small fw-semibold">Payment status</label>
                            <select class="form-select" id="paymentStatus" name="paymentStatus" data-validation="required">
                                <option selected>Paid</option>
                                <option>Pending</option>
                                <option>Refunded</option>
                                <option>Failed</option>
                            </select>
                            <div class="invalid-feedback" id="paymentStatusError"></div>
                        </div>
                        <div>
                            <label for="trackingId" class="form-label small fw-semibold">Tracking ID</label>
                            <input type="text" class="form-control" id="trackingId" name="trackingId"
                                   value="TRK-TC-882194" data-validation="required max" data-max="50">
                            <div class="invalid-feedback" id="trackingIdError"></div>
                        </div>
                        <div>
                            <label for="courierName" class="form-label small fw-semibold">Delivery partner</label>
                            <select class="form-select" id="courierName" name="courierName" data-validation="required">
                                <option selected>TrendCart Express</option>
                                <option>Blue Dart</option>
                                <option>Delhivery</option>
                            </select>
                            <div class="invalid-feedback" id="courierNameError"></div>
                        </div>
                        <div class="full">
                            <div class="admin-switch-card">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="notifyCustomer" name="notifyCustomer" checked
                                           data-validation="max-items" data-max-items="1">
                                    <label class="form-check-label small fw-semibold" for="notifyCustomer">Notify customer when status changes</label>
                                    <div class="invalid-feedback" id="notifyCustomerError"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card admin-card mb-4">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">Items in this order</h2>
                    <span class="admin-section-note">2 products</span>
                </div>
                <div class="table-responsive">
                    <table class="table admin-table mb-0">
                        <thead>
                            <tr><th>Product</th><th style="width:110px">Quantity</th><th>Price</th><th></th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order_items as $itemIndex => $item) : ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="admin-thumb"><i class="fa-solid <?= $item['icon'] ?>"></i></span>
                                            <div>
                                                <p class="fw-bold mb-0"><?= $item['name'] ?></p>
                                                <span class="admin-section-note"><?= $item['sku'] ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" id="itemQty<?= $itemIndex ?>"
                                               name="itemQty<?= $itemIndex ?>" value="<?= $item['qty'] ?>"
                                               aria-label="Quantity for <?= $item['name'] ?>"
                                               data-validation="required numeric min" data-min="1">
                                        <div class="invalid-feedback" id="itemQty<?= $itemIndex ?>Error"></div>
                                    </td>
                                    <td class="fw-bold">&#8377;<?= $item['price'] ?></td>
                                    <td class="text-end"><button type="button" class="btn btn-outline-primary admin-action" title="Remove item"><i class="fa-solid fa-trash"></i></button></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="admin-card-header border-top border-bottom-0">
                    <button type="button" class="btn btn-outline-primary btn-sm">
                        <i class="fa-solid fa-plus me-1"></i>Add item
                    </button>
                </div>
            </div>

            <div class="card admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">Delivery timeline</h2>
                    <span class="admin-section-note">Static dates in this preview</span>
                </div>
                <div class="card-body">
                    <?php foreach ($timeline as $stepIndex => $step) : ?>
                        <div class="row g-2 align-items-center mb-2">
                            <div class="col-auto"><span class="admin-mini-icon"><i class="fa-solid <?= $step['icon'] ?>"></i></span></div>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-sm" id="timelineLabel<?= $stepIndex ?>"
                                       name="timelineLabel<?= $stepIndex ?>" value="<?= $step['label'] ?>" readonly
                                       data-validation="required max" data-max="100">
                                <div class="invalid-feedback" id="timelineLabel<?= $stepIndex ?>Error"></div>
                            </div>
                            <div class="col">
                                <input type="datetime-local" class="form-control form-control-sm" id="timelineDate<?= $stepIndex ?>"
                                       name="timelineDate<?= $stepIndex ?>" value="<?= $step['date'] ?>"
                                       data-validation="required">
                                <div class="invalid-feedback" id="timelineDate<?= $stepIndex ?>Error"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card admin-card mb-4">
                <div class="admin-card-header"><h2 class="admin-card-title">Customer &amp; address</h2></div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="admin-user-avatar">AM</span>
                        <div>
                            <p class="fw-bold mb-0">Aarav Mehta</p>
                            <span class="admin-section-note">Customer since Jan 2024</span>
                        </div>
                    </div>
                    <p class="admin-eyebrow mb-1">Delivery address</p>
                    <p class="small mb-2">402, Sunrise Residency<br>12 Palm Grove Road, Indiranagar<br>Bengaluru, Karnataka 560038</p>
                    <p class="admin-eyebrow mb-1">Contact</p>
                    <p class="small mb-0">+91 98765 43210<br>aarav.mehta@example.com</p>
                    <a href="admin-customer-edit.php" class="small text-decoration-none fw-semibold d-inline-block mt-3">Edit customer details <i class="fa-solid fa-arrow-right ms-1"></i></a>
                </div>
            </div>

            <div class="card admin-card mb-4">
                <div class="admin-card-header"><h2 class="admin-card-title">Price summary</h2></div>
                <div class="card-body">
                    <div class="d-flex justify-content-between small mb-2"><span class="text-muted">Subtotal</span><span>&#8377;7,498</span></div>
                    <div class="d-flex justify-content-between small mb-2"><span class="text-muted">Discount</span><span>&minus;&#8377;500</span></div>
                    <div class="d-flex justify-content-between small mb-2"><span class="text-muted">Delivery</span><span>Free</span></div>
                    <div class="d-flex justify-content-between small mb-3"><span class="text-muted">GST</span><span>Included</span></div>
                    <div class="d-flex justify-content-between fw-bold border-top pt-2"><span>Total</span><span>&#8377;6,998</span></div>
                    <div class="mt-3 p-3 rounded" style="background:var(--tc-brand-pale)">
                        <p class="small fw-bold mb-1"><i class="fa-solid fa-wallet text-primary me-1"></i>Payment</p>
                        <p class="admin-section-note mb-0">UPI &middot; Paid on 18 Nov 2026</p>
                    </div>
                </div>
            </div>

            <div class="admin-save-bar">
                <span class="admin-section-note">Design preview only</span>
                <div class="d-flex flex-wrap gap-2">
                    <a href="admin-orders.php" class="btn btn-outline-primary btn-sm px-3">Cancel</a>
                    <button type="submit" class="btn btn-primary btn-sm px-3">
                        <i class="fa-solid fa-check me-1"></i>Save Order
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<?php
$admin_page_content = ob_get_clean();
require 'admin-layout.php';
