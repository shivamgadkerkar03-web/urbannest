<?php
require_once __DIR__ . '/includes/security.php';
include ('includes/dbconnection.php');
?>

<!DOCTYPE html>
<html lang="en" class="no-js">

<head>
	<?php include __DIR__ . '/includes/head.php'; ?>

	<title>About Us</title>
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
	<section class="banner-area relative" id="home">
		<div class="overlay overlay-bg"></div>
		<div class="container">
			<div class="row d-flex text-center align-items-center justify-content-center">
				<div class="about-content col-lg-12">
					<p class="text-white link-nav"><a href="index.php">Home </a> <span
							class="lnr lnr-arrow-right"></span> <a href="about-us.php">
							About Us</a></p>
					<h1 class="text-white">
						About Us
					</h1>
				</div>
			</div>
		</div>
	</section>
	<!-- End banner Area -->


	<!-- Start About Area -->
	<section class="about-area section-gap bg-white">
		<div class="container">
			<div class="row d-flex justify-content-end align-items-center">
				<div class="col-lg-6">
					<?php


					$ret = mysqli_query($con, "select * from tblpages where PageType='aboutus'");
					$cnt = 1;
					while ($row = mysqli_fetch_array($ret)) {

						?>
						<div class="single-about">
							<h4><?php echo $row['PageTitle']; ?></h4>
							<p>
								<?php echo $row['PageDescription']; ?>
							</p>
						</div>
					<?php } ?>
				</div>
				<div class="col-lg-6 about-right no-padding">
					<img class="img-fluid" src="img/about-us2.jpg" height="400" alt="">
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