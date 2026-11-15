<?php

include ('../connect.php');
session_start();

$postData = json_decode(file_get_contents("php://input"), true);

if ($postData['action'] == "get") {
    if (isset($postData['ID']) && isset($postData['sellerId'])) {
        $userID = mysqli_real_escape_string($con, $postData['ID']);
        $sellerId = mysqli_real_escape_string($con, $postData['sellerId']);
        $sender = mysqli_real_escape_string($con, $postData['sender']);

        $query = "SELECT comm FROM tbluser WHERE ID = '$userID'";
        $result = mysqli_query($con, $query);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            if ($row) {
                $commData = json_decode($row['comm'], true);

                if (isset($commData[$sellerId])) {
                    // Mark messages as read in the database
                    foreach ($commData[$sellerId] as &$message) {
                        // $message['read'] = true;
                        if ($sender != $message['sender']) {
                            $message['read'] = true;
                        }
                    }

                    // Encode the modified 'comm' data back to JSON
                    $updatedCommData = json_encode($commData);

                    // Update 'comm' field in the database
                    $updateQuery = "UPDATE tbluser SET comm = '$updatedCommData' WHERE ID = '$userID'";
                    $updateResult = mysqli_query($con, $updateQuery);

                    // Compare with previous data to determine if there's a change
                    $previousData = isset($_SESSION['previousData']) ? $_SESSION['previousData'] : null;
                    $newData = $commData[$sellerId];
                    if ($previousData !== $newData) {
                        // New data detected
                        $_SESSION['previousData'] = $newData;
                        echo json_encode(array("change" => true, "newData" => $newData));
                    } else {
                        // No data change
                        echo json_encode(array("change" => false));
                    }
                } else {
                    echo json_encode(array("success" => false, "error" => "Seller ID does not exist in the 'comm' data"));
                }
            } else {
                echo json_encode(array("success" => false, "error" => "No data found for the specified ID"));
            }
        } else {
            echo json_encode(array("success" => false, "error" => "Failed to execute query: " . mysqli_error($con)));
        }
    } else {
        echo json_encode(array("success" => false, "error" => "ID or sellerId is missing in the request"));
    }
}

mysqli_close($con);

?>