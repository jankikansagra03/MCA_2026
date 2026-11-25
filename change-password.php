<?php
/*
 * change-password.php - CHANGE PASSWORD
 * Authenticated password update with current password validation.
 */

require_once __DIR__ . '/check_auth.php';
require_once __DIR__ . '/db.php';

$user    = current_user();
$user_id = $user['id'];

$success_message = '';
$error_message   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['currentPassword'] ?? '';
    $newPassword     = $_POST['newPassword'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
        $error_message = 'Please fill in all password fields.';
    } elseif (strlen($newPassword) < 6) {
        $error_message = 'New password must be at least 6 characters long.';
    } elseif ($newPassword !== $confirmPassword) {
        $error_message = 'New password and confirm password do not match.';
    } else {
        try {
            // Re-fetch latest password hash from database
            $dbUser = db_one("SELECT password_hash FROM users WHERE id = ? LIMIT 1", [$user_id]);

            if ($dbUser) {
                $hash = $dbUser['password_hash'];
                $currentValid = password_verify($currentPassword, $hash) || ($currentPassword === $hash);

                if (!$currentValid) {
                    $error_message = 'Current password is incorrect.';
                } else {
                    // Hash new password and update
                    $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
                    db_exec("UPDATE users SET password_hash = ? WHERE id = ?", [$newHash, $user_id]);

                    $success_message = 'Password changed successfully! Keep your new password safe.';
                }
            } else {
                $error_message = 'User account not found.';
            }
        } catch (PDOException $e) {
            $error_message = 'Database error: ' . $e->getMessage();
        }
    }
}

// STEP 1: Settings used by the shared layout.
$page_title     = 'Change Password | TrendCart';
$active_page    = 'change-password';
$account_active = 'change-password';

// STEP 2: Start capturing this page's HTML.
ob_start();
?>

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="account.php" class="text-decoration-none">My Account</a></li>
            <li class="breadcrumb-item active" aria-current="page">Change Password</li>
        </ol>
    </nav>

    <div class="page-header">
        <span class="badge text-bg-primary mb-3 px-3 py-2">
            <i class="fa-solid fa-key me-1"></i>SECURITY
        </span>
        <h1 class="h2 fw-bold mb-3">Change Password</h1>
        <p class="text-muted">Ensure your account is using a long, random password to stay secure.</p>
    </div>

    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i><?= htmlspecialchars($success_message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i><?= htmlspecialchars($error_message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- ========== SHARED SIDEBAR ========== -->
        <aside class="col-12 col-lg-3">
            <?php require 'account-nav.php'; ?>
        </aside>

        <div class="col-12 col-lg-9">
            <!-- Security banner -->
            <div class="card store-card overflow-hidden mb-4">
                <div class="order-banner p-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="feature-icon bg-white text-primary">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        <div>
                            <p class="hint mb-1">ACCOUNT SECURITY</p>
                            <h2 class="h5 fw-bold mb-1">Keep your password strong</h2>
                            <p class="hint mb-0">Use at least 8 characters with a mix of letters, numbers &amp; symbols.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- ========== PASSWORD FORM ========== -->
                <div class="col-12 col-xl-7">
                    <form action="change-password.php" method="post" novalidate>
                        <div class="card store-card h-100">
                            <div class="card-body p-4">
                                <div class="section-heading mb-4">
                                    <div class="feature-icon"><i class="fa-solid fa-key"></i></div>
                                    <div>
                                        <h2 class="h6 fw-bold mb-0">Create a new password</h2>
                                        <p class="hint text-muted mb-0">Enter your current password first, then choose a new one.</p>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="currentPassword" class="form-label small fw-semibold">Current Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                        <input type="password" class="form-control" id="currentPassword"
                                            name="currentPassword" placeholder="Enter current password"
                                            autocomplete="current-password" data-validation="required">
                                    </div>
                                    <div class="invalid-feedback" id="currentPasswordError"></div>
                                </div>

                                <div class="mb-4">
                                    <label for="newPassword" class="form-label small fw-semibold">New Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                                        <input type="password" class="form-control" id="newPassword" name="newPassword"
                                            placeholder="At least 6 characters" autocomplete="new-password"
                                            data-validation="required min" data-min="6">
                                    </div>
                                    <div class="invalid-feedback" id="newPasswordError"></div>
                                    <div class="form-text">Minimum 6 characters.</div>
                                </div>

                                <div class="mb-4">
                                    <label for="confirmPassword" class="form-label small fw-semibold">Confirm New Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                        <input type="password" class="form-control" id="confirmPassword"
                                            name="confirmPassword" placeholder="Repeat new password"
                                            autocomplete="new-password" data-validation="required confirmPassword min"
                                            data-min="6" data-password-id="newPassword">
                                    </div>
                                    <div class="invalid-feedback" id="confirmPasswordError"></div>
                                </div>
                            </div>

                            <div class="card-footer bg-white p-4 border-top d-flex flex-wrap justify-content-between align-items-center gap-2">
                                <a href="profile.php" class="hint text-decoration-none fw-semibold">
                                    <i class="fa-solid fa-arrow-left me-1"></i>Back to Profile
                                </a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fa-solid fa-shield-halved me-1"></i>Update Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- ========== SECURITY SIDEBAR ========== -->
                <div class="col-12 col-xl-5">
                    <div class="card store-card mb-4">
                        <div class="card-body p-4">
                            <div class="section-heading mb-3">
                                <div class="feature-icon"><i class="fa-solid fa-circle-check"></i></div>
                                <div>
                                    <h2 class="h6 fw-bold mb-0">Password Guidelines</h2>
                                    <p class="hint text-muted mb-0">Best practices for a secure account.</p>
                                </div>
                            </div>
                            <ul class="list-unstyled security-list mb-0 gy-2">
                                <li class="mb-2"><i class="fa-solid fa-circle-check text-primary me-2"></i>At least 6 to 8 characters</li>
                                <li class="mb-2"><i class="fa-solid fa-circle-check text-primary me-2"></i>Mix of letters and numbers</li>
                                <li class="mb-2"><i class="fa-solid fa-circle-check text-primary me-2"></i>Avoid using your name or birthdate</li>
                                <li><i class="fa-solid fa-circle-check text-primary me-2"></i>Do not share your password with anyone</li>
                            </ul>
                            <div class="alert alert-primary border-0 hint mt-4 mb-0 py-2">
                                <i class="fa-solid fa-lightbulb me-1"></i>A memorable phrase with numbers makes a strong password.
                            </div>
                        </div>
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
