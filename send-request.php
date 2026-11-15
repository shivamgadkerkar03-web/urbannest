<?php
require_once __DIR__ . '/includes/security.php';
include('includes/dbconnection.php');
include_once('includes/roommate-functions.php');

$uid = require_user('signin.php');

if (isset($_REQUEST['to'])) {
    if (!csrf_valid()) {
        $_SESSION['rm_flash'] = ['type'=>'danger','msg'=>'Your session expired. Please try again.'];
        redirect_to('find-roommate.php');
    }

    $to = (int)$_REQUEST['to'];
    $listing_id = !empty($_REQUEST['listing']) ? (int)$_REQUEST['listing'] : null;
    $msg_text = trim((string)($_POST['message'] ?? ''));
    if ($msg_text === '') {
        $msg_text = null;
    }

    if ($to <= 0 || $to === $uid) {
        redirect_to('find-roommate.php');
    }

    $stmt = mysqli_prepare($con, "SELECT ID FROM tbluser WHERE ID = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 'i', $to);
    mysqli_stmt_execute($stmt);
    $target = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    if (!$target) {
        redirect_to('find-roommate.php');
    }

    if ($listing_id !== null) {
        $stmt = mysqli_prepare($con, "SELECT id FROM roommate_listings WHERE id = ? AND user_id = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 'ii', $listing_id, $to);
        mysqli_stmt_execute($stmt);
        if (!mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))) {
            $listing_id = null;
        }
        mysqli_stmt_close($stmt);
    }

    if (isBlocked($con, $uid, $to)) {
        $_SESSION['rm_flash'] = ['type'=>'danger','msg'=>'Cannot send request.'];
        redirect_to('find-roommate.php');
    }

    $findRequest = function (int $from, int $toUser) use ($con) {
        $stmt = mysqli_prepare($con, "SELECT id, status FROM roommate_requests WHERE sender_id = ? AND receiver_id = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 'ii', $from, $toUser);
        mysqli_stmt_execute($stmt);
        $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);
        return $row;
    };
    $mine = $findRequest($uid, $to);
    $theirs = $findRequest($to, $uid);

    if ($theirs && $theirs['status'] === 'Pending') {
        $_SESSION['rm_flash'] = ['type'=>'info','msg'=>'This user has already sent you a request. You can accept it below.'];
        redirect_to('my-matches.php');
    }
    if (($mine && in_array($mine['status'], ['Pending', 'Accepted'], true)) || ($theirs && $theirs['status'] === 'Accepted')) {
        $_SESSION['rm_flash'] = ['type'=>'warning','msg'=>'A connection request already exists with this user.'];
        redirect_to('my-matches.php');
    }

    // An old rejected/cancelled request is reopened instead of inserting (sender/receiver is unique).
    $reopen = $mine ?: $theirs;
    if ($reopen) {
        $reqId = (int)$reopen['id'];
        $stmt = mysqli_prepare($con, "UPDATE roommate_requests SET sender_id = ?, receiver_id = ?, listing_id = ?, status = 'Pending', message = ?, created_at = NOW(), updated_at = NOW() WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'iiisi', $uid, $to, $listing_id, $msg_text, $reqId);
    } else {
        $stmt = mysqli_prepare($con, "INSERT INTO roommate_requests (sender_id, receiver_id, listing_id, status, message) VALUES (?,?,?,'Pending',?)");
        mysqli_stmt_bind_param($stmt, 'iiis', $uid, $to, $listing_id, $msg_text);
    }

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['rm_flash'] = ['type'=>'success','msg'=>'Connection request sent successfully!'];
    } else {
        error_log('Roommate request failed: ' . mysqli_error($con));
        $_SESSION['rm_flash'] = ['type'=>'danger','msg'=>'Could not send request.'];
    }
    redirect_to('my-matches.php');
}

redirect_to('find-roommate.php');
