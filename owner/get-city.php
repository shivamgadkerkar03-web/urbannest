<?php
require_once __DIR__ . '/../includes/security.php';
include('includes/dbconnection.php');
$ownerid = require_owner('login.php');

if (isset($_POST['sateid'])) {
    $state = trim((string) $_POST['sateid']);

    $stmt = mysqli_prepare($con, "SELECT tblcity.City FROM tblcity JOIN tblstate ON tblstate.ID = tblcity.StateID WHERE tblstate.StateName = ? ORDER BY tblcity.City");
    mysqli_stmt_bind_param($stmt, 's', $state);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 0) {
        echo '<option value="">No cities added for this state yet</option>';
        exit;
    }

    echo '<option value="">Select City</option>';
    while ($rw = mysqli_fetch_assoc($result)) {
        $city = h(trim($rw['City']));
        echo '<option value="' . $city . '">' . $city . '</option>';
    }
}
