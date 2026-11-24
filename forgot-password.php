<?php
/*
 * forgot-password.php - RESET PASSWORD
 */

// STEP 1: Settings used by the shared layout.
$page_title  = 'Reset Password | TrendCart';
$active_page = 'forgot';

// STEP 2: Start capturing this page's HTML.
ob_start();
?>

<div class="container py-4 py-lg-5">
    <nav aria-label="breadcrumb" class="auth-breadcrumb mx-auto">
        <ol class="breadcrumb small mb-3">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="login.php" class="text-decoration-none">Sign In</a></li>
            <li class="breadcrumb-item active" aria-current="page">Reset Password</li>
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
                            <i class="fa-solid fa-key me-1"></i>ACCOUNT HELP
                        </span>
                        <h1 class="h3 fw-bold mb-2">Reset your password</h1>
                        <p class="text-muted hint mb-0">
                            Enter the email address linked to your account. We'll send you a
                            secure link to choose a new password.
                        </p>
                    </div>

                    <form action="#" method="post" novalidate>
                        <div class="mb-4">
                            <label for="email" class="form-label small fw-semibold">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email"
                                       placeholder="you@example.com" autocomplete="email"
                                       data-validation="required email">
                            </div>
                            <div class="invalid-feedback" id="emailError"></div>
                            <div class="form-text">Use the address you registered with.</div>
                        </div>

                        <button type="submit" class="btn btn-warning w-100 py-2 fw-semibold">
                            <i class="fa-solid fa-paper-plane me-2"></i>Send Reset Link
                        </button>
                    </form>

                    <div class="alert alert-light border hint mt-4 mb-0">
                        <i class="fa-solid fa-circle-info text-primary me-2"></i>
                        Didn't receive the email? Check your spam folder or
                        <a href="contact.php" class="text-decoration-none">contact our support team</a>.
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

<?php
// STEP 3: Save the captured HTML and stop buffering.
$page_content = ob_get_clean();

// STEP 4: Render it inside the shared layout.
require 'layout.php';
