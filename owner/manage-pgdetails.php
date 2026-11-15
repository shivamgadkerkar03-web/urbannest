<?php  
require_once __DIR__ . '/../includes/security.php';
include('includes/dbconnection.php');
$ownerid = require_owner('login.php');


// For deletion
if (isset($_GET['delid'])) {
    $pid = (int) $_GET['delid'];
    $ok = false;
    if (csrf_valid() && $pid > 0) {
        $stmt = mysqli_prepare($con, "DELETE FROM tblpg WHERE ID = ? AND OwnerID = ?");
        mysqli_stmt_bind_param($stmt, 'ii', $pid, $ownerid);
        $ok = mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0;
        mysqli_stmt_close($stmt);
    }
    if ($ok) {
        echo "<script>alert('Data Deleted');</script>";
    } else {
        echo "<script>alert('Some thing went wrong . Please try again');</script>";
    }
    echo "<script>window.location.href='manage-pgdetails.php'</script>";
    exit;
}
?>


<!DOCTYPE html>
<head>
<title>UrbanNest || Manage PGs </title>

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
<!--header start-->
<?php include_once('includes/header.php');?>
<?php include_once('includes/sidebar.php');?>
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
$ret=mysqli_query($con,"select * from tblpg where OwnerID=" . (int) $ownerid);
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
                  <td><?php echo (int) $row['IsActive'] === 1 ? 'Active' : 'Awaiting approval'; ?></td>
                  <td><a href="edit-pgdetails.php?editid=<?php echo h($row['ID']); ?>" class="btn btn-primary">Edit Details</a>
                    <a href="manage-pgdetails.php?delid=<?php echo h($row['ID']); ?>&csrf_token=<?= h(csrf_token()) ?>" class="btn btn-danger" onClick="return confirm('Do you really want to delete?');">Delete</a>
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
