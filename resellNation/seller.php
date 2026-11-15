<?php
session_start();
include_once('connect.php');

$msg = '';
$err = '';

if (isset($_POST["submit"])) {
    if (empty($_SESSION['pgasuid'])) {
        $err = "You must be signed in to post an item.";
    } else {
        $productName = mysqli_real_escape_string($con, $_POST["productName"]);
        $category = mysqli_real_escape_string($con, $_POST["category"]);
        $brand = mysqli_real_escape_string($con, $_POST["brand"]);
        $size = mysqli_real_escape_string($con, $_POST["size"]);
        $description = mysqli_real_escape_string($con, $_POST["description"]);
        $price = (int)$_POST["price"];
        $location = mysqli_real_escape_string($con, $_POST["location"]);

        if (empty($_FILES["images"]["name"])) {
            $err = "Please select an image for your listing.";
        } else {
            $targetDir = "upload/";
            $fileName = $_FILES["images"]["name"];
            $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($fileType, $allowed)) {
                $err = "Only JPG, JPEG, PNG, and WEBP image files are allowed.";
            } else {
                $uniqueFileName = uniqid() . '.' . $fileType;
                $targetFilePath = $targetDir . $uniqueFileName;

                if (move_uploaded_file($_FILES["images"]["tmp_name"], $targetFilePath)) {
                    $query = "INSERT INTO product (productName, category, brand, size, description, price, sellersName, location, images, userId) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = mysqli_stmt_init($con);
                    if (mysqli_stmt_prepare($stmt, $query)) {
                        $sellerName = $_SESSION['FullName'] ?? 'Resident';
                        $uid = (int)$_SESSION['pgasuid'];
                        mysqli_stmt_bind_param($stmt, "sssssssssi", $productName, $category, $brand, $size, $description, $price, $sellerName, $location, $uniqueFileName, $uid);
                        if (mysqli_stmt_execute($stmt)) {
                            mysqli_stmt_close($stmt);
                            echo "<script>alert('Your item has been listed successfully!'); window.location.href = 'search.php';</script>";
                            exit;
                        } else {
                            $err = "Database error: " . mysqli_stmt_error($stmt);
                        }
                    } else {
                        $err = "Failed to prepare database statement.";
                    }
                } else {
                    $err = "Failed to upload image file.";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List an Item &bull; Urban Nation</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <style>
        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f8fafc;
            color: #334155;
        }

        .seller-form-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 35px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        }

        .form-label {
            font-weight: 600;
            font-size: 13px;
            color: #475569;
            margin-bottom: 6px;
        }

        .form-control, .form-select {
            padding: 10px 14px;
            font-size: 14px;
            border-radius: 8px;
            border-color: #cbd5e1;
        }

        .form-control:focus, .form-select:focus {
            border-color: #ff3333;
            box-shadow: 0 0 0 0.2rem rgba(255, 51, 51, 0.15);
        }

        .btn-post-item {
            background: #ff3333;
            color: #fff;
            font-weight: 600;
            border: none;
            border-radius: 30px;
            padding: 12px 30px;
            transition: all 0.25s ease;
            width: 100%;
        }

        .btn-post-item:hover {
            background: #cc0000;
            color: #fff;
        }

        .guideline-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>

<body>
    <?php include_once ('includes/header2.php'); ?>

    <div class="container py-4">
        <!-- Breadcrumb -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small">
                        <li class="breadcrumb-item"><a href="../index.php" class="text-decoration-none text-muted">Home</a></li>
                        <li class="breadcrumb-item"><a href="./index.php" class="text-decoration-none text-muted">Urban Nation</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Post Item</li>
                    </ol>
                </nav>
                <h2 class="fw-bold m-0" style="color: #0f172a;">List an Item for Sale</h2>
            </div>
            <a href="search.php" class="btn btn-outline-secondary rounded-pill px-3 py-2 btn-sm">
                &larr; Back to Marketplace
            </a>
        </div>

        <?php if (!empty($err)): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                <?= htmlspecialchars($err) ?>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (empty($_SESSION['pgasuid'])): ?>
            <div class="alert alert-warning border-0 shadow-sm rounded-4 p-4 mb-4 text-center">
                <h5 class="fw-bold mb-2">Sign in to List Items</h5>
                <p class="text-muted small mb-3">You must be logged in to your UrbanNest account so interested buyers can message you.</p>
                <a href="../signin.php" class="btn btn-danger rounded-pill px-4 fw-semibold" style="background:#ff3333; border:none;">Sign In Now</a>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <!-- Left: Listing Form -->
            <div class="col-lg-7">
                <div class="seller-form-card">
                    <form action="" method="post" enctype="multipart/form-data" id="sellerForm">
                        <div class="mb-3">
                            <label for="productName" class="form-label">Item Title <span class="text-danger">*</span></label>
                            <input type="text" id="productName" name="productName" class="form-control"
                                placeholder="e.g. Ergonomic Study Chair, HP Gaming Laptop, Mini Fridge" required>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="F">Furniture</option>
                                    <option value="E">Electronics &amp; Appliances</option>
                                    <option value="L">Lifestyle &amp; Decor</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="brand" class="form-label">Brand / Make</label>
                                <input type="text" id="brand" name="brand" class="form-control" placeholder="e.g. IKEA, HP, Samsung, Nilkamal">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="price" class="form-label">Price (₹) <span class="text-danger">*</span></label>
                                <input type="number" id="price" name="price" class="form-control" placeholder="e.g. 1500" min="50" required>
                            </div>
                            <div class="col-md-6">
                                <label for="size" class="form-label">Size / Dimensions</label>
                                <input type="text" id="size" name="size" class="form-control" placeholder="e.g. Medium, 4x2 ft, Standard">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Pickup Location <span class="text-danger">*</span></label>
                            <input type="text" id="location" name="location" class="form-control"
                                placeholder="e.g. Porvorim, Panaji, Near University Campus" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description &amp; Condition <span class="text-danger">*</span></label>
                            <textarea id="description" name="description" class="form-control" rows="4"
                                placeholder="Mention condition (e.g. gently used, 6 months old), color, meetup preferences, or reason for selling..." required></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="images" class="form-label">Item Photo <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="images" id="images" accept="image/*" required>
                            <small class="text-muted">High-quality clear photos receive 3x more buyer inquiries.</small>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="termsCheck" required>
                            <label class="form-check-label small text-muted" for="termsCheck">
                                I confirm that this item is in working condition as described, belongs to me, and does not violate UrbanNest community policies.
                            </label>
                        </div>

                        <?php if (!empty($_SESSION['pgasuid'])): ?>
                            <button type="submit" name="submit" value="Submit" class="btn-post-item">
                                Publish Listing to Urban Nation &rarr;
                            </button>
                        <?php else: ?>
                            <a href="../signin.php" class="btn-post-item text-center text-decoration-none">
                                Sign In to Post Listing &rarr;
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <!-- Right: Seller Guidelines -->
            <div class="col-lg-5">
                <div class="guideline-card mb-4">
                    <h5 class="fw-bold mb-3" style="color: #0f172a;">
                        <ion-icon name="shield-checkmark-outline" class="text-success align-middle me-1"></ion-icon> Seller Guidelines
                    </h5>
                    <ul class="list-unstyled small text-muted mb-0" style="line-height: 2;">
                        <li><strong>&bull; Price Fairly:</strong> Pre-loved items sell fastest when priced 30-60% below retail.</li>
                        <li><strong>&bull; Clear Photos:</strong> Take photos in daylight showing real item condition.</li>
                        <li><strong>&bull; Honest Details:</strong> Disclose any cosmetic scratches or quirks upfront.</li>
                        <li><strong>&bull; In-App Chat:</strong> Communicate safely with buyers using our built-in messenger.</li>
                        <li><strong>&bull; Safe Pickup:</strong> Arrange pickup in your building lobby or public resident area.</li>
                    </ul>
                </div>

                <div class="guideline-card bg-light border-0">
                    <h6 class="fw-bold mb-2" style="color: #0f172a;">Zero Commission Policy</h6>
                    <p class="text-muted small mb-0" style="line-height: 1.7;">
                        Urban Nation is a free community service for UrbanNest residents. We do not charge listing fees or take cuts from your sales.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <?php include_once ('includes/footer2.php'); ?>
</body>
</html>
