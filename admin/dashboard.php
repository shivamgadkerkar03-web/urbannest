<?php
require_once __DIR__ . '/../includes/security.php';
require_admin('login.php');
include('includes/dbconnection.php');
?>
<!DOCTYPE html>

<head>
	<title>UrbanNest | dashboard </title>
	<script
		type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
	<!-- bootstrap-css -->
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<!-- //bootstrap-css -->
	<!-- Custom CSS -->
	<link href="css/style.css" rel='stylesheet' type='text/css' />
	<link href="css/style-responsive.css" rel="stylesheet" />
	<!-- font CSS -->
	<link
		href='//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic'
		rel='stylesheet' type='text/css'>
	<!-- font-awesome icons -->
	<link rel="stylesheet" href="css/font.css" type="text/css" />
	<link href="css/font-awesome.css" rel="stylesheet">
	<!-- //font-awesome icons -->
	<script src="js/jquery2.0.3.min.js"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../css/panel.css">
</head>

<body>
	<section id="container">
		<!--header start-->

		<!--header end-->
		<?php include_once ('includes/header.php'); ?>
		<!--sidebar start-->

		<!--sidebar end-->
		<?php include_once ('includes/sidebar.php'); ?>
		<!--main content start-->
		<section id="main-content">
			<section class="wrapper">
				<!-- //market-->
				<div class="market-updates">
					<div class="col-md-6 market-update-gd">
						<div class="market-update-block clr-block-2">
							<div class="col-md-4 market-update-right">
								<i class="fa fa-globe"> </i>
							</div>
							<?php $query1 = mysqli_query($con, "Select * from  tblstate");
							$statecounts = mysqli_num_rows($query1);
							?>
							<div class="col-md-8 market-update-left">

								<h4><a href="manage-state.php" style="color: white">Total State</a></h4>
								<h3>
									<?php echo $statecounts; ?>
								</h3>

							</div>
							<div class="clearfix"> </div>
						</div>
					</div>
					<div class="col-md-6 market-update-gd">
						<div class="market-update-block clr-block-1">
							<div class="col-md-4 market-update-right">
								<i class="fa fa-globe"></i>
							</div>
							<?php $query2 = mysqli_query($con, "Select * from  tblcity");
							$citycounts = mysqli_num_rows($query2);
							?>
							<div class="col-md-8 market-update-left">
								<h4><a href="manage-city.php" style="color: white">Total City</a></h4>
								<h3>
									<?php echo $citycounts; ?>
								</h3>

							</div>
							<div class="clearfix"> </div>
						</div>
					</div>

					<div class="col-md-6 market-update-gd" style="margin-top:2%">
						<div class="market-update-block clr-block-3">
							<div class="col-md-4 market-update-right">
								<i class="fa fa-user"></i>
							</div>
							<?php $query3 = mysqli_query($con, "Select * from  tblowner");
							$pgownercounts = mysqli_num_rows($query3);
							?>
							<div class="col-md-8 market-update-left">
								<h4><a href="manage-ownerdetails.php" style="color: white">Total PG Owner</a></h4>
								<h3>
									<?php echo $pgownercounts; ?>
								</h3>

							</div>
							<div class="clearfix"> </div>
						</div>
					</div>
					<div class="market-updates">
						<div class="col-md-6 market-update-gd" style="margin-top:2%">
							<div class="market-update-block clr-block-2">
								<div class="col-md-4 market-update-right">
									<i class="fa fa-home"> </i>
								</div>
								<?php $query4 = mysqli_query($con, "Select * from  tblpg");
								$pgcounts = mysqli_num_rows($query4);
								?>
								<div class="col-md-8 market-update-left">
									<h4><a href="manage-pgdetails.php" style="color: white">Total PGs</a></h4>
									<h3>
										<?php echo $pgcounts; ?>
									</h3>

								</div>
								<div class="clearfix"> </div>
							</div>
						</div>

						<div class="clearfix"> </div>
					</div>


					<div class="market-updates">
						<div class="col-md-6 market-update-gd" style="margin-top:2%">
							<div class="market-update-block clr-block-5">
								<div class="col-md-4 market-update-right">
									<i class="fa fa-user"> </i>
								</div>
								<?php $query4 = mysqli_query($con, "Select * from  tbluser");
								$regusers = mysqli_num_rows($query4);
								?>
								<div class="col-md-8 market-update-left">
									<h4><a href="reg-users.php" style="color: white">Registered Users</a></h4>
									<h3>
										<?php echo $regusers; ?>
									</h3>

								</div>
								<div class="clearfix"> </div>
							</div>
						</div>


						<div class="col-md-6 market-update-gd" style="margin-top:2%">
							<div class="market-update-block clr-block-3">
								<div class="col-md-4 market-update-right">
									<i class="fa fa-user"> </i>
								</div>
								<?php $query4 = mysqli_query($con, "Select * from  tblbookpg");
								$totlabookings = mysqli_num_rows($query4);
								?>
								<div class="col-md-8 market-update-left">
									<h4><a href="bookings.php" style="color: white">Total Bookings </a></h4>
									<h3>
										<?php echo $totlabookings; ?>
									</h3>

								</div>
								<div class="clearfix"> </div>
							</div>
						</div>



						<div class="clearfix"> </div>
					</div>



					<div class="market-updates">

						<div class="row">
							<div class="panel-body">
								<div class="col-md-12 w3ls-graph">
									<!--agileinfo-grap-->

									<!--//agileinfo-grap-->

								</div>
							</div>
						</div>
						<div class="agil-info-calendar">
							<!-- calendar -->
							<div class="col-md-6 agile-calendar">

							</div>
							<!-- //calendar -->
							<div class="col-md-6 w3agile-notifications">

							</div>
							<div class="clearfix"> </div>
						</div>
						<!-- tasks -->
						<div class="agile-last-grids">
							<div class="col-md-4 agile-last-left">

							</div>
							<div class="col-md-4 agile-last-left agile-last-middle">

							</div>
							<div class="col-md-4 agile-last-left agile-last-right">

							</div>
							<div class="clearfix"> </div>
						</div>
						<!-- //tasks -->
						<div class="agileits-w3layouts-stats">
							<div class="col-md-4 stats-info widget">

							</div>
							<div class="col-md-8 stats-info stats-last widget-shadow">

							</div>
							<div class="clearfix"> </div>
						</div>
			</section>
			<!-- footer -->
			<?php include_once ('includes/footer.php'); ?>
			<!-- / footer -->
		</section>
		<!--main content end-->
	</section>
	<script src="js/bootstrap.js"></script>
	<script src="js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="js/scripts.js"></script>
	<script src="js/jquery.slimscroll.js"></script>
	<script src="js/jquery.nicescroll.js"></script>
	<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/flot-chart/excanvas.min.js"></script><![endif]-->
	<script src="js/jquery.scrollTo.js"></script>
</body>

</html>