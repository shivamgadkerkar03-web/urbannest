<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
include_once('includes/roommate-functions.php');

if (empty($_SESSION['pgasuid'])) { header('location: signin.php'); exit; }
$uid = (int)$_SESSION['pgasuid'];

// Flash message
$flash = $_SESSION['rm_flash'] ?? null;
unset($_SESSION['rm_flash']);

$success = trim($_GET['success'] ?? '');
$myProfile  = getRoommateProfile($con, $uid);

// My listings
$myListings = [];
$lq = mysqli_prepare($con, "SELECT * FROM roommate_listings WHERE user_id = ? ORDER BY created_at DESC");
mysqli_stmt_bind_param($lq, 'i', $uid);
mysqli_stmt_execute($lq);
$lr = mysqli_stmt_get_result($lq);
while ($row = mysqli_fetch_assoc($lr)) $myListings[] = $row;

// My preference
$myPref = null;
$pq = mysqli_prepare($con, "SELECT * FROM roommate_preferences WHERE user_id = ? LIMIT 1");
mysqli_stmt_bind_param($pq, 'i', $uid);
mysqli_stmt_execute($pq);
$pr = mysqli_stmt_get_result($pq);
$myPref = mysqli_fetch_assoc($pr);

// Received requests
$received = [];
$rq = mysqli_prepare($con, "SELECT rr.*, u.FullName as sender_name, prof.profile_photo FROM roommate_requests rr JOIN tbluser u ON rr.sender_id = u.ID LEFT JOIN roommate_profiles prof ON prof.user_id = rr.sender_id WHERE rr.receiver_id = ? ORDER BY rr.created_at DESC");
mysqli_stmt_bind_param($rq, 'i', $uid);
mysqli_stmt_execute($rq);
$rr2 = mysqli_stmt_get_result($rq);
while ($row = mysqli_fetch_assoc($rr2)) $received[] = $row;

// Sent requests
$sent = [];
$sq = mysqli_prepare($con, "SELECT rr.*, u.FullName as receiver_name, prof.profile_photo FROM roommate_requests rr JOIN tbluser u ON rr.receiver_id = u.ID LEFT JOIN roommate_profiles prof ON prof.user_id = rr.receiver_id WHERE rr.sender_id = ? ORDER BY rr.created_at DESC");
mysqli_stmt_bind_param($sq, 'i', $uid);
mysqli_stmt_execute($sq);
$sr2 = mysqli_stmt_get_result($sq);
while ($row = mysqli_fetch_assoc($sr2)) $sent[] = $row;

// Accepted connections
$connections = [];
$cq = mysqli_prepare($con, "SELECT rr.*, 
    CASE WHEN rr.sender_id = ? THEN rr.receiver_id ELSE rr.sender_id END as other_id,
    CASE WHEN rr.sender_id = ? THEN u2.FullName ELSE u1.FullName END as other_name,
    CASE WHEN rr.sender_id = ? THEN p2.profile_photo ELSE p1.profile_photo END as other_photo
FROM roommate_requests rr 
JOIN tbluser u1 ON rr.sender_id = u1.ID 
JOIN tbluser u2 ON rr.receiver_id = u2.ID
LEFT JOIN roommate_profiles p1 ON p1.user_id = rr.sender_id
LEFT JOIN roommate_profiles p2 ON p2.user_id = rr.receiver_id
WHERE rr.status = 'Accepted' AND (rr.sender_id = ? OR rr.receiver_id = ?)
ORDER BY rr.updated_at DESC");
mysqli_stmt_bind_param($cq, 'iiiii', $uid,$uid,$uid,$uid,$uid);
mysqli_stmt_execute($cq);
$cr2 = mysqli_stmt_get_result($cq);
while ($row = mysqli_fetch_assoc($cr2)) $connections[] = $row;

// Handle listing delete/toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = trim($_POST['action'] ?? '');
    $lid    = (int)($_POST['listing_id'] ?? 0);
    
    if ($action === 'delete_listing' && $lid) {
        $stmt = mysqli_prepare($con, "DELETE FROM roommate_listings WHERE id = ? AND user_id = ?");
        mysqli_stmt_bind_param($stmt, 'ii', $lid, $uid);
        mysqli_stmt_execute($stmt);
        header('location: my-matches.php?success=deleted');
        exit;
    }
    if ($action === 'toggle_listing' && $lid) {
        $stmt = mysqli_prepare($con, "UPDATE roommate_listings SET is_active = NOT is_active WHERE id = ? AND user_id = ?");
        mysqli_stmt_bind_param($stmt, 'ii', $lid, $uid);
        mysqli_stmt_execute($stmt);
        header('location: my-matches.php');
        exit;
    }
}

$pendingReceived = array_filter($received, fn($r) => $r['status'] === 'Pending');
?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>My Matches – UrbanNest</title>
    <meta name="description" content="Manage your roommate connections, requests, and listings on UrbanNest.">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,400,300,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="css/linearicons.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .matches-hero { background: linear-gradient(135deg, #1a1a2e, #0f3460); color:#fff; padding: 55px 0 35px; }
        .matches-hero h1 { font-size: 2rem; font-weight: 700; }
        .dash-card { border-radius: 14px; border: none; box-shadow: 0 3px 16px rgba(0,0,0,.08); margin-bottom: 24px; overflow: hidden; }
        .dash-card .card-header { background: linear-gradient(135deg, #ff3333, #c0392b); color:#fff; font-weight: 700; padding: 14px 20px; font-size: 1rem; border-radius: 0; }
        .dash-card .card-header.blue { background: linear-gradient(135deg, #1a1a2e, #0f3460); }
        .dash-card .card-header.green { background: linear-gradient(135deg, #27ae60, #1e8449); }
        .dash-card .card-header.orange { background: linear-gradient(135deg, #e67e22, #d35400); }
        .dash-card .card-header.purple { background: linear-gradient(135deg, #6c3483, #5b2c6f); }
        .mini-profile { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-bottom: 1px solid #f0f0f0; }
        .mini-profile:last-child { border-bottom: none; }
        .mini-avatar { width: 44px; height: 44px; border-radius: 50%; object-fit: cover; }
        .badge-status { font-size: .72rem; padding: 4px 10px; border-radius: 20px; }
        .stat-card { border-radius: 12px; padding: 20px; text-align: center; color: #fff; }
        .stat-card h2 { font-size: 2.2rem; font-weight: 800; margin: 0; }
        .stat-card p  { margin: 0; opacity: .85; font-size: .9rem; }
        .listing-row { display: flex; align-items: center; gap: 12px; padding: 14px 18px; border-bottom: 1px solid #f5f5f5; }
        .listing-row:last-child { border-bottom: none; }
        .listing-thumb { width: 70px; height: 50px; border-radius: 8px; object-fit: cover; }
        .empty-mini { text-align: center; padding: 24px; color: #aaa; font-size: .9rem; }
        .profile-summary { background: linear-gradient(135deg, #fff9f0, #fff3e0); border-radius: 12px; padding: 20px; border: 1px solid #fde8c8; }
    </style>
</head>
<body>
<?php include_once('includes/header.php'); ?>

<div class="matches-hero">
    <div class="container">
        <h1>🎯 My Matches Dashboard</h1>
        <p style="opacity:.8;">Manage your roommate listings, connections, and requests</p>
    </div>
</div>

<section class="section-gap">
    <div class="container">

        <!-- Flash -->
        <?php if ($flash): ?>
        <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show rounded-3 mb-4">
            <?= esc($flash['msg']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4">
            <?php 
            $msgs = ['listing'=>'Your listing has been published!','preference'=>'Your requirement has been posted!','deleted'=>'Listing deleted.'];
            echo $msgs[$success] ?? 'Action completed.'; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Stats Row -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="stat-card" style="background:linear-gradient(135deg,#ff3333,#c0392b);">
                    <h2><?= count($myListings) ?></h2><p>My Listings</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card" style="background:linear-gradient(135deg,#27ae60,#1e8449);">
                    <h2><?= count($connections) ?></h2><p>Connections</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card" style="background:linear-gradient(135deg,#e67e22,#d35400);">
                    <h2><?= count($pendingReceived) ?></h2><p>Pending Requests</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card" style="background:linear-gradient(135deg,#6c3483,#5b2c6f);">
                    <h2><?= count($sent) ?></h2><p>Sent Requests</p>
                </div>
            </div>
        </div>

        <div class="row g-4">

            <!-- Left Column -->
            <div class="col-lg-8">

                <!-- My Roommate Profile -->
                <div class="dash-card card">
                    <div class="card-header blue d-flex justify-content-between align-items-center">
                        <span>👤 My Roommate Profile</span>
                        <a href="roommate-profile.php" class="btn btn-sm btn-light rounded-pill">Edit Profile</a>
                    </div>
                    <div class="card-body">
                        <?php if ($myProfile): ?>
                        <div class="profile-summary">
                            <div class="d-flex align-items-center gap-3">
                                <img src="<?= getProfilePhoto($myProfile['profile_photo']) ?>" style="width:60px;height:60px;border-radius:50%;object-fit:cover;" onerror="this.src='img/default-profile.png'">
                                <div>
                                    <h5 class="mb-1"><?= esc($myProfile['FullName']) ?></h5>
                                    <p class="text-muted mb-1 small">
                                        <?= $myProfile['age'] ? $myProfile['age'].' yrs • ' : '' ?>
                                        <?= esc($myProfile['gender'] ?? '') ?> •
                                        <?= esc($myProfile['city'] ?? 'City not set') ?>
                                    </p>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <span class="badge bg-light text-dark">Food: <?= esc($myProfile['food_preference']) ?></span>
                                        <span class="badge bg-light text-dark">Smoking: <?= esc($myProfile['smoking']) ?></span>
                                        <span class="badge bg-light text-dark">Budget: ₹<?= number_format($myProfile['monthly_budget'] ?? 0) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="empty-mini">
                            <p>No roommate profile yet.</p>
                            <a href="roommate-profile.php" class="btn btn-danger rounded-pill btn-sm">Create Profile</a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- My Listings -->
                <div class="dash-card card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>🏠 My Room Listings (<?= count($myListings) ?>)</span>
                        <a href="have-room.php" class="btn btn-sm btn-light rounded-pill">+ Add Listing</a>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($myListings)): ?>
                        <div class="empty-mini">No listings yet. <a href="have-room.php">Post your first listing.</a></div>
                        <?php else: ?>
                        <?php foreach($myListings as $l):
                            $imgs = json_decode($l['images'] ?? '[]', true);
                            $thumb = !empty($imgs) ? 'roommate-uploads/'.esc($imgs[0]) : 'img/banner-bg.jpg';
                        ?>
                        <div class="listing-row">
                            <img src="<?= $thumb ?>" class="listing-thumb" onerror="this.src='img/banner-bg.jpg'">
                            <div class="flex-grow-1">
                                <div class="fw-bold"><?= esc($l['title']) ?></div>
                                <small class="text-muted"><?= esc($l['city']) ?> • ₹<?= number_format($l['monthly_rent']) ?>/mo</small>
                                <span class="badge ms-1 <?= $l['is_active'] ? 'bg-success' : 'bg-secondary' ?>"><?= $l['is_active'] ? 'Active' : 'Hidden' ?></span>
                            </div>
                            <div class="d-flex gap-1">
                                <a href="roommate-details.php?id=<?= $l['id'] ?>&type=listing" class="btn btn-sm btn-outline-primary rounded-pill">View</a>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="listing_id" value="<?= $l['id'] ?>">
                                    <button name="action" value="toggle_listing" class="btn btn-sm btn-outline-warning rounded-pill" type="submit"><?= $l['is_active'] ? 'Hide' : 'Show' ?></button>
                                </form>
                                <form method="POST" onsubmit="return confirm('Delete this listing?');" style="display:inline;">
                                    <input type="hidden" name="listing_id" value="<?= $l['id'] ?>">
                                    <button name="action" value="delete_listing" class="btn btn-sm btn-outline-danger rounded-pill" type="submit">Delete</button>
                                </form>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- My Room Requirement -->
                <div class="dash-card card">
                    <div class="card-header blue d-flex justify-content-between align-items-center">
                        <span>🔍 My Room Requirement</span>
                        <a href="need-room.php" class="btn btn-sm btn-light rounded-pill"><?= $myPref ? 'Edit' : 'Post' ?></a>
                    </div>
                    <div class="card-body">
                        <?php if ($myPref): ?>
                        <div class="row g-2">
                            <div class="col-sm-6"><span class="text-muted small">City:</span> <strong><?= esc($myPref['preferred_city']) ?></strong></div>
                            <div class="col-sm-6"><span class="text-muted small">Budget:</span> <strong>₹<?= number_format($myPref['budget_min']) ?> – ₹<?= number_format($myPref['budget_max']) ?></strong></div>
                            <div class="col-sm-6"><span class="text-muted small">Type:</span> <?= esc($myPref['accommodation_type']) ?></div>
                            <div class="col-sm-6"><span class="text-muted small">Move-in:</span> <?= fmtDate($myPref['move_in_date']) ?></div>
                        </div>
                        <?php else: ?>
                        <div class="empty-mini">No requirement posted. <a href="need-room.php">Post your requirement.</a></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">

                <!-- Accepted Connections -->
                <div class="dash-card card">
                    <div class="card-header green">✅ Connections (<?= count($connections) ?>)</div>
                    <div class="card-body p-0">
                        <?php if (empty($connections)): ?>
                        <div class="empty-mini">No connections yet.</div>
                        <?php else: ?>
                        <?php foreach($connections as $c): ?>
                        <div class="mini-profile">
                            <img src="<?= getProfilePhoto($c['other_photo'] ?? '') ?>" class="mini-avatar" onerror="this.src='img/default-profile.png'">
                            <div class="flex-grow-1">
                                <div class="fw-bold small"><?= esc($c['other_name']) ?></div>
                                <small class="text-muted">Connected</small>
                            </div>
                            <a href="chat.php?user=<?= $c['other_id'] ?>" class="btn btn-sm btn-success rounded-pill">Chat</a>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        <div class="p-2 text-center">
                            <a href="messages.php" class="btn btn-sm btn-outline-success rounded-pill w-100">View Messages</a>
                        </div>
                    </div>
                </div>

                <!-- Received Requests -->
                <div class="dash-card card">
                    <div class="card-header orange">📩 Received Requests (<?= count($received) ?>)</div>
                    <div class="card-body p-0">
                        <?php if (empty($received)): ?>
                        <div class="empty-mini">No requests received.</div>
                        <?php else: ?>
                        <?php foreach($received as $r): ?>
                        <div class="mini-profile">
                            <img src="<?= getProfilePhoto($r['profile_photo'] ?? '') ?>" class="mini-avatar" onerror="this.src='img/default-profile.png'">
                            <div class="flex-grow-1">
                                <div class="fw-bold small"><?= esc($r['sender_name']) ?></div>
                                <span class="badge-status badge bg-<?= $r['status']==='Pending'?'warning text-dark':($r['status']==='Accepted'?'success':'secondary') ?>"><?= esc($r['status']) ?></span>
                            </div>
                            <?php if ($r['status'] === 'Pending'): ?>
                            <div class="d-flex gap-1">
                                <a href="respond-request.php?id=<?= $r['id'] ?>&action=accept" class="btn btn-sm btn-success rounded-pill">✓</a>
                                <a href="respond-request.php?id=<?= $r['id'] ?>&action=reject" class="btn btn-sm btn-outline-danger rounded-pill">✗</a>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Sent Requests -->
                <div class="dash-card card">
                    <div class="card-header purple">📤 Sent Requests (<?= count($sent) ?>)</div>
                    <div class="card-body p-0">
                        <?php if (empty($sent)): ?>
                        <div class="empty-mini">No requests sent.</div>
                        <?php else: ?>
                        <?php foreach($sent as $s): ?>
                        <div class="mini-profile">
                            <img src="<?= getProfilePhoto($s['profile_photo'] ?? '') ?>" class="mini-avatar" onerror="this.src='img/default-profile.png'">
                            <div class="flex-grow-1">
                                <div class="fw-bold small"><?= esc($s['receiver_name']) ?></div>
                                <span class="badge-status badge bg-<?= $s['status']==='Pending'?'warning text-dark':($s['status']==='Accepted'?'success':'secondary') ?>"><?= esc($s['status']) ?></span>
                            </div>
                            <?php if ($s['status'] === 'Pending'): ?>
                            <a href="respond-request.php?id=<?= $s['id'] ?>&action=cancel" class="btn btn-sm btn-outline-secondary rounded-pill">Cancel</a>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<?php include_once('includes/footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/vendor/jquery-2.2.4.min.js"></script>
</body>
</html>
