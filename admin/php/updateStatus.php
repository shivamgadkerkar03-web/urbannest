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
$id = (int) ($data['id'] ?? 0);
$status = (int) ($data['status'] ?? -1);
if ($id <= 0 || ($status !== 0 && $status !== 1)) {
    json_fail(422, 'Invalid request.');
}

$stmt = mysqli_prepare($con, "UPDATE tblowner SET status = ? WHERE ID = ?");
mysqli_stmt_bind_param($stmt, 'ii', $status, $id);
if (!mysqli_stmt_execute($stmt)) {
    json_fail(500, 'Could not update the owner.');
}
$changed = mysqli_stmt_affected_rows($stmt) > 0;
mysqli_stmt_close($stmt);

$label = $status === 1 ? 'Active' : 'Inactive';
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'status'  => $status,
    'message' => $changed ? 'Owner marked ' . $label . '.' : 'Already ' . $label . '.',
]);
