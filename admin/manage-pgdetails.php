<?php
require_once __DIR__ . '/../includes/security.php';
$adminid = require_admin('login.php');
include('includes/dbconnection.php');

if (isset($_POST['toggle_pg'])) {
    csrf_check();
    $pgid = (int) ($_POST['pgid'] ?? 0);
    $active = (int) ($_POST['active'] ?? 0) === 1 ? 1 : 0;
    $stmt = mysqli_prepare($con, "UPDATE tblpg SET IsActive = ? WHERE ID = ?");
    mysqli_stmt_bind_param($stmt, 'ii', $active, $pgid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    redirect_to('manage-pgdetails.php');
}
?>


<!DOCTYPE html>
<head>
<title>UrbanNest || View PG </title>
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
    <div class="panel-heading">
     Paying Guest Details
    </div>
    <div>
      <table class="table" ui-jq="footable" ui-options='{
        "paging": {
          "enabled": true
        },
        "filtering": {
          "enabled": true
        },
        "sorting": {
          "enabled": true
        }}'>
        <thead>
          <tr>
            <th data-breakpoints="xs">S.NO</th>
            <th>PG Name</th>
            <th>State Name</th>
            <th>City Name</th>
            <th>Registration Date</th>
            <th>Status</th>
            <th data-breakpoints="xs">Action</th>
           
           
          </tr>
        </thead>
        <?php
$ret=mysqli_query($con,"select * from  tblpg");
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {

?>
        <tbody>
          <tr data-expanded="true">
            <td><?php echo $cnt;?></td>
              <td><?php echo h($row['PGTitle']); ?></td>
                  <td><?php echo h($row['StateName']); ?></td>
                  <td><?php echo h($row['CityName']); ?></td>
                  <td><?php echo h($row['RegDate']); ?></td>
                  <td><?php echo (int) $row['IsActive'] === 1 ? 'Active' : 'Pending / Inactive'; ?></td>
                  <td><a href="view-pgdetails.php?editid=<?php echo h($row['ID']); ?>" class="btn btn-primary">View Details</a>
                      <form method="post" action="" style="display:inline">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="pgid" value="<?php echo (int) $row['ID']; ?>">
                        <?php if ((int) $row['IsActive'] === 1) { ?>
                          <input type="hidden" name="active" value="0">
                          <button type="submit" name="toggle_pg" class="btn btn-danger" onclick="return confirm('Hide this PG from the website?');">Deactivate</button>
                        <?php } else { ?>
                          <input type="hidden" name="active" value="1">
                          <button type="submit" name="toggle_pg" class="btn btn-success">Approve</button>
                        <?php } ?>
                      </form>
                  </td>
                </tr>
                <?php 
$cnt=$cnt+1;
}?>
 </tbody>
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
