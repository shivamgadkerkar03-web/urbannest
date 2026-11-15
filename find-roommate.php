<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
include_once('includes/roommate-functions.php');
include_once('includes/roommate-compatibility.php');

$uid = isset($_SESSION['pgasuid']) ? (int)$_SESSION['pgasuid'] : 0;
$myProfile = $uid ? getRoommateProfile($con, $uid) : null;

// --- Filters ---
$filters = [];
$search_city = trim($_GET['city'] ?? '');
$search_type = trim($_GET['type'] ?? '');
$search_tab  = trim($_GET['tab'] ?? 'listings');  // 'listings' or 'seekers'
$search_gender = trim($_GET['gender'] ?? '');
$search_budget = (int)($_GET['budget'] ?? 0);
$search_room_type = trim($_GET['room_type'] ?? '');

if ($search_city)      $filters['city']              = $search_city;
if ($search_type)      $filters['accommodation_type'] = $search_type;
if ($search_gender)    $filters['gender_preference']  = $search_gender;
if ($search_budget)    $filters['budget_max']          = $search_budget;
if ($search_room_type) $filters['room_type']           = $search_room_type;

$listings = getRoommateListings($con, $filters);
$seekers  = getRoommatePreferences($con, $filters);
?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Find Your Roommate – UrbanNest</title>
    <meta name="description" content="Find your perfect roommate in India. Browse listings, connect with people, and discover compatibility on UrbanNest.">

    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,400,300,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="css/linearicons.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <style>
        .rm-hero { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); padding: 80px 0 60px; color: #fff; }
        .rm-hero h1 { font-size: 2.6rem; font-weight: 700; margin-bottom: 10px; }
        .rm-hero p  { font-size: 1.1rem; opacity: .85; }
        .option-card { border-radius: 16px; padding: 40px 30px; text-align: center; cursor: pointer; transition: all .3s ease; border: 2px solid transparent; color: #fff; text-decoration: none; display: block; }
        .option-card:hover { transform: translateY(-6px); box-shadow: 0 20px 50px rgba(0,0,0,.2); color:#fff; }
        .option-card.have-room { background: linear-gradient(135deg, #ff3333, #c0392b); }
        .option-card.need-room { background: linear-gradient(135deg, #1a1a2e, #0f3460); border-color: #3498db; }
        .option-card .icon-big { font-size: 3.5rem; margin-bottom: 16px; display: block; }
        .option-card h3 { font-size: 1.5rem; font-weight: 700; }
        .option-card p  { opacity: .9; font-size: .95rem; }
        .filter-bar { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 4px 20px rgba(0,0,0,.08); margin-bottom: 30px; }
        .tab-toggle .btn { border-radius: 30px; padding: 8px 24px; font-weight: 600; }
        .rm-card { border-radius: 12px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,.08); border: none; transition: transform .25s ease, box-shadow .25s ease; }
        .rm-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,.14); }
        .rm-card .card-img-top { height: 200px; object-fit: cover; }
        .rm-card .badge-type { position: absolute; top: 12px; left: 12px; font-size: .75rem; padding: 5px 10px; border-radius: 20px; font-weight: 600; }
        .rm-card .card-body { padding: 18px; }
        .rm-card h5 { font-size: 1rem; font-weight: 700; margin-bottom: 4px; }
        .compatibility-bar { height: 6px; border-radius: 3px; background: #e9ecef; overflow: hidden; margin: 4px 0; }
        .compatibility-bar .fill { height: 100%; border-radius: 3px; transition: width .6s ease; }
        .connect-btn { border-radius: 30px; font-size: .85rem; font-weight: 600; padding: 6px 16px; }
        .avatar-circle { width: 60px; height: 60px; border-radius: 50%; object-fit: cover; }
        .tag-pill { font-size: .72rem; padding: 3px 10px; border-radius: 20px; background: #f0f4ff; color: #444; display: inline-block; margin: 2px; }
        .empty-state { text-align: center; padding: 60px 20px; color: #888; }
        .empty-state .icon { font-size: 4rem; color: #ddd; margin-bottom: 16px; }
    </style>
</head>
<body>
<?php include_once('includes/header.php'); ?>

<!-- Hero Section -->
<section class="rm-hero">
    <div class="container text-center">
        <h1>🏠 Find Your Perfect Roommate</h1>
        <p>Connect with compatible roommates. Browse listings or find fellow room-seekers.</p>

        <?php if ($uid): ?>
        <div class="row justify-content-center mt-5 g-4">
            <div class="col-lg-5 col-md-6">
                <a href="have-room.php" class="option-card have-room">
                    <span class="icon-big">🏡</span>
                    <h3>I Have a Room</h3>
                    <p>List your room, flat, or PG and find a compatible flatmate to share with.</p>
                    <span class="btn btn-light btn-sm mt-2 fw-bold">Post a Listing →</span>
                </a>
            </div>
            <div class="col-lg-5 col-md-6">
                <a href="need-room.php" class="option-card need-room">
                    <span class="icon-big">🔍</span>
                    <h3>I Need a Room</h3>
                    <p>No accommodation yet? Find someone to jointly search and rent with you.</p>
                    <span class="btn btn-primary btn-sm mt-2 fw-bold">Find a Roommate →</span>
                </a>
            </div>
        </div>
        <?php else: ?>
        <div class="mt-4">
            <a href="signin.php" class="btn btn-light btn-lg me-2 fw-bold">Login to Post / Connect</a>
            <a href="register.php" class="btn btn-outline-light btn-lg fw-bold">Register Free</a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Search + Listings -->
<section class="section-gap">
    <div class="container">

        <!-- Tab Toggle -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h2 class="mb-0" style="font-size:1.5rem;font-weight:700;">Browse Roommates</h2>
            <div class="tab-toggle btn-group" role="group">
                <a href="?tab=listings<?= $search_city ? '&city='.urlencode($search_city) : '' ?>" 
                   class="btn <?= $search_tab === 'seekers' ? 'btn-outline-danger' : 'btn-danger' ?>">
                   🏠 Room Listings (<?= count($listings) ?>)
                </a>
                <a href="?tab=seekers<?= $search_city ? '&city='.urlencode($search_city) : '' ?>" 
                   class="btn <?= $search_tab === 'seekers' ? 'btn-danger' : 'btn-outline-danger' ?>">
                   🔍 Room Seekers (<?= count($seekers) ?>)
                </a>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="filter-bar">
            <form method="GET" action="find-roommate.php" id="filterForm">
                <input type="hidden" name="tab" value="<?= esc($search_tab) ?>">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3 col-6">
                        <label class="form-label small fw-bold mb-1">City</label>
                        <input type="text" name="city" class="form-control form-control-sm" placeholder="e.g. Mumbai" value="<?= esc($search_city) ?>">
                    </div>
                    <div class="col-md-2 col-6">
                        <label class="form-label small fw-bold mb-1">Type</label>
                        <select name="type" class="form-select form-select-sm">
                            <option value="">All Types</option>
                            <?php foreach(['PG','Flat','Apartment','Hostel'] as $t): ?>
                            <option value="<?= $t ?>" <?= $search_type === $t ? 'selected' : '' ?>><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2 col-6">
                        <label class="form-label small fw-bold mb-1">Gender Pref</label>
                        <select name="gender" class="form-select form-select-sm">
                            <option value="">Any Gender</option>
                            <option value="Male"   <?= $search_gender === 'Male'   ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= $search_gender === 'Female' ? 'selected' : '' ?>>Female</option>
                        </select>
                    </div>
                    <div class="col-md-2 col-6">
                        <label class="form-label small fw-bold mb-1">Max Budget (₹)</label>
                        <input type="number" name="budget" class="form-control form-control-sm" placeholder="e.g. 10000" value="<?= $search_budget ?: '' ?>">
                    </div>
                    <div class="col-md-2 col-6">
                        <label class="form-label small fw-bold mb-1">Room Type</label>
                        <select name="room_type" class="form-select form-select-sm">
                            <option value="">All</option>
                            <?php foreach(['Single','Double','Shared'] as $rt): ?>
                            <option value="<?= $rt ?>" <?= $search_room_type === $rt ? 'selected' : '' ?>><?= $rt ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-1 col-6">
                        <button type="submit" class="btn btn-danger btn-sm w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- LISTINGS TAB -->
        <?php if ($search_tab !== 'seekers'): ?>
        <?php if (empty($listings)): ?>
        <div class="empty-state">
            <div class="icon">🏠</div>
            <h4>No listings found</h4>
            <p>Try adjusting your filters or <a href="have-room.php">be the first to post a listing</a>.</p>
        </div>
        <?php else: ?>
        <div class="row g-4">
            <?php foreach($listings as $l):
                $imgs = json_decode($l['images'] ?? '[]', true);
                $mainImg = !empty($imgs) ? 'roommate-uploads/' . esc($imgs[0]) : 'img/default-property.jpg';
                $amenities = json_decode($l['amenities'] ?? '[]', true);
                
                // Compatibility
                $compat = ['score' => 0];
                if ($uid && $myProfile) {
                    $theirProf = getRoommateProfile($con, $l['user_id']);
                    if ($theirProf) $compat = calculateCompatibility($myProfile, $theirProf);
                }
                
                // Request status
                $reqStatus = $uid ? getRequestStatus($con, $uid, $l['user_id']) : null;
            ?>
            <div class="col-lg-4 col-md-6">
                <div class="rm-card card h-100 position-relative">
                    <img src="<?= $mainImg ?>" class="card-img-top" alt="<?= esc($l['title']) ?>" onerror="this.src='img/banner-bg.jpg'">
                    <span class="badge bg-danger badge-type"><?= esc($l['accommodation_type']) ?></span>
                    <div class="card-body d-flex flex-column">
                        <h5><?= esc($l['title']) ?></h5>
                        <p class="text-muted small mb-1">
                            <i class="fa fa-map-marker text-danger"></i> <?= esc($l['city']) ?><?= $l['locality'] ? ', '.esc($l['locality']) : '' ?>
                        </p>
                        <p class="mb-1"><strong class="text-danger">₹<?= number_format($l['monthly_rent']) ?></strong>/month</p>
                        <div class="d-flex flex-wrap mb-2">
                            <span class="tag-pill"><?= esc($l['room_type']) ?> Room</span>
                            <span class="tag-pill"><?= esc($l['furnished']) ?></span>
                            <span class="tag-pill">For: <?= esc($l['gender_preference']) ?></span>
                        </div>
                        <?php if (!empty($amenities)): ?>
                        <div class="mb-2">
                            <?php foreach(array_slice($amenities,0,4) as $a): ?>
                            <span class="tag-pill"><i class="fa fa-check text-success"></i> <?= esc($a) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        <p class="small text-muted mb-2">Move in: <?= fmtDate($l['available_from']) ?></p>

                        <?php if ($uid && $uid != $l['user_id']): ?>
                        <!-- Compatibility -->
                        <div class="mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">Compatibility</small>
                                <small class="fw-bold text-<?= compatibilityColor($compat['score']) ?>"><?= $compat['score'] ?>%</small>
                            </div>
                            <div class="compatibility-bar">
                                <div class="fill bg-<?= compatibilityColor($compat['score']) ?>" style="width:<?= $compat['score'] ?>%"></div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="d-flex gap-2 mt-auto pt-2">
                            <a href="roommate-details.php?id=<?= $l['id'] ?>&type=listing" class="btn btn-outline-danger connect-btn flex-grow-1">View Details</a>
                            <?php if ($uid && $uid != $l['user_id']): ?>
                                <?php if ($reqStatus): ?>
                                    <span class="btn btn-sm btn-secondary connect-btn">
                                        <?= esc($reqStatus['status']) ?>
                                    </span>
                                <?php else: ?>
                                    <a href="send-request.php?to=<?= $l['user_id'] ?>&listing=<?= $l['id'] ?>" class="btn btn-danger connect-btn">Connect</a>
                                <?php endif; ?>
                            <?php elseif(!$uid): ?>
                                <a href="signin.php" class="btn btn-danger connect-btn">Login</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- SEEKERS TAB -->
        <?php else: ?>
        <?php if (empty($seekers)): ?>
        <div class="empty-state">
            <div class="icon">🔍</div>
            <h4>No room seekers found</h4>
            <p>Try adjusting your filters or <a href="need-room.php">post your own requirement</a>.</p>
        </div>
        <?php else: ?>
        <div class="row g-4">
            <?php foreach($seekers as $s):
                $photo = getProfilePhoto($s['profile_photo'] ?? '');
                $compat = ['score' => 0];
                if ($uid && $myProfile) {
                    $theirProf = getRoommateProfile($con, $s['user_id']);
                    if ($theirProf) $compat = calculateCompatibility($myProfile, $theirProf);
                }
                $reqStatus = $uid ? getRequestStatus($con, $uid, $s['user_id']) : null;
            ?>
            <div class="col-lg-4 col-md-6">
                <div class="rm-card card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <img src="<?= $photo ?>" class="avatar-circle me-3" alt="<?= esc($s['FullName']) ?>" onerror="this.src='img/default-profile.png'">
                            <div>
                                <h5 class="mb-0"><?= esc($s['FullName']) ?></h5>
                                <small class="text-muted">
                                    <?= $s['age'] ? $s['age'].' yrs • ' : '' ?><?= esc($s['gender'] ?? 'N/A') ?>
                                </small>
                            </div>
                        </div>
                        <p class="text-muted small mb-1"><i class="fa fa-map-marker text-danger"></i> <?= esc($s['preferred_city']) ?><?= $s['preferred_locality'] ? ', '.esc($s['preferred_locality']) : '' ?></p>
                        <p class="mb-1">Budget: <strong class="text-danger">₹<?= number_format($s['budget_min']) ?> – ₹<?= number_format($s['budget_max']) ?></strong>/month</p>
                        <div class="d-flex flex-wrap mb-2">
                            <span class="tag-pill"><?= esc($s['accommodation_type']) ?></span>
                            <span class="tag-pill"><?= esc($s['room_type']) ?> Room</span>
                            <span class="tag-pill"><?= esc($s['food_preference'] ?? 'N/A') ?></span>
                            <span class="tag-pill">Smoke: <?= esc($s['smoking'] ?? 'N/A') ?></span>
                        </div>
                        <p class="small text-muted">Move-in: <?= fmtDate($s['move_in_date']) ?></p>
                        <?php if($s['additional_requirements']): ?>
                        <p class="small text-muted mb-2"><?= esc(substr($s['additional_requirements'],0,80)) ?>...</p>
                        <?php endif; ?>

                        <?php if ($uid && $uid != $s['user_id']): ?>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">Compatibility</small>
                                <small class="fw-bold text-<?= compatibilityColor($compat['score']) ?>"><?= $compat['score'] ?>%</small>
                            </div>
                            <div class="compatibility-bar">
                                <div class="fill bg-<?= compatibilityColor($compat['score']) ?>" style="width:<?= $compat['score'] ?>%"></div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="d-flex gap-2 mt-auto pt-2">
                            <a href="roommate-details.php?id=<?= $s['user_id'] ?>&type=seeker" class="btn btn-outline-danger connect-btn flex-grow-1">View Profile</a>
                            <?php if ($uid && $uid != $s['user_id']): ?>
                                <?php if ($reqStatus): ?>
                                    <span class="btn btn-sm btn-secondary connect-btn"><?= esc($reqStatus['status']) ?></span>
                                <?php else: ?>
                                    <a href="send-request.php?to=<?= $s['user_id'] ?>" class="btn btn-danger connect-btn">Connect</a>
                                <?php endif; ?>
                            <?php elseif(!$uid): ?>
                                <a href="signin.php" class="btn btn-danger connect-btn">Login</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <?php endif; ?>

    </div>
</section>

<?php include_once('includes/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/vendor/jquery-2.2.4.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
