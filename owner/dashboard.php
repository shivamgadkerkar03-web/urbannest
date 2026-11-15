<?php
require_once __DIR__ . '/../includes/security.php';
include('includes/dbconnection.php');
$ownerid = require_owner('login.php');
?>
<!DOCTYPE html>
<head>
<title>UrbanNest | dashboard </title>
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link rel="stylesheet" href="css/bootstrap.min.css" >
<link href="css/style.css" rel='stylesheet' type='text/css' />
<link href="css/style-responsive.css" rel="stylesheet"/>
<link href='//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/font.css" type="text/css"/>
<link href="css/font-awesome.css" rel="stylesheet"> 
<script src="js/jquery2.0.3.min.js"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../css/panel.css">
</head>
<body>
<section id="container">


<?php include_once('includes/header.php');?>

<?php include_once('includes/sidebar.php');?>
<section id="main-content">
	<section class="wrapper">
		<div class="market-updates">
			<a href="manage-pgdetails.php" style="color:#fff;">
			<div class="col-md-6 market-update-gd">
				<div class="market-update-block clr-block-2">
					<div class="col-md-4 market-update-right">
						<i class="fa fa-home fa-3x"> </i>
					</div>
					
					<?php 
$oid=(int) $ownerid;
					$query=mysqli_query($con,"Select * from  tblpg where OwnerID=$oid");
$pgcounts=mysqli_num_rows($query);
?>
					 <div class="col-md-8 market-update-left">
					 <h4>Total Listed PG</h4>
					<h3><?php echo $pgcounts;?></h3>
					
				  </div>
				  <div class="clearfix"> </div>
				</div>
			</div>
		</a>
			<?php 

					$query=mysqli_query($con,"Select * from  tblbookpg join tblpg on  tblpg.id=tblbookpg.Pgid where tblpg.OwnerID=$oid");
$totalrequests=mysqli_num_rows($query);
?>
<a href="all-bookings.php" style="color:#fff;">
			<div class="col-md-6 market-update-gd">
				<div class="market-update-block clr-block-4">
					<div class="col-md-4 market-update-right">
						<i class="fa fa-users" ></i>
					</div>
					<div class="col-md-8 market-update-left">
					<h4>Total Booking</h4>
						<h3><?php echo $totalrequests;?></h3>
					</div>
				  <div class="clearfix"> </div>
				</div>
			</div>
		</a>
		

		   <div class="clearfix"> </div>
		</div>	
		<div class="market-updates">
       			<?php 
$query=mysqli_query($con,"Select * from  tblbookpg join tblpg on  tblpg.id=tblbookpg.Pgid where tblpg.OwnerID=$oid and tblbookpg.Status is null");
$newbooking=mysqli_num_rows($query);
?>
<a href="new-bookingrequest.php" style="color:#fff;">
            <div class="col-md-4 market-update-gd">
                <div class="market-update-block clr-block-3">
                    <div class="col-md-4 market-update-right">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="col-md-8 market-update-left">
                        <h4>New Bookings </h4>
                        <h3><?php echo $newbooking;?></h3>
                      
                    </div>
                  <div class="clearfix"> </div>
                </div>
            </div>
        </a>
                   			<?php 
$query=mysqli_query($con,"Select * from  tblbookpg join tblpg on  tblpg.id=tblbookpg.Pgid where tblpg.OwnerID=$oid and tblbookpg.Status='Confirmed'");
$confirmedbooking=mysqli_num_rows($query);
?>

<a href="confirmed-pgbooking.php" style="color:#fff;">
            <div class="col-md-4 market-update-gd">
                <div class="market-update-block clr-block-1">
                    <div class="col-md-4 market-update-right">
                        <i class="fa fa-users" aria-hidden="true"></i>
                    </div>
                    <div class="col-md-8 market-update-left">
                        <h4>Confirmed booking</h4>
                        <h3><?php echo $confirmedbooking;?></h3>
                    </div>
                  <div class="clearfix"> </div>
                </div>
            </div>
        </a>



<a href="cancelled-pgbooking.php" style="color:#fff;">
	<div class="col-md-4 market-update-gd">
				<div class="market-update-block clr-block-2">
					<div class="col-md-4 market-update-right">
						<i class="fa fa-users"> </i>
					</div>
					
					<?php 
$query=mysqli_query($con,"Select * from  tblbookpg join tblpg on  tblpg.id=tblbookpg.Pgid where tblpg.OwnerID=$oid and tblbookpg.Status='Cancelled'");
$notconfirmedbooking=mysqli_num_rows($query);
?>
					 <div class="col-md-8 market-update-left">
					 <h4>Not Confirmed Booking (Cancelled)</h4>
					<h3><?php echo $notconfirmedbooking;?></h3>
					
				  </div>
				  <div class="clearfix"> </div>
				</div>
			</div>
		</a> 



</div>
	
		

</section>
	<?php include_once('includes/footer.php');?>	  
</section>
</section>
<script src="js/bootstrap.js"></script>
<script src="js/jquery.dcjqaccordion.2.7.js"></script>
<script src="js/scripts.js"></script>
<script src="js/jquery.slimscroll.js"></script>
<script src="js/jquery.nicescroll.js"></script>
<script src="js/jquery.scrollTo.js"></script>
</body>
</html>
