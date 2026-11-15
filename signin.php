<?php
require_once __DIR__ . '/includes/security.php';
if (current_user_id() > 0) {
	redirect_to('index.php');
}
include ('includes/dbconnection.php');

$msg = '';
if (isset($_POST['login'])) {
	csrf_check();
	$email = trim((string) ($_POST['email'] ?? ''));
	$password = (string) ($_POST['password'] ?? '');

	$stmt = mysqli_prepare($con, "SELECT ID, MobileNumber, FullName, Password FROM tbluser WHERE Email = ? LIMIT 1");
	mysqli_stmt_bind_param($stmt, 's', $email);
	mysqli_stmt_execute($stmt);
	$ret = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
	mysqli_stmt_close($stmt);

	$needsRehash = false;
	if ($ret && verify_password($password, $ret['Password'], $needsRehash)) {
		if ($needsRehash) {
			$newHash = hash_password($password);
			$up = mysqli_prepare($con, "UPDATE tbluser SET Password = ? WHERE ID = ?");
			mysqli_stmt_bind_param($up, 'si', $newHash, $ret['ID']);
			mysqli_stmt_execute($up);
			mysqli_stmt_close($up);
		}

		session_regenerate_id(true);
		unset($_SESSION['csrf_token']);
		$_SESSION['pgasuid'] = $ret['ID'];
		$_SESSION['cno'] = $ret['MobileNumber'];
		$_SESSION['FullName'] = $ret['FullName'];

		$dest = 'index.php';
		if (!empty($_GET['redirect'])) {
			$allowed = ['need-room.php', 'have-room.php', 'find-roommate.php', 'my-matches.php', 'roommate-profile.php', 'resellNation/seller.php'];
			if (in_array($_GET['redirect'], $allowed, true)) {
				$dest = $_GET['redirect'];
			}
		}
		redirect_to($dest);
	} else {
		$msg = "Invalid Details.";
	}
}
?>
<!DOCTYPE html>
<html lang="en" class="no-js">

<head>
	<?php include __DIR__ . '/includes/head.php'; ?>

	<title>UrbanNest || User Signin</title>

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
							<?= csrf_field() ?>
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

</body>

</html>
