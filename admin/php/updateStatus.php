<?php
include ('../includes/dbconnection.php');
// Retrieve the ID parameter from the POST request body
$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);

// Extract the ID parameter from the data
$id = $data['id'];
$id = mysqli_real_escape_string($con, $id);
$status = $data['status'];
$msg = $status == 0 ? "Inactive" : "Active";

// Construct and execute the SQL query
$query = "UPDATE tblowner SET status='$status' WHERE ID='$id'";
mysqli_query($con, $query);
if (mysqli_affected_rows($con) > 0) {
    echo "Update successful";
} else {
    echo "Already " . $msg;
}
?>
