<?php
/*
 * forgot-password.php - FORGOT PASSWORD (OTP REQUEST)
 * Generates a 2-minute OTP and enforces max attempts limit in 24 hours.
 */

require_once __DIR__ . '/check_guest.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/mailer.php';

// Auto-create missing tables defensively
try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS password_reset_otps (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(180) NOT NULL,
            otp VARCHAR(10) NOT NULL,
            expires_at DATETIME NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_email (email),
            INDEX idx_expires (expires_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS password_reset_attempts (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(180) NOT NULL,
            ip_address VARCHAR(45) NULL DEFAULT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_email_time (email, created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
} catch (PDOException $e) {}

$error_message = '';
$email_val     = '';
$max_attempts  = 5; // Maximum OTP requests allowed in every 24 hours

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email     = trim($_POST['email'] ?? '');
    $email_val = $email;

    if ($email === '') {
        $error_message = 'Please enter your registered email address.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Please enter a valid email address.';
    } else {
        try {
            $user = db_one("SELECT * FROM users WHERE email = ? LIMIT 1", [$email]);

            if (!$user) {
                $error_message = 'No account found with this email address.';
            } elseif (strtolower($user['status']) !== 'active') {
                $error_message = 'Your account is inactive. Please activate your account via the activation link sent to your email.';
            } else {
                // Check 24-hour rate limit
                $attempts_count = (int)(db_one(
                    "SELECT COUNT(*) AS c FROM password_reset_attempts WHERE email = ? AND created_at >= NOW() - INTERVAL 24 HOUR",
                    [$email]
                )['c'] ?? 0);

                if ($attempts_count >= $max_attempts) {
                    $error_message = "You have reached the maximum allowed OTP requests ({$max_attempts} attempts per 24 hours). Please try again after 24 hours.";
                } else {
                    // Generate 6-digit numeric OTP and 2-minute expiration
                    $otp = str_pad((string)random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

                    // Clean previous OTPs for this email and save new one valid for exactly 2 minutes
                    db_exec("DELETE FROM password_reset_otps WHERE email = ?", [$email]);
                    db_exec(
                        "INSERT INTO password_reset_otps (email, otp, expires_at, created_at) VALUES (?, ?, NOW() + INTERVAL 2 MINUTE, NOW())",
                        [$email, $otp]
                    );

                    // Record attempt for 24-hour limit
                    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
                    db_exec(
                        "INSERT INTO password_reset_attempts (email, ip_address, created_at) VALUES (?, ?, NOW())",
                        [$email, $ip]
                    );

                    // Send email via PHPMailer
                    $appName = env('APP_NAME', 'TrendCart');
                    $subject = "Your {$appName} Password Reset OTP";
                    $body = "
                        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 24px; border: 1px solid #e2e8f0; border-radius: 8px; background-color: #ffffff;'>
                            <h2 style='color: #0d6efd; margin-top: 0;'>{$appName} Password Reset</h2>
                            <p style='color: #4a5568; font-size: 15px;'>Hello " . htmlspecialchars($user['fullname']) . ",</p>
                            <p style='color: #4a5568; font-size: 15px;'>We received a request to reset your password. Use the One-Time Password (OTP) below to proceed:</p>
                            <div style='text-align: center; margin: 30px 0;'>
                                <div style='display: inline-block; background-color: #f8fafc; border: 2px dashed #0d6efd; padding: 14px 32px; font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #0d6efd; border-radius: 8px;'>
                                    {$otp}
                                </div>
                            </div>
                            <p style='color: #dc3545; font-size: 14px; font-weight: bold; text-align: center; margin-bottom: 24px;'>
                                <span style='font-size: 18px;'>&#9201;</span> This OTP is valid for exactly 2 minutes only.
                            </p>
                            <p style='color: #718096; font-size: 13px; line-height: 1.5;'>
                                If you did not request this password reset, please ignore this email. Your current password remains unchanged.
                            </p>
                            <hr style='border: none; border-top: 1px solid #e2e8f0; margin: 24px 0;'>
                            <p style='color: #a0aec0; font-size: 12px; margin: 0;'>
                                Note: A maximum of {$max_attempts} OTP requests are allowed every 24 hours.
                            </p>
                        </div>
                    ";

                    sendEmail($email, $subject, $body);

                    // Store session data for reset-password.php
                    $_SESSION['reset_email']    = $email;
                    $_SESSION['otp_expires_at'] = strtotime($expires_at);
                    $_SESSION['reset_flash']    = [
                        'type'    => 'success',
                        'message' => 'A 6-digit OTP has been sent to <strong>' . htmlspecialchars($email) . '</strong>. It is valid for <strong>2 minutes</strong>.'
                    ];

                    header('Location: reset-password.php');
                    exit;
                }
            }
        } catch (PDOException $e) {
            $error_message = 'Database error: ' . $e->getMessage();
        }
    }
}

// STEP 1: Settings used by the shared layout.
$page_title  = 'Forgot Password | TrendCart';
$active_page = 'forgot';

// STEP 2: Start capturing this page's HTML.
ob_start();
?>

<div class="container py-4 py-lg-5">
    <nav aria-label="breadcrumb" class="auth-breadcrumb mx-auto">
        <ol class="breadcrumb small mb-3">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="login.php" class="text-decoration-none">Sign In</a></li>
            <li class="breadcrumb-item active" aria-current="page">Forgot Password</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-12 mx-auto">
            <div class="card auth-card mx-auto store-card">
                <div class="auth-strip bg-warning"></div>
                <div class="card-body p-4 p-lg-4">

                    <div class="text-center mb-4">
                        <div class="fs-1 text-warning mb-3"><i class="fa-solid fa-key"></i></div>
                        <span class="badge text-bg-primary mb-3 px-3 py-2">
                            <i class="fa-solid fa-shield-halved me-1"></i>PASSWORD RECOVERY
                        </span>
                        <h1 class="h3 fw-bold mb-2">Reset your password</h1>
                        <p class="text-muted hint mb-0">
                            Enter your registered email address. We'll send you a secure 6-digit OTP valid for 2
                            minutes.
                        </p>
                    </div>

                    <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <i class="fa-solid fa-circle-exclamation me-2"></i><?= htmlspecialchars($error_message) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>

                    <form action="forgot-password.php" method="post" id="forgotPasswordForm" novalidate>
                        <div class="mb-4">
                            <label for="email" class="form-label small fw-semibold">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="you@example.com" autocomplete="email"
                                    value="<?= htmlspecialchars($email_val) ?>" data-validation="required email"
                                    onblur="checkEmailAvailability()">
                            </div>
                            <div class="invalid-feedback text-danger fw-semibold" id="emailError"
                                style="display: none; color: #dc3545;"></div>
                            <div class="form-text">
                                Max <?= $max_attempts ?> attempts allowed per 24 hours &middot; OTP valid for 2 minutes.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning w-100 py-2 fw-semibold" id="submitOtpBtn">
                            <i class="fa-solid fa-paper-plane me-2"></i>Send Verification OTP
                        </button>
                    </form>

                    <div class="alert alert-light border hint mt-4 mb-0">
                        <i class="fa-solid fa-circle-info text-primary me-2"></i>
                        Remember your password?
                        <a href="login.php" class="text-decoration-none fw-semibold">Sign In here</a>.
                    </div>

                    <hr class="my-4">

                    <p class="text-center hint mb-0">
                        <a href="login.php" class="text-decoration-none fw-semibold">
                            <i class="fa-solid fa-arrow-left me-1"></i>Back to sign in
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function checkEmailAvailability() {
    var email = $('#email').val().trim();
    if (email === '') return;

    var emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailRegex.test(email)) {
        return;
    }

    $.ajax({
        url: 'check_duplicate_Email.php',
        type: 'GET',
        data: {
            email1: email
        },
        success: function(response) {
            if (response.trim() === 'true') {
                $('#email').removeClass('is-invalid').addClass('is-valid');
                $('#emailError').text('Account found. You can proceed with password reset.').removeClass(
                    'text-danger').addClass('text-success');
                $('#submitOtpBtn').prop('disabled', false);
            } else {
                $('#email').addClass('is-invalid').removeClass('is-valid');
                $('#emailError').text('No account found with this email address.').removeClass(
                    'text-success').addClass('text-danger');

                $('#submitOtpBtn').prop('disabled', true);
            }
        }
    });
}
window.checkEmailAvailability = checkEmailAvailability;

$(document).ready(function() {
    $(document).on('input', '#email', function() {
        $('#submitOtpBtn').prop('disabled', false);
        $('#email').removeClass('is-invalid is-valid');
        $('#emailError').hide().text('').removeClass('text-danger text-success').css('display', 'none');
    });

    $('#forgotPasswordForm').on('submit', function(e) {
        if ($('#email').hasClass('is-invalid')) {
            e.preventDefault();
            $('#emailError')
                .text('No account found with this email address.')
                .css({
                    'color': '#dc3545',
                    'display': 'block'
                })
                .addClass('text-danger')
                .removeClass('text-success');
            return false;
        }
    });
});
</script>
<?php
// STEP 3: Save the captured HTML and stop buffering.
$page_content = ob_get_clean();

// STEP 4: Render it inside the shared layout.
require 'layout.php';