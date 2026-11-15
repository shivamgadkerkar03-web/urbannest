<?php
require_once __DIR__ . '/includes/security.php';
if (current_user_id() > 0) {
	redirect_to('index.php');
}
include ('includes/dbconnection.php');

$msg = '';
if (isset($_POST['submit'])) {
	csrf_check();
	$fname = trim((string) ($_POST['name'] ?? ''));
	$mobno = trim((string) ($_POST['mobilenumber'] ?? ''));
	$email = trim((string) ($_POST['email'] ?? ''));
	$plain = (string) ($_POST['password'] ?? '');

	if (!preg_match("/^[A-Za-z\s]{2,}$/", $fname)) {
		$msg = "Invalid name. Please enter a valid name.";
	} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$msg = "Please enter a valid email address.";
	} elseif (!preg_match('/^[0-9]{10}$/', $mobno)) {
		$msg = "Please enter a valid 10 digit mobile number.";
	} elseif (strlen($plain) < 6) {
		$msg = "Password must be at least 6 characters.";
	} else {
		$stmt = mysqli_prepare($con, "SELECT ID FROM tbluser WHERE Email = ? OR MobileNumber = ? LIMIT 1");
		mysqli_stmt_bind_param($stmt, 'ss', $email, $mobno);
		mysqli_stmt_execute($stmt);
		$exists = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
		mysqli_stmt_close($stmt);

		if ($exists) {
			$msg = "This email or Contact Number already associated with another account";
		} else {
			$password = hash_password($plain);
			$stmt = mysqli_prepare($con, "INSERT INTO tbluser (FullName, MobileNumber, Email, Password) VALUES (?, ?, ?, ?)");
			mysqli_stmt_bind_param($stmt, 'ssss', $fname, $mobno, $email, $password);
			$ok = mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
			if ($ok) {
				redirect_to('signin.php');
			}
			error_log('Registration failed: ' . mysqli_error($con));
			$msg = "Something Went Wrong. Please try again";
		}
	}
}

?>
<!DOCTYPE html>
<html lang="en" class="no-js">

<head>
	<?php include __DIR__ . '/includes/head.php'; ?>

	<title>UrbanNest || User Signup</title>


	<style>
		input {
			color: black !important;
		}
	</style>

	<script type="text/javascript">
		function checkpass() {
			if (document.signup.password.value != document.signup.repeatpassword.value) {
				alert('Password and Repeat Password field does not match');
				document.signup.repeatpassword.focus();
				return false;
			}
			
			if(!isStrongPassword(document.signup.password.value)) {
				alert('Password must be strong !');
				document.signup.repeatpassword.focus();
				return false;
			}
			// console.log(document.signup.password.value);
			return validatePhoneNumber(document.signup.mobilenumber.value);
		}

		function validatePhoneNumber(phone) {
			phone = phone.replace(/\D/g, '');
			if (phone.length == 10) {
				if (phone > 6000000000) {
					return true;
				}
			}
			alert('Contact number Invalid', phone.length);
			document.signup.repeatpassword.focus();
			return false;
		}

		function isStrongPassword(password) {
			// Define regular expressions for each criterion
			const minLength = 8; // Minimum length
			const hasUppercase = /[A-Z]/;
			const hasLowercase = /[a-z]/;
			const hasNumber = /\d/;
			const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/; // Add or remove special characters as needed

			// Check each criterion
			const isMinLength = password.length >= minLength;
			const hasUppercaseLetter = hasUppercase.test(password);
			const hasLowercaseLetter = hasLowercase.test(password);
			const hasNumberDigit = hasNumber.test(password);
			const hasSpecialCharacter = hasSpecialChar.test(password);

			// Return true if all criteria are met, false otherwise
			return (
				isMinLength &&
				hasUppercaseLetter &&
				hasLowercaseLetter &&
				hasNumberDigit &&
				hasSpecialCharacter
			);
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
						<span class="lnr lnr-arrow-right"></span> <a href="register.php">
							Register</a>
					</p>
					<h1 class="text-white">
						Register
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
				<h2 class="text-center mb-4">Register</h2>
				<?php if ($msg) { ?><div class="alert alert-info py-2 text-center"><?= h($msg) ?></div><?php } ?>
				<div class="col-lg-12">
					<form class="row contact_form" action="" method="post" id="" name="signup"
						onsubmit="return checkpass();">
						<?= csrf_field() ?>
						<div class="col-md-12">



							<div class="form-group">
								<input type="text" class="form-control" id="name" name="name"
									placeholder="Enter your name" required="true" pattern="[A-Za-z\s]{2,}">

							</div>
							<div class="form-group">
								<input type="email" class="form-control" id="email" name="email"
									placeholder="Enter email address" required="true">
							</div>
							<div class="form-group">
								<input type="text" class="form-control" id="mobilenumber" name="mobilenumber"
									placeholder="Enter Mobile Number" required="true">
							</div>
							<div class="form-group">
								<input type="password" class="form-control" id="password" name="password"
									placeholder="Password" required="true">
							</div>
							<div class="form-group">
								<input type="password" class="form-control" id="repeatpassword" name="repeatpassword"
									placeholder="Repeat Password" required="true">
							</div>
						</div>

						<div class="col-12">
							<button type="submit" value="submit" name="submit" class="btn btn-primary w-100">Register</button>
						</div>
						<p class="auth-foot col-12">Already have an account? <a href="signin.php">Log in</a></p>
						<!-- <div class="col-md-6 text-left">

							<a href="signin.php" class="btn btn-primary w-100">Login</a>

						</div> -->
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