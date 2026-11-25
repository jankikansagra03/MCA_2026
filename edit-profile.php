<?php
/*
 * edit-profile.php - EDIT PROFILE & PROFILE PICTURE
 * Updates personal information and profile picture in users table.
 */

require_once __DIR__ . '/check_auth.php';
require_once __DIR__ . '/db.php';

$user        = current_user();
$user_id     = $user['id'];
$user_avatar = user_avatar_url($user);

$success_message = '';
$error_message   = '';

// Auto-check/create dob and gender columns if not existing
try {
    $pdo->query("SELECT dob FROM users LIMIT 1");
} catch (PDOException $e) {
    try {
        $pdo->exec("ALTER TABLE users ADD COLUMN dob DATE NULL DEFAULT NULL AFTER profile_picture, ADD COLUMN gender VARCHAR(20) NULL DEFAULT NULL AFTER dob");
    } catch (PDOException $ex) {}
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname     = trim($_POST['fullname'] ?? '');
    $phone        = trim($_POST['phone'] ?? '');
    $dob          = !empty($_POST['dob']) ? trim($_POST['dob']) : null;
    $gender       = trim($_POST['gender'] ?? '');
    $remove_photo = !empty($_POST['remove_photo']);

    if ($fullname === '') {
        $error_message = 'Full Name is required.';
    } else {
        try {
            $new_profile_picture = $user['profile_picture'] ?? null;

            // Handle remove photo request
            if ($remove_photo) {
                if (!empty($user['profile_picture'])) {
                    $old_file = __DIR__ . '/uploads/profile_pictures/' . $user['profile_picture'];
                    if (file_exists($old_file)) {
                        @unlink($old_file);
                    }
                }
                $new_profile_picture = null;
            }

            // Handle profile picture upload
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                $file_tmp  = $_FILES['profile_picture']['tmp_name'];
                $file_name = $_FILES['profile_picture']['name'];
                $file_size = $_FILES['profile_picture']['size'];
                $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
                $max_size = 2 * 1024 * 1024; // 2MB

                if (!in_array($file_ext, $allowed_extensions, true)) {
                    $error_message = 'Invalid file format. Allowed formats: JPG, JPEG, PNG, WEBP.';
                } elseif ($file_size > $max_size) {
                    $error_message = 'Image file size exceeds the 2 MB limit.';
                } else {
                    $upload_dir = __DIR__ . '/uploads/profile_pictures/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }

                    // Delete previous profile picture if exists
                    if (!empty($user['profile_picture'])) {
                        $old_file = $upload_dir . $user['profile_picture'];
                        if (file_exists($old_file)) {
                            @unlink($old_file);
                        }
                    }

                    // Generate unique safe file name
                    $unique_file_name = 'avatar_' . $user_id . '_' . bin2hex(random_bytes(6)) . '.' . $file_ext;
                    $target_file = $upload_dir . $unique_file_name;

                    if (move_uploaded_file($file_tmp, $target_file)) {
                        $new_profile_picture = $unique_file_name;
                    } else {
                        $error_message = 'Failed to upload profile picture. Please check folder permissions.';
                    }
                }
            }

            if (empty($error_message)) {
                // Update users table
                $sql = "UPDATE users SET fullname = ?, phone = ?, dob = ?, gender = ?, profile_picture = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $fullname,
                    $phone !== '' ? $phone : null,
                    $dob,
                    $gender !== '' ? $gender : null,
                    $new_profile_picture,
                    $user_id
                ]);

                // Refresh user and session data
                $user = current_user(true);
                $user_avatar = user_avatar_url($user);

                $_SESSION['profile_flash'] = [
                    'type'    => 'success',
                    'message' => 'Profile updated successfully!'
                ];

                header('Location: profile.php');
                exit;
            }
        } catch (PDOException $e) {
            $error_message = 'Database error: ' . $e->getMessage();
        }
    }
}

// STEP 1: Settings used by the shared layout.
$page_title     = 'Edit Profile | TrendCart';
$active_page    = 'edit-profile';
$account_active = 'edit-profile';

// STEP 2: Start capturing this page's HTML.
ob_start();
?>

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="account.php" class="text-decoration-none">My Account</a></li>
            <li class="breadcrumb-item"><a href="profile.php" class="text-decoration-none">My Profile</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
        </ol>
    </nav>

    <div class="page-header">
        <span class="badge text-bg-primary mb-3 px-3 py-2">
            <i class="fa-solid fa-user-pen me-1"></i>EDIT PROFILE
        </span>
        <h1 class="h2 fw-bold mb-3">Update Your Details</h1>
        <p class="text-muted">Keep your contact information and profile picture current.</p>
    </div>

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
            <!-- Header strip -->
            <div class="card store-card overflow-hidden mb-4">
                <div class="profile-banner p-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <?php if ($user_avatar): ?>
                            <img src="<?= $user_avatar ?>" alt="Avatar" class="profile-avatar rounded-circle border border-3 border-white object-fit-cover shadow-sm" style="width: 64px; height: 64px;">
                        <?php else: ?>
                            <div class="profile-avatar rounded-circle border border-3 border-white d-flex align-items-center justify-content-center text-white" style="width: 64px; height: 64px;">
                                <i class="fa-regular fa-user fa-2x"></i>
                            </div>
                        <?php endif; ?>
                        <div>
                            <p class="hint mb-1">Editing profile for</p>
                            <h2 class="h4 fw-bold mb-1"><?= htmlspecialchars($user['fullname']) ?></h2>
                            <p class="hint mb-0"><i class="fa-solid fa-id-card me-1"></i>TrendCart ID: TC-<?= str_pad((string)$user['id'], 6, '0', STR_PAD_LEFT) ?></p>
                        </div>
                    </div>
                    <a href="profile.php" class="btn btn-light btn-sm px-3">
                        <i class="fa-regular fa-eye me-1"></i>View profile
                    </a>
                </div>
            </div>

            <form action="edit-profile.php" method="post" enctype="multipart/form-data" novalidate>
                <input type="hidden" name="remove_photo" id="removePhotoInput" value="0">

                <div class="row g-4">
                    <!-- ========== MAIN FORM ========== -->
                    <div class="col-12 col-xl-8">
                        <div class="card store-card mb-4">
                            <div class="card-body p-4">
                                <div class="section-heading mb-4">
                                    <div class="feature-icon"><i class="fa-solid fa-user-pen"></i></div>
                                    <div>
                                        <h2 class="h6 fw-bold mb-0">Personal Information</h2>
                                        <p class="hint text-muted mb-0">Used for your account and order notifications.</p>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="fullname" class="form-label small fw-semibold">Full Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                            <input type="text" class="form-control" id="fullname" name="fullname"
                                                value="<?= htmlspecialchars($user['fullname']) ?>" autocomplete="name"
                                                data-validation="required max" data-max="50" required>
                                        </div>
                                        <div class="invalid-feedback" id="fullnameError"></div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="email" class="form-label small fw-semibold">Email Address</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                            <input type="email" class="form-control bg-light" id="email" name="email"
                                                value="<?= htmlspecialchars($user['email']) ?>" readonly
                                                title="Email address cannot be changed">
                                        </div>
                                        <div class="form-text">Email cannot be modified directly.</div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="phone" class="form-label small fw-semibold">Mobile Number</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                                            <input type="tel" class="form-control" id="phone" name="phone"
                                                value="<?= htmlspecialchars($user['phone'] ?? '') ?>" autocomplete="tel"
                                                placeholder="+91 98765 43210"
                                                data-validation="max" data-max="15">
                                        </div>
                                        <div class="invalid-feedback" id="phoneError"></div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="dob" class="form-label small fw-semibold">Date of Birth</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
                                            <input type="date" class="form-control" id="dob" name="dob"
                                                value="<?= htmlspecialchars($user['dob'] ?? '') ?>">
                                        </div>
                                        <div class="invalid-feedback" id="dobError"></div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="gender" class="form-label small fw-semibold">Gender</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-user-group"></i></span>
                                            <select class="form-select" id="gender" name="gender">
                                                <option value="" <?= empty($user['gender']) ? 'selected' : '' ?>>Select gender</option>
                                                <option value="Male" <?= ($user['gender'] ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
                                                <option value="Female" <?= ($user['gender'] ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
                                                <option value="Prefer not to say" <?= ($user['gender'] ?? '') === 'Prefer not to say' ? 'selected' : '' ?>>Prefer not to say</option>
                                            </select>
                                        </div>
                                        <div class="invalid-feedback" id="genderError"></div>
                                    </div>

                                    <div class="col-12 pt-3 d-flex flex-wrap gap-2">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="fa-solid fa-floppy-disk me-1"></i>Save Changes
                                        </button>
                                        <a href="profile.php" class="btn btn-outline-secondary px-4">
                                            <i class="fa-solid fa-xmark me-1"></i>Cancel
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========== PROFILE PHOTO PANEL ========== -->
                    <div class="col-12 col-xl-4">
                        <div class="card store-card">
                            <div class="card-body p-4 text-center">
                                <h2 class="h6 fw-bold mb-3">Profile Photo</h2>

                                <div class="mb-3">
                                    <?php if ($user_avatar): ?>
                                        <img src="<?= $user_avatar ?>" id="avatarPreview" alt="Profile Photo"
                                             class="profile-avatar rounded-circle border border-3 border-primary object-fit-cover shadow-sm mx-auto d-block"
                                             style="width: 100px; height: 100px;">
                                    <?php else: ?>
                                        <div id="avatarFallback" class="profile-avatar rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3 shadow-sm"
                                             style="width: 100px; height: 100px;">
                                            <i class="fa-regular fa-user fa-3x"></i>
                                        </div>
                                        <img id="avatarPreview" src="" alt="Preview"
                                             class="profile-avatar rounded-circle border border-3 border-primary object-fit-cover shadow-sm mx-auto d-none"
                                             style="width: 100px; height: 100px;">
                                    <?php endif; ?>
                                </div>

                                <div class="d-grid gap-2">
                                    <label class="btn btn-sm btn-outline-primary mb-0">
                                        <i class="fa-solid fa-upload me-1"></i>Choose New Photo
                                        <input type="file" class="d-none" id="profilePictureInput"
                                               name="profile_picture" accept="image/png, image/jpeg, image/webp">
                                    </label>

                                    <?php if ($user_avatar): ?>
                                        <button type="button" class="btn btn-link btn-sm text-danger text-decoration-none" id="removePhotoBtn">
                                            <i class="fa-solid fa-trash me-1"></i>Remove Photo
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <p class="hint text-muted mt-3 mb-0">JPG, PNG or WEBP up to 2 MB.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var fileInput     = document.getElementById('profilePictureInput');
    var avatarPreview = document.getElementById('avatarPreview');
    var avatarFallback= document.getElementById('avatarFallback');
    var removeBtn     = document.getElementById('removePhotoBtn');
    var removeInput   = document.getElementById('removePhotoInput');

    // Instant image preview
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                var reader = new FileReader();
                reader.onload = function(evt) {
                    if (avatarPreview) {
                        avatarPreview.src = evt.target.result;
                        avatarPreview.classList.remove('d-none');
                    }
                    if (avatarFallback) {
                        avatarFallback.classList.add('d-none');
                    }
                };
                reader.readAsDataURL(e.target.files[0]);
                if (removeInput) removeInput.value = '0';
            }
        });
    }

    // Remove photo handling
    if (removeBtn) {
        removeBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove your profile photo?')) {
                if (removeInput) removeInput.value = '1';
                if (avatarPreview) avatarPreview.classList.add('d-none');
                if (avatarFallback) avatarFallback.classList.remove('d-none');
                // Automatically submit the form to remove photo
                document.querySelector('form').submit();
            }
        });
    }
});
</script>

<?php
// STEP 3: Save the captured HTML and stop buffering.
$page_content = ob_get_clean();

// STEP 4: Render it inside the shared layout.
require 'layout.php';
