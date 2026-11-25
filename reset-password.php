<?php
/*
 * reset-password.php - VERIFY OTP & SET NEW PASSWORD
 * Enforces 2-minute OTP validity, live countdown timer, resend button after expiry,
 * 24-hour rate limit checks, and deletes OTP upon successful password reset.
 */

require_once __DIR__ . '/check_guest.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/mailer.php';

$email = $_SESSION['reset_email'] ?? '';
if (empty($email)) {
    header('Location: forgot-password.php');
    exit;
}

$flash = $_SESSION['reset_flash'] ?? null;
unset($_SESSION['reset_flash']);

$error_message   = '';
$success_message = '';
$max_attempts    = 5;

// -------------------------------------------------------------
// 1. HANDLE RESEND OTP ACTION
// -------------------------------------------------------------
$action = $_POST['action'] ?? ($_GET['action'] ?? '');

if ($action === 'resend') {
    try {
        // Check 24-hour rate limit
        $attempts_count = (int)(db_one(
            "SELECT COUNT(*) AS c FROM password_reset_attempts WHERE email = ? AND created_at >= NOW() - INTERVAL 24 HOUR",
            [$email]
        )['c'] ?? 0);

        if ($attempts_count >= $max_attempts) {
            $error_message = "You have reached the maximum allowed OTP requests ({$max_attempts} attempts per 24 hours). Please try again after 24 hours.";
        } else {
            // Generate fresh 6-digit OTP valid for 2 minutes
            $otp = str_pad((string)random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

            db_exec("DELETE FROM password_reset_otps WHERE email = ?", [$email]);
            db_exec(
                "INSERT INTO password_reset_otps (email, otp, expires_at, created_at) VALUES (?, ?, NOW() + INTERVAL 2 MINUTE, NOW())",
                [$email, $otp]
            );

            // Record attempt for 24-hour rate limiting
            $ip = $_SERVER['REMOTE_ADDR'] ?? null;
            db_exec(
                "INSERT INTO password_reset_attempts (email, ip_address, created_at) VALUES (?, ?, NOW())",
                [$email, $ip]
            );

            // Send email via PHPMailer
            $user = db_one("SELECT fullname FROM users WHERE email = ? LIMIT 1", [$email]);
            $appName = env('APP_NAME', 'TrendCart');
            $subject = "Your New {$appName} Password Reset OTP";
            $body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 24px; border: 1px solid #e2e8f0; border-radius: 8px; background-color: #ffffff;'>
                    <h2 style='color: #0d6efd; margin-top: 0;'>{$appName} Password Reset</h2>
                    <p style='color: #4a5568; font-size: 15px;'>Hello " . htmlspecialchars($user['fullname'] ?? 'Customer') . ",</p>
                    <p style='color: #4a5568; font-size: 15px;'>Here is your new One-Time Password (OTP) to reset your password:</p>
                    <div style='text-align: center; margin: 30px 0;'>
                        <div style='display: inline-block; background-color: #f8fafc; border: 2px dashed #0d6efd; padding: 14px 32px; font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #0d6efd; border-radius: 8px;'>
                            {$otp}
                        </div>
                    </div>
                    <p style='color: #dc3545; font-size: 14px; font-weight: bold; text-align: center; margin-bottom: 24px;'>
                        <span style='font-size: 18px;'>&#9201;</span> This OTP is valid for exactly 2 minutes only.
                    </p>
                    <p style='color: #718096; font-size: 13px; line-height: 1.5;'>
                        If you did not request this OTP, please ignore this email.
                    </p>
                </div>
            ";
            sendEmail($email, $subject, $body);

            $_SESSION['reset_flash'] = [
                'type'    => 'success',
                'message' => 'A new 6-digit OTP has been sent to <strong>' . htmlspecialchars($email) . '</strong>. Valid for <strong>2 minutes</strong>.'
            ];
            header('Location: reset-password.php');
            exit;
        }
    } catch (PDOException $e) {
        $error_message = 'Database error: ' . $e->getMessage();
    }
}

// -------------------------------------------------------------
// 2. HANDLE RESET PASSWORD SUBMISSION
// -------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($action === 'reset_password' || empty($action))) {
    $entered_otp     = trim($_POST['otp'] ?? '');
    $new_password    = $_POST['newPassword'] ?? '';
    $confirm_password= $_POST['confirmPassword'] ?? '';

    if (empty($entered_otp)) {
        $error_message = 'Please enter the 6-digit OTP sent to your email.';
    } elseif (strlen($entered_otp) !== 6 || !ctype_digit($entered_otp)) {
        $error_message = 'The OTP must be a 6-digit number.';
    } elseif (empty($new_password)) {
        $error_message = 'Please enter a new password.';
    } elseif (strlen($new_password) < 6) {
        $error_message = 'Password must be at least 6 characters long.';
    } elseif ($new_password !== $confirm_password) {
        $error_message = 'New passwords do not match. Please re-enter.';
    } else {
        try {
            // Check OTP in database with remaining seconds computed by MySQL
            $otp_record = db_one(
                "SELECT *, TIMESTAMPDIFF(SECOND, NOW(), expires_at) AS remaining_seconds 
                 FROM password_reset_otps 
                 WHERE email = ? AND otp = ? LIMIT 1",
                [$email, $entered_otp]
            );

            if (!$otp_record) {
                $error_message = 'Invalid OTP entered. Please check your email or click Resend OTP.';
            } elseif ((int)$otp_record['remaining_seconds'] <= 0) {
                $error_message = 'This OTP has expired (validity was 2 minutes). Please click the "Resend OTP" button below to receive a new code.';
            } else {
                // OTP is valid and within 2 minutes!
                // 1. Update user password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                db_exec("UPDATE users SET password_hash = ? WHERE email = ?", [$hashed_password, $email]);

                // 2. Delete OTP from database as required
                db_exec("DELETE FROM password_reset_otps WHERE email = ?", [$email]);

                // 3. Clear session reset data
                unset($_SESSION['reset_email']);

                // 4. Set flash message and redirect to login
                $_SESSION['flash_message'] = [
                    'type'    => 'success',
                    'message' => 'Password reset successfully! You can now sign in with your new password.'
                ];
                header('Location: login.php');
                exit;
            }
        } catch (PDOException $e) {
            $error_message = 'Database error: ' . $e->getMessage();
        }
    }
}

// -------------------------------------------------------------
// 3. GET ACTIVE OTP DETAILS FOR COUNTDOWN TIMER
// -------------------------------------------------------------
try {
    $active_otp = db_one(
        "SELECT *, TIMESTAMPDIFF(SECOND, NOW(), expires_at) AS remaining_seconds 
         FROM password_reset_otps 
         WHERE email = ? ORDER BY id DESC LIMIT 1",
        [$email]
    );
    $remaining_seconds = $active_otp ? max(0, (int)$active_otp['remaining_seconds']) : 0;
} catch (PDOException $e) {
    $active_otp = null;
    $remaining_seconds = 0;
}

// STEP 1: Settings used by the shared layout.
$page_title  = 'Verify OTP & Reset Password | TrendCart';
$active_page = 'forgot';

// STEP 2: Start capturing this page's HTML.
ob_start();
?>

<div class="container py-4 py-lg-5">
    <nav aria-label="breadcrumb" class="auth-breadcrumb mx-auto">
        <ol class="breadcrumb small mb-3">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="login.php" class="text-decoration-none">Sign In</a></li>
            <li class="breadcrumb-item"><a href="forgot-password.php" class="text-decoration-none">Forgot Password</a></li>
            <li class="breadcrumb-item active" aria-current="page">Verify OTP</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-12 mx-auto">
            <div class="card auth-card mx-auto store-card">
                <div class="auth-strip bg-primary"></div>
                <div class="card-body p-4 p-lg-4">

                    <div class="text-center mb-4">
                        <div class="fs-1 text-primary mb-3"><i class="fa-solid fa-envelope-circle-check"></i></div>
                        <span class="badge text-bg-primary mb-3 px-3 py-2">
                            <i class="fa-solid fa-lock me-1"></i>VERIFY OTP
                        </span>
                        <h1 class="h3 fw-bold mb-2">Enter verification code</h1>
                        <p class="text-muted hint mb-0">
                            We've sent a 6-digit OTP to <strong class="text-dark"><?= htmlspecialchars($email) ?></strong>
                        </p>
                    </div>

                    <?php if ($flash): ?>
                        <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show mb-4" role="alert">
                            <i class="fa-solid fa-circle-check me-2"></i><?= $flash['message'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="fa-solid fa-circle-exclamation me-2"></i><?= htmlspecialchars($error_message) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Timer Box -->
                    <div class="card bg-light border-0 mb-4 text-center p-3 rounded">
                        <div class="d-flex align-items-center justify-content-center gap-2 mb-1">
                            <i class="fa-regular fa-clock text-primary"></i>
                            <span class="small fw-semibold text-muted">OTP Validity (2 minutes):</span>
                            <span id="countdownDisplay" class="badge <?= $remaining_seconds > 0 ? 'bg-primary' : 'bg-danger' ?> fs-6 px-2 py-1">
                                <?= sprintf('%02d:%02d', floor($remaining_seconds / 60), $remaining_seconds % 60) ?>
                            </span>
                        </div>
                        <p id="timerStatus" class="hint text-muted mb-0 small">
                            <?= $remaining_seconds > 0 ? 'Please enter the OTP before the timer expires.' : 'This OTP has expired. Click below to request a new code.' ?>
                        </p>
                    </div>

                    <form action="reset-password.php" method="post" id="resetPasswordForm" novalidate>
                        <input type="hidden" name="action" value="reset_password">

                        <!-- 6-digit OTP input -->
                        <div class="mb-3">
                            <label for="otp" class="form-label small fw-semibold">6-Digit OTP</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                                <input type="text" class="form-control text-center fw-bold fs-5 tracking-widest"
                                       id="otp" name="otp" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;"
                                       maxlength="6" autocomplete="one-time-code"
                                       data-validation="required numeric min max" data-min="6" data-max="6"
                                       style="letter-spacing: 6px;">
                            </div>
                            <div class="invalid-feedback" id="otpError"></div>
                        </div>

                        <!-- New Password -->
                        <div class="mb-3">
                            <label for="newPassword" class="form-label small fw-semibold">New Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" class="form-control" id="newPassword" name="newPassword"
                                       placeholder="At least 6 characters" autocomplete="new-password"
                                       data-validation="required min" data-min="6">
                            </div>
                            <div class="invalid-feedback" id="newPasswordError"></div>
                        </div>

                        <!-- Confirm New Password -->
                        <div class="mb-4">
                            <label for="confirmPassword" class="form-label small fw-semibold">Confirm New Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"
                                       placeholder="Repeat new password" autocomplete="new-password"
                                       data-validation="required confirmPassword min" data-min="6"
                                       data-password-id="newPassword">
                            </div>
                            <div class="invalid-feedback" id="confirmPasswordError"></div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold mb-3" id="resetSubmitBtn">
                            <i class="fa-solid fa-shield-check me-2"></i>Reset Password
                        </button>
                    </form>

                    <!-- Resend OTP Section -->
                    <div class="text-center pt-2 border-top">
                        <p class="hint text-muted mb-2">Didn't receive the OTP or did it expire?</p>
                        <form action="reset-password.php" method="post" id="resendForm">
                            <input type="hidden" name="action" value="resend">
                            <button type="submit" id="resendBtn" class="btn btn-outline-primary btn-sm px-3"
                                    <?= $remaining_seconds > 0 ? 'disabled' : '' ?>>
                                <i class="fa-solid fa-rotate-right me-1"></i>Resend OTP
                            </button>
                        </form>
                    </div>

                    <hr class="my-4">

                    <p class="text-center hint mb-0">
                        <a href="forgot-password.php" class="text-decoration-none text-muted me-3">
                            <i class="fa-solid fa-arrow-left me-1"></i>Change Email
                        </a>
                        <a href="login.php" class="text-decoration-none fw-semibold">
                            Back to sign in
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var remainingSeconds = <?= (int)$remaining_seconds ?>;
    var countdownDisplay = document.getElementById('countdownDisplay');
    var timerStatus      = document.getElementById('timerStatus');
    var resendBtn        = document.getElementById('resendBtn');

    function updateTimer() {
        if (remainingSeconds <= 0) {
            countdownDisplay.textContent = 'Expired';
            countdownDisplay.className   = 'badge bg-danger fs-6 px-2 py-1';
            timerStatus.textContent      = 'This OTP has expired. Please click Resend OTP to receive a fresh code.';
            if (resendBtn) {
                resendBtn.removeAttribute('disabled');
                resendBtn.classList.remove('btn-outline-secondary');
                resendBtn.classList.add('btn-outline-primary');
            }
            return;
        }

        var minutes = Math.floor(remainingSeconds / 60);
        var seconds = remainingSeconds % 60;
        countdownDisplay.textContent = (minutes < 10 ? '0' : '') + minutes + ':' + (seconds < 10 ? '0' : '') + seconds;

        remainingSeconds--;
        setTimeout(updateTimer, 1000);
    }

    if (remainingSeconds > 0) {
        if (resendBtn) {
            resendBtn.setAttribute('disabled', 'disabled');
        }
        updateTimer();
    } else {
        updateTimer();
    }
});
</script>

<?php
// STEP 3: Save the captured HTML and stop buffering.
$page_content = ob_get_clean();

// STEP 4: Render it inside the shared layout.
require 'layout.php';
