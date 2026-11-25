<?php
/**
 * auth.php - Authentication Helper Functions
 * -----------------------------------------
 * Centralizes login verification, role checking, redirect middleware,
 * and current user data access with profile picture helpers.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if the user is currently logged in.
 *
 * @return bool
 */
function is_logged_in(): bool
{
    return isset($_SESSION['user_id']) || isset($_SESSION['user']);
}

/**
 * Check if the logged-in user is an administrator.
 *
 * @return bool
 */
function is_admin(): bool
{
    if (!is_logged_in()) {
        return false;
    }
    $role = $_SESSION['user_role'] ?? ($_SESSION['user']['role'] ?? '');
    return strtolower((string) $role) === 'admin';
}

/**
 * Redirect already logged-in users away from guest pages (login, register, forgot-password).
 * Admins go to admin/admin.php, customers go to account.php.
 */
function redirect_if_logged_in(): void
{
    if (is_logged_in()) {
        if (is_admin()) {
            header('Location: admin/admin.php');
        } else {
            header('Location: account.php');
        }
        exit;
    }
}

/**
 * Require user to be logged in. If not, redirect to login.php.
 * Preserves the requested URL for redirect after login.
 */
function require_login(): void
{
    if (!is_logged_in()) {
        $currentPage = basename($_SERVER['PHP_SELF'] ?? 'account.php');
        $query       = $_SERVER['QUERY_STRING'] ?? '';
        $redirectUrl = $query ? "{$currentPage}?{$query}" : $currentPage;

        header('Location: login.php?redirect=' . urlencode($redirectUrl));
        exit;
    }
}

/**
 * Get the current logged-in user data from the database.
 * Updates and syncs session data automatically.
 *
 * @param bool $fresh Force re-query from database
 * @return array|null
 */
function current_user(bool $fresh = false): ?array
{
    static $cachedUser = null;

    if ($cachedUser !== null && !$fresh) {
        return $cachedUser;
    }

    if (!is_logged_in()) {
        return null;
    }

    $userId = $_SESSION['user_id'] ?? ($_SESSION['user']['id'] ?? null);

    require_once __DIR__ . '/db.php';

    if ($userId) {
        try {
            $user = db_one("SELECT * FROM users WHERE id = ? LIMIT 1", [$userId]);
            if ($user) {
                $_SESSION['user_id']    = $user['id'];
                $_SESSION['user_name']  = $user['fullname'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role']  = $user['role'];
                $_SESSION['user']       = $user;
                $cachedUser             = $user;
                return $user;
            }
        } catch (PDOException $e) {
            // Fall back to session if DB query fails
        }
    }

    $cachedUser = $_SESSION['user'] ?? null;
    return $cachedUser;
}

/**
 * Return relative URL to the user's avatar image, or null if none uploaded.
 *
 * @param array|null $user
 * @return string|null
 */
function user_avatar_url(?array $user): ?string
{
    if (!$user) {
        return null;
    }

    $pic = $user['profile_picture'] ?? null;
    if (!empty($pic)) {
        $fullPath = __DIR__ . '/uploads/profile_pictures/' . $pic;
        if (file_exists($fullPath)) {
            return 'uploads/profile_pictures/' . htmlspecialchars($pic);
        }
    }

    return null;
}
