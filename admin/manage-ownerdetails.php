<?php
require_once __DIR__ . '/../includes/security.php';
$adminid = require_admin('login.php');
include('includes/dbconnection.php');


if (isset($_GET['delid'])) {
  $delid = (int) $_GET['delid'];
  $ok = false;
  if (csrf_valid() && $delid > 0) {
    $stmt = mysqli_prepare($con, "DELETE FROM tblowner WHERE ID = ?");
    mysqli_stmt_bind_param($stmt, 'i', $delid);
    $ok = mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0;
    mysqli_stmt_close($stmt);
  }
  if ($ok) {
    echo "<script>alert('Record deleted successfully.');</script>";
  } else {
    echo "<script>alert('Failed to delete record. Please try again.');</script>";
  }
  echo "<script>window.location.href = 'manage-ownerdetails.php';</script>";
  exit;
}

?>


<!DOCTYPE html>

<head>
  <title>UrbanNest || Owner Details </title>
  <meta name="csrf-token" content="<?php echo h(csrf_token()); ?>">
  <script type="application/x-javascript">
        addEventListener("load", function() {
          setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
          window.scrollTo(0, 1);
        }
      </script>
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
              Paying Guest Owner Details
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
                    <th>Full Name</th>
                    <th>Mobile Number</th>
                    <th>Registration Date</th>
                    <th>Status</th>
                    <th>Verification File</th>
                    <th data-breakpoints="xs">Activate</th>
                    <th data-breakpoints="xs">Deactivate</th>


                  </tr>
                </thead>
                <tbody id="allOwn">
                  <?php
                  $ret = mysqli_query($con, "select * from  tblowner");
                  $cnt = 1;
                  while ($row = mysqli_fetch_array($ret)) {

                    ?>
                    <tr data-expanded="true">
                      <td>
                        <?php echo $cnt; ?>
                      </td>

                      <td>
                        <?php echo h($row['FullName']); ?>
                      </td>
                      <td>
                        <?php echo h($row['MobileNumber']); ?>
                      </td>
                      <td>
                        <?php echo h($row['RegDate']); ?>
                      </td>
                      <td id="status-<?php echo (int) $row['ID']; ?>"
                        style="background-color: <?php echo $row['status'] ? "#0b3" : "red"; ?>;color: white;text-align: center;">
                        <?php echo $row['status'] ? "Active" : "Inactive"; ?>
                      </td>
                      <td>
                        <?php if (!empty($row['VerificationFile'])) { ?>
                          <a href="verification-file.php?id=<?php echo (int) $row['ID']; ?>" target="_blank">Verification File</a>
                        <?php } else { ?>
                          Not uploaded
                        <?php } ?>
                      </td>
                      <td>
                        <a class="btn btn-primary js-owner-status" href="javascript:;" data-id="<?php echo (int) $row['ID']; ?>" data-status="1">Active</a>
                      </td>
                      <td>
                        <a class="btn btn-danger js-owner-status" href="javascript:;" data-id="<?php echo (int) $row['ID']; ?>" data-status="0">Inactive</a>
                      </td>

                    </tr>
                    <?php
                    $cnt = $cnt + 1;
                  } ?>
                </tbody>
              </table>

              <script>
                document.getElementById("allOwn").addEventListener('click', (event) => {
                  const btn = event.target.closest('.js-owner-status');
                  if (!btn) {
                    return;
                  }
                  event.preventDefault();
                  const id = btn.dataset.id;
                  const status = parseInt(btn.dataset.status, 10);
                  const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                  fetch('./php/updateStatus.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': token },
                    body: JSON.stringify({ id: id, status: status })
                  })
                    .then(response => response.json())
                    .then(data => {
                      if (!data.success) {
                        alert(data.message || 'Could not update the owner.');
                        return;
                      }
                      const cell = document.getElementById('status-' + id);
                      if (cell) {
                        cell.textContent = status === 1 ? 'Active' : 'Inactive';
                        cell.style.backgroundColor = status === 1 ? '#0b3' : 'red';
                      }
                    })
                    .catch(() => {
                      alert('Could not update the owner. Please try again.');
                    });
                });
              </script>



            </div>
          </div>
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
