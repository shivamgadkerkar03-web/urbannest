<?php
require_once __DIR__ . '/../../includes/security.php';
require_once __DIR__ . '/../includes/dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_fail(405, 'Method not allowed.');
}
if (current_admin_id() <= 0) {
    json_fail(401, 'Please log in again.');
}
if (!csrf_valid()) {
    json_fail(403, 'Invalid or expired request. Please refresh the page.');
}

$data = json_decode((string) file_get_contents('php://input'), true);
$productId = (int) ($data['productId'] ?? 0);
if ($productId <= 0) {
    json_fail(422, 'Invalid product.');
}

$stmt = mysqli_prepare($con, "SELECT images FROM product WHERE productId = ?");
mysqli_stmt_bind_param($stmt, 'i', $productId);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);
if (!$row) {
    json_fail(404, 'Product not found.');
}

$stmt = mysqli_prepare($con, "DELETE FROM product WHERE productId = ?");
mysqli_stmt_bind_param($stmt, 'i', $productId);
if (!mysqli_stmt_execute($stmt)) {
    json_fail(500, 'Could not delete the product.');
}
mysqli_stmt_close($stmt);

$image = basename((string) $row['images']);
if ($image !== '' && $image !== '.' && $image !== '..') {
    $path = __DIR__ . '/../../resellNation/upload/' . $image;
    if (is_file($path)) {
        @unlink($path);
    }
}

header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'Product deleted.']);
