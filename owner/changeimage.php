<?php
require_once __DIR__ . '/../includes/security.php';
include('includes/dbconnection.php');
$ownerid = require_owner('login.php');


$cid = (int) ($_GET['editid'] ?? 0);
$msg = '';

$own = mysqli_prepare($con, "SELECT ID FROM tblpg WHERE ID = ? AND OwnerID = ?");
mysqli_stmt_bind_param($own, 'ii', $cid, $ownerid);
mysqli_stmt_execute($own);
$ownsPg = (bool) mysqli_fetch_assoc(mysqli_stmt_get_result($own));
mysqli_stmt_close($own);
if (!$ownsPg) {
    redirect_to('manage-pgdetails.php');
}

if (isset($_POST['submit'])) {
    csrf_check();
    $imageDir = __DIR__ . '/roomimages';
    $upload = store_uploaded_image($_FILES['roomimages'] ?? [], $imageDir);
    protect_upload_dir($imageDir);
    if (!$upload['ok']) {
        $msg = $upload['error'];
    } else {
        $roompic = $upload['name'];
        $stmt = mysqli_prepare($con, "UPDATE tblpg SET Image = ? WHERE ID = ? AND OwnerID = ?");
        mysqli_stmt_bind_param($stmt, 'sii', $roompic, $cid, $ownerid);
        if (mysqli_stmt_execute($stmt)) {
            $msg = "PG Image has been Updated.";
        } else {
            @unlink($imageDir . '/' . $roompic);
            $msg = "Something Went Wrong. Please try again";
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<head>
<title>UrbanNest | Image </title>

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
<!--header end-->
<!--sidebar start-->
<?php include_once('includes/sidebar.php');?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
    <div class="form-w3layouts">
    
        <div class="row">
        <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                Update Image
            </header>
            <div class="panel-body">
                <p style="font-size:16px; color:red" align="left"> <?php echo h($msg ?? ''); ?> </p>

  <?php
$ret=mysqli_query($con,"select * from tblpg where ID=" . $cid . " and OwnerID=" . (int) $ownerid);
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {

?>
                <form class="form-horizontal bucket-form" method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                                    
                  
                    <div class="form-group">
                        <label class=" col-sm-3 control-label">Pictures</label>
                        <div class="col-lg-6">
                             <img src="roomimages/<?php echo h(rawurlencode(basename((string) $row['Image']))); ?>" width="200" height="150" alt="">
                        </div>
                    </div>
                 <div class="form-group">
                        <label class=" col-sm-3 control-label">Pictures</label>
                        <div class="col-lg-6">
                             <input type="file" class="form-control" name="roomimages" id="roomimages" required="true" accept="image/jpeg,image/png,image/webp,image/gif">
                        </div>
                    </div>

                    <hr />
<div class="form-group">
                                    <div class="col-lg-offset-3 col-lg-6">
                                        <button class="btn btn-primary" type="submit" name="submit">Update</button>
                                    </div>
                                </div>

                </form>
            </div>
        </section>



     <!-- page end-->
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
<script src="js/jquery.scrollTo.js"></script>
</body>
</html>
<?php  } ?>
