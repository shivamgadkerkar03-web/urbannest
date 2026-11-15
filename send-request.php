<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
include_once('includes/roommate-functions.php');

if (empty($_SESSION['pgasuid'])) { header('location: signin.php'); exit; }
$uid = (int)$_SESSION['pgasuid'];

// --- Send request handler ---
if (isset($_GET['to'])) {
    $to = (int)$_GET['to'];
    $listing_id = isset($_GET['listing']) ? (int)$_GET['listing'] : null;
    $msg_text = '';

    if ($to <= 0 || $to === $uid) {
        header('location: find-roommate.php');
        exit;
    }
    
    // Check if blocked
    if (isBlocked($con, $uid, $to)) {
        $_SESSION['rm_flash'] = ['type'=>'danger','msg'=>'Cannot send request.'];
        header('location: find-roommate.php');
        exit;
    }

    // Check if request already exists
    $existing = getRequestStatus($con, $uid, $to);
    if ($existing) {
        $_SESSION['rm_flash'] = ['type'=>'warning','msg'=>'A connection request already exists with this user.'];
        header('location: find-roommate.php');
        exit;
    }

    // Insert request
    $stmt = mysqli_prepare($con, "INSERT INTO roommate_requests (sender_id, receiver_id, listing_id, status) VALUES (?,?,?,'Pending')");
    mysqli_stmt_bind_param($stmt, 'iii', $uid, $to, $listing_id);
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['rm_flash'] = ['type'=>'success','msg'=>'Connection request sent successfully!'];
    } else {
        $_SESSION['rm_flash'] = ['type'=>'danger','msg'=>'Could not send request.'];
    }
    header('location: my-matches.php');
    exit;
}

header('location: find-roommate.php');
exit;
