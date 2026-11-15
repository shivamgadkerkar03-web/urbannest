<?php
session_start();
error_reporting(0);
include ('includes/dbconnection.php');
if (strlen($_SESSION['pgasuid'] == 0)) {
	header('location:logout.php');
} else {

	?>
	<!DOCTYPE html>
	<html lang="zxx" class="no-js">

	<head>
		<!-- Mobile Specific Meta -->
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Favicon-->
		<link rel="shortcut icon" href="img/fav.png">
		<!-- Author Meta -->
		<meta name="author" content="CodePixar">
		<!-- Meta Description -->
		<meta name="description" content="">
		<!-- Meta Description -->
		<meta name="keywords" content="">
		<!-- meta character set -->
		<meta charset="UTF-8">
		<!-- Site Title -->
		<title>UrbanNest || PG Response</title>

		<link href="https://fonts.googleapis.com/css?family=Poppins:100,200,400,300,500,600,700" rel="stylesheet">
		<!--
			CSS
			============================================= -->
		<link rel="stylesheet" href="css/linearicons.css">=
		<link rel="stylesheet" href="css/font-awesome.min.css">
		<link rel="stylesheet" href="css/nice-select.css">
		<link rel="stylesheet" href="css/ion.rangeSlider.css" />
		<link rel="stylesheet" href="css/ion.rangeSlider.skinFlat.css" />
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/owl.carousel.css">
		<link rel="stylesheet" href="css/main.css">
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
							<span class="lnr lnr-arrow-right"></span> <a href="user-profile.php">
								Response of PG Owner</a>
						</p>
						<h1 class="text-white">
							My Bookings
						</h1>

					</div>
				</div>
			</div>
		</section>
		<!-- End banner Area -->

		<!-- Start contact-page Area -->

		<section class="contact-page-area section-gap">
			<div class="container">
				<p style="text-align: center;color: red;font-size: 30px"><strong>My Roomie</strong></p>
				<div class="row mt-20">

					<p style="font-size:16px; color:red" align="center">
						<?php if ($msg) {
							echo $msg;
						} ?>
					</p>


					<div class="col-lg-12">
						<form class="row contact_form" action="" method="post" id="">
							<div class="col-md-12">



								<div class="form-group">

									<table width="100%" border="1" style="text-align: center; font-size:18px;"
										cellpadding="10" id="mainTable">
										<tr>
											<th>Photo</th>
											<th>PG Name</th>
											<th>Location</th>
											<th>Contact</th>
											<th>Prefered Gender</th>
											<th>Room Descriptions</th>
											<th>Action</th>
										</tr>
										<?php

										session_start();
										$cno = $_SESSION['pgasuid'];
										$ret = mysqli_query($con, "select * from adRoomie where userId='$cno'");
										while ($row = mysqli_fetch_array($ret)) {
											$setStatus = $row["hide"] == 1 ? 0 : 1;
											$text = $row["hide"] == 1 ? "unhide" : "hide";
											echo '<tr>
														<td><img class="imgCard" src="img/' . $row["image"] . '"></td>
														<td>' . $row["choosePG"] . '</td>
														<td>' . $row["location"] . '</td>
														<td>' . $row["contactNo"] . '</td>
														<td>' . $row["Gender"] . '</td>
														<td>' . $row["roomDetails"] . '</td>
														<td>
															<button action="hide" type="button" class="btn btn-primary" id="' . $row["roomieId"] . '" set="' . $setStatus . '">' . $text . '</button>
															<button action="edit"  image="' . $row["image"] . '" pgName="' . $row["choosePG"] . '" cno="' . $row["contactNo"] . '"  location="' . $row["location"] . '" Gender="' . $row["Gender"] . '" hobbies="' . $row["hobbies"] . '"  roomDetails="' . $row["roomDetails"] . '"  addiction="' . $row["addiction"] . '"  knowledgeAboutAddiction="' . $row["knowledgeAboutAddiction"] . '" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#exampleModal">Edit</button>
															<button action="del" pgName="' . $row["choosePG"] . '" cno="' . $row["contactNo"] . '" location="' . $row["location"] . '" Gender="' . $row["Gender"] . '" hobbies="' . $row["hobbies"] . '"  roomDetails="' . $row["roomDetails"] . '"  addiction="' . $row["addiction"] . '"  knowledgeAboutAddiction="' . $row["knowledgeAboutAddiction"] . '" class="btn btn-danger">Delete</Button>
														</td>
													</tr>';
										}
										?>

									</table>
									<script>
										document.addEventListener("DOMContentLoaded", function (event) {
											let ContactEditNumber;
											document.getElementById("confirmDeleteButton").addEventListener("click", (event) => {
												$("#confirmationModal").modal("hide");
												console.log("Deleted");
											});

											document.getElementById("mainTable").addEventListener("click", (event) => {
												event.preventDefault();
												if (event.target.getAttribute("action") == "edit") {
													ContactEditNumber = event.target.getAttribute("pgname");

													document.getElementById("roomPhoto").setAttribute("src", `img/${event.target.getAttribute('image')}`);
													// document.getElementById("pgName").value = event.target.getAttribute("pgname");
													// document.getElementById("cno").value = event.target.getAttribute("cno");
													// document.getElementById("loc").value = event.target.getAttribute("location");
													document.getElementById("gen").value = event.target.getAttribute("Gender");
													//document.getElementById("hob").value = event.target.getAttribute("hobbies");
													document.getElementById("det").value = event.target.getAttribute("roomdetails");
													//document.getElementById("adct").value = event.target.getAttribute("addiction");
													//document.getElementById("abadct").value = event.target.getAttribute("knowledgeAboutAddiction");
												} else if (event.target.getAttribute("action") == "del") {

													$("#confirmationModal").modal("show");

													$("#confirmDeleteButton").click(function () {
														$("#confirmationModal").modal("hide");
														console.log("Deleted");
														function sendData(action, data) {
															var url = "edDel.php";
															var jsonData = {
																action: action,
																data: data
															};
															var options = {
																method: 'POST',
																headers: {
																	'Content-Type': 'application/json'
																},
																body: JSON.stringify(jsonData)
															};
															fetch(url, options)
																.then(response => {
																	if (!response.ok) {
																		throw new Error('Network response was not ok');
																	}
																	return response.text();
																})
																.then(data => {
																	console.log(data);
																	location.reload();
																})
																.catch(error => {
																	console.error('There was a problem with the fetch operation:', error);
																});
														}

														var deleteData = {
															// choosePG: event.target.getAttribute("pgname"),
															// roomDetails: event.target.getAttribute("roomdetails"),
															// cno: event.target.getAttribute("contactNo"),
															// hobbies: event.target.getAttribute("hobbies"),
															location: event.target.getAttribute("location"),
															// addiction: event.target.getAttribute("addiction"),
															Gender: event.target.getAttribute("Gender"),
															// knowledgeAboutAddiction: event.target.getAttribute("knowledgeAboutAddiction")
														};
														sendData("delete", deleteData);
													});
												} else if (event.target.getAttribute("action") == "hide") {
													console.log(event.target.id);

													data = event.target.getAttribute("set");
													rid = event.target.id;

													var url = "edDel.php";
													var jsonData = {
														action: "hide",
														data: data,
														id: rid
													};
													var options = {
														method: 'POST',
														headers: {
															'Content-Type': 'application/json'
														},
														body: JSON.stringify(jsonData)
													};

													fetch(url, options)
														.then(response => {
															if (!response.ok) {
																throw new Error('Network response was not ok');
															}
															return response.text();
														})
														.then(data => {
															console.log("hidden");
															location.reload();
														})
														.catch(error => {
															console.error('There was a problem with the fetch operation:', error);
														});
												}
											})

											document.getElementById("editor").addEventListener('click', () => {
												const phpSessionVariableId = "<?php echo isset($_SESSION['pgasuid']) ? $_SESSION['pgasuid'] : ''; ?>";

												function updateUserDetails(userId, cnoo, newData) {
													const requestData = {
														action: 'edit',
														userId: userId,
														name: cnoo, // Changed from 'name' to 'cnoo'
														newData: newData
													};

													fetch('edDel.php', {
														method: 'POST',
														body: JSON.stringify(requestData)
													})
														.then(response => response.text())
														.then(result => {
															console.log(result);
															location.reload();
														})
														.catch(error => {
															console.error('Error:', error);
														});
												}

												const userId = phpSessionVariableId;

												const fileInput = document.getElementById('imageNew');
												const file = fileInput.files[0];
												readImageFile(file, function (imageData) {
													let imageObject = null;
													if (file) {
														imageObject = {
															name: file.name,
															type: file.type,
															size: file.size,
															data: imageData
														};
														console.log(imageObject);
													}

													// Extract other data for editing
													let newData = {
														// choosePG: document.getElementById("pgName").value,
														roomDetails: document.getElementById("det").value,
														// cno: document.getElementById("cno").value,
														// hobbies: document.getElementById("hob").value,
														// location: document.getElementById("loc").value,
														// addiction: document.getElementById("adct").value,
														Gender: document.getElementById("gen").value,
														// knowledgeAboutAddiction: document.getElementById("abadct").value,
														imageObj: imageObject // Include the image object if selected
													};

													// Send the edit data to the server
													console.log(userId, ContactEditNumber, newData);

													updateUserDetails(userId, ContactEditNumber, newData);
												});

												function readImageFile(file, callback) {
													if (file) {
														const reader = new FileReader();
														reader.onload = function () {
															const imageData = reader.result;
															callback(imageData);
														};
														reader.readAsDataURL(file);
													} else {
														// If no image is selected, call the callback function with null
														callback(null);
													}
												}
											});


										});
									</script>

									<!-- model for edit -->
									<div class="modal fade" id="exampleModal" tabindex="-1"
										aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-lg">
											<div class="modal-content">
												<div class="modal-header">
													<h1 class="modal-title fs-5" id="exampleModalLabel">Edit Roomie</h1>
													<button type="button" class="btn-close" data-bs-dismiss="modal"
														aria-label="Close"></button>
												</div>
												<div class="modal-body">
													<div class="d-flex">
														<div style="width: 210px;">
															<img src="" id="roomPhoto" class="imgCard">
															<input type="file" class="mt-2" style="width: 200px;"
																id="imageNew">
														</div>
														<div class="container-fluid">
															<!-- <div class="input-group mb-3">
																<label class="input-group-text equal-width"
																	for="inputGroupSelect01">PG Name</label>
																<select class="form-select" id="pgName">
																	<?php
																	//$query = mysqli_query($con, "select * from tblpg");
																	//while ($row = mysqli_fetch_array($query)) {
																		?>
																		<option value="<?php //echo $row['PGTitle']; ?>">
																			<?php //echo $row['PGTitle']; ?>
																		</option>
																	<?php //} ?>
																</select>
															</div>
															<div class="input-group mb-3">
																<span class="input-group-text equal-width"
																	id="basic-addon1">Contact</span>
																<input type="text" class="form-control" placeholder=""
																	aria-label="Username" aria-describedby="basic-addon1"
																	id="cno">
															</div>
															<div class="input-group mb-3">
																<span class="input-group-text equal-width"
																	id="basic-addon1">Location</span>
																<input type="text" class="form-control" placeholder=""
																	aria-label="Username" aria-describedby="basic-addon1"
																	id="loc">
															</div> -->
															<div class="input-group mb-3">
																<label class="input-group-text equal-width"
																	for="inputGroupSelect01">Prefered Gender</label>
																<select class="form-select" id="gen">
																	<option value="Male">Male</option>
																	<option value="Female">Female</option>
																</select>
															</div>
															<!-- <div class="input-group mb-3">
																<span class="input-group-text equal-width"
																	id="basic-addon1">Hobbies</span>
																<input type="text" class="form-control" placeholder=""
																	aria-label="Username" aria-describedby="basic-addon1"
																	id="hob">
															</div> -->
															<div class="input-group my-3">
																<span class="input-group-text equal-width" id="basic-addon1">Room
																	Details</span>
																<textarea class="form-control" aria-label="With textarea"
																	id="det"></textarea>
															</div>
														</div>
													</div>
													<!-- <div class="input-group mb-3">
														<label class="input-group-text equal-width"
															for="inputGroupSelect01">Addiction ?</label>
														<select class="form-select" id="adct">
															<option value="Yes">Yes</option>
															<option value="No">No</option>
														</select>
													</div>
													<div class="input-group mb-3">
														<span class="input-group-text equal-width" id="basic-addon1">About
															Addiction</span>
														<textarea class="form-control" aria-label="With textarea"
															id="abadct"></textarea>
													</div> -->
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary"
														data-bs-dismiss="modal">Close</button>
													<button type="button" class="btn btn-primary"
														id="editor">Confirm</button>
												</div>
											</div>
										</div>
									</div>
								</div>


						</form>
					</div>
				</div>
				<div class="modal" id="confirmationModal">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Confirmation</h5>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>
							<div class="modal-body">
								Are you sure you want to delete?
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
								<button id="confirmDeleteButton" type="button" class="btn btn-danger">Yes</button>
							</div>
						</div>
					</div>
				</div>

			</div>
		</section>
		<!-- End contact-page Area -->
		<style>
			.equal-width {
				flex: 0.5;
				min-width: 80px;
				/* Adjust as needed */
			}

			.input-group {
				display: flex;
			}

			.form-control {
				border: 1px solid #dee2e6 !important;
				font-size: inherit !important;
				color: inherit !important;
			}

			.imgCard {
				height: 200px;
				width: 200px;
				overflow: hidden;
				object-fit: cover;
				border-radius: 1rem;
			}
		</style>
		<!-- start footer Area -->
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
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
			integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
			crossorigin="anonymous"></script>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
			integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	</body>

	</html>
<?php } ?>