<?php

include ('../connect.php');

$postData = json_decode(file_get_contents("php://input"), true);

if ($postData['action'] == "get") {
    if (isset($postData['ID']) && isset($postData['sellerId'])) {
        $userID = mysqli_real_escape_string($con, $postData['ID']);
        $sellerId = mysqli_real_escape_string($con, $postData['sellerId']);
        $sender = isset($postData['sender']) ? mysqli_real_escape_string($con, $postData['sender']) : '';

        // Use prepared statement to prevent SQL injection
        $stmt = mysqli_prepare($con, "SELECT comm FROM tbluser WHERE ID = ?");
        mysqli_stmt_bind_param($stmt, "s", $userID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            if ($row) {
                $commData = json_decode($row['comm'], true);
                if(!is_array($commData)) $commData = [];

                if (isset($commData[$sellerId]) && is_array($commData[$sellerId])) {
                    // Update 'read' attribute to true for received messages
                    foreach ($commData[$sellerId] as &$message) {
                        if ($sender != $message['sender']) {
                            $message['read'] = true;
                        }
                    }
                    unset($message); // break reference

                    // Save updated read status back
                    $updatedCommData = json_encode($commData);
                    $updateStmt = mysqli_prepare($con, "UPDATE tbluser SET comm = ? WHERE ID = ?");
                    mysqli_stmt_bind_param($updateStmt, "ss", $updatedCommData, $userID);
                    $updateResult = mysqli_stmt_execute($updateStmt);
                    mysqli_stmt_close($updateStmt);

                    if ($updateResult) {
                        echo json_encode(array("success" => true, "comm" => $commData[$sellerId]));
                    } else {
                        echo json_encode(array("success" => false, "error" => "Failed to update read status: " . mysqli_error($con)));
                    }
                } else {
                    // No conversation yet — return empty chat (not an error)
                    echo json_encode(array("success" => true, "comm" => []));
                }
            } else {
                echo json_encode(array("success" => false, "error" => "No data found for the specified ID"));
            }
        } else {
            echo json_encode(array("success" => false, "error" => "Failed to execute query: " . mysqli_error($con)));
        }
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(array("success" => false, "error" => "ID or sellerId is missing in the request"));
    }
}

mysqli_close($con);

?>