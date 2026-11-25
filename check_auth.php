<?php
/**
 * check_auth.php
 * Include this at the top of protected pages (account.php, orders.php, cart.php, etc.).
 * If the user is not signed in, redirects them to login.php.
 */

require_once __DIR__ . '/auth.php';

require_login();
