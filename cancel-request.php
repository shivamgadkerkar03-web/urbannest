<?php
require_once __DIR__ . '/includes/security.php';
include('includes/dbconnection.php');
include_once('includes/roommate-functions.php');

$uid = require_user('signin.php');

$req_id = (int)($_REQUEST['id'] ?? 0);
if ($req_id) {
    if (!csrf_valid()) {
        $_SESSION['rm_flash'] = ['type'=>'danger','msg'=>'Your session expired. Please try again.'];
        redirect_to('my-matches.php');
    }
    // Only the sender can cancel their own pending request
    $stmt = mysqli_prepare($con, "UPDATE roommate_requests SET status = 'Cancelled', updated_at = NOW() WHERE id = ? AND sender_id = ? AND status = 'Pending'");
    mysqli_stmt_bind_param($stmt, 'ii', $req_id, $uid);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $_SESSION['rm_flash'] = ['type'=>'success','msg'=>'Request cancelled.'];
    }
}
redirect_to('my-matches.php');
