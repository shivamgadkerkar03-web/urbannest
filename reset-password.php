<?php
require_once __DIR__ . '/includes/security.php';
include ('includes/dbconnection.php');

$resetUid = (int) ($_SESSION['reset_uid'] ?? 0);
if ($resetUid <= 0) {
	redirect_to('forgot-password.php');
}

$msg = '';
if (isset($_POST['submit'])) {
	csrf_check();
	$new = (string) ($_POST['newpassword'] ?? '');
	$confirm = (string) ($_POST['confirmpassword'] ?? '');

	if (strlen($new) < 6) {
		$msg = "Password must be at least 6 characters.";
	} elseif ($new !== $confirm) {
		$msg = "New Password and Confirm Password do not match.";
	} else {
		$hash = hash_password($new);
		$stmt = mysqli_prepare($con, "UPDATE tbluser SET Password = ? WHERE ID = ?");
		mysqli_stmt_bind_param($stmt, 'si', $hash, $resetUid);
		mysqli_stmt_execute($stmt);
		$changed = mysqli_stmt_affected_rows($stmt) > 0;
		mysqli_stmt_close($stmt);

		if ($changed) {
			unset($_SESSION['reset_uid']);
			echo "<script>alert('Password successfully changed'); window.location.href='signin.php';</script>";
			exit;
		}
		$msg = "Something went wrong. Please try again.";
	}
}
?>
<!DOCTYPE html>
<html lang="en" class="no-js">

<head>
	<?php include __DIR__ . '/includes/head.php'; ?>

	<title>UrbanNest || Reset Password</title>


	<script type="text/javascript">
		function checkpass() {
			if (document.changepassword.newpassword.value != document.changepassword.confirmpassword.value) {
				alert('New Password and Confirm Password field does not match');
				document.changepassword.confirmpassword.focus();
				return false;
			}
			return true;
		}

	</script>
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
						<span class="lnr lnr-arrow-right"></span> <a href="forgot-password.php">
							Recover Password</a>
					</p>
					<h1 class="text-white">
						Recover Password
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
				<div class="col-xl-5 col-lg-6 col-md-8">
				<div class="auth-card">
				<h2 class="text-center mb-4">Recover Password</h2>
				<?php if ($msg) { ?><div class="alert alert-info py-2 text-center"><?= h($msg) ?></div><?php } ?>
				<div class="col-lg-12">
					<form class="row contact_form" action="" method="post" id="" name="changepassword"
						onsubmit="return checkpass();">
						<?= csrf_field() ?>
						<div class="col-md-12">


							<div class="form-group" style="padding-top: 20px">
								<input type="password" class="form-control" id="newpassword" name="newpassword"
									placeholder="New Password" required="true">
							</div>

							<div class="form-group" style="padding-top: 20px">
								<input type="password" class="form-control" id="confirmpassword" name="confirmpassword"
									placeholder="Confirm Password" required="true">
							</div>

						</div>

						<div class="col-12">

							<button type="submit" value="submit" name="submit" class="btn btn-primary w-100">Reset</button>
						</div>
						<div class="col-md-6 text-left">
							<a href="signin.php" class="btn btn-primary w-100">Sign in</a>
						</div>

					</form>

				</div>
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