<?php

include ('includes/dbconnection.php');

// Check if POST request is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decode JSON data sent from JS
    $postData = json_decode(file_get_contents("php://input"), true);

    // Check if action is provided
    if (isset($postData['action'])) {
        $action = $postData['action'];

        // Check if action is 'delete' or 'edit'
        if ($action == 'delete') {
            // Extract data for deletion
            $deleteData = $postData['data'];

            // Build the WHERE clause to find the row
            $whereClause = "";
            foreach ($deleteData as $columnName => $value) {
                if (!empty($value)) {
                    if ($whereClause != "") {
                        $whereClause .= " AND ";
                    }
                    $whereClause .= "$columnName = '$value'";
                }
            }

            // Prepare and execute SQL query to delete the row
            $sql = "DELETE FROM adRoomie WHERE $whereClause";
            $result = mysqli_query($con, $sql);

            // Check if query was successful
            if (!$result) {
                echo "Error deleting row: " . mysqli_error($con);
                exit;
            }
            echo "Row deleted successfully.";
        } elseif ($action == 'edit') {

            $requestData = $postData['newData'];
            // Extract userId and name (cno) from the request data
            $userId = mysqli_real_escape_string($con, $postData['userId']);
            $name = mysqli_real_escape_string($con, $postData['name']);
            // Construct the SQL query to update other columns
            // $sql = "UPDATE adRoomie SET choosePG = '{$requestData['choosePG']}', 
            //                      roomDetails = '{$requestData['roomDetails']}', 
            //                      hobbies = '{$requestData['hobbies']}', 
            //                      location = '{$requestData['location']}', 
            //                      addiction = '{$requestData['addiction']}', 
            //                      Gender = '{$requestData['Gender']}', 
            //                      knowledgeAboutAddiction = '{$requestData['knowledgeAboutAddiction']}' 
            // WHERE userId = '{$userId}' AND choosePG = '{$name}'";

            $sql = "UPDATE adRoomie SET roomDetails = '{$requestData['roomDetails']}', 
                                  Gender = '{$requestData['Gender']}'
             WHERE userId = '{$userId}' AND choosePG = '{$name}'";

            // Execute the query
            if (!mysqli_query($con, $sql)) {
                echo "Error updating record: " . mysqli_error($con);
                exit;
            }

            // Check if an image was provided
            if (isset($requestData['imageObj'])) {
                // Extract image data
                $imageData = $requestData['imageObj']['data'];
                $imageName = $requestData['imageObj']['name'];

                // Directory where the image will be saved
                $uploadDir = 'img/';

                // Path where the image will be saved
                $imagePath = $uploadDir . $imageName;

                // Save the image to the specified path
                file_put_contents($imagePath, base64_decode(explode(',', $imageData)[1]));

                // Update the 'image' column in the database
                $sql = "UPDATE adRoomie SET image = '$imageName' WHERE userId = '$userId' AND choosePG = '$name'";
                if (!mysqli_query($con, $sql)) {
                    echo "Error updating image: " . mysqli_error($con);
                    exit;
                }
            }

            echo "Record updated successfully for userId: $userId and contactNo: $name";
        } elseif ($action == 'hide') {
            $data = $postData['data'];
            $id = $postData['id'];

            $sql = "UPDATE adRoomie SET hide=".$data." WHERE roomieId=".$id."";
            $result = mysqli_query($con, $sql);

            if (!$result) {
                echo "done";
                exit;
            }
            echo "updated";
        } else {
            echo "Invalid action.";
        }
    } else {
        echo "Action is missing.";
    }
} else {
    echo "Only POST requests are allowed.";
}

?>