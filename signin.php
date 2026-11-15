<?php
session_start();
error_reporting(0);
include ('includes/dbconnection.php');

if (isset($_POST['login'])) {
	$email = $_POST['email'];
	$password = md5($_POST['password']);
	$query = mysqli_query($con, "SELECT ID, MobileNumber, FullName FROM tbluser WHERE Email='$email' && Password='$password'");
	$ret = mysqli_fetch_array($query);
	if ($ret > 0) {
		$ret_json = json_encode($ret);
		$_SESSION['pgasuid'] = $ret['ID'];
		$_SESSION['cno'] = $ret['MobileNumber'];
		$_SESSION['FullName'] = $ret['FullName'];

		$dest = 'index.php';
		if (!empty($_GET['redirect'])) {
			$allowed = ['need-room.php', 'have-room.php', 'find-roommate.php', 'my-matches.php', 'roommate-profile.php', 'resellNation/seller.php'];
			if (in_array($_GET['redirect'], $allowed)) {
				$dest = $_GET['redirect'];
			}
		}
		header('location:' . $dest);
	} else {
		$msg = "Invalid Details.";
	}
}
?>
<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>

	<title>UrbanNest || User Signin</title>

	<link href="https://fonts.googleapis.com/css?family=Poppins:100,200,400,300,500,600,700" rel="stylesheet">
	<!-- CSS ============================================= -->
	<link rel="stylesheet" href="css/linearicons.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/nice-select.css">
	<link rel="stylesheet" href="css/ion.rangeSlider.css" />
	<link rel="stylesheet" href="css/ion.rangeSlider.skinFlat.css" />
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/owl.carousel.css">
	<link rel="stylesheet" href="css/main.css">
	<style>
		.login-card {
			background: #fff;
			padding: 40px 35px;
			border-radius: 12px;
			box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
			border: 1px solid #eee;
		}
		.login-card .form-control {
			height: 48px;
			border-radius: 6px;
			border: 1px solid #ddd;
			padding: 10px 16px;
			font-size: 14px;
			color: #333 !important;
		}
		.login-card .form-control:focus {
			border-color: #ff3333;
			box-shadow: 0 0 0 0.2rem rgba(255, 51, 51, 0.15);
		}
		.login-card .btn-login {
			background: #ff3333;
			color: #fff;
			font-weight: 600;
			border: none;
			border-radius: 6px;
			padding: 12px;
			width: 100%;
			font-size: 15px;
			transition: all 0.3s;
			cursor: pointer;
		}
		.login-card .btn-login:hover {
			background: #cc0000;
			color: #fff;
		}
	</style>

</head>

<body>

	<!-- Start Header Area -->
	<?php include_once ('includes/header.php'); ?>
	<!-- End Header Area -->

	<!-- start banner Area -->
	<section class="banner-area relative" id="home">
		<div class="overlay overlay-bg"></div>
		<div class="container">
			<div class="row d-flex text-center align-items-center justify-content-center">
				<div class="about-content col-lg-12">
					<p class="text-white link-nav"><a href="index.php">Home </a>
						<span class="lnr lnr-arrow-right"></span> <a href="signin.php">
							Login</a>
					</p>
					<h1 class="text-white">
						Login
					</h1>
				</div>
			</div>
		</div>
	</section>
	<!-- End banner Area -->

	<!-- Start contact-page Area -->
	<section class="contact-page-area section-gap">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-5 col-md-7 col-sm-10">
					<div class="login-card">
						<h3 class="text-center mb-4" style="color: #222; font-weight: 700;">User Sign In</h3>
						<?php if (isset($_GET['msg']) && $_GET['msg'] === 'login_required') { ?>
							<div class="alert alert-info text-center" role="alert" style="font-size: 14px; padding: 10px; margin-bottom: 20px;">
								<i class="fa fa-info-circle me-1"></i> Please log in to post or manage roommate requirements.
							</div>
						<?php } ?>
						<?php if ($msg) { ?>
							<div class="alert alert-danger text-center" role="alert" style="font-size: 14px; padding: 10px; margin-bottom: 20px;">
								<?= htmlspecialchars($msg) ?>
							</div>
						<?php } ?>
						<form class="contact_form" action="" method="post" name="login">
							<div class="form-group mb-3">
								<label for="email" style="font-weight: 500; font-size: 13px; color: #555;">Email Address</label>
								<input type="email" class="form-control" id="email" name="email"
									placeholder="e.g. shivam@gmail.com" required="true">
							</div>
							<div class="form-group mb-2">
								<div class="d-flex justify-content-between align-items-center" style="margin-bottom: 5px;">
									<label for="password" style="font-weight: 500; font-size: 13px; color: #555; margin-bottom: 0;">Password</label>
									<a href="forgot-password.php" style="font-size: 12px; color: #ff3333;">Forgot Password?</a>
								</div>
								<input type="password" class="form-control" id="password" name="password"
									placeholder="Enter your password" required="true">
							</div>
							<div class="mt-4">
								<button type="submit" value="login" name="login" class="btn-login">Login</button>
								<p class="text-center mt-3" style="font-size: 13px; color: #666; margin-bottom: 0;">
									New user? <a href="register.php" style="color: #ff3333; font-weight: 600;">Register now!</a>
								</p>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- End contact-page Area -->

	<!-- start footer Area -->
	<?php include_once ('includes/footer.php'); ?>
	<!-- End footer Area -->

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
