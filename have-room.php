<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
include_once('includes/roommate-functions.php');

// Auth check
if (empty($_SESSION['pgasuid'])) {
    header('location: signin.php');
    exit;
}
$uid = (int)$_SESSION['pgasuid'];
$msg = '';
$msgType = 'success';

// Handle form submission
if (isset($_POST['submit_listing'])) {
    $title      = trim($_POST['title'] ?? '');
    $city       = trim($_POST['city'] ?? '');
    $locality   = trim($_POST['locality'] ?? '');
    $address    = trim($_POST['full_address'] ?? '');
    $rent       = (int)($_POST['monthly_rent'] ?? 0);
    $deposit    = (int)($_POST['security_deposit'] ?? 0);
    $room_type  = trim($_POST['room_type'] ?? 'Single');
    $spaces     = (int)($_POST['available_spaces'] ?? 1);
    $acc_type   = trim($_POST['accommodation_type'] ?? 'PG');
    $avail_from = trim($_POST['available_from'] ?? '');
    $furnished  = trim($_POST['furnished'] ?? 'Unfurnished');
    $amenities  = isset($_POST['amenities']) ? json_encode($_POST['amenities']) : '[]';
    $gender_pref = trim($_POST['gender_preference'] ?? 'Any');
    $age_min    = (int)($_POST['preferred_age_min'] ?? 18);
    $age_max    = (int)($_POST['preferred_age_max'] ?? 45);
    $lifestyle  = trim($_POST['lifestyle_prefs'] ?? '');
    $description = trim($_POST['description'] ?? '');

    // Validation
    if (!$title || !$city || !$rent) {
        $msg = 'Please fill in Title, City, and Monthly Rent.';
        $msgType = 'danger';
    } else {
        // Handle image uploads
        $imageArr = [];
        if (!empty($_FILES['images']['name'][0])) {
            foreach($_FILES['images']['name'] as $k => $name) {
                if (!empty($name)) {
                    $file = [
                        'name'     => $_FILES['images']['name'][$k],
                        'type'     => $_FILES['images']['type'][$k],
                        'tmp_name' => $_FILES['images']['tmp_name'][$k],
                        'error'    => $_FILES['images']['error'][$k],
                        'size'     => $_FILES['images']['size'][$k],
                    ];
                    $fn = uploadRoommateImage($file, 'listing');
                    if ($fn) $imageArr[] = $fn;
                }
            }
        }
        $images_json = json_encode($imageArr);

        $stmt = mysqli_prepare($con,
            "INSERT INTO roommate_listings (user_id,title,city,locality,full_address,monthly_rent,security_deposit,room_type,available_spaces,accommodation_type,available_from,furnished,amenities,gender_preference,preferred_age_min,preferred_age_max,lifestyle_prefs,description,images) 
             VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"
        );
        mysqli_stmt_bind_param($stmt, 'issssiisisssssiiiss',
            $uid, $title, $city, $locality, $address, $rent, $deposit, $room_type, $spaces,
            $acc_type, $avail_from, $furnished, $amenities, $gender_pref,
            $age_min, $age_max, $lifestyle, $description, $images_json
        );

        if (mysqli_stmt_execute($stmt)) {
            header('location: my-matches.php?success=listing');
            exit;
        } else {
            $msg = 'Something went wrong. Please try again.';
            $msgType = 'danger';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>I Have a Room – UrbanNest</title>
    <meta name="description" content="Post your room, flat, or PG listing on UrbanNest to find a perfect roommate.">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,400,300,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="css/linearicons.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .page-hero { background: linear-gradient(135deg, #c0392b, #ff3333); padding: 60px 0 40px; color: #fff; text-align: center; }
        .form-section { background: #fff; border-radius: 16px; padding: 36px; box-shadow: 0 4px 24px rgba(0,0,0,.09); margin-bottom: 30px; }
        .form-section h4 { font-weight: 700; font-size: 1.1rem; border-left: 4px solid #ff3333; padding-left: 12px; margin-bottom: 20px; }
        .amenity-check label { cursor: pointer; padding: 6px 14px; border-radius: 20px; border: 1px solid #dee2e6; font-size: .85rem; transition: all .2s; }
        .amenity-check input:checked + label { background: #ff3333; color: #fff; border-color: #ff3333; }
        .amenity-check input { display: none; }
        .btn-submit { background: #ff3333; color: #fff; border-radius: 30px; padding: 12px 40px; font-weight: 700; font-size: 1rem; border: none; transition: all .3s; }
        .btn-submit:hover { background: #c0392b; transform: translateY(-2px); }
        .preview-img { width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 2px solid #dee2e6; margin: 4px; }
    </style>
</head>
<body>
<?php include_once('includes/header.php'); ?>

<div class="page-hero">
    <div class="container">
        <p class="text-white-50 mb-1"><a href="find-roommate.php" class="text-white-50">Find Roommate</a> &rsaquo; Post Listing</p>
        <h1 style="font-size:2rem;font-weight:700;">🏡 I Have a Room</h1>
        <p style="opacity:.85;">Post your room listing and find the perfect flatmate</p>
    </div>
</div>

<section class="section-gap">
    <div class="container" style="max-width:800px;">
        <?php if ($msg): ?>
        <div class="alert alert-<?= $msgType ?> alert-dismissible fade show rounded-3" role="alert">
            <?= esc($msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" novalidate>

            <!-- Basic Info -->
            <div class="form-section">
                <h4>Basic Information</h4>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-bold">Listing Title *</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Furnished 2BHK in Andheri" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">City *</label>
                        <input type="text" name="city" class="form-control" placeholder="e.g. Mumbai" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Locality</label>
                        <input type="text" name="locality" class="form-control" placeholder="e.g. Andheri West">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Full Address</label>
                        <textarea name="full_address" class="form-control" rows="2" placeholder="Approximate address or landmark"></textarea>
                    </div>
                </div>
            </div>

            <!-- Pricing & Availability -->
            <div class="form-section">
                <h4>Pricing & Availability</h4>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Monthly Rent (₹) *</label>
                        <input type="number" name="monthly_rent" class="form-control" placeholder="8000" min="0" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Security Deposit (₹)</label>
                        <input type="number" name="security_deposit" class="form-control" placeholder="0" min="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Available From</label>
                        <input type="date" name="available_from" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Accommodation Type</label>
                        <select name="accommodation_type" class="form-select">
                            <?php foreach(['PG','Flat','Apartment','Hostel'] as $t): ?>
                            <option value="<?= $t ?>"><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Room Type</label>
                        <select name="room_type" class="form-select">
                            <?php foreach(['Single','Double','Shared'] as $t): ?>
                            <option value="<?= $t ?>"><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Available Spaces</label>
                        <input type="number" name="available_spaces" class="form-control" value="1" min="1" max="20">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Furnishing</label>
                        <select name="furnished" class="form-select">
                            <?php foreach(['Fully Furnished','Semi-Furnished','Unfurnished'] as $t): ?>
                            <option value="<?= $t ?>"><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Amenities -->
            <div class="form-section">
                <h4>Amenities</h4>
                <div class="d-flex flex-wrap gap-2">
                    <?php foreach(['Wi-Fi','AC','Kitchen','Washing Machine','Parking','Refrigerator','Water Purifier','Power Backup','CCTV','Housekeeping','TV','Geyser'] as $a): ?>
                    <div class="amenity-check">
                        <input type="checkbox" name="amenities[]" id="am_<?= str_replace(' ','_',$a) ?>" value="<?= $a ?>">
                        <label for="am_<?= str_replace(' ','_',$a) ?>">
                            <i class="fa fa-check me-1"></i><?= $a ?>
                        </label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Preferences -->
            <div class="form-section">
                <h4>Roommate Preferences</h4>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Gender Preference</label>
                        <select name="gender_preference" class="form-select">
                            <option value="Any">Any</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Preferred Age – Min</label>
                        <input type="number" name="preferred_age_min" class="form-control" value="18" min="16" max="80">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Preferred Age – Max</label>
                        <input type="number" name="preferred_age_max" class="form-control" value="35" min="16" max="80">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Lifestyle Preferences</label>
                        <input type="text" name="lifestyle_prefs" class="form-control" placeholder="e.g. Non-smoker, vegetarian preferred, students OK">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Describe the room, surroundings, house rules, etc."></textarea>
                    </div>
                </div>
            </div>

            <!-- Images -->
            <div class="form-section">
                <h4>Photos</h4>
                <input type="file" name="images[]" id="imageUpload" class="form-control" multiple accept="image/*">
                <small class="text-muted">Upload up to 5 images. Max 5MB each. JPG, PNG, WebP accepted.</small>
                <div id="imagePreview" class="mt-3 d-flex flex-wrap"></div>
            </div>

            <div class="text-center">
                <button type="submit" name="submit_listing" class="btn-submit btn">🏡 Publish Listing</button>
                <a href="find-roommate.php" class="btn btn-outline-secondary ms-3 rounded-pill px-4">Cancel</a>
            </div>
        </form>
    </div>
</section>

<?php include_once('includes/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/vendor/jquery-2.2.4.min.js"></script>
<script>
// Image preview
document.getElementById('imageUpload').addEventListener('change', function(e) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    Array.from(e.target.files).slice(0,5).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'preview-img';
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
});
</script>
</body>
</html>
