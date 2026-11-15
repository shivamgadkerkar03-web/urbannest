<?php  
require_once __DIR__ . '/../includes/security.php';
$adminid = require_admin('login.php');
include('includes/dbconnection.php');




?>


<!DOCTYPE html>
<head>
<title>UrbanNest || Booking Requests</title>

<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- bootstrap-css -->
<link rel="stylesheet" href="css/bootstrap.min.css" >
<!-- //bootstrap-css -->
<!-- Custom CSS -->
<link href="css/style.css" rel='stylesheet' type='text/css' />
<link href="css/style-responsive.css" rel="stylesheet"/>
<!-- font CSS -->
<link href='//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
<!-- font-awesome icons -->
<link rel="stylesheet" href="css/font.css" type="text/css"/>
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
<?php include_once('includes/header.php');?>
<!--header end-->
<!--sidebar start-->
<?php include_once('includes/sidebar.php');?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<div class="table-agile-info">
 <div class="panel panel-default">
    <p style="font-size:16px; color:red" align="center"> <?php echo h($msg ?? ''); ?> </p>
    <div class="panel-heading">
     Paying Guest Details
    </div>
    <div>
<?php
        
        $vid = (int) ($_GET['viewid'] ?? 0);
$ret=mysqli_query($con,"select tblbookpg.BookingNumber, tblbookpg.CheckinDate,tblbookpg.UserMsg,tblbookpg.Remark, tblbookpg.Status,tblbookpg.RemDate,tblpg.PGTitle,tblpg.StateName,tblpg.CityName,tbluser.FullName,tbluser.Email,tbluser.MobileNumber from tbluser left join tblbookpg on tblbookpg.Userid=tbluser.ID left join tblpg on tblbookpg.Pgid=tblpg.ID where tblbookpg.ID=$vid");

while ($row=mysqli_fetch_array($ret)) {

?>

      <table border="1" class="table table-bordered mg-b-0">
        
  <tr>
    <th>Booking Number </th>
    <td><?php echo h($row['BookingNumber']); ?></td>
  </tr>
   <tr>
    <th>Checkin Date</th>
    <td><?php echo h($row['CheckinDate']); ?></td>
  </tr>
<tr>
    <th>Message</th>
    <td><?php echo h($row['UserMsg']); ?></td>
  </tr>

<tr>
    <th>Name Of PG</th>
    <td><?php echo h($row['PGTitle']); ?></td>
  </tr>

  <tr>
    <th>State</th>
    <td><?php echo h($row['StateName']); ?></td>
  </tr>



<tr>
    <th>City Name</th>
    <td><?php echo h($row['CityName']); ?></td>
  </tr>


<tr>
<th>Full Name</th>
<td>
<?php echo h($row['FullName']); ?>
  </td>
  </tr>
  <tr>
<th>Email</th>
<td>
<?php echo h($row['Email']); ?>
  </td>
  </tr>
  <tr>
<th>Mobile Number</th>
<td>
<?php echo h($row['MobileNumber']); ?>
  </td>
  </tr>
<tr>
    <th>Status</th>
    <td> <?php  
if($row['Status']=="")
{
  echo "Not Confirmed Yet";
}
else
{
  echo h($row['Status']);
}

     ;?></td>
  </tr>
  </table>
  <table border="1" class="table table-bordered mg-b-0">
<?php if($row['Status']==""){ ?>

<?php } else { ?>

<tr>
    <th>Remark</th>
    <td><?php echo h($row['Remark']); ?></td>
  </tr>


<tr>
<th>Remark date</th>
<td>
<?php echo h($row['RemDate']); ?>
  </td>
  </tr>

 

<?php } ?>

</table>
            
            
          
    </div>
  </div>
</div>
</section>
 <!-- footer -->
		 <?php include_once('includes/footer.php');?>  
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
<?php }  ?>
