<?php
require_once __DIR__ . '/includes/security.php';
include ('includes/dbconnection.php');
?>

<!DOCTYPE html>
<html lang="en" class="no-js">

<head>
	<?php include __DIR__ . '/includes/head.php'; ?>

	<title>Contact Us</title>

	<style>
		footer {
			width: 100%;
			display: grid;
			place-item: center;
			padding: 20px !important;
		}
	</style>
</head>

<body>

	<!-- Start Header Area -->
	<?php include_once ('includes/header.php'); ?>
	<!-- End Header Area -->

	<!-- start banner Area -->


	<section class="about-area section-gap bg-white">
		<div class="container">
			<div class="row d-flex justify-content-end align-items-center">
				<div class="col-lg-6">
					<?php
					$ret = mysqli_query($con, "select * from tblpages where PageType='contactus'");
					$cnt = 1;
					while ($row = mysqli_fetch_array($ret)) {
						?>
						<div class="single-about">
							<h4>
								<?php echo $row['PageTitle']; ?>
							</h4>
							<p>
								<?php echo $row['PageDescription']; ?>
							</p>
						</div>
					<?php } ?>

					<!-- Contact Form -->
					<div class="single-about">
						<h4>Contact Form</h4>
						<form action="https://api.web3forms.com/submit" method="post">
							<div class="form-group">
								<input type="hidden" name="access_key" value="483653cf-ad41-4bae-877c-adf2fd219d82">
								<input type="text" class="form-control" name="name" placeholder="Your Name" required>
							</div>
							<div class="form-group">
								<input type="email" class="form-control" name="email" placeholder="Your Email" required>
							</div>
							<div class="form-group">
								<input type="text" class="form-control" name="subject" placeholder="Subject" required>
							</div>
							<div class="form-group">
								<textarea class="form-control" rows="5" name="message" placeholder="Your Message"
									required></textarea>
							</div>
							<button type="submit" class="btn btn-primary">Send Message</button>
						</form>
					</div>
				</div>
				<div class="col-lg-6 about-right no-padding">
					<img class="img-fluid" src="img/about-img.jpg" alt="" style="max-height: 400px;">
				</div>
			</div>
		</div>
	</section>

	<!-- End About Area -->

	<!-- Start testomial Area -->

	<!-- End testomial Area -->

	<!-- start footer Area -->

	<?php include_once ('includes/footer.php'); ?>
	<!-- End footer Area -->



</body>

</html>