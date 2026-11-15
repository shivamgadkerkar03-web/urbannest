<?php
require_once __DIR__ . '/includes/security.php';
if (current_user_id() > 0) {
	redirect_to('index.php');
}
include ('includes/dbconnection.php');

$msg = '';
$maxAttempts = 5;

if (isset($_POST['submit'])) {
	csrf_check();
	$attempts = (int) ($_SESSION['reset_attempts'] ?? 0);
	$contactno = trim((string) ($_POST['mobilenumber'] ?? ''));
	$email = trim((string) ($_POST['email'] ?? ''));

	if ($attempts >= $maxAttempts) {
		$msg = "Too many attempts. Please try again later.";
	} elseif ($email === '' || $contactno === '') {
		$msg = "Please enter both email and mobile number.";
	} else {
		$_SESSION['reset_attempts'] = $attempts + 1;
		unset($_SESSION['reset_uid']);

		$stmt = mysqli_prepare($con, "SELECT ID FROM tbluser WHERE Email = ? AND MobileNumber = ? LIMIT 1");
		mysqli_stmt_bind_param($stmt, 'ss', $email, $contactno);
		mysqli_stmt_execute($stmt);
		$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
		mysqli_stmt_close($stmt);

		if ($row) {
			session_regenerate_id(true);
			$_SESSION['reset_uid'] = (int) $row['ID'];
			unset($_SESSION['reset_attempts']);
			redirect_to('reset-password.php');
		}
		$msg = "Invalid email or mobile number. Please try again.";
	}
}
?>
<!DOCTYPE html>
<html lang="en" class="no-js">

<head>
	<?php include __DIR__ . '/includes/head.php'; ?>

	<title>UrbanNest || User Forget Password</title>



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
							Forgot Password</a>
					</p>
					<h1 class="text-white">
						Forgot Password
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
				<h2 class="text-center mb-4">Forgot Password</h2>
				<?php if ($msg) { ?><div class="alert alert-info py-2 text-center"><?= h($msg) ?></div><?php } ?>
				<div class="col-lg-12">
					<form class="row contact_form" action="" method="post" id="" name="login">
						<?= csrf_field() ?>
						<div class="col-md-12">


							<div class="form-group">
								<input type="email" class="form-control" id="email" name="email"
									placeholder="Enter email address" required="true">
							</div>
							<div class="form-group">
								<input type="text" class="form-control" id="mobilenumber" name="mobilenumber"
									placeholder="Enter Mobile Number" maxlength="10" pattern="[0-9]{10}"
									required="true">
							</div>



						</div>

						<div class="col-12">

							<button type="submit" value="login" name="submit" class="btn btn-primary w-100">Reset</button>
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