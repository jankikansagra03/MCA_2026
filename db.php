<?php
/**
 * db.php - Shared MySQL connection for the TrendCart admin panel.
 * Values are loaded from .env via env.php.
 */

require_once __DIR__ . '/env.php';

/** Escape output for HTML. */
function h($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$db_host = env('DB_HOST', '127.0.0.1');
$db_port = env('DB_PORT', '3306');
$db_name = env('DB_NAME', 'MCA_2026');
$db_user = env('DB_USER', 'root');
$db_pass = env('DB_PASS', '');

try {
    $pdo = new PDO(
        "mysql:host={$db_host};port={$db_port};dbname={$db_name};charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die('Database connection failed. Check db.php settings. ' . h($e->getMessage()));
}

/** Run a SELECT and return all rows. */
function db_all(string $sql, array $params = []): array
{
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/** Run a SELECT and return one row. */
function db_one(string $sql, array $params = []): ?array
{
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $row = $stmt->fetch();
    return $row === false ? null : $row;
}

/** Run INSERT / UPDATE / DELETE and return affected rows. */
function db_exec(string $sql, array $params = []): int
{
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->rowCount();
}

/** Format paise as Indian rupees. */
function money($paise): string
{
    return '&#8377;' . number_format(((float) $paise) / 100, 2);
}

/** Yes / No badge helper. */
function yes_no($value): string
{
    return ((int) $value === 1)
        ? '<span class="admin-status">Yes</span>'
        : '<span class="admin-status muted">No</span>';
}
