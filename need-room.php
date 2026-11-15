<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
include_once('includes/roommate-functions.php');

// Auth check
if (empty($_SESSION['pgasuid'])) {
    header('location: signin.php?redirect=need-room.php&msg=login_required');
    exit;
}
$uid = (int)$_SESSION['pgasuid'];
$msg = '';
$msgType = 'success';

// Handle form submission
if (isset($_POST['submit_pref'])) {
    $city       = trim($_POST['preferred_city'] ?? '');
    $locality   = trim($_POST['preferred_locality'] ?? '');
    $budget_min = (int)($_POST['budget_min'] ?? 0);
    $budget_max = (int)($_POST['budget_max'] ?? 0);
    $acc_type   = trim($_POST['accommodation_type'] ?? 'Any');
    $room_type  = trim($_POST['room_type'] ?? 'Any');
    $move_in_raw = trim($_POST['move_in_date'] ?? '');
    $move_in    = !empty($move_in_raw) ? $move_in_raw : null;
    $people     = (int)($_POST['people_required'] ?? 1);
    $gender_pref = trim($_POST['gender_preference'] ?? 'Any');
    $age_min    = (int)($_POST['preferred_age_min'] ?? 18);
    $age_max    = (int)($_POST['preferred_age_max'] ?? 45);
    $food       = trim($_POST['food_preference'] ?? 'Both');
    $smoking    = trim($_POST['smoking'] ?? 'No');
    $drinking   = trim($_POST['drinking'] ?? 'No');
    $work_study = trim($_POST['work_study_status'] ?? 'Working');
    $additional = trim($_POST['additional_requirements'] ?? '');
    $lifestyle  = trim($_POST['lifestyle_prefs'] ?? '');

    if (!$city) {
        $msg = 'Please enter your preferred city.';
        $msgType = 'danger';
    } else {
        // Check if pref already exists for this user; update or insert
        $check = mysqli_query($con, "SELECT id FROM roommate_preferences WHERE user_id='$uid' LIMIT 1");
        $exists = mysqli_fetch_assoc($check);

        $stmt = $exists
            ? mysqli_prepare($con,
                "UPDATE roommate_preferences SET preferred_city=?,preferred_locality=?,budget_min=?,budget_max=?,accommodation_type=?,room_type=?,move_in_date=?,people_required=?,gender_preference=?,preferred_age_min=?,preferred_age_max=?,food_preference=?,smoking=?,drinking=?,work_study_status=?,additional_requirements=?,lifestyle_prefs=?,is_active=1,updated_at=NOW() WHERE user_id=?"
              )
            : mysqli_prepare($con,
                "INSERT INTO roommate_preferences (preferred_city,preferred_locality,budget_min,budget_max,accommodation_type,room_type,move_in_date,people_required,gender_preference,preferred_age_min,preferred_age_max,food_preference,smoking,drinking,work_study_status,additional_requirements,lifestyle_prefs,user_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"
              );

        if ($exists) {
            mysqli_stmt_bind_param($stmt, 'ssiisssisiissssssi',
                $city,$locality,$budget_min,$budget_max,$acc_type,$room_type,$move_in,$people,
                $gender_pref,$age_min,$age_max,$food,$smoking,$drinking,$work_study,$additional,$lifestyle,$uid
            );
        } else {
            mysqli_stmt_bind_param($stmt, 'ssiisssisiissssssi',
                $city,$locality,$budget_min,$budget_max,$acc_type,$room_type,$move_in,$people,
                $gender_pref,$age_min,$age_max,$food,$smoking,$drinking,$work_study,$additional,$lifestyle,$uid
            );
        }

        if (mysqli_stmt_execute($stmt)) {
            header('location: my-matches.php?success=preference');
            exit;
        } else {
            $msg = 'Something went wrong: ' . mysqli_error($con);
            $msgType = 'danger';
        }
    }
}

// Load existing preference if any
$existingPref = null;
$pcheck = mysqli_query($con, "SELECT * FROM roommate_preferences WHERE user_id='$uid' LIMIT 1");
if ($pcheck) $existingPref = mysqli_fetch_assoc($pcheck);
?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>I Need a Room – UrbanNest</title>
    <meta name="description" content="Post your roommate requirements and find someone to search and rent a room with you.">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,400,300,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="css/linearicons.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .page-hero { background: linear-gradient(135deg, #1a1a2e, #0f3460); padding: 60px 0 40px; color: #fff; text-align: center; }
        .form-section { background: #fff; border-radius: 16px; padding: 36px; box-shadow: 0 4px 24px rgba(0,0,0,.09); margin-bottom: 30px; }
        .form-section h4 { font-weight: 700; font-size: 1.1rem; border-left: 4px solid #0f3460; padding-left: 12px; margin-bottom: 20px; }
        .btn-submit { background: linear-gradient(135deg, #1a1a2e, #0f3460); color: #fff; border-radius: 30px; padding: 12px 40px; font-weight: 700; font-size: 1rem; border: none; transition: all .3s; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(15,52,96,.4); }
        .existing-banner { background: #e8f4fd; border-left: 4px solid #3498db; border-radius: 8px; padding: 14px 18px; margin-bottom: 24px; }
    </style>
</head>
<body>
<?php include_once('includes/header.php'); ?>

<div class="page-hero">
    <div class="container">
        <p class="mb-1" style="opacity:.6;"><a href="find-roommate.php" style="color:rgba(255,255,255,.6);">Find Roommate</a> &rsaquo; Post Requirement</p>
        <h1 style="font-size:2rem;font-weight:700;">🔍 I Need a Room</h1>
        <p style="opacity:.85;">Share your requirements and find someone to search with</p>
    </div>
</div>

<section class="section-gap">
    <div class="container" style="max-width:800px;">
        <?php if ($msg): ?>
        <div class="alert alert-<?= $msgType ?> alert-dismissible fade show rounded-3">
            <?= esc($msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if ($existingPref): ?>
        <div class="existing-banner">
            <i class="fa fa-info-circle me-2 text-primary"></i>
            <strong>You already have an active requirement posted.</strong> Submitting this form will update it.
        </div>
        <?php endif; ?>

        <form method="POST" novalidate>

            <!-- Location & Budget -->
            <div class="form-section">
                <h4>Location & Budget</h4>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Preferred City *</label>
                        <input type="text" name="preferred_city" class="form-control" placeholder="e.g. Pune"
                               value="<?= esc($existingPref['preferred_city'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Preferred Locality</label>
                        <input type="text" name="preferred_locality" class="form-control" placeholder="e.g. Koregaon Park"
                               value="<?= esc($existingPref['preferred_locality'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Min Budget / person (₹)</label>
                        <input type="number" name="budget_min" class="form-control" placeholder="5000" min="0"
                               value="<?= $existingPref['budget_min'] ?? '' ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Max Budget / person (₹)</label>
                        <input type="number" name="budget_max" class="form-control" placeholder="12000" min="0"
                               value="<?= $existingPref['budget_max'] ?? '' ?>">
                    </div>
                </div>
            </div>

            <!-- Accommodation Preferences -->
            <div class="form-section">
                <h4>Accommodation Preferences</h4>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Accommodation Type</label>
                        <select name="accommodation_type" class="form-select">
                            <?php foreach(['Any','PG','Flat','Apartment','Hostel'] as $t): ?>
                            <option value="<?= $t ?>" <?= ($existingPref['accommodation_type'] ?? '') === $t ? 'selected':'' ?>><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Room Type</label>
                        <select name="room_type" class="form-select">
                            <?php foreach(['Any','Single','Double','Shared'] as $t): ?>
                            <option value="<?= $t ?>" <?= ($existingPref['room_type'] ?? '') === $t ? 'selected':'' ?>><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Expected Move-in Date</label>
                        <input type="date" name="move_in_date" class="form-control"
                               value="<?= $existingPref['move_in_date'] ?? '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">People Required (incl. you)</label>
                        <input type="number" name="people_required" class="form-control" min="1" max="10"
                               value="<?= $existingPref['people_required'] ?? 2 ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Gender Preference</label>
                        <select name="gender_preference" class="form-select">
                            <option value="Any" <?= ($existingPref['gender_preference']??'Any')==='Any'?'selected':'' ?>>Any</option>
                            <option value="Male" <?= ($existingPref['gender_preference']??'')==='Male'?'selected':'' ?>>Male</option>
                            <option value="Female" <?= ($existingPref['gender_preference']??'')==='Female'?'selected':'' ?>>Female</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Lifestyle Preferences -->
            <div class="form-section">
                <h4>Your Lifestyle</h4>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Food Preference</label>
                        <select name="food_preference" class="form-select">
                            <?php foreach(['Both','Vegetarian','Non-Vegetarian'] as $t): ?>
                            <option value="<?= $t ?>" <?= ($existingPref['food_preference']??'Both')===$t?'selected':'' ?>><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Smoking</label>
                        <select name="smoking" class="form-select">
                            <?php foreach(['No','Yes','Occasionally'] as $t): ?>
                            <option value="<?= $t ?>" <?= ($existingPref['smoking']??'No')===$t?'selected':'' ?>><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Drinking</label>
                        <select name="drinking" class="form-select">
                            <?php foreach(['No','Yes','Occasionally'] as $t): ?>
                            <option value="<?= $t ?>" <?= ($existingPref['drinking']??'No')===$t?'selected':'' ?>><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Work / Study Status</label>
                        <select name="work_study_status" class="form-select">
                            <?php foreach(['Working','Studying','Other'] as $t): ?>
                            <option value="<?= $t ?>" <?= ($existingPref['work_study_status']??'Working')===$t?'selected':'' ?>><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Preferred Age – Min</label>
                        <input type="number" name="preferred_age_min" class="form-control" min="16" max="80"
                               value="<?= $existingPref['preferred_age_min'] ?? 18 ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Preferred Age – Max</label>
                        <input type="number" name="preferred_age_max" class="form-control" min="16" max="80"
                               value="<?= $existingPref['preferred_age_max'] ?? 35 ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Lifestyle Preferences</label>
                        <input type="text" name="lifestyle_prefs" class="form-control" placeholder="e.g. Looking for a quiet working professional"
                               value="<?= esc($existingPref['lifestyle_prefs'] ?? '') ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Additional Requirements</label>
                        <textarea name="additional_requirements" class="form-control" rows="4"
                                  placeholder="Any special requirements, preferences, or details about yourself..."><?= esc($existingPref['additional_requirements'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" name="submit_pref" class="btn-submit btn">🔍 Post Requirement</button>
                <a href="find-roommate.php" class="btn btn-outline-secondary ms-3 rounded-pill px-4">Cancel</a>
            </div>
        </form>
    </div>
</section>

<?php include_once('includes/footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/vendor/jquery-2.2.4.min.js"></script>
</body>
</html>
