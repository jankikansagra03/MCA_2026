<?php

/**
 * verify_email.php - Account Activation Handler
 * Verifies email and token, then updates user status to 'active'.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

$page_title  = 'Account Verification | TrendCart';
$active_page = 'login';

$email = trim($_GET['email'] ?? '');
$token = trim($_GET['token'] ?? '');

$verified = false;
$message  = '';
$type     = 'danger'; // 'success' or 'danger'

if ($email === '' || $token === '') {
    $message = 'Invalid verification link. Missing email or token.';
} else {
    try {
        // Find user with matching email and token
        $user = db_one("SELECT id, fullname, status, token FROM users WHERE email = ? AND token = ? LIMIT 1", [$email, $token]);

        if ($user) {
            if (strtolower($user['status']) === 'active') {
                $verified = true;
                $type     = 'info';
                $message  = 'Your account is already active. You can sign in right away.';
            } else {
                // Update user status to active and clear token
                db_exec("UPDATE users SET status = 'active', token = NULL WHERE id = ?", [$user['id']]);
                $verified = true;
                $type     = 'success';
                $message  = 'Your account has been activated successfully! You can now sign in.';
            }
        } else if (db_one("SELECT id FROM users WHERE email = ? LIMIT 1", [$email])) {
            // Check if user is already active and token is already cleared
            $activeUser = db_one("SELECT id, status FROM users WHERE email = ? LIMIT 1", [$email]);
            if ($activeUser && strtolower($activeUser['status']) === 'active') {
                $verified = true;
                $type     = 'info';
                $message  = 'Your account has already been verified and is active. Please sign in.';
            } else {
                $message = 'Invalid or expired activation link. Please register again or request a new link.';
            }
        } else {
            // email not registered
            $message = 'The email address <strong>' . htmlspecialchars($email) . '</strong> is not registered. Please sign up first.';
        }
    } catch (PDOException $e) {
        $message = 'Database error during verification: ' . $e->getMessage();
    }
}

// Store flash message in session for login page
$_SESSION['flash_message'] = [
    'type'    => $type,
    'message' => $message
];

// Start capturing page HTML for layout
ob_start();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card store-card text-center p-4 p-lg-5">
                <div class="mb-4">
                    <?php if ($verified): ?>
                        <div class="display-3 text-success mb-3"><i class="fa-solid fa-circle-check"></i></div>
                        <h2 class="h4 fw-bold">Account Activated!</h2>
                    <?php else: ?>
                        <div class="display-3 text-danger mb-3"><i class="fa-solid fa-circle-xmark"></i></div>
                        <h2 class="h4 fw-bold">Verification Failed</h2>
                    <?php endif; ?>
                </div>

                <div class="alert alert-<?= $type ?> mb-4" role="alert">
                    <?= h($message) ?>
                </div>

                <div class="d-grid gap-2">
                    <a href="login.php" class="btn btn-primary py-2 fw-semibold">
                        <i class="fa-solid fa-right-to-bracket me-2"></i>Go to Sign In
                    </a>
                    <a href="index.php" class="btn btn-outline-secondary py-2">
                        <i class="fa-solid fa-house me-2"></i>Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-redirect to login.php after 3 seconds on successful activation
    <?php if ($verified): ?>
        setTimeout(function() {
            window.location.href = 'login.php';
        }, 3000);
    <?php endif; ?>
</script>

<?php
$page_content = ob_get_clean();
require 'layout.php';
