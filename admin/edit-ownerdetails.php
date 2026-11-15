<?php
require_once __DIR__ . '/../includes/security.php';
$adminid = require_admin('login.php');
include('includes/dbconnection.php');

$eid = (int) ($_GET['editid'] ?? 0);
$msg = '';
if (isset($_POST['submit'])) {
    csrf_check();
    $fullname = trim((string) ($_POST['fullname'] ?? ''));
    $mobno = trim((string) ($_POST['contactnumber'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $status = (int) ($_POST['status'] ?? 0) === 1 ? 1 : 0;

    if ($fullname === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^[0-9]{10}$/', $mobno)) {
        $msg = "Please enter a valid name, email and 10 digit mobile number.";
    } else {
        $stmt = mysqli_prepare($con, "UPDATE tblowner SET FullName = ?, Email = ?, MobileNumber = ?, status = ? WHERE ID = ?");
        mysqli_stmt_bind_param($stmt, 'sssii', $fullname, $email, $mobno, $status, $eid);
        if (mysqli_stmt_execute($stmt)) {
            $msg = "Owner profile has been updated.";
        } else {
            $msg = "Something Went Wrong. Please try again";
        }
        mysqli_stmt_close($stmt);
    }
}
?>

    <!DOCTYPE html>

    <head>
        <title>UrbanNest || Update Owner </title>
        <script type="application/x-javascript">
            addEventListener("load", function() {
                setTimeout(hideURLbar, 0);
            }, false);

            function hideURLbar() {
                window.scrollTo(0, 1);
            }
        </script>
        <!-- bootstrap-css -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <!-- //bootstrap-css -->
        <!-- Custom CSS -->
        <link href="css/style.css" rel='stylesheet' type='text/css' />
        <link href="css/style-responsive.css" rel="stylesheet" />
        <!-- font CSS -->
        <link href='//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
        <!-- font-awesome icons -->
        <link rel="stylesheet" href="css/font.css" type="text/css" />
        <link href="css/font-awesome.css" rel="stylesheet">
        <!-- //font-awesome icons -->
        <script src="js/jquery2.0.3.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../css/panel.css">
</head>

    <body>
        <section id="container">
            <!--header start-->
            <?php include_once('includes/header.php'); ?>
            <!--header end-->
            <!--sidebar start-->
            <?php include_once('includes/sidebar.php'); ?>
            <!--sidebar end-->
            <!--main content start-->
            <section id="main-content">
                <section class="wrapper">
                    <div class="form-w3layouts">
                        <!-- page start-->


                        <div class="row">
                            <div class="col-lg-12">
                                <section class="panel">
                                    <header class="panel-heading">
                                        Update Owner Details
                                        <span class="tools pull-right">
                                            <a class="fa fa-chevron-down" href="javascript:;"></a>
                                            <a class="fa fa-cog" href="javascript:;"></a>
                                            <a class="fa fa-times" href="javascript:;"></a>
                                        </span>
                                    </header>
                                    <div class="panel-body">
                                        <div class="form">
                                            <p style="font-size:16px; color:red" align="center"> <?php echo h($msg ?? ''); ?> </p>
                                            <?php
                                            $ret = mysqli_query($con, "select * from tblowner where ID=" . $eid);
                                            ?>
                                                <form class="cmxform form-horizontal " method="post" action="">
                                                    <?php echo csrf_field(); ?>
                                            <?php
                                            while ($row = mysqli_fetch_array($ret)) {
                                            ?>
                                                    <div class="form-group ">
                                                        <label for="adminname" class="control-label col-lg-3">Full Name</label>
                                                        <div class="col-lg-6">
                                                            <input class=" form-control" id="fullname" name="fullname" type="text" value="<?php echo h($row['FullName']); ?>" required="true">
                                                        </div>
                                                    </div>
                                                    <div class="form-group ">
                                                        <label for="email" class="control-label col-lg-3">Email</label>
                                                        <div class="col-lg-6">
                                                            <input class="form-control " id="email" name="email" type="email" value="<?php echo h($row['Email']); ?>" required="true">
                                                        </div>
                                                    </div>
                                                    <div class="form-group ">
                                                        <label for="username" class="control-label col-lg-3">Mobile Number</label>
                                                        <div class="col-lg-6">
                                                            <input class="form-control " id="contactnumber" name="contactnumber" type="text" value="<?php echo h($row['MobileNumber']); ?>" required="true">
                                                        </div>
                                                    </div>
                                                    <div class="form-group ">
                                                        <label for="username" class="control-label col-lg-3">Registration Date</label>
                                                        <div class="col-lg-6">
                                                            <input class="form-control " id="regDate" name="regdate" type="text" value="<?php echo h($row['RegDate']); ?>" readonly="true">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="status" class="control-label col-lg-3">Status</label>
                                                        <div class="col-lg-6">
                                                            <label class="radio-inline">
                                                                <input type="radio" name="status" value="1" <?php if ($row['status'] == 1) echo 'checked'; ?>> Active
                                                            </label>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="status" value="0" <?php if ($row['status'] == 0) echo 'checked'; ?>> Inactive
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php } ?>



                                                <div class="form-group">
                                                    <div class="col-lg-offset-3 col-lg-6">
                                                        <p style="text-align: center;"> <button class="btn btn-primary" type="submit" name="submit">Update</button></p>

                                                    </div>
                                                </div>

                                                </form>
                                        </div>
                                    </div>

                                </section>
                            </div>
                        </div>
                        <!-- page end-->
                    </div>

                </section>
                <!-- footer -->
                <?php include_once('includes/footer.php'); ?>
                <!-- / footer -->
            </section>

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
