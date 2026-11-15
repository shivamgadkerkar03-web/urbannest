<?php
require_once __DIR__ . '/../includes/security.php';
include('includes/dbconnection.php');
$ownerid = require_owner('login.php');

$msg = '';
if (isset($_POST['submit'])) {
    csrf_check();
    $fullname = trim((string) ($_POST['fullname'] ?? ''));
    $mobno = trim((string) ($_POST['contactnumber'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));

    if ($fullname === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^[0-9]{10}$/', $mobno)) {
        $msg = "Please enter a valid name, email and 10 digit mobile number.";
    } else {
        $dup = mysqli_prepare($con, "SELECT ID FROM tblowner WHERE (Email = ? OR MobileNumber = ?) AND ID <> ? LIMIT 1");
        mysqli_stmt_bind_param($dup, 'ssi', $email, $mobno, $ownerid);
        mysqli_stmt_execute($dup);
        $taken = mysqli_fetch_assoc(mysqli_stmt_get_result($dup));
        mysqli_stmt_close($dup);

        if ($taken) {
            $msg = "This email or contact number is already associated with another account.";
        } else {
            $stmt = mysqli_prepare($con, "UPDATE tblowner SET FullName = ?, Email = ?, MobileNumber = ? WHERE ID = ?");
            mysqli_stmt_bind_param($stmt, 'sssi', $fullname, $email, $mobno, $ownerid);
            if (mysqli_stmt_execute($stmt)) {
                $msg = "Owner profile has been updated.";
            } else {
                $msg = "Something Went Wrong. Please try again.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<head>
<title>UrbanNest || Owner Profile  </title>

<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link rel="stylesheet" href="css/bootstrap.min.css" >
<link href="css/style.css" rel='stylesheet' type='text/css' />
<link href="css/style-responsive.css" rel="stylesheet"/>
<!-- font CSS -->
<link href='//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/font.css" type="text/css"/>
<link href="css/font-awesome.css" rel="stylesheet"> 
<script src="js/jquery2.0.3.min.js"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../css/panel.css">
</head>
<body>
<section id="container">
<?php include_once('includes/header.php');?>
<?php include_once('includes/sidebar.php');?>
<section id="main-content">
	<section class="wrapper">
		<div class="form-w3layouts">
            <!-- page start-->
            
          
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Owner Profile
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
$ret=mysqli_query($con,"select * from tblowner where ID=" . (int) $ownerid);
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {

?>
                                <form class="cmxform form-horizontal " method="post" action="">
                                    <?php echo csrf_field(); ?>
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
		  <?php include_once('includes/footer.php');?>    
  <!-- / footer -->
</section>

</section>

<script src="js/bootstrap.js"></script>
<script src="js/jquery.dcjqaccordion.2.7.js"></script>
<script src="js/scripts.js"></script>
<script src="js/jquery.slimscroll.js"></script>
<script src="js/jquery.nicescroll.js"></script>
<script src="js/jquery.scrollTo.js"></script>
</body>
</html>
