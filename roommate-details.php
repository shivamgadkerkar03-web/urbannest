<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
include_once('includes/roommate-functions.php');
include_once('includes/roommate-compatibility.php');

$uid = isset($_SESSION['pgasuid']) ? (int)$_SESSION['pgasuid'] : 0;
$myProfile = $uid ? getRoommateProfile($con, $uid) : null;

$type = trim($_GET['type'] ?? 'listing'); // 'listing' or 'seeker'
$id   = (int)($_GET['id'] ?? 0);

$data = null;
$ownerProfile = null;
$ownerPref    = null;
$compat       = ['score' => 0, 'breakdown' => []];

if ($type === 'listing') {
    $stmt = mysqli_prepare($con, "SELECT rl.*, u.FullName, u.Email FROM roommate_listings rl JOIN tbluser u ON rl.user_id = u.ID WHERE rl.id = ? AND rl.is_active = 1 LIMIT 1");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $r = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($r);
    if ($data) {
        $ownerProfile = getRoommateProfile($con, $data['user_id']);
        if ($uid && $myProfile && $ownerProfile) {
            $compat = calculateCompatibility($myProfile, $ownerProfile);
        }
    }
} else {
    // seeker: $id is user_id
    $stmt = mysqli_prepare($con, "SELECT * FROM tbluser WHERE ID = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $r = mysqli_stmt_get_result($stmt);
    $urow = mysqli_fetch_assoc($r);
    if ($urow) {
        $ownerProfile = getRoommateProfile($con, $id);
        $pref_q = mysqli_prepare($con, "SELECT * FROM roommate_preferences WHERE user_id = ? LIMIT 1");
        mysqli_stmt_bind_param($pref_q, 'i', $id);
        mysqli_stmt_execute($pref_q);
        $pr = mysqli_stmt_get_result($pref_q);
        $ownerPref = mysqli_fetch_assoc($pr);
        $data = array_merge($urow, $ownerPref ?? [], $ownerProfile ?? []);
        if ($uid && $myProfile && $ownerProfile) {
            $compat = calculateCompatibility($myProfile, $ownerProfile, $ownerPref);
        }
    }
}

if (!$data) {
    echo "<script>alert('Listing not found.');window.location='find-roommate.php';</script>";
    exit;
}

$targetUserId = $data['user_id'] ?? $id;
$reqStatus = $uid ? getRequestStatus($con, $uid, $targetUserId) : null;
$connected = $uid ? areConnected($con, $uid, $targetUserId) : false;
$photos    = json_decode($data['images'] ?? '[]', true);
?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= esc($data['title'] ?? ($data['FullName'].' – Roommate')) ?> – UrbanNest</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,400,300,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="css/linearicons.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .detail-hero { background: linear-gradient(135deg, #1a1a2e 0%, #0f3460 100%); padding: 50px 0 30px; color:#fff; }
        .detail-card { border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,.10); border: none; padding: 30px; margin-bottom: 24px; }
        .info-grid dt { font-weight: 600; color: #555; font-size: .88rem; }
        .info-grid dd { font-size: .95rem; margin-bottom: 12px; }
        .tag-pill { font-size: .78rem; padding: 4px 12px; border-radius: 20px; background: #f0f4ff; color: #444; display: inline-block; margin: 3px; }
        .compat-ring { width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.2rem; color:#fff; flex-shrink: 0; }
        .factor-bar { height: 8px; border-radius: 4px; background: #e9ecef; overflow: hidden; }
        .factor-bar .fill { height: 100%; border-radius: 4px; }
        .photo-gallery img { width: 100%; height: 220px; object-fit: cover; border-radius: 12px; cursor: pointer; }
        .avatar-lg { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #fff; box-shadow: 0 2px 12px rgba(0,0,0,.15); }
        .action-btn { border-radius: 30px; padding: 10px 28px; font-weight: 700; }
    </style>
</head>
<body>
<?php include_once('includes/header.php'); ?>

<div class="detail-hero">
    <div class="container">
        <p style="opacity:.6;font-size:.85rem;"><a href="find-roommate.php" style="color:rgba(255,255,255,.6)">Find Roommate</a> &rsaquo; Details</p>
        <?php if ($type === 'listing'): ?>
        <h1 style="font-size:1.9rem;font-weight:700;"><?= esc($data['title']) ?></h1>
        <p style="opacity:.8;"><i class="fa fa-map-marker me-1"></i><?= esc($data['city']) ?><?= $data['locality'] ? ', '.esc($data['locality']) : '' ?></p>
        <?php else: ?>
        <h1 style="font-size:1.9rem;font-weight:700;"><?= esc($data['FullName']) ?>'s Profile</h1>
        <p style="opacity:.8;">Looking for a Roommate</p>
        <?php endif; ?>
    </div>
</div>

<section class="section-gap">
    <div class="container">
        <div class="row g-4">

            <!-- Left Column: Main Info -->
            <div class="col-lg-8">

                <?php if ($type === 'listing'): ?>
                <!-- Photo Gallery -->
                <?php if (!empty($photos)): ?>
                <div class="row g-2 mb-4 photo-gallery">
                    <?php foreach($photos as $p): ?>
                    <div class="col-4">
                        <img src="roommate-uploads/<?= esc($p) ?>" alt="Room Photo" onerror="this.src='img/banner-bg.jpg'">
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Listing Details -->
                <div class="detail-card">
                    <h3 class="mb-4" style="font-weight:700;">Room Details</h3>
                    <dl class="row info-grid">
                        <dt class="col-sm-4">Accommodation Type</dt><dd class="col-sm-8"><?= esc($data['accommodation_type']) ?></dd>
                        <dt class="col-sm-4">Room Type</dt><dd class="col-sm-8"><?= esc($data['room_type']) ?></dd>
                        <dt class="col-sm-4">Monthly Rent</dt><dd class="col-sm-8"><strong class="text-danger">₹<?= number_format($data['monthly_rent']) ?>/month</strong></dd>
                        <dt class="col-sm-4">Security Deposit</dt><dd class="col-sm-8">₹<?= number_format($data['security_deposit']) ?></dd>
                        <dt class="col-sm-4">Available Spaces</dt><dd class="col-sm-8"><?= esc($data['available_spaces']) ?></dd>
                        <dt class="col-sm-4">Available From</dt><dd class="col-sm-8"><?= fmtDate($data['available_from']) ?></dd>
                        <dt class="col-sm-4">Furnishing</dt><dd class="col-sm-8"><?= esc($data['furnished']) ?></dd>
                        <dt class="col-sm-4">Gender Preferred</dt><dd class="col-sm-8"><?= esc($data['gender_preference']) ?></dd>
                        <dt class="col-sm-4">Age Range</dt><dd class="col-sm-8"><?= $data['preferred_age_min'] ?> – <?= $data['preferred_age_max'] ?> years</dd>
                        <?php if ($data['lifestyle_prefs']): ?>
                        <dt class="col-sm-4">Lifestyle Prefs</dt><dd class="col-sm-8"><?= esc($data['lifestyle_prefs']) ?></dd>
                        <?php endif; ?>
                    </dl>

                    <?php $amenities = json_decode($data['amenities'] ?? '[]', true); ?>
                    <?php if (!empty($amenities)): ?>
                    <h5 class="mt-3 mb-2">Amenities</h5>
                    <div><?php foreach($amenities as $a): ?><span class="tag-pill"><i class="fa fa-check text-success me-1"></i><?= esc($a) ?></span><?php endforeach; ?></div>
                    <?php endif; ?>

                    <?php if ($data['description']): ?>
                    <h5 class="mt-4 mb-2">About this Place</h5>
                    <p class="text-muted"><?= nl2br(esc($data['description'])) ?></p>
                    <?php endif; ?>
                </div>

                <?php else: ?>
                <!-- Seeker Profile Details -->
                <div class="detail-card">
                    <h3 class="mb-4" style="font-weight:700;">Requirements</h3>
                    <dl class="row info-grid">
                        <dt class="col-sm-4">Preferred City</dt><dd class="col-sm-8"><?= esc($data['preferred_city'] ?? 'N/A') ?></dd>
                        <dt class="col-sm-4">Preferred Locality</dt><dd class="col-sm-8"><?= esc($data['preferred_locality'] ?? 'N/A') ?></dd>
                        <dt class="col-sm-4">Budget Range</dt><dd class="col-sm-8"><strong class="text-danger">₹<?= number_format($data['budget_min'] ?? 0) ?> – ₹<?= number_format($data['budget_max'] ?? 0) ?>/mo</strong></dd>
                        <dt class="col-sm-4">Accommodation Type</dt><dd class="col-sm-8"><?= esc($data['accommodation_type'] ?? 'Any') ?></dd>
                        <dt class="col-sm-4">Room Type</dt><dd class="col-sm-8"><?= esc($data['room_type'] ?? 'Any') ?></dd>
                        <dt class="col-sm-4">Move-in Date</dt><dd class="col-sm-8"><?= fmtDate($data['move_in_date'] ?? '') ?></dd>
                        <dt class="col-sm-4">People Required</dt><dd class="col-sm-8"><?= esc($data['people_required'] ?? 1) ?></dd>
                        <dt class="col-sm-4">Gender Preference</dt><dd class="col-sm-8"><?= esc($data['gender_preference'] ?? 'Any') ?></dd>
                        <dt class="col-sm-4">Food</dt><dd class="col-sm-8"><?= esc($data['food_preference'] ?? 'N/A') ?></dd>
                        <dt class="col-sm-4">Smoking</dt><dd class="col-sm-8"><?= esc($data['smoking'] ?? 'N/A') ?></dd>
                        <dt class="col-sm-4">Drinking</dt><dd class="col-sm-8"><?= esc($data['drinking'] ?? 'N/A') ?></dd>
                        <dt class="col-sm-4">Work/Study</dt><dd class="col-sm-8"><?= esc($data['work_study_status'] ?? 'N/A') ?></dd>
                    </dl>
                    <?php if ($data['additional_requirements']): ?>
                    <h5 class="mt-3 mb-2">Additional Requirements</h5>
                    <p class="text-muted"><?= nl2br(esc($data['additional_requirements'])) ?></p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Owner/Seeker Profile -->
                <?php if ($ownerProfile): ?>
                <div class="detail-card">
                    <h4 style="font-weight:700;" class="mb-3">About <?= esc($ownerProfile['FullName'] ?? $data['FullName']) ?></h4>
                    <div class="d-flex align-items-start gap-3">
                        <img src="<?= getProfilePhoto($ownerProfile['profile_photo'] ?? '') ?>" class="avatar-lg" onerror="this.src='img/default-profile.png'">
                        <div>
                            <h5 class="mb-1"><?= esc($ownerProfile['FullName'] ?? $data['FullName']) ?></h5>
                            <p class="text-muted mb-2">
                                <?= $ownerProfile['age'] ? $ownerProfile['age'].' yrs • ' : '' ?>
                                <?= esc($ownerProfile['gender'] ?? '') ?>
                                <?= $ownerProfile['occupation'] ? ' • '.esc($ownerProfile['occupation']) : '' ?>
                            </p>
                            <?php if ($ownerProfile['about_me']): ?>
                            <p class="text-muted"><?= nl2br(esc($ownerProfile['about_me'])) ?></p>
                            <?php endif; ?>
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                <?php if ($ownerProfile['sleeping_schedule']): ?><span class="tag-pill">🌙 <?= esc($ownerProfile['sleeping_schedule']) ?></span><?php endif; ?>
                                <?php if ($ownerProfile['cleanliness']): ?><span class="tag-pill">🧹 <?= esc($ownerProfile['cleanliness']) ?></span><?php endif; ?>
                                <?php if ($ownerProfile['social_lifestyle']): ?><span class="tag-pill">🤝 <?= esc($ownerProfile['social_lifestyle']) ?></span><?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Right Column: Action + Compatibility -->
            <div class="col-lg-4">
                <!-- Action Card -->
                <div class="detail-card text-center">
                    <?php if ($uid && $uid != $targetUserId): ?>
                        <?php if ($reqStatus && $reqStatus['status'] === 'Accepted'): ?>
                            <div class="mb-3">
                                <i class="fa fa-check-circle text-success" style="font-size:2.5rem;"></i>
                                <p class="fw-bold mt-2 text-success">You are Connected!</p>
                            </div>
                            <?php $convId = getOrCreateConversation($con, $uid, $targetUserId); ?>
                            <a href="chat.php?user=<?= $targetUserId ?>" class="btn btn-success action-btn w-100 mb-2">
                                <i class="fa fa-comment me-2"></i>Send Message
                            </a>
                        <?php elseif ($reqStatus && $reqStatus['status'] === 'Pending'): ?>
                            <div class="mb-3">
                                <i class="fa fa-clock-o text-warning" style="font-size:2.5rem;"></i>
                                <p class="fw-bold mt-2 text-warning">Request Pending</p>
                            </div>
                            <?php if ($reqStatus['sender_id'] == $uid): ?>
                            <a href="cancel-request.php?id=<?= $reqStatus['id'] ?>" class="btn btn-outline-secondary action-btn w-100">Cancel Request</a>
                            <?php else: ?>
                            <a href="respond-request.php?id=<?= $reqStatus['id'] ?>&action=accept" class="btn btn-success action-btn w-100 mb-2">Accept</a>
                            <a href="respond-request.php?id=<?= $reqStatus['id'] ?>&action=reject" class="btn btn-outline-danger action-btn w-100">Decline</a>
                            <?php endif; ?>
                        <?php elseif ($reqStatus): ?>
                            <span class="badge bg-secondary p-3 mb-3"><?= esc($reqStatus['status']) ?></span>
                        <?php else: ?>
                        <a href="send-request.php?to=<?= $targetUserId ?><?= isset($data['id']) && $type==='listing' ? '&listing='.$data['id'] : '' ?>" class="btn btn-danger action-btn w-100 mb-2">
                            <i class="fa fa-user-plus me-2"></i>Connect
                        </a>
                        <?php endif; ?>
                    <?php elseif (!$uid): ?>
                        <a href="signin.php" class="btn btn-danger action-btn w-100">Login to Connect</a>
                    <?php else: ?>
                        <span class="badge bg-secondary p-3">Your Listing</span>
                    <?php endif; ?>
                    <a href="find-roommate.php" class="btn btn-outline-secondary action-btn w-100 mt-2">← Back to Listings</a>
                </div>

                <!-- Compatibility Card (only for logged-in users) -->
                <?php if ($uid && $uid != $targetUserId && $myProfile && !empty($compat['breakdown'])): ?>
                <div class="detail-card">
                    <h5 style="font-weight:700;" class="mb-3">Compatibility Score</h5>
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="compat-ring bg-<?= compatibilityColor($compat['score']) ?>">
                            <?= $compat['score'] ?>%
                        </div>
                        <div>
                            <div class="fw-bold text-<?= compatibilityColor($compat['score']) ?>"><?= compatibilityLabel($compat['score']) ?></div>
                            <small class="text-muted">Based on lifestyle factors</small>
                        </div>
                    </div>
                    <?php foreach($compat['breakdown'] as $factor => $info): ?>
                    <div class="mb-2">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted"><?= esc($factor) ?></small>
                            <small class="fw-bold"><?= $info['score'] ?>/<?= $info['max'] ?></small>
                        </div>
                        <div class="factor-bar">
                            <div class="fill bg-<?= $info['match'] ? 'success' : 'warning' ?>" style="width:<?= ($info['max'] > 0 ? round($info['score']/$info['max']*100) : 0) ?>%"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php elseif ($uid && $uid != $targetUserId && !$myProfile): ?>
                <div class="detail-card text-center">
                    <p class="text-muted small">Complete your <a href="roommate-profile.php" class="text-danger">roommate profile</a> to see compatibility.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include_once('includes/footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/vendor/jquery-2.2.4.min.js"></script>
</body>
</html>
