<?php
/**
 * check_duplicate_Email.php
 * Verifies if an email is already registered in the users table via AJAX.
 */

require_once __DIR__ . '/db.php';

$email = trim($_REQUEST['email'] ?? ($_REQUEST['email1'] ?? ''));

if ($email !== '') {
    $user = db_one("SELECT id, status FROM users WHERE email = ? LIMIT 1", [$email]);

    if ($user) {
        echo 'true';  // Email is registered
    } else {
        echo 'false'; // Email is not registered
    }
}
