<?php
require_once __DIR__ . '/../includes/security.php';
include('includes/dbconnection.php');
$ownerid = require_owner('login.php');


$msg = '';
if (isset($_POST['submit'])) {
    csrf_check();

    $ownerStatus = mysqli_fetch_assoc(mysqli_query($con, "SELECT status FROM tblowner WHERE ID=" . (int) $ownerid));
    $fields = [
        'state' => '', 'city' => '', 'pgname' => '', 'typepg' => '', 'rpmonth' => '', 'norooms' => '',
        'address' => '', 'electricity' => '', 'parking' => '', 'refregerator' => '', 'furnished' => '',
        'ac' => '', 'balcony' => '', 'table' => '', 'laundry' => '', 'security' => '',
        'breakfast' => '', 'lunch' => '', 'dinner' => '',
    ];
    foreach ($fields as $key => $default) {
        $fields[$key] = trim((string) ($_POST[$key] ?? $default));
    }

    if (!$ownerStatus || (int) $ownerStatus['status'] !== 1) {
        $msg = "Your account is awaiting admin approval.";
    } elseif ($fields['pgname'] === '' || $fields['state'] === '' || $fields['city'] === '') {
        $msg = "Please fill in all required fields.";
    } else {
        $imageDir = __DIR__ . '/roomimages';
        $upload = store_uploaded_image($_FILES['roomimages'] ?? [], $imageDir);
        protect_upload_dir($imageDir);
        if (!$upload['ok']) {
            $msg = $upload['error'];
        } else {
            $roompic = $upload['name'];
            // New listings stay hidden until an admin approves them.
            $stmt = mysqli_prepare($con, "INSERT INTO tblpg(OwnerID, StateName, CityName, PGTitle, Type, RPmonth, norooms, Address, Electricity, Parking, Refregerator, Furnished, AC, Balcony, StudyTable, Laundry, Security, MealsBreakfast, MealLunch, MealDinner, Image, IsActive) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
            mysqli_stmt_bind_param(
                $stmt,
                'issssssssssssssssssss',
                $ownerid,
                $fields['state'],
                $fields['city'],
                $fields['pgname'],
                $fields['typepg'],
                $fields['rpmonth'],
                $fields['norooms'],
                $fields['address'],
                $fields['electricity'],
                $fields['parking'],
                $fields['refregerator'],
                $fields['furnished'],
                $fields['ac'],
                $fields['balcony'],
                $fields['table'],
                $fields['laundry'],
                $fields['security'],
                $fields['breakfast'],
                $fields['lunch'],
                $fields['dinner'],
                $roompic
            );
            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('PG details has been submitted and will be visible after admin approval.');</script>";
                echo "<script>window.location.href='manage-pgdetails.php'</script>";
                exit;
            }
            mysqli_stmt_close($stmt);
            @unlink($imageDir . '/' . $roompic);
            $msg = "Something went wrong. Please try again.";
        }
    }
}
?>
    <!DOCTYPE html>

    <head>
        <title>UrbanNest | Form </title>

        <script
            type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
        <!-- //bootstrap-css -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <!-- //bootstrap-css -->
        <!-- Custom CSS -->
        <link href="css/style.css" rel='stylesheet' type='text/css' />
        <link href="css/style-responsive.css" rel="stylesheet" />
        <!-- font CSS -->
        <link
            href='//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic'
            rel='stylesheet' type='text/css'>
        <!-- font-awesome icons -->
        <link rel="stylesheet" href="css/font.css" type="text/css" />
        <link href="css/font-awesome.css" rel="stylesheet">
        <script src="js/jquery2.0.3.min.js"></script>

        <script>
            function getCity(val) {
                $.ajax({
                    type: "POST",
                    url: "get-city.php",
                    data: { sateid: val },
                    success: function (data) {
                        $("#city").html(data);
                    }
                });
            }
        </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../css/panel.css">
</head>

    <body>
        <section id="container">
            <!--header start-->
            <?php include_once ('includes/header.php'); ?>
            <!--header end-->
            <!--sidebar start-->
            <?php include_once ('includes/sidebar.php'); ?>
            <!--sidebar end-->
            <!--main content start-->
            <section id="main-content">
                <section class="wrapper">
                    <div class="form-w3layouts">

                        <div class="row">
                            <div class="col-lg-12">
                                <section class="panel">
                                    <?php
                                    if ($ownerid > 0) {
                                        $query = "SELECT * FROM tblowner WHERE ID=" . (int) $ownerid;

                                        // Execute the query
                                        $result = mysqli_query($con, $query);

                                        // Check if there is exactly one row returned
                                        if (mysqli_num_rows($result) == 1) {
                                            // Fetch the row
                                            $row = mysqli_fetch_assoc($result);
                                            // echo "Status: " . $row['status'];
                                            if ($row['status'] == 1) {
                                                // echo "Varified";
                                                ?>




                                                <header class="panel-heading">
                                                    List Your PG
                                                </header>
                                                <div class="panel-body">
                                                    <p style="font-size:16px; color:red" align="left"> <?php echo h($msg ?? ''); ?> </p>
                                                    <form class="form-horizontal bucket-form" method="post"
                                                        enctype="multipart/form-data">
                                                        <?php echo csrf_field(); ?>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label">PG Name</label>
                                                            <div class="col-sm-6">
                                                                <input type="text" name="pgname" class="form-control" required="true">
                                                            </div>
                                                        </div>


                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label">Type of PG:</label>
                                                            <div class="col-sm-6">
                                                                <input type="radio" name="typepg" id="typepg" value="Boys"
                                                                    checked="true">
                                                                Boys
                                                                <input type="radio" name="typepg" id="typepg" value="Girls">
                                                                Girls
                                                                <input type="radio" name="typepg" id="typepg" value="Both">
                                                                Both
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Choose
                                                                State</label>
                                                            <div class="col-lg-6">
                                                                <select class="form-control m-bot15" name="state" id="state"
                                                                    onChange="getCity(this.value);">
                                                                    <option value="">Choose State</option>
                                                                    <?php $query = mysqli_query($con, "select * from tblstate");
                                                                    while ($row = mysqli_fetch_array($query)) {
                                                                        ?>
                                                                        <option value="<?php echo h($row['StateName']); ?>">
                                                                            <?php echo h($row['StateName']); ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>


                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Choose
                                                                City</label>
                                                            <div class="col-lg-6">
                                                                <select class="form-control m-bot15" name="city" id="city"
                                                                    required="true">

                                                                </select>


                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label">Rent Per Month</label>
                                                            <div class="col-sm-6">
                                                                <input class="form-control" id="rpmonth" name="rpmonth" type="text"
                                                                    required="true" value="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label">Number of Rooms</label>
                                                            <div class="col-sm-6">
                                                                <input type="text" class="form-control" name="norooms" id="norooms"
                                                                    required="true" value="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label">Address</label>
                                                            <div class="col-sm-6">
                                                                <textarea type="text" class="form-control" name="address" id="address"
                                                                    value="" required="true"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class=" col-sm-3 control-label">Pictures</label>
                                                            <div class="col-lg-6">
                                                                <input type="file" class="form-control" name="roomimages"
                                                                    id="roomimages" required="true" accept="image/jpeg,image/png,image/webp,image/gif">
                                                            </div>
                                                        </div>
                                                        <hr />
                                                        <div class="form-group has-success">
                                                            <label class="col-sm-3 control-label col-lg-3" for="inputSuccess"
                                                                style="text-align: center; color: red">Facilities</label>

                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label col-lg-3"
                                                                for="inputSuccess">Electricity</label>
                                                            <div class="col-lg-6">
                                                                <label class="checkbox-inline">
                                                                    <input type="radio" id="electricity" name="electricity" value="Yes"
                                                                        required="true"> Yes
                                                                </label>
                                                                <label class="checkbox-inline">
                                                                    <input type="radio" id="electricity" name="electricity" value="No">
                                                                    No
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label col-lg-3"
                                                                for="inputSuccess">Parking</label>
                                                            <div class="col-lg-6">
                                                                <label class="checkbox-inline">
                                                                    <input type="radio" id="parking" name="parking" value="Yes"
                                                                        required="true"> Yes
                                                                </label>
                                                                <label class="checkbox-inline">
                                                                    <input type="radio" id="parking" name="parking" value="No"
                                                                        required="true"> No
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label col-lg-3"
                                                                for="inputSuccess">Refregerator</label>
                                                            <div class="col-lg-6">
                                                                <label class="checkbox-inline">
                                                                    <input type="radio" id="refregerator" name="refregerator"
                                                                        value="Yes" required="true"> Yes
                                                                </label>
                                                                <label class="checkbox-inline">
                                                                    <input type="radio" id="refregerator" name="refregerator"
                                                                        value="No"> No
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Full
                                                                Furnished</label>
                                                            <div class="col-lg-6">
                                                                <label class="checkbox-inline">
                                                                    <input type="radio" id="furnished" name="furnished" value="Yes"
                                                                        required="true"> Yes
                                                                </label>
                                                                <label class="checkbox-inline">
                                                                    <input type="radio" id="furnished" name="furnished" value="No"> No
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">AC</label>
                                                            <div class="col-lg-6">
                                                                <label class="checkbox-inline">
                                                                    <input type="radio" id="ac" name="ac" value="Yes" required="true">
                                                                    Yes
                                                                </label>
                                                                <label class="checkbox-inline">
                                                                    <input type="radio" id="ac" name="ac" value="No"> No
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label col-lg-3"
                                                                for="inputSuccess">Balcony</label>
                                                            <div class="col-lg-6">
                                                                <label class="checkbox-inline">
                                                                    <input type="radio" id="balcony" name="balcony" value="Yes"
                                                                        required="true"> Yes
                                                                </label>
                                                                <label class="checkbox-inline">
                                                                    <input type="radio" id="balcony" name="balcony" value="No"> No
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label col-lg-3"
                                                                for="inputSuccess">Table/Study Lamp</label>
                                                            <div class="col-lg-6">
                                                                <label class="checkbox-inline">
                                                                    <input type="radio" id="table" name="table" value="Yes"
                                                                        required="true"> Yes
                                                                </label>
                                                                <label class="checkbox-inline">
                                                                    <input type="radio" id="table" name="table" value="No"> No
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label col-lg-3"
                                                                for="inputSuccess">Laundry</label>
                                                            <div class="col-lg-6">
                                                                <label class="checkbox-inline">
                                                                    <input type="radio" id="laundry" name="laundry" value="Yes"
                                                                        required="true"> Yes
                                                                </label>
                                                                <label class="checkbox-inline">
                                                                    <input type="radio" id="laundry" name="laundry" value="No"> No
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label col-lg-3"
                                                                for="inputSuccess">Security</label>
                                                            <div class="col-lg-6">
                                                                <label class="checkbox-inline">
                                                                    <input type="radio" id="security" name="security" value="Yes"
                                                                        required="true"> Yes
                                                                </label>
                                                                <label class="checkbox-inline">
                                                                    <input type="radio" id="security" name="security" value="No"> No
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <hr />
                                                        <div class="form-group has-success">
                                                            <label class="col-sm-3 control-label col-lg-3" for="inputSuccess"
                                                                style="text-align: center; color: red">Meals</label>

                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Choose
                                                                meals which you offer with PG</label>
                                                            <div class="col-lg-6">
                                                                <label class="checkbox-inline">
                                                                    <input type="checkbox" id="breakfast" name="breakfast" value="Yes">
                                                                    Breakfast
                                                                </label>
                                                                <label class="checkbox-inline">
                                                                    <input type="checkbox" id="lunch" name="lunch" value="Yes"> Lunch
                                                                </label>
                                                                <label class="checkbox-inline">
                                                                    <input type="checkbox" id="dinner" name="dinner" value="Yes"> Dinner
                                                                </label>

                                                            </div>
                                                        </div>

                                                        <hr />
                                                        <div class="form-group">
                                                            <div class="col-lg-offset-3 col-lg-6">
                                                                <button class="btn btn-primary" type="submit"
                                                                    name="submit">Submit</button>
                                                            </div>
                                                        </div>

                                                    </form>
                                                </div>
                                                <?php
                                            } else {
                                                echo "Get Verified !!";
                                            }
                                        } else {
                                            echo "No Data Found";
                                        }
                                    }
                                    ?>
                                </section>
                                <!-- page end-->

                            </div>
                </section>
                <!-- footer -->
                <?php include_once ('includes/footer.php'); ?>
            </section>
            <!--main content end-->
        </section>
        <script src="js/bootstrap.js"></script>
        <script src="js/jquery.dcjqaccordion.2.7.js"></script>
        <script src="js/scripts.js"></script>
        <script src="js/jquery.slimscroll.js"></script>
        <script src="js/jquery.nicescroll.js"></script>
        <!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/flot-chart/excanvas.min.js"></script><![endif]-->
        <script src="js/jquery.scrollTo.js"></script>
    </body>

    </html>
