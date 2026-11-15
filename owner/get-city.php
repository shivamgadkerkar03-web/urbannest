<?php
require_once __DIR__ . '/../includes/security.php';
include('includes/dbconnection.php');
$ownerid = require_owner('login.php');


 if(isset($_POST['sateid'])){
 $sid = (string) $_POST['sateid'];

  $stmt = mysqli_prepare($con, "select tblcity.City from tblcity join tblstate on tblstate.ID=tblcity.StateID where tblstate.StateName = ?");
  mysqli_stmt_bind_param($stmt, 's', $sid);
  mysqli_stmt_execute($stmt);
  $query = mysqli_stmt_get_result($stmt);
  echo '<option value="">Select City</option>';
    while($rw=mysqli_fetch_array($query))
    {
     ?>      
 <option value="<?php echo h($rw['City']); ?>"><?php echo h($rw['City']); ?></option>
                  

<?php }} ?>