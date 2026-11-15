<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Delete functionality
if (isset($_POST['delete'])) {
    $roomId = $_POST['roomId'];
    $query = "DELETE FROM adRoomie WHERE name = '$roomId'";
    if (mysqli_query($con, $query)) {
        echo "<script>alert('Entry deleted successfully');</script>";
    } else {
        echo "<script>alert('Failed to delete entry');</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
    <title>View and Delete Entries - UrbanNest</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,400,300,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="css/linearicons.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/nice-select.css">
    <link rel="stylesheet" href="css/ion.rangeSlider.css" />
    <link rel="stylesheet" href="css/ion.rangeSlider.skinFlat.css" />
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="css/main.css">
    <style>
        body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    background: url('back.jpg') fixed center/cover no-repeat;
    backdrop-filter: blur(5px); /* Adjust the blur intensity as needed */
}
        header {
            background-color: #343a40;
            color: #fff;
            text-align: center;
            padding: 20px;
            margin-bottom: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .delete-btn {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
        }

        .delete-btn:hover {
            background-color: #bd2130;
        }
    </style>
</head>

<body>

<header>

        <div style="float: right;">
            <a href="dashboard.php" style="text-decoration: none; color: #fff; margin-right: 10px;">Dashboard</a>
        </div>
        <div style="clear: both;"></div>
    </header>

    <div class="container">
        <h2>Roomie Entries</h2>

        <table>
            <thead>
                <tr>
                    <th>PG Name</th>
                    <th>Name</th>
                    <th>Prefered Gender</th>
                    <th>Room Description</th>
                    <!-- <th>Addiction</th> -->
                    <th>Contact No</th>
                    <!-- <th>Hobbies</th> -->
                    <th>Location</th>
                    <!-- <th>Addiction Info</th> -->
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $ret = mysqli_query($con, "SELECT * FROM adRoomie");
                while ($row = mysqli_fetch_assoc($ret)) {
                    ?>
                    <tr>
                        <td><?php echo $row['choosePG']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['Gender']; ?></td>
                        <td><?php echo $row['roomDetails']; ?></td>
                        <!-- <td><?php //echo $row['addiction']; ?></td> -->
                        <td><?php echo $row['contactNo']; ?></td>
                        <!-- <td><?php //echo $row['hobbies']; ?></td> -->
                        <td><?php echo $row['location']; ?></td>
                        <!-- <td><?php //echo $row['knowledgeAboutAddiction']; ?></td> -->
                        <td>
                            <form method="post">
                                <input type="hidden" name="roomId" value="<?php echo $row['name']; ?>">
                                <button type="submit" name="delete" class="delete-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="js/vendor/jquery-2.2.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"
        integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
        crossorigin="anonymous"></script>
    <script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBhOdIF3Y9382fqJYt5I_sswSrEw5eihAA"></script>
    <script src="js/vendor/bootstrap.min.js"></script>
    <script src="js/jquery.ajaxchimp.min.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/ion.rangeSlider.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>

</body>

</html>
