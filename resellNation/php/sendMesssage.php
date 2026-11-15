<?php

include ('../connect.php');

// Retrieve data from the POST request
$postData = json_decode(file_get_contents("php://input"), true);

// Check if the ID, sellerId, and new message data are provided in the request
if(isset($postData['ID']) && isset($postData['sellerId']) && isset($postData['newMessage'])) {
    $userID = mysqli_real_escape_string($con, $postData['ID']);
    $sellerId = mysqli_real_escape_string($con, $postData['sellerId']);
    $newMessage = $postData['newMessage'];
    
    // Determine the sender type from the message
    $senderType = isset($newMessage['sender']) ? $newMessage['sender'] : 'buy';
    
    // Build a mirror message for the other party with swapped sender label
    $mirrorMessage = $newMessage;
    // The mirror copy keeps the same sender field so chat rendering works correctly
    
    // --- Update BUYER's comm (keyed by sellerId) ---
    $stmtBuyer = mysqli_prepare($con, "SELECT comm FROM tbluser WHERE ID = ?");
    mysqli_stmt_bind_param($stmtBuyer, "s", $userID);
    mysqli_stmt_execute($stmtBuyer);
    $resultBuyer = mysqli_stmt_get_result($stmtBuyer);
    
    $buyerSuccess = false;
    $buyerCommThread = [];
    if($resultBuyer && $rowBuyer = mysqli_fetch_assoc($resultBuyer)) {
        $commData = json_decode($rowBuyer['comm'], true);
        if(!is_array($commData)) $commData = [];
        
        if(isset($commData[$sellerId])) {
            $commData[$sellerId][] = $newMessage;
        } else {
            $commData[$sellerId] = array($newMessage);
        }
        
        $updatedCommData = json_encode($commData);
        $updateStmt = mysqli_prepare($con, "UPDATE tbluser SET comm = ? WHERE ID = ?");
        mysqli_stmt_bind_param($updateStmt, "ss", $updatedCommData, $userID);
        $buyerSuccess = mysqli_stmt_execute($updateStmt);
        $buyerCommThread = $commData[$sellerId];
        mysqli_stmt_close($updateStmt);
    }
    mysqli_stmt_close($stmtBuyer);
    
    // --- Update SELLER's comm (keyed by buyerId) ---
    $stmtSeller = mysqli_prepare($con, "SELECT comm FROM tbluser WHERE ID = ?");
    mysqli_stmt_bind_param($stmtSeller, "s", $sellerId);
    mysqli_stmt_execute($stmtSeller);
    $resultSeller = mysqli_stmt_get_result($stmtSeller);
    
    if($resultSeller && $rowSeller = mysqli_fetch_assoc($resultSeller)) {
        $sellerComm = json_decode($rowSeller['comm'], true);
        if(!is_array($sellerComm)) $sellerComm = [];
        
        if(isset($sellerComm[$userID])) {
            $sellerComm[$userID][] = $mirrorMessage;
        } else {
            $sellerComm[$userID] = array($mirrorMessage);
        }
        
        $updatedSellerComm = json_encode($sellerComm);
        $updateSellerStmt = mysqli_prepare($con, "UPDATE tbluser SET comm = ? WHERE ID = ?");
        mysqli_stmt_bind_param($updateSellerStmt, "ss", $updatedSellerComm, $sellerId);
        mysqli_stmt_execute($updateSellerStmt);
        mysqli_stmt_close($updateSellerStmt);
    }
    mysqli_stmt_close($stmtSeller);
    
    if($buyerSuccess) {
        echo json_encode(array("success" => true, "updatedCommData" => $buyerCommThread));
    } else {
        echo json_encode(array("success" => false, "error" => "Failed to update comm data: " . mysqli_error($con)));
    }
} else {
    echo json_encode(array("success" => false, "error" => "ID, sellerId, or newMessage is missing in the request"));
}

mysqli_close($con);

?>
