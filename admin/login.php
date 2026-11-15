<?php
require_once __DIR__ . '/../includes/security.php';
include('includes/dbconnection.php');

if (current_admin_id() > 0) {
    redirect_to('dashboard.php');
}

$msg = '';
if (isset($_POST['login'])) {
    $adminuser = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    $stmt = mysqli_prepare($con, "SELECT ID, Password FROM tbladmin WHERE UserName = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 's', $adminuser);
    mysqli_stmt_execute($stmt);
    $ret = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    $needsRehash = false;
    if ($ret && verify_password($password, $ret['Password'], $needsRehash)) {
        $adminId = (int) $ret['ID'];
        if ($needsRehash) {
            $newHash = hash_password($password);
            $up = mysqli_prepare($con, "UPDATE tbladmin SET Password = ? WHERE ID = ?");
            mysqli_stmt_bind_param($up, 'si', $newHash, $adminId);
            mysqli_stmt_execute($up);
            mysqli_stmt_close($up);
        }
        session_regenerate_id(true);
        $_SESSION['pgasaid'] = $adminId;
        redirect_to('dashboard.php');
    }
    $msg = "Invalid Details.";
}
?>
<!DOCTYPE html>
<head>
<title>UrbanNest | Login </title>
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- bootstrap-css -->
<link rel="stylesheet" href="css/bootstrap.min.css" >
<!-- //bootstrap-css -->
<!-- Custom CSS -->
<link href="css/style.css" rel='stylesheet' type='text/css' />
<link href="css/style-responsive.css" rel="stylesheet"/>
<!-- font CSS -->
<link href='//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
<!-- font-awesome icons -->
<link rel="stylesheet" href="css/font.css" type="text/css"/>
<link href="css/font-awesome.css" rel="stylesheet"> 
<!-- //font-awesome icons -->
<script src="js/jquery2.0.3.min.js"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../css/panel.css">
</head>
<body>
<div class="log-w3">
<div class="w3layouts-main">
        <h2 style="color:#fff;">UrbanNest | Admin</h2>
  <hr />
  <h3 align="center">Sign In Now</h3>
  <hr />
		<form action="#" method="post" name="login">
			<p style="font-size:16px; color:red" align="center"> <?php echo h($msg); ?> </p>
			<input type="text" class="ggg" name="username" placeholder="User Name" required="true">
			<input type="password" class="ggg" name="password" placeholder="PASSWORD" required="true">
			
			<a href="forgot-password.php">Forgot Password?</a>
				<div class="clearfix"></div>
				<input type="submit" value="Sign In" name="login">
		</form>
		        <hr />
    <p align="center">
      <a href="../index.php" class="btn btn-primary">Back to Home Page</a></p>
</div>
</div>
<script src="js/bootstrap.js"></script>
<script src="js/jquery.dcjqaccordion.2.7.js"></script>
<script src="js/scripts.js"></script>
<script src="js/jquery.slimscroll.js"></script>
<script src="js/jquery.nicescroll.js"></script>
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/flot-chart/excanvas.min.js"></script><![endif]-->
<script src="js/jquery.scrollTo.js"></script>
</body>
</html>
