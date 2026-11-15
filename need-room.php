<?php
require_once __DIR__ . '/includes/security.php';
include('includes/dbconnection.php');
include_once('includes/roommate-functions.php');

$uid = require_user('signin.php?redirect=need-room.php&msg=login_required');
$msg = '';
$msgType = 'success';

function pick_option($value, array $allowed, $default) {
    return in_array($value, $allowed, true) ? $value : $default;
}

// Handle form submission
if (isset($_POST['submit_pref'])) {
    csrf_check();
    $city       = mb_substr(trim($_POST['preferred_city'] ?? ''), 0, 100);
    $locality   = mb_substr(trim($_POST['preferred_locality'] ?? ''), 0, 200);
    $budget_min = max(0, (int)($_POST['budget_min'] ?? 0));
    $budget_max = max(0, (int)($_POST['budget_max'] ?? 0));
    if ($budget_max && $budget_min > $budget_max) {
        [$budget_min, $budget_max] = [$budget_max, $budget_min];
    }
    $acc_type   = pick_option(trim($_POST['accommodation_type'] ?? ''), ['PG','Flat','Apartment','Hostel','Any'], 'Any');
    $room_type  = pick_option(trim($_POST['room_type'] ?? ''), ['Single','Double','Shared','Any'], 'Any');
    $move_in_raw = trim($_POST['move_in_date'] ?? '');
    $moveDate   = $move_in_raw !== '' ? DateTime::createFromFormat('!Y-m-d', $move_in_raw) : null;
    $dateValid  = $move_in_raw === '' || ($moveDate && $moveDate->format('Y-m-d') === $move_in_raw);
    $move_in    = ($move_in_raw !== '' && $dateValid) ? $move_in_raw : null;
    $people     = min(20, max(1, (int)($_POST['people_required'] ?? 1)));
    $gender_pref = pick_option(trim($_POST['gender_preference'] ?? ''), ['Male','Female','Any'], 'Any');
    $age_min    = min(80, max(16, (int)($_POST['preferred_age_min'] ?? 18)));
    $age_max    = min(80, max(16, (int)($_POST['preferred_age_max'] ?? 45)));
    if ($age_min > $age_max) {
        [$age_min, $age_max] = [$age_max, $age_min];
    }
    $food       = pick_option(trim($_POST['food_preference'] ?? ''), ['Vegetarian','Non-Vegetarian','Both'], 'Both');
    $smoking    = pick_option(trim($_POST['smoking'] ?? ''), ['Yes','No','Occasionally'], 'No');
    $drinking   = pick_option(trim($_POST['drinking'] ?? ''), ['Yes','No','Occasionally'], 'No');
    $work_study = pick_option(trim($_POST['work_study_status'] ?? ''), ['Working','Studying','Other'], 'Working');
    $additional = trim($_POST['additional_requirements'] ?? '');
    $lifestyle  = trim($_POST['lifestyle_prefs'] ?? '');

    if (!$city) {
        $msg = 'Please enter your preferred city.';
        $msgType = 'danger';
    } elseif (!$dateValid) {
        $msg = 'Please enter a valid move-in date.';
        $msgType = 'danger';
    } else {
        // Update the user's existing preference or insert a new one
        $check = mysqli_prepare($con, "SELECT id FROM roommate_preferences WHERE user_id = ? LIMIT 1");
        mysqli_stmt_bind_param($check, 'i', $uid);
        mysqli_stmt_execute($check);
        $exists = mysqli_fetch_assoc(mysqli_stmt_get_result($check));

        $stmt = $exists
            ? mysqli_prepare($con,
                "UPDATE roommate_preferences SET preferred_city=?,preferred_locality=?,budget_min=?,budget_max=?,accommodation_type=?,room_type=?,move_in_date=?,people_required=?,gender_preference=?,preferred_age_min=?,preferred_age_max=?,food_preference=?,smoking=?,drinking=?,work_study_status=?,additional_requirements=?,lifestyle_prefs=?,is_active=1,updated_at=NOW() WHERE user_id=?"
              )
            : mysqli_prepare($con,
                "INSERT INTO roommate_preferences (preferred_city,preferred_locality,budget_min,budget_max,accommodation_type,room_type,move_in_date,people_required,gender_preference,preferred_age_min,preferred_age_max,food_preference,smoking,drinking,work_study_status,additional_requirements,lifestyle_prefs,user_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"
              );

        mysqli_stmt_bind_param($stmt, 'ssiisssisiissssssi',
            $city,$locality,$budget_min,$budget_max,$acc_type,$room_type,$move_in,$people,
            $gender_pref,$age_min,$age_max,$food,$smoking,$drinking,$work_study,$additional,$lifestyle,$uid
        );

        if (mysqli_stmt_execute($stmt)) {
            redirect_to('my-matches.php?success=preference');
        } else {
            error_log('Roommate preference save failed: ' . mysqli_error($con));
            $msg = 'Something went wrong. Please try again.';
            $msgType = 'danger';
        }
    }
}

// Load existing preference if any
$pcheck = mysqli_prepare($con, "SELECT * FROM roommate_preferences WHERE user_id = ? LIMIT 1");
mysqli_stmt_bind_param($pcheck, 'i', $uid);
mysqli_stmt_execute($pcheck);
$existingPref = mysqli_fetch_assoc(mysqli_stmt_get_result($pcheck));
?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
	<?php include __DIR__ . '/includes/head.php'; ?>
    <title>I Need a Room – UrbanNest</title>
    <meta name="description" content="Post your roommate requirements and find someone to search and rent a room with you.">
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
        <div class="alert alert-<?= esc($msgType) ?> alert-dismissible fade show rounded-3">
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
            <?= csrf_field() ?>

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
                               value="<?= esc($existingPref['budget_min'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Max Budget / person (₹)</label>
                        <input type="number" name="budget_max" class="form-control" placeholder="12000" min="0"
                               value="<?= esc($existingPref['budget_max'] ?? '') ?>">
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
                               value="<?= esc($existingPref['move_in_date'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">People Required (incl. you)</label>
                        <input type="number" name="people_required" class="form-control" min="1" max="10"
                               value="<?= esc($existingPref['people_required'] ?? 2) ?>">
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
                               value="<?= esc($existingPref['preferred_age_min'] ?? 18) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Preferred Age – Max</label>
                        <input type="number" name="preferred_age_max" class="form-control" min="16" max="80"
                               value="<?= esc($existingPref['preferred_age_max'] ?? 35) ?>">
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
</body>
</html>
