<?php
/*
 * login.php - SIGN IN
 * A compact two-panel card: brand story on the left, form on the right.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/check_guest.php';

$flash = $_SESSION['flash_message'] ?? null;
unset($_SESSION['flash_message']);

$login_error = '';
$email_val   = $_COOKIE['remember_user'] ?? '';

// Handle login submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email     = trim($_POST['email'] ?? '');
    $password  = $_POST['password'] ?? '';
    $email_val = $email;


        try {
            $user = db_one("SELECT * FROM users WHERE email = ? LIMIT 1", [$email]);

            if ($user) {
                // Verify account status
                if (strtolower($user['status']) !== 'active') {
                    $login_error = 'Your account is inactive. Please check your email for the activation link to activate your account.';
                } else {
                    // Check password using password_verify, fallback to plaintext for old demo rows
                    $password_valid = password_verify($password, $user['password_hash']) || ($password === $user['password_hash']);

                    if ($password_valid) {
                        // Store user session data
                        $_SESSION['user_id']    = $user['id'];
                        $_SESSION['user_name']  = $user['fullname'];
                        $_SESSION['user_email'] = $user['email'];
                        $_SESSION['user_role']  = $user['role'];
                        $_SESSION['user']       = $user;

                        // Remember email cookie
                        if (!empty($_POST['remember'])) {
                            setcookie('remember_user', $user['email'], time() + (30 * 24 * 60 * 60), '/');
                        } else {
                            if (isset($_COOKIE['remember_user'])) {
                                setcookie('remember_user', '', time() - 3600, '/');
                            }
                        }

                        // Redirect based on role or requested page
                        if (strtolower($user['role']) === 'admin') {
                            header('Location: admin/admin.php');
                        } else {
                            $redirect = $_GET['redirect'] ?? 'account.php';
                            header("Location: {$redirect}");
                        }
                        exit;
                    } else {
                        $login_error = 'Invalid email address or password.';
                    }
                }
            } else {
                $login_error = 'Invalid email address or password.';
            }
        } catch (PDOException $e) {
            $login_error = 'Database connection error: ' . $e->getMessage();
        }
    }

// STEP 1: Settings used by the shared layout.
$page_title  = 'Sign In | TrendCart';
$active_page = 'login';

// STEP 2: Start capturing this page's HTML.
ob_start();
?>

<div class="container py-4 py-lg-5">
    <nav aria-label="breadcrumb" class="login-breadcrumb mx-auto">
        <ol class="breadcrumb small mb-3">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Sign In</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card auth-split mx-auto store-card">
                <div class="auth-strip bg-primary"></div>

                <div class="row g-0">
                    <!-- ========== BRAND PANEL ========== -->
                    <div class="col-md-5 auth-panel text-white p-4 p-lg-5 d-none d-md-flex flex-column">
                        <a class="navbar-brand fw-bold fs-4 text-white" href="index.php">
                            <i class="fa-solid fa-bag-shopping me-2"></i>Trend<span class="on-dark-accent">Cart</span>
                        </a>

                        <div class="fs-1 on-dark-accent my-4"><i class="fa-solid fa-bag-shopping"></i></div>
                        <h2 class="h3 fw-bold mb-3">Good to see you again.</h2>
                        <p class="text-white-50 mb-4">
                            Sign in to pick up where you left off and enjoy a faster,
                            more personal shopping experience.
                        </p>

                        <ul class="list-unstyled small mb-0">
                            <li class="mb-3"><i class="fa-solid fa-circle-check on-dark-accent me-2"></i>Track all your
                                orders in one place</li>
                            <li class="mb-3"><i class="fa-solid fa-circle-check on-dark-accent me-2"></i>Save favourites
                                to your wishlist</li>
                            <li class="mb-3"><i class="fa-solid fa-circle-check on-dark-accent me-2"></i>Member-only
                                deals and faster checkout</li>
                        </ul>

                        <p class="text-white-50 small mt-auto mb-0">
                            New here?
                            <a href="register.php" class="text-white fw-bold text-decoration-none">Create an account</a>
                        </p>
                    </div>

                    <!-- ========== SIGN-IN FORM ========== -->
                    <div class="col-md-7 p-4 p-lg-5">
                        <span class="badge text-bg-primary mb-3 px-3 py-2">
                            <i class="fa-solid fa-right-to-bracket me-1"></i>WELCOME BACK
                        </span>
                        <h1 class="h3 fw-bold mb-2">Sign in to your account</h1>
                        <p class="text-muted hint mb-4">
                            Enter your details below to continue shopping.
                        </p>

                        <?php if ($flash): ?>
                        <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show mb-4"
                            role="alert">
                            <?= htmlspecialchars($flash['message']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($login_error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="fa-solid fa-circle-exclamation me-2"></i><?= htmlspecialchars($login_error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php endif; ?>

                        <form
                            action="login.php<?= isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : '' ?>"
                            method="post" novalidate>
                            <div class="mb-3">
                                <label for="email" class="form-label small fw-semibold">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="you@example.com" autocomplete="email"
                                        value="<?= htmlspecialchars($email_val) ?>" data-validation="required email">
                                </div>
                                <div class="invalid-feedback" id="emailError"></div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label small fw-semibold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Enter your password" autocomplete="current-password"
                                        data-validation="required">
                                </div>
                                <div class="invalid-feedback" id="passwordError"></div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember"
                                        value="1" <?= !empty($email_val) ? 'checked' : '' ?>>
                                    <label class="form-check-label hint" for="remember">Keep me signed in</label>
                                </div>
                                <a href="forgot-password.php" class="text-decoration-none hint">Forgot password?</a>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                                <i class="fa-solid fa-right-to-bracket me-2"></i>Sign In
                            </button>
                        </form>

                        <div class="d-flex align-items-center my-4">
                            <hr class="flex-grow-1">
                            <span class="text-muted hint px-3 mb-0">or continue with</span>
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

                        <p class="text-center hint mb-0 mt-4 d-md-none">
                            New to TrendCart?
                            <a href="register.php" class="text-decoration-none fw-semibold">Create an account</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// STEP 3: Save the captured HTML and stop buffering.
$page_content = ob_get_clean();

// STEP 4: Render it inside the shared layout.
require 'layout.php';