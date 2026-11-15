<?php
session_start();
error_reporting(0);
include ('includes/dbconnection.php');

$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);
$parameter = $data['parameter'];

function fetchDataFromDatabase($con, $parameter) {
    if(!isset($_SESSION['lastData'])){
        $_SESSION['lastData'] = null;
    }

    // while (true) {
        // $result = mysqli_query($con, "SELECT * FROM adRoomie WHERE column_name = '$parameter'");
        $result = mysqli_query($con, "SELECT * FROM adRoomie");
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

        // if ($data !== $_SESSION['lastData']) {
        //     $_SESSION['lastData'] = $data;
            return $data;
        // }
    //     sleep(2);
    // }
}

$data = fetchDataFromDatabase($con, $parameter);
echo json_encode($data);
?>
