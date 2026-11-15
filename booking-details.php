<?php
require_once __DIR__ . '/includes/security.php';
include('includes/dbconnection.php');
$uid = require_user('signin.php');

$bookid = (int) ($_GET['bkid'] ?? 0);
$stmt = mysqli_prepare($con, "SELECT tblbookpg.*, tblpg.PGTitle, tblpg.Image, tblpg.StateName, tblpg.CityName, tblpg.RPmonth, tblpg.norooms, tblpg.Address, tblpg.MealsBreakfast, tblpg.MealLunch, tblpg.MealDinner, tblpg.Electricity, tblpg.Parking, tblpg.Refregerator, tblpg.Furnished, tblpg.AC, tblpg.Balcony, tblpg.StudyTable, tblpg.Laundry, tblpg.Security FROM tblbookpg JOIN tblpg ON tblpg.ID = tblbookpg.Pgid WHERE tblbookpg.ID = ? AND tblbookpg.Userid = ?");
mysqli_stmt_bind_param($stmt, 'ii', $bookid, $uid);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include __DIR__ . '/includes/head.php'; ?>
    <title>Booking Details | UrbanNest</title>
    <style>
        .bd-img {
            width: 100%;
            aspect-ratio: 16 / 10;
            object-fit: cover;
            border-radius: var(--un-radius);
        }

        .detail-table th {
            width: 38%;
            font-weight: 500;
            color: var(--un-muted);
        }

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
                        <span class="lnr lnr-arrow-right"></span> <a href="my-bookings.php">My Bookings</a>
                        <span class="lnr lnr-arrow-right"></span> <span>Booking Details</span></p>
                    <h1 class="text-white">Booking Details</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="section-gap">
        <div class="container">
            <?php if (!$row) { ?>
                <div class="empty-state un-card">
                    <span class="lnr lnr-calendar-full"></span>
                    Booking not found.
                    <div class="mt-3"><a href="my-bookings.php" class="btn btn-primary">Back to My Bookings</a></div>
                </div>
            <?php } else {
                $status = trim((string) $row['Status']);
                $statusClass = $status === 'Confirmed' ? 'is-confirmed' : ($status === 'Cancelled' ? 'is-cancelled' : '');
                $show = function ($v) {
                    $v = trim((string) $v);
                    return $v === '' ? '<span class="text-muted">&ndash;</span>' : h($v);
                };
                $yesList = function (array $map) use ($row) {
                    $out = [];
                    foreach ($map as $label => $col) {
                        if (strcasecmp(trim((string) $row[$col]), 'Yes') === 0) {
                            $out[] = $label;
                        }
                    }
                    return $out;
                };
                $meals = $yesList(['Breakfast' => 'MealsBreakfast', 'Lunch' => 'MealLunch', 'Dinner' => 'MealDinner']);
                $facilities = $yesList(['Electricity' => 'Electricity', 'Parking' => 'Parking', 'Refrigerator' => 'Refregerator', 'Furnished' => 'Furnished', 'AC' => 'AC', 'Balcony' => 'Balcony', 'Study table' => 'StudyTable', 'Laundry' => 'Laundry', 'Security' => 'Security']);
                $rent = is_numeric($row['RPmonth']) ? number_format((float) $row['RPmonth']) : h($row['RPmonth']);
            ?>
                <div class="row g-4">
                    <div class="col-lg-5">
                        <img class="bd-img mb-3" src="owner/roomimages/<?= rawurlencode(basename((string) $row['Image'])) ?>"
                            alt="<?= h($row['PGTitle']) ?>" onerror="this.src='img/default-property.jpg'">
                        <h2 class="h4 mb-1"><?= h($row['PGTitle']) ?></h2>
                        <p class="text-muted mb-2"><span class="lnr lnr-map-marker"></span> <?= h($row['CityName']) ?>, <?= h($row['StateName']) ?></p>
                        <p class="mb-3"><?= h($row['Address']) ?></p>
                        <a class="btn btn-outline-primary btn-sm" href="pg-details.php?pid=<?= (int) $row['Pgid'] ?>">View PG listing</a>
                    </div>

                    <div class="col-lg-7">
                        <div class="un-card mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="h5 mb-0">Booking #<?= h($row['BookingNumber']) ?></h3>
                                <span class="status-pill <?= $statusClass ?>"><?= $status === '' ? 'Awaiting owner' : h($status) ?></span>
                            </div>
                            <div class="table-responsive">
                                <table class="table detail-table mb-0">
                                    <tr><th>Requested on</th><td><?= h(date('d M Y, h:i A', strtotime($row['BookingDate']))) ?></td></tr>
                                    <tr><th>Check-in date</th><td><?= h(date('d M Y', strtotime($row['CheckinDate']))) ?></td></tr>
                                    <tr><th>Your message</th><td><?= $show($row['UserMsg']) ?></td></tr>
                                    <tr><th>Owner remark</th><td><?= $show($row['Remark']) ?></td></tr>
                                    <tr><th>Remark date</th><td><?= !empty($row['RemDate']) ? h(date('d M Y', strtotime($row['RemDate']))) : $show('') ?></td></tr>
                                </table>
                            </div>
                        </div>

                        <div class="un-card">
                            <h3 class="h5 mb-3">PG summary</h3>
                            <div class="table-responsive">
                                <table class="table detail-table mb-0">
                                    <tr><th>Rent per month</th><td>&#8377;<?= $rent ?></td></tr>
                                    <tr><th>Rooms</th><td><?= $show($row['norooms']) ?></td></tr>
                                    <tr><th>Meals</th><td><?= $meals ? h(implode(', ', $meals)) : $show('') ?></td></tr>
                                    <tr><th>Facilities</th><td><?= $facilities ? h(implode(', ', $facilities)) : $show('') ?></td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>

    <?php include_once('includes/footer.php'); ?>
</body>

</html>
