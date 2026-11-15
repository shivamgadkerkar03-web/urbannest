<?php
require_once __DIR__ . '/includes/security.php';
include('includes/dbconnection.php');
$uid = require_user('signin.php');

$msg = '';
if (isset($_POST['submit'])) {
	csrf_check();
	$current = (string) ($_POST['currentpassword'] ?? '');
	$new = (string) ($_POST['newpassword'] ?? '');
	$confirm = (string) ($_POST['confirmpassword'] ?? '');

	$stmt = mysqli_prepare($con, "SELECT Password FROM tbluser WHERE ID = ?");
	mysqli_stmt_bind_param($stmt, 'i', $uid);
	mysqli_stmt_execute($stmt);
	$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
	mysqli_stmt_close($stmt);

	if (!$row || !verify_password($current, $row['Password'])) {
		$msg = "Your current password is wrong";
	} elseif (strlen($new) < 6) {
		$msg = "New password must be at least 6 characters.";
	} elseif ($new !== $confirm) {
		$msg = "New Password and Confirm Password do not match.";
	} else {
		$hash = hash_password($new);
		$stmt = mysqli_prepare($con, "UPDATE tbluser SET Password = ? WHERE ID = ?");
		mysqli_stmt_bind_param($stmt, 'si', $hash, $uid);
		$ok = mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		$msg = $ok ? "Your password successfully changed" : "Something went wrong. Please try again.";
	}
}
?>
<!DOCTYPE html>
<html lang="en" class="no-js">

<head>
	<?php include __DIR__ . '/includes/head.php'; ?>
	
	<title>UrbanNest || Change Password</title>

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
</head>

<body>

   <!-- Start Header Area -->
<?php include_once('includes/header.php');?>
   <!-- End Header Area -->

	<!-- start banner Area -->
	
	<section class="banner-area relative" id="home">
		<div class="overlay overlay-bg"></div>
		<div class="container">
			<div class="row d-flex text-center align-items-center justify-content-center">
				<div class="about-content col-lg-12">
					<p class="text-white link-nav"><a href="index.php">Home </a>
						<span class="lnr lnr-arrow-right"></span> <a href="change-password.php">
							Change Password</a></p>
					<h1 class="text-white">
						Change Password
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
				<h2 class="text-center mb-4">Change Password</h2>
				
 <?php if ($msg) { ?><div class="alert alert-info py-2 text-center"><?= h($msg) ?></div><?php } ?>
				<div class="col-lg-12">
					<form class="row contact_form" action="" method="post" id="" name="changepassword" onsubmit="return checkpass();">
						<?= csrf_field() ?>
						<div class="col-md-12">

							
							

							<div class="form-group">
								<input type="password" class="form-control" id="currentpassword" name="currentpassword" value="" placeholder="Current Password" required="true">
							</div>
							<div class="form-group">
								<input type="password" class="form-control" id="newpassword" name="newpassword" value="" placeholder="New Password" required="true">
							</div>
							<div class="form-group">
								<input type="password" class="form-control" id="confirmpassword" name="confirmpassword" value="" placeholder="Confirm Password" required="true">
							</div>
							
						</div>
						<div class="col-12">
							<button type="submit" value="submit" name='submit' class="btn btn-primary w-100">Change</button>
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

	<?php include_once('includes/footer.php');?>


</body>

</html>
