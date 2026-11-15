<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
include_once('includes/roommate-functions.php');

if (empty($_SESSION['pgasuid'])) { header('location: signin.php'); exit; }
$uid = (int)$_SESSION['pgasuid'];
$msg = '';
$msgType = 'success';

// Handle form submission
if (isset($_POST['save_profile'])) {
    $age          = (int)($_POST['age'] ?? 0);
    $gender       = trim($_POST['gender'] ?? '');
    $occupation   = trim($_POST['occupation'] ?? '');
    $college      = trim($_POST['college_company'] ?? '');
    $city         = trim($_POST['city'] ?? '');
    $locality     = trim($_POST['preferred_locality'] ?? '');
    $budget       = (int)($_POST['monthly_budget'] ?? 0);
    $move_in      = trim($_POST['move_in_date'] ?? '');
    $food         = trim($_POST['food_preference'] ?? 'Both');
    $smoking      = trim($_POST['smoking'] ?? 'No');
    $drinking     = trim($_POST['drinking'] ?? 'No');
    $sleeping     = trim($_POST['sleeping_schedule'] ?? 'Flexible');
    $cleanliness  = trim($_POST['cleanliness'] ?? 'Moderate');
    $social       = trim($_POST['social_lifestyle'] ?? 'Moderate');
    $about        = trim($_POST['about_me'] ?? '');

    // Handle photo upload
    $photo = '';
    if (!empty($_FILES['profile_photo']['name'])) {
        $fn = uploadRoommateImage($_FILES['profile_photo'], 'profile');
        if ($fn) $photo = $fn;
    }

    // Check if profile exists
    $check = mysqli_query($con, "SELECT id, profile_photo FROM roommate_profiles WHERE user_id='$uid' LIMIT 1");
    $existing = mysqli_fetch_assoc($check);

    if ($existing) {
        $current_photo = $photo ?: $existing['profile_photo'];
        $stmt = mysqli_prepare($con, "UPDATE roommate_profiles SET age=?,gender=?,occupation=?,college_company=?,city=?,preferred_locality=?,monthly_budget=?,move_in_date=?,food_preference=?,smoking=?,drinking=?,sleeping_schedule=?,cleanliness=?,social_lifestyle=?,about_me=?,profile_photo=?,updated_at=NOW() WHERE user_id=?");
        mysqli_stmt_bind_param($stmt, 'isssssisdsssssssi',
            $age,$gender,$occupation,$college,$city,$locality,$budget,$move_in,
            $food,$smoking,$drinking,$sleeping,$cleanliness,$social,$about,$current_photo,$uid
        );
    } else {
        $current_photo = $photo ?: 'default-profile.png';
        $stmt = mysqli_prepare($con, "INSERT INTO roommate_profiles (user_id,age,gender,occupation,college_company,city,preferred_locality,monthly_budget,move_in_date,food_preference,smoking,drinking,sleeping_schedule,cleanliness,social_lifestyle,about_me,profile_photo) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        mysqli_stmt_bind_param($stmt, 'iisssssidssssssss',
            $uid,$age,$gender,$occupation,$college,$city,$locality,$budget,$move_in,
            $food,$smoking,$drinking,$sleeping,$cleanliness,$social,$about,$current_photo
        );
    }

    if (mysqli_stmt_execute($stmt)) {
        $msg = 'Profile updated successfully!';
        $msgType = 'success';
    } else {
        $msg = 'Something went wrong: ' . mysqli_error($con);
        $msgType = 'danger';
    }
}

// Load profile
$profile = getRoommateProfile($con, $uid);
?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>My Roommate Profile – UrbanNest</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,400,300,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="css/linearicons.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <div class="alert alert-<?= $msgType ?> alert-dismissible fade show rounded-3 mb-4">
            <?= esc($msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" novalidate>

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
                        <input type="number" name="age" class="form-control" min="16" max="80" value="<?= $profile['age'] ?? '' ?>">
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
                        <input type="number" name="monthly_budget" class="form-control" min="0" value="<?= $profile['monthly_budget'] ?? '' ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Move-in Date</label>
                        <input type="date" name="move_in_date" class="form-control" value="<?= $profile['move_in_date'] ?? '' ?>">
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/vendor/jquery-2.2.4.min.js"></script>
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
