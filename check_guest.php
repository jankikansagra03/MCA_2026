<?php
/**
 * check_guest.php
 * Include this at the top of guest-only pages (login.php, register.php, forgot-password.php).
 * If the user is already signed in, redirects them to their dashboard or admin panel.
 */

require_once __DIR__ . '/auth.php';

redirect_if_logged_in();
