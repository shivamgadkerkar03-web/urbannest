<?php
require_once __DIR__ . '/../includes/security.php';
require_admin('login.php');
include('includes/dbconnection.php');

$id = (int) ($_GET['id'] ?? 0);
$stmt = mysqli_prepare($con, "SELECT VerificationFile FROM tblowner WHERE ID = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

$name = $row ? basename((string) $row['VerificationFile']) : '';
$path = realpath(__DIR__ . '/../owner/uploads/' . $name);
$base = realpath(__DIR__ . '/../owner/uploads');

if ($name === '' || $path === false || $base === false || strpos($path, $base . DIRECTORY_SEPARATOR) !== 0 || !is_file($path)) {
    http_response_code(404);
    exit('File not found.');
}

$mime = (new finfo(FILEINFO_MIME_TYPE))->file($path);
$allowed = ['application/pdf', 'image/jpeg', 'image/png', 'image/webp', 'image/gif'];
if (!in_array($mime, $allowed, true)) {
    http_response_code(404);
    exit('File not found.');
}

header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($path));
header('Content-Disposition: inline; filename="' . preg_replace('/[^A-Za-z0-9._-]/', '_', $name) . '"');
header('X-Content-Type-Options: nosniff');
readfile($path);
