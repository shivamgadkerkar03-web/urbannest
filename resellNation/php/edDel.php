<?php

include ('../connect.php');

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
            $sql = "DELETE FROM product WHERE $whereClause";
            $result = mysqli_query($con, $sql);

            // Check if query was successful
            if (!$result) {
                echo "Error deleting row: " . mysqli_error($con);
                exit;
            }
            echo "Row deleted successfully.";
        } elseif ($action == 'edit') {
            // Extract data from postData
            $data = $postData['data'];

            // Extract productId from data
            $productId = $data['productId'];
            $newData = $data['newData'];

            // Handle image upload only if an image is selected
            if (isset($newData['imageObj'])) {
                $imageData = $newData['imageObj']['data'];
                $imageName = $newData['imageObj']['name'];
                $imageType = $newData['imageObj']['type'];

                // Directory where the image will be saved
                $uploadDir = '../upload/';

                // Create the directory if it doesn't exist
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                // Path where the image will be saved
                $imagePath = $uploadDir . $imageName;

                // Save the image to the specified path
                file_put_contents($imagePath, base64_decode(explode(',', $imageData)[1]));

                // Update the 'images' column in the database
                $sql = "UPDATE product SET images = '$imageName' WHERE productId = $productId";
                $result = mysqli_query($con, $sql);

                // Check if the update query was successful
                if (!$result) {
                    // If there was an error, send an error message back to the client-side JavaScript
                    echo "Error updating product image: " . mysqli_error($con);
                    exit;
                }
            }

            // Prepare and execute SQL query to update other product data
            $sql = "UPDATE product SET ";
            foreach ($newData as $key => $value) {
                // Skip the 'imageObj' key as it has already been handled
                if ($key !== 'imageObj') {
                    $sql .= "$key = '$value', ";
                }
            }
            // Remove the trailing comma and space from the SQL statement
            $sql = rtrim($sql, ", ");
            $sql .= " WHERE productId = $productId";

            // Execute the SQL query
            if (mysqli_query($con, $sql)) {
                // If the query was successful, send a success message back to the client-side JavaScript
                echo "Product updated successfully";
            } else {
                // If there was an error with the query, send an error message back to the client-side JavaScript
                echo "Error updating product: " . mysqli_error($con);
            }

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