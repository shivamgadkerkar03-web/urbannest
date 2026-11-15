<?php
require_once __DIR__ . '/includes/security.php';
include('includes/dbconnection.php');
include_once('includes/roommate-functions.php');

$uid = require_user('signin.php');

$req_id = (int)($_REQUEST['id'] ?? 0);
$action = trim((string)($_REQUEST['action'] ?? ''));

if (!$req_id || !in_array($action, ['accept','reject','cancel'], true)) {
    redirect_to('my-matches.php');
}
if (!csrf_valid()) {
    $_SESSION['rm_flash'] = ['type'=>'danger','msg'=>'Your session expired. Please try again.'];
    redirect_to('my-matches.php');
}

// Fetch the request and validate ownership
$stmt = mysqli_prepare($con, "SELECT * FROM roommate_requests WHERE id = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, 'i', $req_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$req = mysqli_fetch_assoc($result);

if (!$req) {
    $_SESSION['rm_flash'] = ['type'=>'danger','msg'=>'Request not found.'];
    redirect_to('my-matches.php');
}

$allowed = false;
$new_status = '';

switch ($action) {
    case 'accept':
    case 'reject':
        // Only receiver can accept/reject
        if ($req['receiver_id'] == $uid && $req['status'] === 'Pending') {
            $allowed = true;
            $new_status = $action === 'accept' ? 'Accepted' : 'Rejected';
        }
        break;
    case 'cancel':
        // Only sender can cancel a pending request
        if ($req['sender_id'] == $uid && $req['status'] === 'Pending') {
            $allowed = true;
            $new_status = 'Cancelled';
        }
        break;
}

if ($allowed && $new_status) {
    $stmt2 = mysqli_prepare($con, "UPDATE roommate_requests SET status = ?, updated_at = NOW() WHERE id = ?");
    mysqli_stmt_bind_param($stmt2, 'si', $new_status, $req_id);
    mysqli_stmt_execute($stmt2);
    if ($new_status === 'Accepted') {
        getOrCreateConversation($con, $uid, $req['sender_id']);
    }
    $_SESSION['rm_flash'] = ['type'=>'success','msg'=>'Request ' . strtolower($new_status) . '.'];
} else {
    $_SESSION['rm_flash'] = ['type'=>'danger','msg'=>'You are not authorized to perform this action.'];
}

redirect_to('my-matches.php');
