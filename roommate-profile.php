<?php
require_once __DIR__ . '/includes/security.php';
include('includes/dbconnection.php');
include_once('includes/roommate-functions.php');

$uid = require_user('signin.php');
$msg = '';
$msgType = 'success';

function pick_option($value, array $allowed, $default) {
    return in_array($value, $allowed, true) ? $value : $default;
}

function valid_date_or_null($value) {
    $value = trim((string)$value);
    if ($value === '') return null;
    $d = DateTime::createFromFormat('!Y-m-d', $value);
    return ($d && $d->format('Y-m-d') === $value) ? $value : null;
}

// Handle form submission
if (isset($_POST['save_profile'])) {
    csrf_check();
    $age          = (int)($_POST['age'] ?? 0);
    $age          = ($age >= 16 && $age <= 80) ? $age : null;
    $gender       = pick_option(trim($_POST['gender'] ?? ''), ['Male','Female','Other'], null);
    $occupation   = mb_substr(trim($_POST['occupation'] ?? ''), 0, 100);
    $college      = mb_substr(trim($_POST['college_company'] ?? ''), 0, 200);
    $city         = mb_substr(trim($_POST['city'] ?? ''), 0, 100);
    $locality     = mb_substr(trim($_POST['preferred_locality'] ?? ''), 0, 200);
    $budget       = max(0, (int)($_POST['monthly_budget'] ?? 0));
    $budget       = $budget > 0 ? $budget : null;
    $move_in      = valid_date_or_null($_POST['move_in_date'] ?? '');
    $food         = pick_option(trim($_POST['food_preference'] ?? ''), ['Vegetarian','Non-Vegetarian','Both'], 'Both');
    $smoking      = pick_option(trim($_POST['smoking'] ?? ''), ['Yes','No','Occasionally'], 'No');
    $drinking     = pick_option(trim($_POST['drinking'] ?? ''), ['Yes','No','Occasionally'], 'No');
    $sleeping     = pick_option(trim($_POST['sleeping_schedule'] ?? ''), ['Early Bird','Night Owl','Flexible'], 'Flexible');
    $cleanliness  = pick_option(trim($_POST['cleanliness'] ?? ''), ['Very Clean','Moderate','Relaxed'], 'Moderate');
    $social       = pick_option(trim($_POST['social_lifestyle'] ?? ''), ['Very Social','Moderate','Introvert'], 'Moderate');
    $about        = trim($_POST['about_me'] ?? '');

    // Handle photo upload
    $photo = '';
    $uploadFailed = false;
    if (!empty($_FILES['profile_photo']['name'])) {
        $fn = uploadRoommateImage($_FILES['profile_photo'], 'profile');
        if ($fn) {
            $photo = $fn;
        } else {
            $uploadFailed = true;
        }
    }

    // Check if profile exists
    $check = mysqli_prepare($con, "SELECT id, profile_photo FROM roommate_profiles WHERE user_id = ? LIMIT 1");
    mysqli_stmt_bind_param($check, 'i', $uid);
    mysqli_stmt_execute($check);
    $existing = mysqli_fetch_assoc(mysqli_stmt_get_result($check));

    if ($existing) {
        $current_photo = $photo ?: $existing['profile_photo'];
        $stmt = mysqli_prepare($con, "UPDATE roommate_profiles SET age=?,gender=?,occupation=?,college_company=?,city=?,preferred_locality=?,monthly_budget=?,move_in_date=?,food_preference=?,smoking=?,drinking=?,sleeping_schedule=?,cleanliness=?,social_lifestyle=?,about_me=?,profile_photo=?,updated_at=NOW() WHERE user_id=?");
        mysqli_stmt_bind_param($stmt, 'isssssisssssssssi',
            $age,$gender,$occupation,$college,$city,$locality,$budget,$move_in,
            $food,$smoking,$drinking,$sleeping,$cleanliness,$social,$about,$current_photo,$uid
        );
    } else {
        $current_photo = $photo ?: 'default-profile.png';
        $stmt = mysqli_prepare($con, "INSERT INTO roommate_profiles (user_id,age,gender,occupation,college_company,city,preferred_locality,monthly_budget,move_in_date,food_preference,smoking,drinking,sleeping_schedule,cleanliness,social_lifestyle,about_me,profile_photo) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        mysqli_stmt_bind_param($stmt, 'iisssssisssssssss',
            $uid,$age,$gender,$occupation,$college,$city,$locality,$budget,$move_in,
            $food,$smoking,$drinking,$sleeping,$cleanliness,$social,$about,$current_photo
        );
    }

    if (mysqli_stmt_execute($stmt)) {
        $msg = $uploadFailed
            ? 'Profile saved, but the photo could not be uploaded (JPG, PNG, WebP or GIF up to 5 MB).'
            : 'Profile updated successfully!';
        $msgType = $uploadFailed ? 'warning' : 'success';
    } else {
        error_log('Roommate profile save failed: ' . mysqli_error($con));
        $msg = 'Something went wrong. Please try again.';
        $msgType = 'danger';
    }
}

// Load profile
$profile = getRoommateProfile($con, $uid);
?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
	<?php include __DIR__ . '/includes/head.php'; ?>
    <title>My Roommate Profile – UrbanNest</title>
    <style>
        .profile-hero { background: linear-gradient(135deg, #ff3333, #c0392b); padding: 55px 0 35px; color:#fff; text-align:center; }
        .form-section { background:#fff; border-radius:16px; padding:32px; box-shadow:0 4px 20px rgba(0,0,0,.08); margin-bottom:24px; }
        .form-section h4 { font-weight:700; font-size:1rem; border-left:4px solid #ff3333; padding-left:12px; margin-bottom:18px; }
        .avatar-upload { position:relative; width:110px; margin:0 auto 20px; }
        .avatar-upload img { width:110px; height:110px; border-radius:50%; object-fit:cover; border:4px solid #fff; box-shadow:0 4px 16px rgba(0,0,0,.2); }
        .avatar-upload .edit-btn { position:absolute; bottom:0; right:0; background:#ff3333; color:#fff; border-radius:50%; width:32px; height:32px; display:flex; align-items:center; justify-content:center; cursor:pointer; border:2px solid #fff; }
        .btn-save { background:#ff3333; color:#fff; border-radius:30px; padding:12px 40px; font-weight:700; border:none; }
        .btn-save:hover { background:#c0392b; transform:translateY(-2px); }
    </style>
</head>
<body>
<?php include_once('includes/header.php'); ?>

<div class="profile-hero">
    <div class="container">
        <p style="opacity:.6;font-size:.85rem;"><a href="my-matches.php" style="color:rgba(255,255,255,.6)">My Matches</a> &rsaquo; Profile</p>
        <h1 style="font-size:1.9rem;font-weight:700;">👤 My Roommate Profile</h1>
        <p style="opacity:.85;">Complete your profile to improve roommate matching</p>
    </div>
</div>

<section class="section-gap">
    <div class="container" style="max-width:750px;">
        <?php if ($msg): ?>
        <div class="alert alert-<?= esc($msgType) ?> alert-dismissible fade show rounded-3 mb-4">
            <?= esc($msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" novalidate>
            <?= csrf_field() ?>

            <!-- Photo -->
            <div class="form-section text-center">
                <div class="avatar-upload">
                    <img src="<?= getProfilePhoto($profile['profile_photo'] ?? '') ?>" id="avatarPreview" onerror="this.src='img/default-profile.png'" alt="Profile Photo">
                    <label class="edit-btn" for="photoInput" title="Change Photo">
                        <i class="fa fa-camera"></i>
                    </label>
                </div>
                <input type="file" id="photoInput" name="profile_photo" accept="image/*" style="display:none;">
                <p class="text-muted small">Click the camera icon to change your photo</p>
                <h5 class="fw-bold"><?= esc($profile['FullName'] ?? $_SESSION['FullName'] ?? '') ?></h5>
            </div>

            <!-- Personal Info -->
            <div class="form-section">
                <h4>Personal Information</h4>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Age</label>
                        <input type="number" name="age" class="form-control" min="16" max="80" value="<?= esc($profile['age'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="">Select</option>
                            <?php foreach(['Male','Female','Other'] as $g): ?>
                            <option value="<?= $g ?>" <?= ($profile['gender']??'')===$g?'selected':'' ?>><?= $g ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Occupation</label>
                        <input type="text" name="occupation" class="form-control" placeholder="e.g. Software Engineer" value="<?= esc($profile['occupation'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">College / Company</label>
                        <input type="text" name="college_company" class="form-control" placeholder="Your college or workplace" value="<?= esc($profile['college_company'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">City</label>
                        <input type="text" name="city" class="form-control" placeholder="Current city" value="<?= esc($profile['city'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Preferred Locality</label>
                        <input type="text" name="preferred_locality" class="form-control" placeholder="Preferred area" value="<?= esc($profile['preferred_locality'] ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Monthly Budget (₹)</label>
                        <input type="number" name="monthly_budget" class="form-control" min="0" value="<?= esc($profile['monthly_budget'] ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Move-in Date</label>
                        <input type="date" name="move_in_date" class="form-control" value="<?= esc($profile['move_in_date'] ?? '') ?>">
                    </div>
                </div>
            </div>

            <!-- Lifestyle -->
            <div class="form-section">
                <h4>Lifestyle & Preferences</h4>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Food Preference</label>
                        <select name="food_preference" class="form-select">
                            <?php foreach(['Both','Vegetarian','Non-Vegetarian'] as $t): ?>
                            <option value="<?= $t ?>" <?= ($profile['food_preference']??'Both')===$t?'selected':'' ?>><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Smoking</label>
                        <select name="smoking" class="form-select">
                            <?php foreach(['No','Yes','Occasionally'] as $t): ?>
                            <option value="<?= $t ?>" <?= ($profile['smoking']??'No')===$t?'selected':'' ?>><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Drinking</label>
                        <select name="drinking" class="form-select">
                            <?php foreach(['No','Yes','Occasionally'] as $t): ?>
                            <option value="<?= $t ?>" <?= ($profile['drinking']??'No')===$t?'selected':'' ?>><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Sleeping Schedule</label>
                        <select name="sleeping_schedule" class="form-select">
                            <?php foreach(['Flexible','Early Bird','Night Owl'] as $t): ?>
                            <option value="<?= $t ?>" <?= ($profile['sleeping_schedule']??'Flexible')===$t?'selected':'' ?>><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Cleanliness</label>
                        <select name="cleanliness" class="form-select">
                            <?php foreach(['Moderate','Very Clean','Relaxed'] as $t): ?>
                            <option value="<?= $t ?>" <?= ($profile['cleanliness']??'Moderate')===$t?'selected':'' ?>><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Social Lifestyle</label>
                        <select name="social_lifestyle" class="form-select">
                            <?php foreach(['Moderate','Very Social','Introvert'] as $t): ?>
                            <option value="<?= $t ?>" <?= ($profile['social_lifestyle']??'Moderate')===$t?'selected':'' ?>><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">About Me</label>
                        <textarea name="about_me" class="form-control" rows="4" placeholder="Tell potential roommates about yourself, your lifestyle, habits, interests..."><?= esc($profile['about_me'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" name="save_profile" class="btn-save btn">Save Profile</button>
                <a href="my-matches.php" class="btn btn-outline-secondary ms-3 rounded-pill px-4">Back to Matches</a>
            </div>
        </form>
    </div>
</section>

<?php include_once('includes/footer.php'); ?>
<script>
// Photo preview
document.getElementById('photoInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('avatarPreview').src = e.target.result;
        reader.readAsDataURL(file);
    }
});
</script>
</body>
</html>
