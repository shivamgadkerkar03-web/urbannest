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

	<link rel="stylesheet" href="css/linearicons.css">=
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/nice-select.css">
	<link rel="stylesheet" href="css/ion.rangeSlider.css" />
	<link rel="stylesheet" href="css/ion.rangeSlider.skinFlat.css" />
	<!-- <link rel="stylesheet" href="css/bootstrap.css"> -->
	<link rel="stylesheet" href="css/owl.carousel.css">
	<link rel="stylesheet" href="css/main.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
		crossorigin="anonymous"></script>
	<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
	<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>

<body>


	<?php include_once ('includes/header.php'); ?>



	<!-- <section class="home-banner-area relative" id="home">
		<div class="overlay overlay-bg"></div>
		<div class="container">
			<div class="row fullscreen align-items-end justify-content-center">
				<div class="banner-content col-lg-12 col-md-12">

				</div>
			</div>
		</div>
	</section> -->

	<div style="margin-top: 5.5rem; position: relative; padding: 8rem 6rem; background: url('roomie.jpg');">
		<div class="overlay"
			style="position: absolute; left: 0; top: 0; width: 80%; height: 100%; background: linear-gradient(to right, #000, #0000);">
		</div>
		<div class="content" style="width: 50%; margin: 0; padding:1rem 0; color: white;">
			<h1 style="color: white; font-size: 3rem;">Elevate Your Space with <span class="gradient-text"
					style="background: linear-gradie -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Exceptional
					Furniture</span></h1>

			<p style="color: #aaa; padding: 0.5rem 0;">Discover timeless pieces that transform your space into a
				sanctuary of style and comfort. Whether you're looking for modern elegance or classic charm, we have
				something for every taste and budget.</p>
		</div>
	</div>


	<!-- <div class="search-field">
		<form class="search-form" method="post" action="search-result.php">
			<div class="row">
				<div class="col-lg-12 d-flex align-items-center justify-content-center toggle-wrap">
					<div class="row">
						<div class="col">
							<h4 class="search-title">Search Properties PG</h4>
						</div>

					</div>
				</div>
				<div class="d-flex">
					<div>
						<select class="container-fluid" name="choosePG" id="choosePG"
							required="true">
							<option value="">Choose PG</option>
							<?php
							// $query = mysqli_query($con, "select * from tblpg");
							// while ($row = mysqli_fetch_array($query)) {
								?>
								<option value="<?php //echo $row['PGTitle']; ?>">
									<?php //echo $row['PGTitle']; ?>
								</option>
							<?php //} ?>
						</select>
					</div>
						<button class="primary-btn m-0 mx-3">Search Roomie<span class="lnr lnr-arrow-right"></span></button>
				</div>
				<div class="col-lg-6 col-md-6 col-xs-6">
					<input type="text" name="pgname" class="app-select form-control" required>
				</div>


			</div>
		</form>
	</div> -->

	<div class="mx-5">
		<div class="row">
			<div class="col-lg-12 d-flex align-items-center justify-content-center toggle-wrap">
				<div class="row">
					<div class="col">
						<h4 class="search-title">Search Properties PG</h4>
					</div>

				</div>
			</div>
			<div class="d-flex">
				<select class="container-fluid" id="choosePGNew" required="true">
					<option value="none" class="container-fluid">Choose PG</option>
					<?php
					$query = mysqli_query($con, "select * from tblpg");
					while ($row = mysqli_fetch_array($query)) {
						?>
						<option value="<?php echo $row['PGTitle'] . ' - ' . $row['CityName']; ?>">
							<?php echo $row['PGTitle'] . ' - ' . $row['CityName']; ?>
						</option>
					<?php } ?>
				</select>
				<button id="SearchPg" class="primary-btn m-0 mx-3">Search<span
						class="lnr lnr-arrow-right"></span></button>
				<!-- <button onclick="doIt()">Yes <span class="lnr lnr-arrow-right"></span></button> -->
			</div>
		</div>
	</div>


	<!-- Start property Area -->
	<section class="property-area section-gap relative" id="property">
		<div class="container">
			<div class="row d-flex justify-content-center">
				<div class="col-md-10 header-text">
					<h1>PG Roomate</h1>
					<p>
						Find the one who fits your lifestyle
					</p>
				</div>
			</div>

			<style>
				.imageEdit {
					width: 300px;
					height: 300px;
					object-fit: cover;
				}

				.single-property {
					height: 100%;
				}
			</style>

			<div class="row" id="allPGs">

				<?php
				function addUi($fil)
				{
					require 'dbconnection.php';
					if (isset($_GET['arg'])) {
						$ret = mysqli_query($con, "select * from adRoomie where choosePG='" . $_GET['arg'] . "'");
					} else {
						$ret = mysqli_query($con, "select * from adRoomie");
					}
					$cnt = 1;
					while ($row = mysqli_fetch_array($ret)) {
						if (!$row['hide']) {
							?>
							<div class="col-lg-4 mt-3">
								<div class="single-property">
									<div class="images">
										<img class="mx-auto d-block imageEdit" src="img/<?php echo $row["image"]; ?>"
											title="<?php echo $row['image']; ?>" style="object-fit: cover;">
										<span>ForRoomie</span>
									</div>

									<div class="desc">
									<!-- href="pg-details.php?pid=<?php //echo $row['ID']; ?>" -->
										<div class="top d-flex justify-content-between">
											<h4><a>PG Name:
													<?php echo $row['choosePG']; ?>
												</a></h4>

										</div>


										<div class="middle">
											<div class="d-flex justify-content-start">
												<p>Name <span class="gr">
														<?php echo $row['name']; ?>
													</span></p>
												<p>Prefered Gender: <span class="rd">
														<?php echo $row['Gender']; ?>
													</span></p>
												<!-- <p>Addiction: <span class="rd">
														<?php //echo $row['addiction']; ?>
													</span></p> -->
											</div>
											<div class="d-flex justify-content-start">
												<p>Contactno: <span class="gr">
														<?php echo $row['contactNo']; ?>
													</span></p>
												<!-- <p>Hoobies: <span class="rd">
														<?php //echo $row['hobbies']; ?>
													</span></p> -->
													<br>
												<p>Location: <span class="rd">
														<?php echo $row['location']; ?>
													</span></p>
											</div>
											<!-- <div class="d-flex justify-content-start">
												<p>Addictioninfo: <span class="rd">
														<?php //echo $row['knowledgeAboutAddiction']; ?>
													</span></p>
											</div> -->
										</div>
										<div class="bottom d-flex justify-content-start">
											<!-- <p><span class="lnr lnr-heart"></span>
												<?php //echo $row['StateName']; ?>
											</p>
											<p><span class="lnr lnr-bubble"></span>
												<?php //echo $row['CityName']; ?>
											</p> -->
										</div>
										<div class="mt-1 d-flex justify-content-between align-items-center">
										<p> <span style="font-weight: 600;">Room Detals:</span><br><span>
														<?php echo $row['roomDetails']; ?>
													</span></p>
											<?php
											// $ret2 = mysqli_query($con, "SELECT * FROM tblpg WHERE PGTitle = '".$row['choosePG']."'");
											// while ($row2 = mysqli_fetch_array($ret2)) {
											// 	echo '<button class="btn btn-warning" userId="'.$row["userId"].'" OwnerID="'.$row2["OwnerID"].'">Chat With User</button>';
											// }
											?>
											<!-- <button class="btn btn-warning" userId="<?php //echo $row["userId"]; ?>" ownerId="<?php //echo $row["userId"]; ?>">Chat With User</button> -->

										
										</div>
									</div>
								</div>
							</div>
							<?php
						}
					}
				}

				function loggedOut()
				{
					echo '<h4 class="container-fluid mt-5"><span
							class="badge text-bg-warning container-fluid mb-2 d-flex align-items-center"
							style="width: 100% !important;"><ion-icon size="large" class="mx-2"
							name="alert-circle-outline"></ion-icon>Warning : Login / Register </span></h4>';
				}


				if (!isset($_SESSION['pgasuid'])) {
					loggedOut();
				} else {
					addUi("");
				}
				?>

			</div>
	</section>

	<script>
		// Define the parameter you want to pass
		// const parameter = 'example_parameter_value';

		// function check() {
		// 	fetch('liveList.php', {
		// 		method: 'POST',
		// 		body: JSON.stringify({ parameter: parameter }) // Pass the parameter in the body
		// 	})
		// 		.then(response => response.json())
		// 		.then(data => {
		// 			// Handle the data received from the server
		// 			console.log(data);
		// 			// check();
		// 		})
		// 		.catch(error => {
		// 			// Handle any errors that occur during the fetch call
		// 			console.error('Error:', error);
		// 		});
		// }



		document.getElementById("SearchPg").addEventListener('click', () => {
			console.log("done");
			// document.getElementById("choosePGNew").value;
			var selectedValue = document.getElementById("choosePGNew").value;
			if (selectedValue == "none") {
				window.location.href = "View_details.php";
			} else {
				window.location.href = "View_details.php?arg=" + encodeURIComponent(selectedValue);
			}
		});

	</script>




	<?php include_once ('includes/footer.php'); ?>


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