<?php
require_once __DIR__ . '/../includes/security.php';
include('includes/dbconnection.php');
$ownerid = require_owner('login.php');

$vid = (int) ($_GET['viewid'] ?? 0);
$msg = '';

if (isset($_POST['submit'])) {
    csrf_check();
    $ressta = (string) ($_POST['status'] ?? '');
    $remark = trim((string) ($_POST['remark'] ?? ''));

    if (!in_array($ressta, ['Confirmed', 'Cancelled'], true)) {
        $msg = "Please select a status.";
    } elseif ($remark === '' || mb_strlen($remark) > 50) {
        $msg = "Please enter a remark of up to 50 characters.";
    } else {
        $stmt = mysqli_prepare(
            $con,
            "UPDATE tblbookpg b JOIN tblpg p ON p.ID = b.Pgid SET b.Remark = ?, b.Status = ?
             WHERE b.ID = ? AND p.OwnerID = ? AND b.Status IS NULL"
        );
        mysqli_stmt_bind_param($stmt, 'ssii', $remark, $ressta, $vid, $ownerid);
        if (mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0) {
            $msg = "Owner Remark has been updated.";
        } else {
            $msg = "Something Went Wrong. Please try again";
        }
        mysqli_stmt_close($stmt);
    }
}
?>


  <!DOCTYPE html>

  <head>
    <title>UrbanNest || Booking Requests</title>

    <script
      type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <link href="css/style-responsive.css" rel="stylesheet" />
    <link
      href='//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic'
      rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/font.css" type="text/css" />
    <link href="css/font-awesome.css" rel="stylesheet">
    <script src="js/jquery2.0.3.min.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../css/panel.css">
</head>

  <body>
    <section id="container">
      <?php include_once ('includes/header.php'); ?>
      <?php include_once ('includes/sidebar.php'); ?>
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

                $ret = mysqli_query($con, "select tblbookpg.BookingNumber, tblbookpg.CheckinDate,tblbookpg.UserMsg,tblbookpg.Remark, tblbookpg.Status,tblbookpg.RemDate,tblpg.PGTitle,tblpg.StateName,tblpg.CityName,tbluser.FullName,tbluser.Email,tbluser.MobileNumber from tbluser left join tblbookpg on tblbookpg.Userid=tbluser.ID join tblpg on tblbookpg.Pgid=tblpg.ID where tblbookpg.ID=" . $vid . " and tblpg.OwnerID=" . (int) $ownerid);

                while ($row = mysqli_fetch_array($ret)) {

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
                      if ($row['Status'] == "") {
                        echo "Not Confirmed Yet";
                      } else {
                        echo h($row['Status']);
                      }

                      ; ?></td>
                    </tr>
                  </table>
                  <table border="1" class="table table-bordered mg-b-0">
                    <?php if ($row['Status'] == "") { ?>
                      <form method="post" name="submit">
                        <?php echo csrf_field(); ?>
                        <tr>
                          <th>Remark :</th>
                          <td>
                            <textarea name="remark" placeholder="remark" rows="12" cols="14" class="form-control wd-450"
                              required="true" maxlength="50"></textarea>
                          </td>
                        </tr>
                        <tr>
                          <th>Status :</th>
                          <td>
                            <select name="status" class="form-control wd-450" required="true">
                              <option value="">Select status</option>
                              <option value="Confirmed">Confirmed</option>
                              <option value="Cancelled">Cancelled</option>
                            </select>
                          </td>
                        </tr>

                        <tr align="center">
                          <td colspan="2"><button type="submit" name="submit"
                              class="btn btn-az-primary pd-x-20">Update</button></td>
                        </tr>
                      </form>
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

                      <!-- <tr>
                        <th>Remove From Pg</th>
                        <td>
                          <button type="submit" name="submit" lass="btn btn-az-primary pd-x-20">Delete</button>
                        </td>
                      </tr> -->

                    <?php } ?>

                  </table>



                </div>
              </div>
            </div>
          </section>
          <?php include_once ('includes/footer.php'); ?>
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
  <?php } ?>
