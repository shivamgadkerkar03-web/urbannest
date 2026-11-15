<?php
require_once __DIR__ . '/../includes/security.php';
include('includes/dbconnection.php');

$msg = '';
$attempts = (int) ($_SESSION['owner_reset_attempts'] ?? 0);
if (isset($_POST['submit'])) {
    csrf_check();
    if ($attempts >= 5) {
        $msg = "Too many attempts. Please try again later.";
    } else {
        $contactno = trim((string) ($_POST['contactno'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));

        $stmt = mysqli_prepare($con, "SELECT ID FROM tblowner WHERE Email = ? AND MobileNumber = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 'ss', $email, $contactno);
        mysqli_stmt_execute($stmt);
        $ret = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);

        if ($ret) {
            unset($_SESSION['owner_reset_attempts']);
            $_SESSION['owner_reset_id'] = (int) $ret['ID'];
            redirect_to('reset-password.php');
        }
        $_SESSION['owner_reset_attempts'] = $attempts + 1;
        $msg = "Invalid Details. Please try again.";
    }
}
?>
<!DOCTYPE html>
<head>
<title>UrbanNest | Forgot </title>

<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link rel="stylesheet" href="css/bootstrap.min.css" >
<link href="css/style.css" rel='stylesheet' type='text/css' />
<link href="css/style-responsive.css" rel="stylesheet"/>
<link href='//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/font.css" type="text/css"/>
<link href="css/font-awesome.css" rel="stylesheet"> 
<script src="js/jquery2.0.3.min.js"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../css/panel.css">
</head>
<body>
<div class="log-w3">
<div class="w3layouts-main">
      <h2 style="color:#fff;">UrbanNest | OWNER</h2>
  <hr />
  <h3 align="center">Forgot Password</h3>
  <hr />

		<form action="#" method="post" name="submit">
			<?php echo csrf_field(); ?>
			<p style="font-size:16px; color:red" align="center"> <?php echo h($msg ?? ''); ?> </p>
			<input type="email" class="ggg" name="email" placeholder="Email" required="true">
       <input class="ggg"  type="text" name="contactno" required="" placeholder="Mobile Number">
			
			
			
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
