<?php
$con = mysqli_connect("localhost", "root", "", "urbanest");
if (mysqli_connect_errno()) {
    echo "Connection Fail" . mysqli_connect_error();
}

$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);
$uid = $data['uid'];
$pgid = $data['pgid'];



// Construct and execute the SQL query
// $query = "DELETE FROM tblbookpg WHERE Userid='$uid' AND Pgid='$pgid'";
// mysqli_query($con, $query);

// $result = mysqli_query($con, $query);
// if ($result) {
//     if (mysqli_affected_rows($con) > 0) {
//         echo "Delete successful";
//     } else {
//         echo "No matching found";
//     }
// } else {
//     // echo "Delete failed: " . mysqli_error($con);
//     echo "Delete failed: ";
// }


$query = "SELECT PGTitle FROM tblpg WHERE ID='$pgid'";
$result = mysqli_query($con, $query);
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $pgName = $row['PGTitle'];

    // Construct and execute the SQL query to delete from tblbookpg
    $deleteQuery = "DELETE FROM tblbookpg WHERE Userid='$uid' AND Pgid='$pgid'";
    mysqli_query($con, $deleteQuery);
    
    if (mysqli_affected_rows($con) > 0) {
        // Construct and execute the SQL query to delete from adroomie
        // echo "DELETE FROM adroomie WHERE choosePG='$pgName' AND userId='$uid'";
        $deleteAdroomieQuery = "DELETE FROM adroomie WHERE choosePG='$pgName' AND userId='$uid'";
        mysqli_query($con, $deleteAdroomieQuery);

        if (mysqli_affected_rows($con) > 0) {
            echo "Delete successful";
        } else {
            echo "No matching found in adroomie";
        }
    } else {
        echo "No matching found in tblbookpg";
    }
} else {
    echo "No PGTitle found for the given pgid";
}

?>