<?php
require_once __DIR__ . '/includes/security.php';
include('includes/dbconnection.php');
$uid = require_user('signin.php');

$msg = '';
if (isset($_POST['submit'])) {
	csrf_check();
	$fullname = trim((string) ($_POST['fullname'] ?? ''));
	$mobno = trim((string) ($_POST['mobilenumber'] ?? ''));
	$dob = trim((string) ($_POST['dob'] ?? ''));
	$dobDate = DateTime::createFromFormat('Y-m-d', $dob);

	if ($fullname === '' || !preg_match("/^[A-Za-z\s]{2,}$/", $fullname)) {
		$msg = "Please enter a valid name.";
	} elseif (!preg_match('/^[0-9]{10}$/', $mobno)) {
		$msg = "Please enter a valid 10 digit mobile number.";
	} elseif (!$dobDate || $dobDate->format('Y-m-d') !== $dob || $dobDate > new DateTime('today')) {
		$msg = "Please enter a valid date of birth.";
	} else {
		$stmt = mysqli_prepare($con, "SELECT ID FROM tbluser WHERE MobileNumber = ? AND ID <> ? LIMIT 1");
		mysqli_stmt_bind_param($stmt, 'si', $mobno, $uid);
		mysqli_stmt_execute($stmt);
		$taken = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
		mysqli_stmt_close($stmt);

		if ($taken) {
			$msg = "This mobile number is already associated with another account.";
		} else {
			$stmt = mysqli_prepare($con, "UPDATE tbluser SET FullName = ?, MobileNumber = ?, dob = ? WHERE ID = ?");
			mysqli_stmt_bind_param($stmt, 'sssi', $fullname, $mobno, $dob, $uid);
			if (mysqli_stmt_execute($stmt)) {
				$_SESSION['FullName'] = $fullname;
				$_SESSION['cno'] = $mobno;
				$msg = "User profile has been updated.";
			} else {
				error_log('Profile update failed: ' . mysqli_error($con));
				$msg = "Something Went Wrong. Please try again.";
			}
			mysqli_stmt_close($stmt);
		}
	}
}

$stmt = mysqli_prepare($con, "SELECT FullName, Email, MobileNumber, dob, RegDate, LastUpdationDate FROM tbluser WHERE ID = ?");
mysqli_stmt_bind_param($stmt, 'i', $uid);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);
if (!$row) {
	redirect_to('logout.php');
}
?>
<!DOCTYPE html>
<html lang="en" class="no-js">

<head>
	<?php include __DIR__ . '/includes/head.php'; ?>
	
	<title>UrbanNest || User Profile</title>

</head>

<body>
<!-- End footer Area -->
<?php include_once('includes/header.php');?>
<!-- End Header Area -->

	<!-- start banner Area -->

	<section class="banner-area relative" id="home">
		<div class="overlay overlay-bg"></div>
		<div class="container">
			<div class="row d-flex text-center align-items-center justify-content-center">
				<div class="about-content col-lg-12">
					<p class="text-white link-nav"><a href="index.php">Home </a>
						<span class="lnr lnr-arrow-right"></span> <a href="user-profile.php">
							User Profile</a></p>
					<h1 class="text-white">
						User Profile
					</h1>
				</div>
			</div>
		</div>
	</section>

	<section class="contact-page-area section-gap">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-xl-6 col-lg-7 col-md-9">
					<div class="auth-card">
						<h2 class="mb-1">My Profile</h2>
						<p class="auth-sub mb-4">
							Member since <?= h(date('M Y', strtotime($row['RegDate']))) ?>
							<?php if (!empty($row['LastUpdationDate'])) { ?> &middot; Last updated <?= h(date('d M Y', strtotime($row['LastUpdationDate']))) ?><?php } ?>
						</p>
						<?php if ($msg) { ?><div class="alert alert-info py-2 text-center"><?= h($msg) ?></div><?php } ?>
						<form class="contact_form" action="" method="post">
							<?= csrf_field() ?>
							<div class="form-group">
								<label class="form-label" for="fullname">Full name</label>
								<input type="text" class="form-control" id="fullname" name="fullname" required maxlength="100" value="<?= h($row['FullName']) ?>">
							</div>
							<div class="form-group">
								<label class="form-label" for="email">Email</label>
								<input type="email" class="form-control" id="email" value="<?= h($row['Email']) ?>" readonly disabled>
							</div>
							<div class="row">
								<div class="col-sm-6 form-group">
									<label class="form-label" for="mobilenumber">Mobile number</label>
									<input type="text" class="form-control" id="mobilenumber" name="mobilenumber" value="<?= h($row['MobileNumber']) ?>" maxlength="10" pattern="[0-9]{10}" required>
								</div>
								<div class="col-sm-6 form-group">
									<label class="form-label" for="dob">Date of birth</label>
									<input type="date" class="form-control" id="dob" name="dob" required max="<?= date('Y-m-d') ?>" value="<?= h($row['dob']) ?>">
								</div>
							</div>
							<button type="submit" value="submit" name="submit" class="btn btn-primary w-100 mt-2">Save changes</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
     <!-- End contact-page Area -->

	<!-- start footer Area -->

	<?php include_once('includes/footer.php');?>
    <!-- End footer Area -->

</body>

</html>
