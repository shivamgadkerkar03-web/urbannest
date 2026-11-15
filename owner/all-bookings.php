<?php
session_start();
error_reporting(0);
include ('includes/dbconnection.php');
if (strlen($_SESSION['pgasoid'] == 0)) {
  header('location:logout.php');
} else {

  ?>


  <!DOCTYPE html>

  <head>
    <title>UrbanNest || Booking Requests</title>

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
  </head>

  <body>
    <section id="container">
      <!--header start-->
      <?php include_once ('includes/header.php'); ?>
      <!--header end-->
      <!--sidebar start-->
      <?php include_once ('includes/sidebar.php'); ?>
      <!--sidebar end-->
      <!--main content start-->
      <section id="main-content">
        <section class="wrapper">
          <div class="table-agile-info">
            <div class="panel panel-default">
              <div class="panel-heading">
                All Requests Received for Paying Guest
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
                      <th>User Name</th>
                      <th>Booking Number</th>
                      <th>PG Name</th>
                      <th>Checkin Date</th>
                      <th>Booking Date</th>

                      <th data-breakpoints="xs">Action</th>


                    </tr>
                  </thead>
                  <tbody id="allStu">
                    <?php
                    $oid = $_SESSION['pgasoid'];
                    $ret = mysqli_query($con, "select tblbookpg.Userid,tblbookpg.Pgid,tblbookpg.ID,tblbookpg.BookingNumber,tblbookpg.CheckinDate,tblbookpg.BookingDate,tblpg.PGTitle,tbluser.FullName from  tblbookpg join tblpg on tblpg.ID=tblbookpg.Pgid join tbluser on tbluser.ID=tblbookpg.Userid where tblpg.OwnerID='$oid' ");
                    $cnt = 1;
                    while ($row = mysqli_fetch_array($ret)) {

                      ?>
                      <tr data-expanded="true">
                        <td><?php echo $cnt; ?></td>
                        <td><?php echo $row['FullName']; ?></td>
                        <td><?php echo $row['BookingNumber']; ?></td>
                        <td><?php echo $row['PGTitle']; ?></td>
                        <td><?php echo $row['CheckinDate']; ?></td>
                        <td><?php echo $row['BookingDate']; ?></td>
                        <td><a href="view-requestdetails.php?viewid=<?php echo $row['ID']; ?>" class="btn btn-primary">View
                            Details</a>
                          <a action="del" uId="<?php echo $row['Userid']; ?>" pgId="<?php echo $row['Pgid']; ?>"
                            class="btn btn-danger">Delete</a>
                      </tr>
                      <?php
                      $cnt = $cnt + 1;
                    } ?>
                  </tbody>
                </table>



              </div>
            </div>
          </div>
          <script>
            document.getElementById("allStu").addEventListener('click', (event) => {
              if (event.target.getAttribute("action") == "del") {
                // console.log(event.target.getAttribute("uid"));
                // console.log(event.target.getAttribute("pgid"));
                // const id = 'some_id_value'; // Replace 'some_id_value' with the actual ID value
                console.log({ uid: event.target.getAttribute("uid"), pgid: event.target.getAttribute("pgid") });

                fetch('./php/delet.php', {
                  method: 'POST',
                  body: JSON.stringify({ uid: event.target.getAttribute("uid"), pgid: event.target.getAttribute("pgid") })
                })
                  .then(response => {
                    if (response.ok) {
                      return response.text(); 
                    }
                    throw new Error('Network response was not ok.');
                  })
                  .then(text => {
                    alert(text); 
                    location.reload();
                  })
                  .catch(error => {
                    console.error('Error:', error);
                  });
              }
            })
          </script>
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
    <script src="js/jquery.scrollTo.js"></script>
  </body>

  </html>
<?php } ?>