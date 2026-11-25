<?php
/*
 * register.php - CREATE ACCOUNT
 * The form uses a two-column grid: related fields sit side by side on wider screens.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/check_guest.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/mailer.php';

$success_message = '';
$error_message   = '';

// Handle form submission and insert data directly into users table with inactive status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    try {
        // Ensure token column exists in users table (auto-migration if needed)
        try {
            $pdo->query("SELECT token FROM users LIMIT 1");
        } catch (PDOException $e) {
            try {
                $pdo->exec("ALTER TABLE users ADD COLUMN token VARCHAR(255) NULL DEFAULT NULL AFTER status");
            } catch (PDOException $ex) {
            }
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $token         = bin2hex(random_bytes(24));

        // Insert directly into users table with status 'inactive' and activation token
        $sql = "INSERT INTO users (fullname, email, phone, password_hash, role, status, token)
                VALUES (?, ?, ?, ?, 'customer', 'inactive', ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $fullname,
            $email,
            $phone,
            $password_hash,
            $token
        ]);

        // Build Activation Link
        $appName = env('APP_NAME', 'TrendCart');
        $appUrl  = rtrim(env('APP_URL', 'http://localhost/PHP/MCA_2026'), '/');
        $activationLink = "{$appUrl}/verify_email.php?email=" . urlencode($email) . "&token=" . urlencode($token);

        // Send Activation Email via PHPMailer
        $subject = "Activate your {$appName} account";
        $body    = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 24px; border: 1px solid #e2e8f0; border-radius: 8px; background-color: #ffffff;'>
                <h2 style='color: #198754; margin-top: 0;'>Welcome to {$appName}, " . htmlspecialchars($fullname, ENT_QUOTES, 'UTF-8') . "!</h2>
                <p style='color: #4a5568; font-size: 15px; line-height: 1.6;'>
                    Thank you for signing up. Please click the button below to verify your email address and activate your account:
                </p>
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='{$activationLink}' style='display: inline-block; background-color: #198754; color: #ffffff; text-decoration: none; padding: 12px 28px; border-radius: 6px; font-weight: bold; font-size: 15px;'>
                        Activate My Account
                    </a>
                </div>
                <p style='color: #718096; font-size: 13px; line-height: 1.5;'>
                    If the button above does not work, copy and paste this URL into your browser:<br>
                    <a href='{$activationLink}' style='color: #198754; word-break: break-all;'>{$activationLink}</a>
                </p>
                <hr style='border: none; border-top: 1px solid #e2e8f0; margin: 25px 0;'>
                <p style='color: #a0aec0; font-size: 12px; margin: 0;'>
                    If you did not register for this account, please ignore this email.
                </p>
            </div>
        ";

        @sendEmail($email, $subject, $body);

        $success_message = 'Account created successfully! We have sent an activation link to <strong>' . htmlspecialchars($email) . '</strong>. Please check your inbox and click the link to activate your account.';
    } catch (PDOException $e) {
        $error_message = 'Database error: ' . $e->getMessage();
    }
}

// STEP 1: Settings used by the shared layout.
$page_title  = 'Create Account | TrendCart';
$active_page = 'register';

// STEP 2: Start capturing this page's HTML.
ob_start();
?>

<div class="container py-4 py-lg-5">
    <nav aria-label="breadcrumb" class="register-breadcrumb mx-auto">
        <ol class="breadcrumb small mb-3">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Create Account</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12 mx-auto">
            <div class="card auth-card register-card mx-auto store-card">
                <div class="auth-strip bg-success"></div>
                <div class="card-body p-4 p-lg-4">

                    <div class="text-center mb-4">
                        <span class="badge text-bg-success mb-3 px-3 py-2">
                            <i class="fa-solid fa-user-plus me-1"></i>CREATE ACCOUNT
                        </span>
                        <h1 class="h3 fw-bold mb-2">Join TrendCart</h1>
                        <p class="text-muted hint mb-3">
                            Create an account for faster checkout, order tracking and member-only deals.
                        </p>
                        <div class="d-flex flex-wrap justify-content-center gap-3 hint text-muted">
                            <span><i class="fa-solid fa-check text-success me-1"></i>Faster checkout</span>
                            <span><i class="fa-solid fa-check text-success me-1"></i>Order tracking</span>
                            <span><i class="fa-solid fa-check text-success me-1"></i>Exclusive offers</span>
                        </div>
                    </div>

                    <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i><?= $success_message ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <i class="fa-solid fa-circle-exclamation me-2"></i><?= h($error_message) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>

                    <!-- Full name, contact details, then passwords. -->
                    <form action="register.php" method="post" id="registerForm" novalidate>
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="fullname" class="form-label small fw-semibold">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                    <input type="text" class="form-control" id="fullname" name="fullname"
                                        placeholder="Your full name" autocomplete="name"
                                        data-validation="required min max" data-min="2" data-max="50">
                                </div>
                                <div class="invalid-feedback" id="fullnameError"></div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="email" class="form-label small fw-semibold">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="you@example.com" autocomplete="email"
                                        data-validation="required email" onblur="setupEmailCheck()">
                                </div>
                                <div class="invalid-feedback" id="emailError"></div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="phone" class="form-label small fw-semibold">Mobile Number</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                                    <input type="tel" class="form-control" id="phone" name="phone"
                                        placeholder="+91 98765 43210" autocomplete="tel" data-validation="required max"
                                        data-max="15">
                                </div>
                                <div class="invalid-feedback" id="phoneError"></div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="password" class="form-label small fw-semibold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="At least 6 characters" autocomplete="new-password"
                                        data-validation="required min" data-min="6">
                                </div>
                                <div class="invalid-feedback" id="passwordError"></div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="confirm" class="form-label small fw-semibold">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                    <input type="password" class="form-control" id="confirm" name="confirm"
                                        placeholder="Repeat your password" autocomplete="new-password"
                                        data-validation="required confirmPassword min" data-min="6"
                                        data-password-id="password">
                                </div>
                                <div class="invalid-feedback" id="confirmError"></div>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" name="terms"
                                        data-validation="required" <?= isset($_POST['terms']) ? 'checked' : '' ?>>
                                    <label class="form-check-label hint" for="terms">
                                        I agree to the <a href="about.php" class="text-decoration-none">Terms of
                                            Service</a>
                                        and <a href="about.php" class="text-decoration-none">Privacy Policy</a>.
                                    </label>
                                    <div class="invalid-feedback" id="termsError"></div>
                                </div>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
                                    <i class="fa-solid fa-user-plus me-2"></i>Create Account
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="d-flex align-items-center my-4">
                        <hr class="flex-grow-1">
                        <span class="text-muted hint px-3 mb-0">or sign up with</span>
                        <hr class="flex-grow-1">
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <button type="button" class="btn btn-outline-secondary w-100 btn-sm py-2">
                                <i class="fa-brands fa-google text-danger me-2"></i>Google
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-outline-secondary w-100 btn-sm py-2">
                                <i class="fa-brands fa-facebook-f text-primary me-2"></i>Facebook
                            </button>
                        </div>
                    </div>

                    <hr class="my-4">

                    <p class="text-center hint mb-0">
                        Already have an account?
                        <a href="login.php" class="text-decoration-none fw-semibold">Sign in</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function setupEmailCheck() {
    var email = $('#email').val().trim();
    if (email === '') return;

    $.ajax({
        type: 'GET',
        url: 'check_duplicate_Email.php',
        data: {
            email1: email
        },
        success: function(response) {
            if (response.trim() === 'true') {
                $('#emailError').text('Email already registered.').show();
                $('#email').addClass('is-invalid').removeClass('is-valid');
                $('#emailError').addClass('text-danger').removeClass('text-success');
            } else {
                $('#emailError').text('This email is available.').show();
                $('#email').removeClass('is-invalid').addClass('is-valid');
                $('#emailError').addClass('text-success').removeClass('text-danger');
            }
        }
    });
}
window.setupEmailCheck = setupEmailCheck;
</script>

<?php
// STEP 3: Save the captured HTML and stop buffering.
$page_content = ob_get_clean();

// STEP 4: Render it inside the shared layout.
require 'layout.php';