<?php
/**
 * admin-crud.php - Generic database-backed CRUD screen.
 * Each root-level admin-*.php wrapper sets $table_key and includes this file.
 */

require 'db.php';
require 'admin-data.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$table_key = $table_key ?? '';
if (!isset($admin_tables[$table_key])) {
    die('Unknown admin table key: ' . h($table_key));
}

$cfg       = $admin_tables[$table_key];
$table     = $cfg['table'];
$columns   = $cfg['columns'];
$pk        = 'id';
$statusCol = 'status';
$activeVal   = $cfg['active_value']   ?? $admin_default_active_value;
$inactiveVal = $cfg['inactive_value'] ?? $admin_default_inactive_value;
$perPage   = $cfg['per_page'] ?? 10;
$self      = basename($_SERVER['PHP_SELF']);

$admin_page_title = $cfg['title'] . ' | TrendCart Admin';
$admin_active     = $cfg['nav_key'];

/** Set a one-time flash message. */
function flash(string $message, string $type = 'success'): void
{
    $_SESSION['admin_flash'] = ['message' => $message, 'type' => $type];
}

/** Redirect to the current listing page. */
function redirect_to(string $path): void
{
    header('Location: ' . $path);
    exit;
}

/** Collect whitelisted POST values for insert/update. */
function collect_post(array $columns, string $action): array
{
    $data = [];
    foreach ($columns as $name => $col) {
        if (!empty($col['auto'])) {
            continue;
        }
        if (!array_key_exists($name, $_POST)) {
            continue;
        }

        $value = trim((string) $_POST[$name]);

        // On edit, a blank password keeps the existing hash.
        if ($action === 'update' && ($col['type'] ?? '') === 'password' && $value === '') {
            continue;
        }

        if (!empty($col['required']) && $value === '') {
            flash('Please fill in ' . ($col['label'] ?? $name) . '.', 'danger');
            redirect_to(basename($_SERVER['PHP_SELF']));
        }

        $data[$name] = $value;
    }
    return $data;
}

/** Build a status badge. */
function status_badge($value): string
{
    $v    = strtolower((string) $value);
    $class = 'admin-status';

    if (in_array($v, ['active', 'success', 'paid', 'delivered'], true)) {
        // default green style
    } elseif (in_array($v, ['pending', 'processing', 'shipped', 'open'], true)) {
        $class .= ' warn';
    } else {
        $class .= ' muted';
    }

    return '<span class="' . $class . '">' . h($value) . '</span>';
}

/** Render one form field (used by create and edit modals). */
function render_field(string $name, array $col, $value, string $prefix, array $lookups): string
{
    if (!empty($col['auto'])) {
        return '';
    }

    $id       = $prefix . '_' . $name;
    $label    = h($col['label'] ?? ucfirst($name));
    $type     = $col['type'] ?? 'text';
    $isRequired = !empty($col['required']);
    $val      = (string) $value;

    // Native HTML validation is switched off on every form. js/validator.js
    // validates these generated fields through data-validation attributes.
    $rules     = [];
    $dataAttrs = '';
    switch ($type) {
        case 'email':
            if ($isRequired) { $rules[] = 'required'; }
            $rules[] = 'email';
            break;
        case 'number':
            if ($isRequired) { $rules[] = 'required'; }
            $rules[] = 'numeric';
            break;
        case 'password':
            // On edit, a blank password means "keep the current hash".
            if ($prefix === 'create' && $isRequired) { $rules[] = 'required'; }
            $rules[]   = 'max';
            $dataAttrs = ' data-max="255"';
            break;
        case 'textarea':
            if ($isRequired) { $rules[] = 'required'; }
            $rules[]   = 'max';
            $dataAttrs = ' data-max="1000"';
            break;
        case 'select':
        case 'lookup':
            if ($isRequired) {
                $rules[] = 'required';
            } else {
                $rules[]   = 'max';
                $dataAttrs = ' data-max="255"';
            }
            break;
        default:
            if ($isRequired) { $rules[] = 'required'; }
            $rules[]   = 'max';
            $dataAttrs = ' data-max="255"';
    }
    $validation = ' data-validation="' . implode(' ', $rules) . '"' . $dataAttrs;

    $html = '<div><label for="' . h($id) . '" class="form-label small fw-semibold">' . $label . '</label>';

    if ($type === 'textarea') {
        $html .= '<textarea class="form-control" id="' . h($id) . '" name="' . h($name) . '" rows="3"' . $validation . '>' . h($val) . '</textarea>';
    } elseif ($type === 'select') {
        $html .= '<select class="form-select" id="' . h($id) . '" name="' . h($name) . '"' . $validation . '>';
        foreach (($col['options'] ?? []) as $key => $optionLabel) {
            $selected = ((string) $key === $val) ? ' selected' : '';
            $html .= '<option value="' . h($key) . '"' . $selected . '>' . h($optionLabel) . '</option>';
        }
        $html .= '</select>';
    } elseif ($type === 'lookup') {
        $html .= '<select class="form-select" id="' . h($id) . '" name="' . h($name) . '"' . $validation . '>';
        $html .= '<option value="">-- Select --</option>';
        foreach (($lookups[$name] ?? []) as $key => $optionLabel) {
            $selected = ((string) $key === $val) ? ' selected' : '';
            $html .= '<option value="' . h($key) . '"' . $selected . '>' . h($optionLabel) . '</option>';
        }
        $html .= '</select>';
    } elseif ($type === 'password') {
        $placeholder = ($prefix !== 'create') ? 'Leave blank to keep current hash' : 'Enter password hash';
        $html .= '<input type="password" class="form-control" id="' . h($id) . '" name="' . h($name) . '" value="" placeholder="' . h($placeholder) . '"' . $validation . '>';
    } elseif ($type === 'number') {
        $html .= '<input type="number" step="any" class="form-control" id="' . h($id) . '" name="' . h($name) . '" value="' . h($val) . '"' . $validation . '>';
    } elseif ($type === 'email') {
        $html .= '<input type="email" class="form-control" id="' . h($id) . '" name="' . h($name) . '" value="' . h($val) . '"' . $validation . '>';
    } else {
        $html .= '<input type="text" class="form-control" id="' . h($id) . '" name="' . h($name) . '" value="' . h($val) . '"' . $validation . '>';
    }

    return $html . '<div class="invalid-feedback" id="' . h($id) . 'Error"></div></div>';
}

/** Render a read-only value for the table and view modal. */
function render_value(string $name, array $col, $value, array $lookups): string
{
    if (($col['type'] ?? '') === 'lookup') {
        $key = (string) $value;
        return h($lookups[$name][$key] ?? $value);
    }

    if (($col['format'] ?? '') === 'money') {
        return money($value);
    }

    if ($name === 'status') {
        return status_badge($value);
    }

    $text = (string) $value;
    if (strlen($text) > 60) {
        $text = substr($text, 0, 57) . '...';
    }

    return h($text);
}

/** Cart or wishlist items for one customer, joined with product details. */
function collection_items(string $table, int $userId): array
{
    $qtyExpr = ($table === 'carts') ? 'x.qty' : '1 AS qty';

    return db_all(
        'SELECT x.id, x.product_id, ' . $qtyExpr . ', x.status, x.guest_token, ' .
        'p.name AS product_name, p.sku, p.price_paise ' .
        'FROM `' . $table . '` x ' .
        'LEFT JOIN products p ON p.id = x.product_id ' .
        'WHERE x.user_id = ? ORDER BY x.id DESC',
        [$userId]
    );
}

/** Render the item list shown inside customer cart / wishlist modals. */
function render_collection_body(array $items, string $emptyLabel, bool $showQty = true): string
{
    if (!$items) {
        return '<div class="alert alert-light border mb-0">' . h($emptyLabel) . '</div>';
    }

    $html = '<div class="list-group list-group-flush">';
    foreach ($items as $item) {
        $productName = $item['product_name'] ?: ('Product #' . $item['product_id']);
        $lineTotal   = (float) $item['price_paise'] * (int) $item['qty'];

        $html .= '<div class="list-group-item px-0 d-flex align-items-center gap-3">';
        $html .= '<span class="admin-thumb"><i class="fa-solid fa-box"></i></span>';
        $html .= '<div class="me-auto">';
        $html .= '<p class="small fw-bold mb-0">' . h($productName) . '</p>';
        $html .= '<span class="admin-section-note">' . h($item['sku'] ?: ('ID ' . $item['id'])) . '</span>';
        $html .= '<div class="mt-1">' . status_badge($item['status']) . '</div>';
        $html .= '</div>';
        $html .= '<div class="text-end">';
        $html .= '<strong>' . money($lineTotal) . '</strong>';
        if ($showQty) {
            $html .= '<br><span class="admin-section-note">Qty ' . (int) $item['qty'] . '</span>';
        }
        $html .= '</div>';
        $html .= '</div>';
    }
    return $html . '</div>';
}

/** Format a datetime for the detail modal. */
function view_date($value): string
{
    if (!$value) {
        return '—';
    }
    return date('d M Y, h:i A', strtotime((string) $value));
}

/** Full, untruncated value for the View details modal. */
function render_view_value(string $name, array $col, $value, array $lookups): string
{
    if ($value === null || $value === '') {
        return '<span class="text-muted">&mdash;</span>';
    }

    if (($col['type'] ?? '') === 'password') {
        return '<span class="text-muted"><i class="fa-solid fa-lock me-1"></i>Stored securely</span>';
    }

    if (in_array($name, ['is_featured', 'is_default'], true)) {
        return yes_no($value);
    }

    if (($col['type'] ?? '') === 'lookup') {
        return h($lookups[$name][(string) $value] ?? $value);
    }

    if (($col['format'] ?? '') === 'money') {
        return money($value);
    }

    if ($name === 'status') {
        return status_badge($value);
    }

    if (($col['type'] ?? '') === 'textarea') {
        return nl2br(h($value));
    }

    return h($value);
}

// --------------------------------------------------------------
// Handle POST actions: create, update, delete, toggle
// --------------------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id     = (int) ($_POST['id'] ?? 0);

    if ($action === 'create' || $action === 'update') {
        $data = collect_post($columns, $action);

        if ($data) {
            $names = array_keys($data);
            $quoted = [];
            foreach ($names as $name) {
                $quoted[] = '`' . $name . '`';
            }

            try {
                if ($action === 'create') {
                    $placeholders = implode(', ', array_fill(0, count($names), '?'));
                    $sql = 'INSERT INTO `' . $table . '` (' . implode(', ', $quoted) . ') VALUES (' . $placeholders . ')';
                    db_exec($sql, array_values($data));
                    flash($cfg['singular'] . ' added successfully.');
                } else {
                    $sets = [];
                    foreach ($names as $name) {
                        $sets[] = '`' . $name . '` = ?';
                    }
                    $params = array_values($data);
                    $params[] = $id;
                    $sql = 'UPDATE `' . $table . '` SET ' . implode(', ', $sets) . ' WHERE `id` = ?';
                    db_exec($sql, $params);
                    flash($cfg['singular'] . ' updated successfully.');
                }
            } catch (PDOException $e) {
                flash('Save failed. Check duplicate values and related records. ' . $e->getMessage(), 'danger');
            }
        }

        redirect_to($self);
    }

    if ($action === 'delete' && $id > 0) {
        try {
            db_exec('DELETE FROM `' . $table . '` WHERE `id` = ?', [$id]);
            flash($cfg['singular'] . ' deleted successfully.');
        } catch (PDOException $e) {
            flash('Delete failed. This record may be used by another table. ' . $e->getMessage(), 'danger');
        }
        redirect_to($self);
    }

    if ($action === 'toggle' && $id > 0) {
        $row = db_one('SELECT `' . $statusCol . '` AS current_status FROM `' . $table . '` WHERE `id` = ?', [$id]);
        if ($row) {
            $newStatus = ($row['current_status'] === $activeVal) ? $inactiveVal : $activeVal;
            db_exec('UPDATE `' . $table . '` SET `' . $statusCol . '` = ? WHERE `id` = ?', [$newStatus, $id]);
            flash($cfg['singular'] . ' status changed to ' . $newStatus . '.');
        }
        redirect_to($self);
    }
}

// --------------------------------------------------------------
// Search, filter, sort and pagination
// --------------------------------------------------------------

$q      = trim((string) ($_GET['q'] ?? ''));
$filter = trim((string) ($_GET['status'] ?? ''));
$sort   = (string) ($_GET['sort'] ?? ($cfg['sort'] ?? 'id'));
$dir    = strtolower((string) ($_GET['dir'] ?? ($cfg['dir'] ?? 'ASC')));

if (!isset($columns[$sort])) {
    $sort = 'id';
}
$dir = in_array($dir, ['asc', 'desc'], true) ? strtoupper($dir) : 'ASC';

$page = max(1, (int) ($_GET['page'] ?? 1));

$where  = [];
$params = [];

if ($q !== '') {
    $likes = [];
    foreach (($cfg['search'] ?? []) as $field) {
        $likes[] = '`' . $field . '` LIKE ?';
        $params[] = '%' . $q . '%';
    }
    if ($likes) {
        $where[] = '(' . implode(' OR ', $likes) . ')';
    }
}

if ($filter !== '') {
    $where[] = '`' . $statusCol . '` = ?';
    $params[] = $filter;
}

$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$countRow = db_one('SELECT COUNT(*) AS total FROM `' . $table . '` ' . $whereSql, $params);
$total    = (int) ($countRow['total'] ?? 0);
$pages    = max(1, (int) ceil($total / $perPage));
$page     = min($page, $pages);
$offset   = ($page - 1) * $perPage;

$rows = db_all(
    'SELECT * FROM `' . $table . '` ' . $whereSql . ' ORDER BY `' . $sort . '` ' . $dir . ' LIMIT ' . $perPage . ' OFFSET ' . $offset,
    $params
);

// Lookup maps for select options and list labels.
$lookups = [];
foreach ($columns as $name => $col) {
    if (($col['type'] ?? '') !== 'lookup') {
        continue;
    }
    $l = $col['lookup'];
    $pairs = db_all(
        'SELECT `' . $l['value'] . '` AS v, `' . $l['label'] . '` AS l FROM `' . $l['table'] . '` ORDER BY `' . $l['label'] . '`'
    );
    foreach ($pairs as $pair) {
        $lookups[$name][$pair['v']] = $pair['l'];
    }
}

$listColumns = [];
foreach ($columns as $name => $col) {
    if (!empty($col['list'])) {
        $listColumns[$name] = $col;
    }
}

$statusOptions = $columns[$statusCol]['options'] ?? [];

$url_with = function (array $overrides = []) use ($self, $q, $filter, $sort, $dir) {
    $params = ['q' => $q, 'status' => $filter, 'sort' => $sort, 'dir' => $dir];
    foreach ($overrides as $key => $value) {
        $params[$key] = $value;
    }
    $params = array_filter($params, function ($value) {
        return $value !== '' && $value !== null;
    });
    return h($self . '?' . http_build_query($params));
};

ob_start();
?>
<div class="admin-page-head d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <span class="admin-status mb-2 d-inline-block"><i class="fa-solid <?= h($cfg['icon']) ?> me-1"></i>DATABASE RECORDS</span>
        <h1 class="h3 fw-bold mb-1"><?= h($cfg['title']) ?></h1>
        <p class="text-muted mb-0">Create, view, edit, activate, deactivate and delete <?= h(strtolower($cfg['title'])) ?>.</p>
    </div>
    <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#createModal">
        <i class="fa-solid fa-plus me-1"></i>Add <?= h($cfg['singular']) ?>
    </button>
</div>

<?php if (!empty($_SESSION['admin_flash'])) : ?>
    <?php $flash = $_SESSION['admin_flash']; unset($_SESSION['admin_flash']); ?>
    <div class="alert alert-<?= h($flash['type'] === 'danger' ? 'danger' : 'success') ?> alert-dismissible fade show" role="alert">
        <?= h($flash['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card admin-card mb-4">
    <div class="card-body">
        <form method="get" action="<?= h($self) ?>" class="row g-2 align-items-end admin-crud-filters" novalidate>
            <input type="hidden" name="sort" value="<?= h($sort) ?>">
            <input type="hidden" name="dir" value="<?= h($dir) ?>">
            <div class="col-12 col-md-5 col-lg-4">
                <label for="crudSearch" class="form-label small fw-semibold mb-1">Search</label>
                <input type="search" class="form-control" id="crudSearch" name="q"
                       value="<?= h($q) ?>" placeholder="Type keyword and click Search"
                       data-validation="max" data-max="100">
                <div class="invalid-feedback" id="crudSearchError"></div>
            </div>
            <div class="col-12 col-md-4 col-lg-3">
                <label for="crudStatus" class="form-label small fw-semibold mb-1">Status</label>
                <select class="form-select" id="crudStatus" name="status" data-validation="max" data-max="255">
                    <option value="">All statuses</option>
                    <?php foreach ($statusOptions as $key => $label) : ?>
                        <option value="<?= h($key) ?>" <?= ((string) $key === $filter) ? 'selected' : '' ?>><?= h($label) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback" id="crudStatusError"></div>
            </div>
            <div class="col-12 col-md-3 col-lg-5 d-flex flex-wrap gap-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fa-solid fa-magnifying-glass me-1"></i>Search
                </button>
                <a href="<?= h($self) ?>" class="btn btn-outline-secondary px-3">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card admin-card">
    <div class="admin-card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h2 class="admin-card-title mb-0"><?= h($cfg['title']) ?> <span class="admin-status"><?= (int) $total ?></span></h2>
        <span class="admin-section-note">Page <?= (int) $page ?> of <?= (int) $pages ?></span>
    </div>

    <div class="table-responsive">
        <table class="table admin-table align-middle mb-0">
            <thead>
                <tr>
                    <?php foreach ($listColumns as $name => $col) : ?>
                        <?php
                        $nextDir = ($sort === $name && $dir === 'ASC') ? 'desc' : 'asc';
                        $indicator = ($sort === $name) ? ($dir === 'ASC' ? '&#9650;' : '&#9660;') : '';
                        ?>
                        <th>
                            <a href="<?= $url_with(['sort' => $name, 'dir' => $nextDir, 'page' => 1]) ?>" class="text-decoration-none text-reset">
                                <?= h($col['label'] ?? $name) ?> <small><?= $indicator ?></small>
                            </a>
                        </th>
                    <?php endforeach; ?>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!$rows) : ?>
                    <tr>
                        <td colspan="<?= count($listColumns) + 1 ?>" class="text-center text-muted py-5">
                            No records found. Click <strong>Add <?= h($cfg['singular']) ?></strong> to create one.
                        </td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($rows as $row) : ?>
                        <?php $rowId = (int) $row[$pk]; ?>
                        <tr>
                            <?php foreach ($listColumns as $name => $col) : ?>
                                <td><?= render_value($name, $col, $row[$name] ?? '', $lookups) ?></td>
                            <?php endforeach; ?>
                            <td>
                                <?php $isActive = ($row[$statusCol] === $activeVal); ?>
                                <div class="admin-row-actions d-flex flex-wrap justify-content-end gap-1">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewModal_<?= $rowId ?>" title="View details" aria-label="View details">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editModal_<?= $rowId ?>" title="Edit" aria-label="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <?php if (in_array('cart', $cfg['row_actions'] ?? [], true)) : ?>
                                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#cartModal_<?= $rowId ?>" title="View cart items" aria-label="View cart items">
                                            <i class="fa-solid fa-cart-shopping"></i>
                                        </button>
                                    <?php endif; ?>
                                    <?php if (in_array('wishlist', $cfg['row_actions'] ?? [], true)) : ?>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#wishlistModal_<?= $rowId ?>" title="View wishlist items" aria-label="View wishlist items">
                                            <i class="fa-regular fa-heart"></i>
                                        </button>
                                    <?php endif; ?>
                                    <form method="post" class="d-inline" novalidate>
                                        <input type="hidden" name="action" value="toggle">
                                        <input type="hidden" name="id" value="<?= $rowId ?>">
                                        <button type="submit" class="btn btn-sm <?= $isActive ? 'btn-outline-warning' : 'btn-outline-success' ?>" title="<?= $isActive ? 'Deactivate' : 'Activate' ?>" aria-label="<?= $isActive ? 'Deactivate' : 'Activate' ?>">
                                            <i class="fa-solid <?= $isActive ? 'fa-toggle-off' : 'fa-toggle-on' ?>"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal_<?= $rowId ?>" title="Delete" aria-label="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($pages > 1) : ?>
        <nav class="admin-pagination d-flex flex-wrap justify-content-between align-items-center gap-2 p-3" aria-label="Pagination">
            <span class="text-muted small">Showing <?= ($offset + 1) ?> to <?= min($offset + $perPage, $total) ?> of <?= $total ?></span>
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= $url_with(['page' => max(1, $page - 1)]) ?>">Previous</a>
                </li>
                <?php for ($p = 1; $p <= $pages; $p++) : ?>
                    <li class="page-item <?= $p === $page ? 'active' : '' ?>">
                        <a class="page-link" href="<?= $url_with(['page' => $p]) ?>"><?= $p ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $page >= $pages ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= $url_with(['page' => min($pages, $page + 1)]) ?>">Next</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<!-- ========== CREATE MODAL ========== -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form method="post" action="<?= h($self) ?>" novalidate>
                <input type="hidden" name="action" value="create">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="createModalLabel">Add <?= h($cfg['singular']) ?></h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="admin-form-grid">
                        <?php foreach ($columns as $name => $col) : ?>
                            <?= render_field($name, $col, ($col['default'] ?? ''), 'create', $lookups) ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i>Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========== ROW MODALS ========== -->
<?php foreach ($rows as $row) : ?>
    <?php $rowId = (int) $row[$pk]; ?>

    <?php
    $viewColumns = $columns;
    $viewColumns['created_at'] = ['label' => 'Created at', 'type' => 'text'];
    $viewColumns['updated_at'] = ['label' => 'Updated at', 'type' => 'text'];
    $viewTitle = $row['fullname'] ?? $row['name'] ?? $row['order_number'] ?? $row['page_name']
        ?? $row['setting_key'] ?? $row['email'] ?? ('Record #' . $rowId);
    ?>
    <div class="modal fade" id="viewModal_<?= $rowId ?>" tabindex="-1" aria-labelledby="viewLabel_<?= $rowId ?>" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header admin-view-header">
                    <div class="d-flex align-items-center gap-3">
                        <span class="admin-view-icon"><i class="fa-solid <?= h($cfg['icon']) ?>"></i></span>
                        <div>
                            <span class="admin-status mb-1 d-inline-block">Record #<?= $rowId ?></span>
                            <h2 class="modal-title h5 mb-0" id="viewLabel_<?= $rowId ?>"><?= h($viewTitle) ?></h2>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body admin-view-body">
                    <div class="admin-view-summary">
                        <div>
                            <span>Current status</span>
                            <strong><?= status_badge($row[$statusCol] ?? '') ?></strong>
                        </div>
                        <div>
                            <span>Created</span>
                            <strong><?= h(view_date($row['created_at'] ?? null)) ?></strong>
                        </div>
                        <div>
                            <span>Last updated</span>
                            <strong><?= h(view_date($row['updated_at'] ?? null)) ?></strong>
                        </div>
                    </div>

                    <div class="admin-view-fields">
                        <?php foreach ($viewColumns as $name => $col) : ?>
                            <?php if (in_array($name, ['id', 'created_at', 'updated_at', $statusCol], true)) { continue; } ?>
                            <?php $isWide = ($col['type'] ?? '') === 'textarea'; ?>
                            <div class="admin-view-field <?= $isWide ? 'full' : '' ?>">
                                <span class="admin-view-label"><?= h($col['label'] ?? $name) ?></span>
                                <div class="admin-view-value"><?= render_view_value($name, $col, $row[$name] ?? '', $lookups) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?php if (in_array('cart', $cfg['row_actions'] ?? [], true)) : ?>
        <?php $cartItems = collection_items('carts', $rowId); ?>
        <div class="modal fade" id="cartModal_<?= $rowId ?>" tabindex="-1" aria-labelledby="cartLabel_<?= $rowId ?>" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title h5" id="cartLabel_<?= $rowId ?>">
                            Cart items for <?= h($row['fullname'] ?? ('Customer #' . $rowId)) ?>
                        </h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?= render_collection_body($cartItems, 'This customer has no cart items.') ?>
                    </div>
                    <div class="modal-footer">
                        <a href="admin-carts.php" class="btn btn-outline-secondary me-auto">Manage carts</a>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (in_array('wishlist', $cfg['row_actions'] ?? [], true)) : ?>
        <?php $wishlistItems = collection_items('wishlists', $rowId); ?>
        <div class="modal fade" id="wishlistModal_<?= $rowId ?>" tabindex="-1" aria-labelledby="wishlistLabel_<?= $rowId ?>" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title h5" id="wishlistLabel_<?= $rowId ?>">
                            Wishlist items for <?= h($row['fullname'] ?? ('Customer #' . $rowId)) ?>
                        </h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?= render_collection_body($wishlistItems, 'This customer has no wishlist items.', false) ?>
                    </div>
                    <div class="modal-footer">
                        <a href="admin-wishlists.php" class="btn btn-outline-secondary me-auto">Manage wishlists</a>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="modal fade" id="editModal_<?= $rowId ?>" tabindex="-1" aria-labelledby="editLabel_<?= $rowId ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <form method="post" action="<?= h($self) ?>" novalidate>
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?= $rowId ?>">
                    <div class="modal-header">
                        <h2 class="modal-title h5" id="editLabel_<?= $rowId ?>">Edit <?= h($cfg['singular']) ?> #<?= $rowId ?></h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="admin-form-grid">
                            <?php foreach ($columns as $name => $col) : ?>
                                <?= render_field($name, $col, $row[$name] ?? '', 'edit_' . $rowId, $lookups) ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i>Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal_<?= $rowId ?>" tabindex="-1" aria-labelledby="deleteLabel_<?= $rowId ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="<?= h($self) ?>" novalidate>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $rowId ?>">
                    <div class="modal-header">
                        <h2 class="modal-title h5" id="deleteLabel_<?= $rowId ?>">Delete <?= h($cfg['singular']) ?> #<?= $rowId ?></h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">This record will be permanently removed. Continue?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash me-1"></i>Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php
$admin_page_content = ob_get_clean();
require 'admin-layout.php';