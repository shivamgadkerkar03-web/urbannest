<?php
require_once __DIR__ . '/includes/security.php';
include('includes/dbconnection.php');
$uid = require_user('signin.php');

$stmt = mysqli_prepare($con, "SELECT tblbookpg.ID AS bookid, tblbookpg.BookingNumber, tblbookpg.BookingDate, tblbookpg.CheckinDate, tblbookpg.Status, tblpg.PGTitle FROM tblbookpg JOIN tblpg ON tblpg.ID = tblbookpg.Pgid WHERE tblbookpg.Userid = ? ORDER BY tblbookpg.ID DESC");
mysqli_stmt_bind_param($stmt, 'i', $uid);
mysqli_stmt_execute($stmt);
$ret = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php include __DIR__ . '/includes/head.php'; ?>
	<title>My Bookings | UrbanNest</title>
	<style>
		.status-pill {
			display: inline-block;
			font-size: 12px;
			font-weight: 600;
			padding: 4px 10px;
			border-radius: 999px;
			background: #fff4e0;
			color: #a86400;
		}

		.status-pill.is-confirmed {
			background: #e8f6ee;
			color: #1e7d48;
		}

		.status-pill.is-cancelled {
			background: #fdecee;
			color: #c1121f;
		}

		.bookings-table td,
		.bookings-table th {
			vertical-align: middle;
			white-space: nowrap;
		}
	</style>
</head>

<body>

	<?php include_once('includes/header.php'); ?>

	<section class="banner-area relative">
		<div class="overlay overlay-bg"></div>
		<div class="container">
			<div class="row text-center justify-content-center">
				<div class="about-content col-lg-12">
					<p class="text-white link-nav"><a href="index.php">Home</a>
						<span class="lnr lnr-arrow-right"></span> <span>My Bookings</span></p>
					<h1 class="text-white">My Bookings</h1>
				</div>
			</div>
		</div>
	</section>

	<section class="section-gap">
		<div class="container">
			<div class="un-card">
				<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
					<h2 class="h5 mb-0">Your booking requests</h2>
					<a class="btn btn-outline-primary btn-sm" href="pg.php">Find another PG</a>
				</div>

				<?php if (mysqli_num_rows($ret) === 0) { ?>
					<div class="empty-state">
						<span class="lnr lnr-calendar-full"></span>
						You haven't requested any bookings yet.
						<div class="mt-3"><a class="btn btn-primary" href="pg.php">Browse PGs</a></div>
					</div>
				<?php } else { ?>
					<div class="table-responsive">
						<table class="table bookings-table mb-0">
							<thead>
								<tr>
									<th>#</th>
									<th>Booking no.</th>
									<th>PG</th>
									<th>Requested on</th>
									<th>Check-in</th>
									<th>Status</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php $cnt = 1;
								while ($row = mysqli_fetch_assoc($ret)) {
									$status = trim((string) $row['Status']);
									$statusClass = $status === 'Confirmed' ? 'is-confirmed' : ($status === 'Cancelled' ? 'is-cancelled' : '');
								?>
									<tr>
										<td><?= $cnt++ ?></td>
										<td><?= h($row['BookingNumber']) ?></td>
										<td><?= h($row['PGTitle']) ?></td>
										<td><?= h(date('d M Y', strtotime($row['BookingDate']))) ?></td>
										<td><?= h(date('d M Y', strtotime($row['CheckinDate']))) ?></td>
										<td><span class="status-pill <?= $statusClass ?>"><?= $status === '' ? 'Awaiting owner' : h($status) ?></span></td>
										<td class="text-end"><a class="btn btn-sm btn-light" href="booking-details.php?bkid=<?= (int) $row['bookid'] ?>">View</a></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>

	<?php include_once('includes/footer.php'); ?>
</body>

</html>
