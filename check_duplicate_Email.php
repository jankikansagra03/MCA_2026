<?php
/**
 * check_duplicate_Email.php
 * Verifies if an email is already registered in the users table via AJAX.
 */

require_once __DIR__ . '/db.php';

if (isset($_GET['email1'])) {
    $email = trim($_GET['email1']);
    
    $user = db_one("SELECT id FROM users WHERE email = ? LIMIT 1", [$email]);

    if ($user) {
        echo 'true';  // Email already registered
    } else {
        echo 'false'; // Email is available
    }
}