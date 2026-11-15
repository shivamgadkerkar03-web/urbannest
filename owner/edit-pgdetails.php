<?php
require_once __DIR__ . '/../includes/security.php';
include('includes/dbconnection.php');
$ownerid = require_owner('login.php');


$eid = (int) ($_GET['editid'] ?? 0);
$msg = '';

$own = mysqli_prepare($con, "SELECT ID FROM tblpg WHERE ID = ? AND OwnerID = ?");
mysqli_stmt_bind_param($own, 'ii', $eid, $ownerid);
mysqli_stmt_execute($own);
$ownsPg = (bool) mysqli_fetch_assoc(mysqli_stmt_get_result($own));
mysqli_stmt_close($own);
if (!$ownsPg) {
    redirect_to('manage-pgdetails.php');
}

if (isset($_POST['submit'])) {
    csrf_check();
    $keys = ['state', 'city', 'pgname', 'typepg', 'rpmonth', 'norooms', 'address', 'electricity', 'parking',
        'refregerator', 'furnished', 'ac', 'balcony', 'table', 'laundry', 'security', 'breakfast', 'lunch', 'dinner'];
    $f = [];
    foreach ($keys as $key) {
        $f[$key] = trim((string) ($_POST[$key] ?? ''));
    }

    $stmt = mysqli_prepare($con, "UPDATE tblpg SET StateName = ?, CityName = ?, PGTitle = ?, Type = ?, RPmonth = ?, norooms = ?, Address = ?, Electricity = ?, Parking = ?, Refregerator = ?, Furnished = ?, AC = ?, Balcony = ?, StudyTable = ?, Laundry = ?, Security = ?, MealsBreakfast = ?, MealLunch = ?, MealDinner = ? WHERE ID = ? AND OwnerID = ?");
    mysqli_stmt_bind_param(
        $stmt,
        'sssssssssssssssssssii',
        $f['state'], $f['city'], $f['pgname'], $f['typepg'], $f['rpmonth'], $f['norooms'], $f['address'],
        $f['electricity'], $f['parking'], $f['refregerator'], $f['furnished'], $f['ac'], $f['balcony'],
        $f['table'], $f['laundry'], $f['security'], $f['breakfast'], $f['lunch'], $f['dinner'],
        $eid, $ownerid
    );
    if ($f['pgname'] !== '' && mysqli_stmt_execute($stmt)) {
        $msg = "PG details has been Updated.";
    } else {
        $msg = "Something Went Wrong. Please try again";
    }
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<head>
<title>UrbanNest  | PG Updation </title>

<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link rel="stylesheet" href="css/bootstrap.min.css" >
<link href="css/style.css" rel='stylesheet' type='text/css' />
<link href="css/style-responsive.css" rel="stylesheet"/>
<link href='//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/font.css" type="text/css"/>
<link href="css/font-awesome.css" rel="stylesheet"> 

<script src="js/jquery2.0.3.min.js"></script>
<script>
function getCity(val) { 
  $.ajax({
type:"POST",
url:"get-city.php",
data:{ sateid: val },
success:function(data){
$("#city").html(data);
}});
}
 </script>


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
	<div class="form-w3layouts">
    
        <div class="row">
        <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                Update Your PG
            </header>

            <div class="panel-body">
<?php if ($msg) { ?>
<div class="alert alert-info" role="alert">
                    <strong>Message !</strong>
    <?php echo h($msg); ?>
                </div>
<?php } ?>

 

     <?php
$ret=mysqli_query($con,"select * from tblpg where ID=" . $eid . " and OwnerID=" . (int) $ownerid);
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {

?>
                <form class="form-horizontal bucket-form" method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">PG Name</label>
                        <div class="col-sm-6">
                            <input type="text" name="pgname" class="form-control" required="true" value="<?php echo h($row['PGTitle']); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Choose State</label>
                        <div class="col-lg-6">
                           
                 <select class="form-control m-bot15" name="state" id="state" onChange="getCity(this.value);">
                                <option value="<?php echo h($row['StateName']); ?>"><?php echo h($row['StateName']); ?></option>
                                <?php $query1=mysqli_query($con,"select * from tblstate");
              while($row1=mysqli_fetch_array($query1))
              {
              ?>    
              <option value="<?php echo h($row1['StateName']); ?>"><?php echo h($row1['StateName']); ?></option>
                  <?php } ?> 
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Choose City</label>
                        <div class="col-lg-6">
                          <select class="form-control m-bot15" name="city" id="city" required="true">
                 <option value="<?php echo h($row['CityName']); ?>"><?php echo h($row['CityName']); ?></option>
                            </select>  


                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Rent Per Month</label>
                        <div class="col-sm-6">
                            <input class="form-control" id="rpmonth" name="rpmonth" type="text" required="true" value="<?php echo h($row['RPmonth']); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Number of Rooms</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="norooms" id="norooms" required="true" value="<?php echo h($row['norooms']); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Address</label>
                        <div class="col-sm-6">
                            <textarea type="text" class="form-control" name="address" id="address" value="" required="true"><?php echo h($row['Address']); ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class=" col-sm-3 control-label">Pictures</label>
                        <div class="col-lg-6">
                             <img src="roomimages/<?php echo h(rawurlencode(basename((string) $row['Image']))); ?>" width="200" height="150" alt=""><a href="changeimage.php?editid=<?php echo h($row['ID']); ?>">Edit Image</a>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group has-success">
                        <label class="col-sm-3 control-label col-lg-3" for="inputSuccess" style="text-align: center; color: red">Facilities</label>
                        
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Electricity</label>
                        <div class="col-lg-6">
                            
<?php  if($row['Electricity']=="Yes"){ ?>
<input type="radio" id="electricity" name="electricity" value="Yes" checked="ture"> Yes
<input type="radio" id="electricity" name="electricity" value="No" > No
<?php } else { ?>
<input type="radio" id="electricity" name="electricity" value="Yes"> Yes
<input type="radio" id="electricity" name="electricity" value="No" checked="ture"> No
<?php } ?>
                            
                          </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Parking</label>
                        <div class="col-lg-6">
<?php  if($row['Parking']=="Yes"){ ?>
<input type="radio" id="parking" name="parking" value="Yes" checked="ture"> Yes
<input type="radio" id="parking" name="parking" value="No" > No
<?php } else { ?>
<input type="radio" id="parking" name="parking" value="Yes"> Yes
<input type="radio" id="parking" name="parking" value="No" checked="ture"> No
<?php } ?>
                               
                           
                          </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Refregerator</label>
                        <div class="col-lg-6">
<?php  if($row['Refregerator']=="Yes"){ ?>
<input type="radio" id="refregerator" name="refregerator" value="Yes" checked="ture"> Yes
<input type="radio" id="refregerator" name="refregerator" value="No" > No
<?php } else { ?>
<input type="radio" id="refregerator" name="refregerator" value="Yes"> Yes
<input type="radio" id="refregerator" name="refregerator" value="No" checked="ture"> No
<?php } ?>

                          </div>
                    </div>
                     <div class="form-group">
                        <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Full Furnished</label>
                        <div class="col-lg-6">

<?php  if($row['Furnished']=="Yes"){ ?>
<input type="radio" id="furnished" name="furnished" value="Yes" checked="ture"> Yes
<input type="radio" id="furnished" name="furnished" value="No" > No
<?php } else { ?>
<input type="radio" id="furnished" name="furnished" value="Yes"> Yes
<input type="radio" id="furnished" name="furnished" value="No" checked="ture"> No
<?php } ?>
                          </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">AC</label>
                        <div class="col-lg-6">
<?php  if($row['AC']=="Yes"){ ?>
<input type="radio" id="ac" name="ac" value="Yes" checked="ture"> Yes
<input type="radio" id="ac" name="ac" value="No" > No
<?php } else { ?>
<input type="radio" id="ac" name="ac" value="Yes"> Yes
<input type="radio" id="ac" name="ac" value="No" checked="ture"> No
<?php } ?>

                          </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Balcony</label>
                        <div class="col-lg-6">

<?php  if($row['Balcony']=="Yes"){ ?>
<input type="radio" id="balcony" name="balcony" value="Yes" checked="ture"> Yes
<input type="radio" id="balcony" name="balcony" value="No" > No
<?php } else { ?>
<input type="radio" id="balcony" name="balcony" value="Yes"> Yes
<input type="radio" id="balcony" name="balcony" value="No" checked="ture"> No
<?php } ?>
                           
                          </div>
                    </div>
                     <div class="form-group">
                        <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Table/Study Lamp</label>
                        <div class="col-lg-6">
                           
<?php  if($row['StudyTable']=="Yes"){ ?>
<input type="radio" id="table" name="table" value="Yes" checked="ture"> Yes
<input type="radio" id="table" name="table" value="No" > No
<?php } else { ?>
<input type="radio" id="table" name="table" value="Yes"> Yes
<input type="radio" id="table" name="table" value="No" checked="ture"> No
<?php } ?>
                          </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Laundry</label>
                        <div class="col-lg-6">
                           
<?php  if($row['Laundry']=="Yes"){ ?>
<input type="radio" id="laundry" name="laundry" value="Yes" checked="ture"> Yes
<input type="radio" id="laundry" name="laundry" value="No" > No
<?php } else { ?>
<input type="radio" id="laundry" name="laundry" value="Yes"> Yes
<input type="radio" id="laundry" name="laundry" value="No" checked="ture"> No
<?php } ?>

                          </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Security</label>
                        <div class="col-lg-6">
                           
<?php  if($row['Security']=="Yes"){ ?>
<input type="radio" id="security" name="security" value="Yes" checked="ture"> Yes
<input type="radio" id="security" name="security" value="No" > No
<?php } else { ?>
<input type="radio" id="security" name="security" value="Yes"> Yes
<input type="radio" id="security" name="security" value="No" checked="ture"> No
<?php } ?>
                          </div>
                    </div>
                    <hr />
                    <div class="form-group has-success">
                        <label class="col-sm-3 control-label col-lg-3" for="inputSuccess" style="text-align: center; color: red">Meals</label>
                        
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Choose meals which you offer with PG</label>
                  </div>


           <div class="form-group">
                        <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Breakfast</label>
                        <div class="col-lg-6">
                           
<?php  if($row['MealsBreakfast']=="Yes"){ ?>
<input type="radio" id="breakfast" name="breakfast" value="Yes" checked="ture"> Yes
<input type="radio" id="breakfast" name="breakfast" value="No" > No
<?php } else { ?>
<input type="radio" id="breakfast" name="breakfast" value="Yes"> Yes
<input type="radio" id="breakfast" name="breakfast" value="No" checked="ture"> No
<?php } ?>
                          </div>
                    </div>


      <div class="form-group">
                        <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Lunch</label>
                        <div class="col-lg-6">
                           
<?php  if($row['MealLunch']=="Yes"){ ?>
<input type="radio" id="lunch" name="lunch" value="Yes" checked="ture"> Yes
<input type="radio" id="lunch" name="lunch" value="No" > No
<?php } else { ?>
<input type="radio" id="lunch" name="lunch" value="Yes"> Yes
<input type="radio" id="lunch" name="lunch" value="No" checked="ture"> No
<?php } ?>
                          </div>
                    </div>

      <div class="form-group">
                        <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Dinner</label>
                        <div class="col-lg-6">
                           
<?php  if($row['MealDinner']=="Yes"){ ?>
<input type="radio" id="dinner" name="dinner" value="Yes" checked="ture"> Yes
<input type="radio" id="dinner" name="dinner" value="No" > No
<?php } else { ?>
<input type="radio" id="dinner" name="dinner" value="Yes"> Yes
<input type="radio" id="dinner" name="dinner" value="No" checked="ture"> No
<?php } ?>
                          </div>
                    </div>


                    <hr />
<div class="form-group">
                                    <div class="col-lg-offset-3 col-lg-6">
                                        <button class="btn btn-primary" type="submit" name="submit">Update</button>
                                    </div>
                                </div>

                </form>
            <?php } ?>
            </div>
        </section>
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

