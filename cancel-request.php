<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
include_once('includes/roommate-functions.php');

if (empty($_SESSION['pgasuid'])) { header('location: signin.php'); exit; }
$uid = (int)$_SESSION['pgasuid'];

$req_id = (int)($_GET['id'] ?? 0);
if ($req_id) {
    // Only sender can cancel their own pending request
    $stmt = mysqli_prepare($con, "UPDATE roommate_requests SET status = 'Cancelled', updated_at = NOW() WHERE id = ? AND sender_id = ? AND status = 'Pending'");
    mysqli_stmt_bind_param($stmt, 'ii', $req_id, $uid);
    mysqli_stmt_execute($stmt);
    if (mysqli_affected_rows($con) > 0) {
        $_SESSION['rm_flash'] = ['type'=>'success','msg'=>'Request cancelled.'];
    }
}
header('location: my-matches.php');
exit;
