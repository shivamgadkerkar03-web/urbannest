<?php
require_once __DIR__ . '/includes/security.php';
include ('includes/dbconnection.php');

$pid = (int) ($_GET['pid'] ?? 0);
$uid = current_user_id();

$stmt = mysqli_prepare($con, "SELECT * FROM tblpg WHERE ID = ? AND IsActive = 1");
mysqli_stmt_bind_param($stmt, 'i', $pid);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

function active_booking_exists($con, int $uid, int $pid): bool
{
  $stmt = mysqli_prepare($con, "SELECT ID FROM tblbookpg WHERE Userid = ? AND Pgid = ? AND (Status IS NULL OR Status = '' OR Status = 'Confirmed') LIMIT 1");
  mysqli_stmt_bind_param($stmt, 'ii', $uid, $pid);
  mysqli_stmt_execute($stmt);
  $found = (bool) mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);
  return $found;
}

$bookingError = '';
if (isset($_POST['submit'])) {
  if ($uid <= 0) {
    redirect_to('signin.php');
  }
  csrf_check();

  $chkin = trim((string) ($_POST['chkin'] ?? ''));
  $userMsg = trim((string) ($_POST['message'] ?? ''));
  $chkinDate = DateTime::createFromFormat('!Y-m-d', $chkin);
  $today = new DateTime('today');
  $maxDate = (new DateTime('today'))->modify('+3 months');

  if (!$row) {
    $bookingError = 'This PG is not available for booking.';
  } elseif (!$chkinDate || $chkinDate->format('Y-m-d') !== $chkin) {
    $bookingError = 'Please select a valid check-in date.';
  } elseif ($chkinDate < $today) {
    $bookingError = 'Check-in date cannot be in the past.';
  } elseif ($chkinDate > $maxDate) {
    $bookingError = 'Check-in date must be within the next 3 months.';
  } elseif ($userMsg === '' || mb_strlen($userMsg) > 500) {
    $bookingError = 'Please enter a message (up to 500 characters).';
  } elseif (active_booking_exists($con, $uid, $pid)) {
    $bookingError = 'You already have an active booking for this PG.';
  } else {
    $booknumber = random_int(100000000, 999999999);
    $stmt = mysqli_prepare($con, "INSERT INTO tblbookpg (Userid, Pgid, CheckinDate, UserMsg, BookingNumber) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'iissi', $uid, $pid, $chkin, $userMsg, $booknumber);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    if ($ok) {
      redirect_to('pg-details.php?pid=' . $pid . '&booked=1');
    }
    error_log('Booking insert failed: ' . mysqli_error($con));
    $bookingError = 'Something Went Wrong. Please try again.';
  }
}

$alreadyBooked = ($row && $uid > 0) ? active_booking_exists($con, $uid, $pid) : false;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include __DIR__ . '/includes/head.php'; ?>
  <title><?= $row ? h($row['PGTitle']) . ' | ' : '' ?>UrbanNest</title>
  <style>
    .pg-hero-img {
      width: 100%;
      aspect-ratio: 16 / 9;
      object-fit: cover;
      border-radius: var(--un-radius);
      background: var(--un-bg);
    }

    .pg-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 8px 18px;
      color: var(--un-muted);
      font-size: 14px;
    }

    .pg-meta .lnr {
      margin-right: 4px;
    }

    .info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
      gap: 10px;
    }

    .info-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 12px;
      border: 1px solid var(--un-line);
      border-radius: 10px;
      font-size: 14px;
      color: var(--un-ink);
    }

    .info-item .dot {
      width: 22px;
      height: 22px;
      flex: 0 0 22px;
      border-radius: 50%;
      display: grid;
      place-items: center;
      font-size: 12px;
      background: #f1f2f5;
      color: #9aa0ad;
    }

    .info-item.is-yes .dot {
      background: #e8f6ee;
      color: #1e7d48;
    }

    .info-item.is-no {
      color: var(--un-muted);
    }

    .detail-table th {
      width: 40%;
      font-weight: 500;
      color: var(--un-muted);
    }

    .booking-card {
      position: sticky;
      top: 90px;
    }

    .booking-card .price {
      font-size: 28px;
      font-weight: 700;
      color: var(--un-ink);
    }

    .booking-card .price small {
      font-size: 14px;
      font-weight: 400;
      color: var(--un-muted);
    }
  </style>
</head>

<body>

  <?php include_once ('includes/header.php'); ?>

  <section class="banner-area relative">
    <div class="overlay overlay-bg"></div>
    <div class="container">
      <div class="row text-center justify-content-center">
        <div class="about-content col-lg-12">
          <p class="text-white link-nav"><a href="index.php">Home</a>
            <span class="lnr lnr-arrow-right"></span> <a href="pg.php">PG Accommodation</a>
            <?php if ($row) { ?><span class="lnr lnr-arrow-right"></span> <span><?= h($row['PGTitle']) ?></span><?php } ?>
          </p>
          <h1 class="text-white"><?= $row ? h($row['PGTitle']) : 'PG Details' ?></h1>
        </div>
      </div>
    </div>
  </section>

  <section class="section-gap">
    <div class="container">
      <?php if (!$row) { ?>
        <div class="empty-state un-card">
          <span class="lnr lnr-apartment"></span>
          <h3 class="h5">PG not found</h3>
          <p>The PG you are looking for does not exist or is no longer listed.</p>
          <a href="pg.php" class="btn btn-primary">Browse PGs</a>
        </div>
      <?php } else {
        $yes = function ($value): bool {
          return strcasecmp(trim((string) $value), 'Yes') === 0;
        };
        $facilities = [
          'Electricity'        => $row['Electricity'],
          'Parking'            => $row['Parking'],
          'Refrigerator'       => $row['Refregerator'],
          'Fully furnished'    => $row['Furnished'],
          'AC'                 => $row['AC'],
          'Balcony'            => $row['Balcony'],
          'Study table & lamp' => $row['StudyTable'],
          'Laundry'            => $row['Laundry'],
          'Security'           => $row['Security'],
        ];
        $meals = [
          'Breakfast' => $row['MealsBreakfast'],
          'Lunch'     => $row['MealLunch'],
          'Dinner'    => $row['MealDinner'],
        ];
        $rent = is_numeric($row['RPmonth']) ? number_format((float) $row['RPmonth']) : h($row['RPmonth']);
      ?>
        <?php if (isset($_GET['booked'])) { ?>
          <div class="alert alert-success">Your booking request has been sent to the owner. You can track it in <a href="my-bookings.php">My Bookings</a>.</div>
        <?php } ?>

        <div class="row g-4 g-lg-5">
          <div class="col-lg-8">
            <img class="pg-hero-img mb-4" src="owner/roomimages/<?= rawurlencode(basename((string) $row['Image'])) ?>"
              alt="<?= h($row['PGTitle']) ?>" onerror="this.src='img/default-property.jpg'">

            <h2 class="h3 mb-2"><?= h($row['PGTitle']) ?></h2>
            <div class="pg-meta mb-4">
              <span><span class="lnr lnr-map-marker"></span><?= h($row['CityName']) ?>, <?= h($row['StateName']) ?></span>
              <?php if (!empty($row['Type'])) { ?><span><span class="lnr lnr-users"></span>For <?= h($row['Type']) ?></span><?php } ?>
              <?php if (!empty($row['norooms'])) { ?><span><span class="lnr lnr-home"></span><?= h($row['norooms']) ?> room<?= (int) $row['norooms'] === 1 ? '' : 's' ?></span><?php } ?>
            </div>

            <div class="un-card mb-4">
              <h3 class="h5 mb-3">Overview</h3>
              <div class="table-responsive">
                <table class="table detail-table mb-0">
                  <tr><th>Rent per month</th><td>&#8377;<?= $rent ?></td></tr>
                  <tr><th>Number of rooms</th><td><?= h($row['norooms']) ?></td></tr>
                  <tr><th>City</th><td><?= h($row['CityName']) ?></td></tr>
                  <tr><th>State</th><td><?= h($row['StateName']) ?></td></tr>
                  <tr><th>Address</th><td><?= h($row['Address']) ?></td></tr>
                </table>
              </div>
            </div>

            <div class="un-card mb-4">
              <h3 class="h5 mb-3">Facilities</h3>
              <div class="info-grid">
                <?php foreach ($facilities as $label => $value) { $ok = $yes($value); ?>
                  <div class="info-item <?= $ok ? 'is-yes' : 'is-no' ?>">
                    <span class="dot"><?= $ok ? '&#10003;' : '&times;' ?></span><?= h($label) ?>
                  </div>
                <?php } ?>
              </div>
            </div>

            <div class="un-card">
              <h3 class="h5 mb-3">Meals included</h3>
              <div class="info-grid">
                <?php foreach ($meals as $label => $value) { $ok = $yes($value); ?>
                  <div class="info-item <?= $ok ? 'is-yes' : 'is-no' ?>">
                    <span class="dot"><?= $ok ? '&#10003;' : '&times;' ?></span><?= h($label) ?>
                  </div>
                <?php } ?>
              </div>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="un-card booking-card" id="book">
              <div class="price mb-1">&#8377;<?= $rent ?> <small>/ month</small></div>
              <p class="text-muted small mb-3">Send a booking request and the owner will confirm it.</p>

              <?php if ($uid <= 0) { ?>
                <a href="signin.php" class="btn btn-primary w-100">Login to book</a>
                <p class="small text-muted text-center mt-2 mb-0">New here? <a href="register.php">Create an account</a></p>
              <?php } elseif ($alreadyBooked) { ?>
                <div class="alert alert-success mb-2">You already have an active booking for this PG.</div>
                <a href="my-bookings.php" class="btn btn-outline-primary w-100">View my bookings</a>
              <?php } else { ?>
                <?php if ($bookingError) { ?>
                  <div class="alert alert-danger py-2"><?= h($bookingError) ?></div>
                <?php } ?>
                <form method="post" action="pg-details.php?pid=<?= (int) $pid ?>#book">
                  <?= csrf_field() ?>
                  <div class="mb-3">
                    <label class="form-label" for="chkin">Check-in date</label>
                    <input class="form-control" type="date" id="chkin" name="chkin" required
                      min="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d', strtotime('+3 months')) ?>"
                      value="<?= h($_POST['chkin'] ?? '') ?>">
                  </div>
                  <div class="mb-3">
                    <label class="form-label" for="message">Message to owner</label>
                    <textarea class="form-control" name="message" id="message" rows="3" maxlength="500" required
                      placeholder="Introduce yourself and mention any questions"><?= h($_POST['message'] ?? '') ?></textarea>
                  </div>
                  <button type="submit" class="btn btn-primary w-100" name="submit">Request booking</button>
                </form>
              <?php } ?>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </section>

  <?php include_once ('includes/footer.php'); ?>
</body>

</html>
