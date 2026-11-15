<?php
session_start();
error_reporting(0);
include ('includes/dbconnection.php');
?>
<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>

	<title>UrbanNest</title>

	<link href="https://fonts.googleapis.com/css?family=Poppins:100,200,400,300,500,600,700" rel="stylesheet">
	<!--CSS ============================================= -->

	<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
	<link rel="stylesheet" href="css/linearicons.css">=
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/nice-select.css">
	<link rel="stylesheet" href="css/ion.rangeSlider.css" />
	<link rel="stylesheet" href="css/ion.rangeSlider.skinFlat.css" />
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/owl.carousel.css">
	<link rel="stylesheet" href="css/main.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
		crossorigin="anonymous"></script>
	<style>
		.home-banner-area {
			height: 85vh !important;
			object-fit: center !important;
			background-size: cover;
			background-position: center;
		}



		.btn-explore {
			display: inline-block;
			padding: 15px 30px;
			background-color: #ff3333;
			/* Red color */
			color: #fff;
			font-size: 18px;
			font-weight: bold;
			text-transform: uppercase;
			border-radius: 5px;
			text-decoration: none;
			transition: background-color 0.3s ease;
			background-color: #ff3333;
			/* Red color */
			border: none;
		}

		.btn-explore:hover {
			background-color: #cc0000 !important;
			/* Darker shade of red on hover */
		}

		.carousel-inner {
			/* height: 60vh; */
		}

		.custImg {
			height: 80vh !important;
			object-position: center !important;
			object-fit: cover;
		}
	</style>

</head>

<body>

	<!-- Start Header Area -->
	<?php include_once ('includes/header.php'); ?>
	<!-- End Header Area -->

	<!-- start banner Area -->

	<div id="carouselExampleCaptions" class="carousel slide">
		<div class="carousel-indicators">
			<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"
				aria-current="true" aria-label="Slide 1"></button>
			<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
				aria-label="Slide 2"></button>
			<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
				aria-label="Slide 3"></button>
		</div>
		<div class="carousel-inner">
			<div class="carousel-item active">
				<img src="img/banner-bg.jpg" class="d-block w-100" alt="..." class="custImg"
					style="height: 60vh !important; object-position: center !important; object-fit: cover;">
				<div class="carousel-caption d-none d-md-block">
					<h1 class="text-white mb-50" style="font-weight: bold;">Find Your Perfect PG Accommodation</h1>
					<p class="text-white mb-2" style="font-size: 15px;">Search from a wide range of PG accommodations.
						Discover the perfect place to live.</p>
					<h2 class="text-white mb-20">Discover UrbanNest: Your Gateway to Seamless PG Booking!</h2>
					<a href="./pg.php" class="btn btn-explore mb-20">Explore</a>
				</div>
			</div>
			<div class="carousel-item">
				<img src="img/friends.jpeg" class="d-block w-100" alt="..." class="custImg"
					style="height: 60vh !important; object-position: center !important; object-fit: cover;">
				<div class="carousel-caption d-none d-md-block">
					<h1 class="text-white mb-50" style="font-weight: bold;">Find Your Perfect Roomie Match: Join Our
						Cozy Abode Today!</h1>
					<p class="text-white mb-2" style="font-size: 15px;"></p>
					<a href="./find-roommate.php" class="btn btn-explore mb-20">Explore</a>

				</div>
			</div>
			<div class="carousel-item">
				<img src="img/store.jpeg" class="d-block w-100" alt="..." class="custImg"
					style="height: 60vh !important; object-position: center !important; object-fit: cover;">
				<div class="carousel-caption d-none d-md-block">
					<h1>Upgrade Your Space with Fantastic Secondhand FInds! </h1>
					<p></p>
					<a href="./resellNation/" class="btn btn-explore mb-20">Explore</a>

				</div>
			</div>
		</div>
		<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
			data-bs-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="visually-hidden">Previous</span>
		</button>
		<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
			data-bs-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="visually-hidden">Next</span>
		</button>
	</div>

	<!-- old banner -->
	<!-- <section class="home-banner-area relative" id="home">
		<div class="overlay overlay-bg"></div>
		<div class="container">

			<div class="row fullscreen align-items-end justify-content-center">
				<div class="banner-content col-lg-12 col-md-12">
					<h1 class="text-white mb-15" style="font-weight: bold;">Find Your Perfect PG Accommodation</h1>
					<p class="text-white mb-2" style="font-size: 15px;">Search from a wide range of PG accommodations.
						Discover the perfect place to live.</p>
					<h2 class="text-white mb-4">Discover UrbanNest: Your Gateway to Seamless PG Booking!</h2>
					<a href="pg.php" class="btn btn-explore">Explore</a>
				</div>
				<div class="banner-content col-lg-12 col-md-12">
				</div>
			</div>
		</div>
	</section> -->
	<!-- End banner Area -->


	<div class="search-field">
		<form class="search-form" method="post" action="search-result.php">
			<div class="row">
				<div class="col-lg-12 d-flex align-items-center justify-content-center toggle-wrap">
					<div class="row">
						<div class="col">
							<h4 class="search-title">Search Properties PG</h4>
						</div>

					</div>
				</div>
				<div class="container d-flex justify-content-center">
					<!-- Added class container and justify-content-center -->
					<div class="col-lg-6 col-md-6 col-xs-6">
						<select name="location" class="form-control" required="true" style="border:solid 1px #000">
							<option value="">Choose State</option>
							<?php
							$query = mysqli_query($con, "select * from tblstate");
							while ($row = mysqli_fetch_array($query)) {
								?>
								<option value="<?php echo $row['StateName']; ?>">
									<?php echo $row['StateName']; ?>
								</option>
							<?php } ?>
						</select>
					</div>
				</div>


				<!--<div class="col-lg-6 col-md-6 col-xs-6">
					<select name="gender" class="form-control" required>
						<option value="">Type</option>
						<option value="Boys">Boys</option>
						<option value="Girls">Girls</option>
						<option value="Both">Both</option>
					</select>
				</div>--->

				<div class="col-lg-12 col-md-6 col-xs-6" align="center">
					<button class="primary-btn">Search Properties<span class="lnr lnr-arrow-right"></span></button>
				</div>


			</div>
		</form>
	</div>
	<!-- Start property Area -->
	<section class="property-area section-gap relative" id="property">
		<div class="container">
			<div class="row d-flex justify-content-center">
				<div class="col-md-10 header-text">
					<h1>PG Accomodation in Various States or Cities</h1>
					<p>
						Who are in extremely love with eco friendly system.
					</p>
				</div>
			</div>
			<div class="container text-center">
				<div class="row">
					<?php


					$ret = mysqli_query($con, "select * from tblpg order by rand() limit 6");
					$cnt = 1;
					while ($row = mysqli_fetch_array($ret)) {

						?>
						<div class="col-4 mt-3">
							<a href="pg-details.php?pid=<?php echo $row['ID']; ?>">
								<div class="clikable">
									<div class="single-property">
										<div class="images">
											<img class="img-fluid mx-auto d-block"
												src="owner/roomimages/<?php echo $row['Image']; ?>" width="400" height="180"
												alt="">
											<span>For PG</span>
										</div>

										<div class="desc">
											<div class="top d-flex justify-content-between">
												<h4><a href="pg-details.php?pid=<?php echo $row['ID']; ?>">PG Name:
														<?php echo $row['PGTitle']; ?>
													</a></h4>

											</div>


											<div class="middle">
												<div class="d-flex justify-content-start">
													<p>AC: <span class="gr">
															<?php echo $row['AC']; ?>
														</span></p>
													<p>Balcony: <span class="rd">
															<?php echo $row['Balcony']; ?>
														</span></p>
													<p>Laundry: <span class="rd">
															<?php echo $row['Furnished']; ?>
														</span></p>
												</div>
												<div class="d-flex justify-content-start">
													<p>Parking: <span class="gr">
															<?php echo $row['Parking']; ?>
														</span></p>
													<p>Price: <span class="rd">
															<?php echo $row['RPmonth']; ?> P/M
														</span></p>
													<p>Type: <span class="rd">
															<?php echo $row['Type']; ?>
														</span></p>
												</div>
											</div>
											<div class="bottom d-flex justify-content-start">
												<p><span class="lnr lnr-heart"></span>
													<?php echo $row['StateName']; ?>
												</p>
												<p><span class="lnr lnr-bubble"></span>
													<?php echo $row['CityName']; ?>
												</p>

												<i class="fa fa-star rating-color"></i>
												<i class="fa fa-star rating-color"></i>
												<i class="fa fa-star rating-color"></i>
												<i class="fa fa-star rating-color"></i>
												<i class="fa fa-star"></i>

											</div>
											<div class="mt-1 d-flex justify-content-between align-items-center">


											</div>
										</div>
									</div>
								</div>
							</a>
						</div>
					<?php } ?>
				</div>
			</div>
	</section>

	<script>
		document.getElementById("clickableDiv").onclick = function () {
			window.location = "pg-details.php?pid=<?php echo $row['ID']; ?>";
		};
	</script>

	<script>
		$(document).ready(function () {
			// Interval between slides (in milliseconds)
			var slideInterval = 3000; // 5 seconds

			// Function to handle the sliding
			function slide() {
				var currentSlide = $('.carousel-item.active');
				var nextSlide = currentSlide.next('.carousel-item');

				if (nextSlide.length === 0) {
					nextSlide = $('.carousel-item').first(); // Start from the beginning if no next slide
				}

				// Switch active classes to perform sliding effect
				currentSlide.removeClass('active');
				nextSlide.addClass('active');
			}

			// Start sliding at the specified interval
			var slideTimer = setInterval(slide, slideInterval);

			// Pause sliding when mouse enters the carousel
			$('.carousel').mouseenter(function () {
				clearInterval(slideTimer);
			});

			// Resume sliding when mouse leaves the carousel
			$('.carousel').mouseleave(function () {
				slideTimer = setInterval(slide, slideInterval);
			});
		});
	</script>

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
