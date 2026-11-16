<?php
/**
 * Shared helpers: sessions, auth guards, output escaping, CSRF,
 * password hashing and safe image uploads.
 *
 * Include once at the top of any page:  require_once __DIR__ . '/includes/security.php';
 * (from admin/owner/resellNation use:   require_once __DIR__ . '/../includes/security.php';)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
    session_start();
}

/* ---------- Output escaping ---------- */

function h($value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}

/* ---------- Redirects & auth guards ---------- */

function redirect_to(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function current_user_id(): int
{
    return (int) ($_SESSION['pgasuid'] ?? 0);
}

function current_admin_id(): int
{
    return (int) ($_SESSION['pgasaid'] ?? 0);
}

function current_owner_id(): int
{
    return (int) ($_SESSION['pgasoid'] ?? 0);
}

/** Tenant/user pages. $loginUrl is relative to the calling page. */
function require_user(string $loginUrl = 'signin.php'): int
{
    $id = current_user_id();
    if ($id <= 0) {
        redirect_to($loginUrl);
    }
    return $id;
}

function require_admin(string $loginUrl = 'login.php'): int
{
    $id = current_admin_id();
    if ($id <= 0) {
        redirect_to($loginUrl);
    }
    return $id;
}

function require_owner(string $loginUrl = 'login.php'): int
{
    $id = current_owner_id();
    if ($id <= 0) {
        redirect_to($loginUrl);
    }

    // Owners deactivated by the admin lose access immediately.
    global $con;
    if (isset($con) && $con instanceof mysqli) {
        $stmt = mysqli_prepare($con, 'SELECT status FROM tblowner WHERE ID = ?');
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        if (!$row || (int) $row['status'] !== 1) {
            $_SESSION = [];
            session_destroy();
            redirect_to($loginUrl);
        }
    }
    return $id;
}

/** For JSON/AJAX endpoints: respond 401 instead of redirecting. */
function json_fail(int $status, string $message): void
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => $message]);
    exit;
}

/* ---------- Location lookups ---------- */

/** True when $city is one of the cities stored for $state. */
function city_in_state(mysqli $con, string $state, string $city): bool
{
    $stmt = mysqli_prepare($con, 'SELECT 1 FROM tblcity JOIN tblstate ON tblstate.ID = tblcity.StateID WHERE TRIM(tblstate.StateName) = ? AND TRIM(tblcity.City) = ? LIMIT 1');
    $state = trim($state);
    $city = trim($city);
    mysqli_stmt_bind_param($stmt, 'ss', $state, $city);
    mysqli_stmt_execute($stmt);
    $ok = (bool) mysqli_fetch_row(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $ok;
}

/* ---------- CSRF ---------- */

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . h(csrf_token()) . '">';
}

/** Checks POST field, GET param, or X-CSRF-Token header. */
function csrf_valid(): bool
{
    $sent = $_POST['csrf_token'] ?? $_GET['csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
    return is_string($sent) && $sent !== '' && hash_equals(csrf_token(), $sent);
}

function csrf_check(): void
{
    if (!csrf_valid()) {
        http_response_code(400);
        exit('Invalid or expired form. Please go back, refresh the page and try again.');
    }
}

/* ---------- Passwords ---------- */

function hash_password(string $plain): string
{
    return password_hash($plain, PASSWORD_DEFAULT);
}

/**
 * Verifies a password against a stored hash. Accepts legacy MD5 hashes;
 * $needsRehash is set to true when the stored hash should be upgraded.
 */
function verify_password(string $plain, ?string $stored, bool &$needsRehash = false): bool
{
    $stored = (string) $stored;
    $needsRehash = false;
    if ($stored === '') {
        return false;
    }
    if (preg_match('/^[a-f0-9]{32}$/i', $stored)) {
        $ok = hash_equals(strtolower($stored), md5($plain));
        $needsRehash = $ok;
        return $ok;
    }
    if (password_verify($plain, $stored)) {
        $needsRehash = password_needs_rehash($stored, PASSWORD_DEFAULT);
        return true;
    }
    return false;
}

/* ---------- Uploads ---------- */

/**
 * Validates and stores an uploaded image under a random name.
 *
 * @param array  $file      One entry from $_FILES
 * @param string $destDir   Absolute directory path
 * @param int    $maxBytes  Size limit
 * @return array ['ok' => bool, 'name' => string, 'error' => string]
 */
function store_uploaded_image(array $file, string $destDir, int $maxBytes = 5242880): array
{
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];

    if (!isset($file['error']) || is_array($file['error'])) {
        return ['ok' => false, 'name' => '', 'error' => 'Invalid upload.'];
    }
    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['ok' => false, 'name' => '', 'error' => 'Please choose an image.'];
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['ok' => false, 'name' => '', 'error' => 'Upload failed. Please try again.'];
    }
    if ($file['size'] > $maxBytes) {
        return ['ok' => false, 'name' => '', 'error' => 'Image is too large (max ' . round($maxBytes / 1048576) . ' MB).'];
    }

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    if (!isset($allowed[$mime]) || @getimagesize($file['tmp_name']) === false) {
        return ['ok' => false, 'name' => '', 'error' => 'Only JPG, PNG, WebP or GIF images are allowed.'];
    }

    if (!is_dir($destDir) && !mkdir($destDir, 0755, true)) {
        return ['ok' => false, 'name' => '', 'error' => 'Upload folder is not writable.'];
    }

    $name = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
    if (!move_uploaded_file($file['tmp_name'], rtrim($destDir, '/\\') . DIRECTORY_SEPARATOR . $name)) {
        return ['ok' => false, 'name' => '', 'error' => 'Could not save the image.'];
    }
    return ['ok' => true, 'name' => $name, 'error' => ''];
}

/**
 * Validates and stores an uploaded document (PDF or image) under a random name.
 */
function store_uploaded_document(array $file, string $destDir, int $maxBytes = 5242880): array
{
    $allowed = [
        'application/pdf' => 'pdf',
        'image/jpeg'      => 'jpg',
        'image/png'       => 'png',
        'image/webp'      => 'webp',
    ];

    if (!isset($file['error']) || is_array($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['ok' => false, 'name' => '', 'error' => 'Please upload a valid document.'];
    }
    if ($file['size'] > $maxBytes) {
        return ['ok' => false, 'name' => '', 'error' => 'Document is too large (max ' . round($maxBytes / 1048576) . ' MB).'];
    }
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    if (!isset($allowed[$mime])) {
        return ['ok' => false, 'name' => '', 'error' => 'Only PDF, JPG, PNG or WebP documents are allowed.'];
    }
    if (!is_dir($destDir) && !mkdir($destDir, 0755, true)) {
        return ['ok' => false, 'name' => '', 'error' => 'Upload folder is not writable.'];
    }
    $name = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
    if (!move_uploaded_file($file['tmp_name'], rtrim($destDir, '/\\') . DIRECTORY_SEPARATOR . $name)) {
        return ['ok' => false, 'name' => '', 'error' => 'Could not save the document.'];
    }
    return ['ok' => true, 'name' => $name, 'error' => ''];
}

/** Writes a deny-scripts .htaccess into an upload folder (Apache deployments). */
function protect_upload_dir(string $dir): void
{
    $file = rtrim($dir, '/\\') . DIRECTORY_SEPARATOR . '.htaccess';
    if (is_dir($dir) && !file_exists($file)) {
        @file_put_contents($file, "<FilesMatch \"\\.(php|phtml|phar|php\\d)$\">\n  Require all denied\n</FilesMatch>\nOptions -ExecCGI -Indexes\n");
    }
}
