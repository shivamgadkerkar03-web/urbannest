<?php
require_once __DIR__ . '/../includes/security.php';
include('includes/dbconnection.php');

$resetId = (int) ($_SESSION['owner_reset_id'] ?? 0);
if ($resetId <= 0) {
    redirect_to('forgot-password.php');
}

$msg = '';
if (isset($_POST['submit'])) {
    csrf_check();
    $newpassword = (string) ($_POST['newpassword'] ?? '');
    $confirm = (string) ($_POST['confirmpassword'] ?? '');
    if ($newpassword === '' || $newpassword !== $confirm) {
        $msg = "New Password and Confirm Password field does not match";
    } else {
        $hash = hash_password($newpassword);
        $stmt = mysqli_prepare($con, "UPDATE tblowner SET Password = ? WHERE ID = ?");
        mysqli_stmt_bind_param($stmt, 'si', $hash, $resetId);
        mysqli_stmt_execute($stmt);
        $changed = mysqli_stmt_affected_rows($stmt) > 0;
        mysqli_stmt_close($stmt);
        if ($changed) {
            unset($_SESSION['owner_reset_id']);
            echo "<script>alert('Password successfully changed'); window.location.href='login.php';</script>";
            exit;
        }
        $msg = "Unable to change the password. Please try again.";
    }
}
?>
<!DOCTYPE html>
<head>
<title>Paying Guest Accomodation System | Forgot </title>

<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link rel="stylesheet" href="css/bootstrap.min.css" >
<link href="css/style.css" rel='stylesheet' type='text/css' />
<link href="css/style-responsive.css" rel="stylesheet"/>
<link href='//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/font.css" type="text/css"/>
<link href="css/font-awesome.css" rel="stylesheet"> 
<script type="text/javascript">
function checkpass()
{
if(document.changepassword.newpassword.value!=document.changepassword.confirmpassword.value)
{
alert('New Password and Confirm Password field does not match');
document.changepassword.confirmpassword.focus();
return false;
}
return true;
} 

</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../css/panel.css">
</head>
<body>
<div class="log-w3">
<div class="w3layouts-main">
	<h2>Recover Password</h2>
		<form action="" method="post" name="changepassword" onsubmit="return checkpass();">
			<?php echo csrf_field(); ?>
			<p style="font-size:16px; color:red" align="center"> <?php echo h($msg ?? ''); ?> </p>
			<input class="ggg" type="password" required="true" name="newpassword" placeholder="New Password">
      <input class="ggg" type="password" name="confirmpassword" required="true" placeholder="Confirm Your Password">
			
			
			
				<div class="clearfix"></div>
				<input type="submit" value="Reset" name="submit">
		</form>
		<p><a href="login.php">Sign In</a></p>
</div>
</div>
<script src="js/bootstrap.js"></script>
<script src="js/jquery.dcjqaccordion.2.7.js"></script>
<script src="js/scripts.js"></script>
<script src="js/jquery.slimscroll.js"></script>
<script src="js/jquery.nicescroll.js"></script>
<script src="js/jquery.scrollTo.js"></script>
</body>
</html>
