<?php
/*
 * edit-profile.php - EDIT PROFILE
 * Grouped profile form with preference switches and account shortcuts.
 */

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
        <p class="text-muted">Keep your contact information current so deliveries stay smooth.</p>
    </div>

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
                        <div
                            class="profile-avatar rounded-circle border border-3 border-white d-flex align-items-center justify-content-center text-white">
                            <i class="fa-regular fa-user fa-2x"></i>
                        </div>
                        <div>
                            <p class="hint mb-1">Editing profile for</p>
                            <h2 class="h4 fw-bold mb-1">Aarav Mehta</h2>
                            <p class="hint mb-0"><i class="fa-solid fa-id-card me-1"></i>TrendCart ID TC-102493</p>
                        </div>
                    </div>
                    <a href="profile.php" class="btn btn-light btn-sm px-3">
                        <i class="fa-regular fa-eye me-1"></i>View profile
                    </a>
                </div>
            </div>

            <form action="#" method="post" novalidate>
                <div class="row g-4">
                    <!-- ========== MAIN FORM ========== -->
                    <div class="col-12 col-xl-8">
                        <div class="card store-card mb-4">
                            <div class="card-body p-4">
                                <div class="section-heading mb-4">
                                    <div class="feature-icon"><i class="fa-solid fa-user-pen"></i></div>
                                    <div>
                                        <h2 class="h6 fw-bold mb-0">Personal information</h2>
                                        <p class="hint text-muted mb-0">Used for your account and order updates.</p>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="fullname" class="form-label small fw-semibold">Full Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                            <input type="text" class="form-control" id="fullname" name="fullname"
                                                value="Aarav Mehta" autocomplete="name"
                                                data-validation="required max" data-max="50">
                                        </div>
                                        <div class="invalid-feedback" id="fullnameError"></div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="email" class="form-label small fw-semibold">Email Address</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="aarav.mehta@example.com" autocomplete="email"
                                                data-validation="required email">
                                        </div>
                                        <div class="invalid-feedback" id="emailError"></div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="phone" class="form-label small fw-semibold">Mobile Number</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                                            <input type="tel" class="form-control" id="phone" name="phone"
                                                value="+91 98765 43210" autocomplete="tel"
                                                data-validation="required max" data-max="15">
                                        </div>
                                        <div class="invalid-feedback" id="phoneError"></div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="dob" class="form-label small fw-semibold">Date of Birth</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i
                                                    class="fa-solid fa-calendar-days"></i></span>
                                            <input type="date" class="form-control" id="dob" name="dob"
                                                value="1996-08-14" data-validation="max" data-max="10">
                                        </div>
                                        <div class="invalid-feedback" id="dobError"></div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="gender" class="form-label small fw-semibold">Gender</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-user-group"></i></span>
                                            <select class="form-select" id="gender" name="gender" data-validation="required">
                                                <option selected>Male</option>
                                                <option>Female</option>
                                                <option>Prefer not to say</option>
                                            </select>
                                        </div>
                                        <div class="invalid-feedback" id="genderError"></div>
                                    </div>

                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="fa-solid fa-floppy-disk me-1"></i>Save Changes
                                        </button>
                                        <a href="profile.php" class="btn btn-outline-primary px-4">
                                            <i class="fa-solid fa-xmark me-1"></i>Cancel
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>




                    </div>

                    <!-- ========== SIDE PANELS ========== -->
                    <div class="col-12 col-xl-4">
                        <div class="card store-card">
                            <div class="card-body p-4 text-center">
                                <h2 class="h6 fw-bold mb-3">Profile photo</h2>
                                <div
                                    class="profile-avatar rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3">
                                    <i class="fa-regular fa-user fa-2x"></i>
                                </div>
                                <div class="d-grid gap-2">
                                    <input type="file" class="form-control form-control-sm" id="profilePicture"
                                        name="profile_picture" accept="image/png, image/jpeg"
                                        data-validation="file filesize" data-filetype="png,jpg,jpeg"
                                        data-filesize="2048" data-max-items="1">
                                    <div class="invalid-feedback" id="profilePictureError"></div>
                                    <button type="button" class="btn btn-link btn-sm text-muted">Remove photo</button>
                                </div>
                                <p class="hint text-muted mt-3 mb-0">JPG or PNG, up to 2 MB.</p>
                            </div>
                        </div>


                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// STEP 3: Save the captured HTML and stop buffering.
$page_content = ob_get_clean();

// STEP 4: Render it inside the shared layout.
require 'layout.php';