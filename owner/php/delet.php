<?php
require_once __DIR__ . '/../../includes/security.php';
require_once __DIR__ . '/../includes/dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_fail(405, 'Method not allowed.');
}
$ownerid = current_owner_id();
if ($ownerid <= 0) {
    json_fail(401, 'Please log in again.');
}
if (!csrf_valid()) {
    json_fail(403, 'Invalid or expired request. Please refresh the page.');
}

$data = json_decode((string) file_get_contents('php://input'), true);
$bookingId = (int) ($data['bookingId'] ?? 0);
if ($bookingId <= 0) {
    json_fail(422, 'Invalid booking.');
}

// Bookings are cancelled rather than deleted so the tenant still sees the outcome.
$stmt = mysqli_prepare(
    $con,
    "UPDATE tblbookpg b JOIN tblpg p ON p.ID = b.Pgid
     SET b.Status = 'Cancelled', b.Remark = COALESCE(NULLIF(b.Remark, ''), 'Cancelled by owner')
     WHERE b.ID = ? AND p.OwnerID = ? AND (b.Status IS NULL OR b.Status <> 'Cancelled')"
);
mysqli_stmt_bind_param($stmt, 'ii', $bookingId, $ownerid);
if (!mysqli_stmt_execute($stmt)) {
    json_fail(500, 'Could not cancel the booking.');
}
$changed = mysqli_stmt_affected_rows($stmt) > 0;
mysqli_stmt_close($stmt);

if (!$changed) {
    json_fail(404, 'Booking not found or already cancelled.');
}

header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'Booking cancelled.']);
