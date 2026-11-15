<?php
session_start();
include_once('connect.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Urban Nation &bull; Pre-Loved Marketplace | UrbanNest</title>

  <!-- Google Fonts & Bootstrap 5 -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

  <style>
    body {
      font-family: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif;
      color: #333;
      background-color: #fbfbfd;
    }

    /* Hero Section */
    .un-hero {
      position: relative;
      min-height: 55vh;
      display: flex;
      align-items: center;
      background: linear-gradient(180deg, rgba(15, 23, 42, 0.72) 0%, rgba(15, 23, 42, 0.90) 100%),
                  url('./images/Thrift store.jpg') center/cover no-repeat;
      color: #fff;
      padding: 70px 0;
    }

    .un-hero-badge {
      display: inline-block;
      background: rgba(255, 51, 51, 0.18);
      color: #ff6b6b;
      border: 1px solid rgba(255, 51, 51, 0.35);
      border-radius: 30px;
      padding: 6px 16px;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 1.2px;
      text-transform: uppercase;
      margin-bottom: 20px;
    }

    .un-hero h1 {
      font-size: 2.8rem;
      font-weight: 700;
      line-height: 1.2;
      margin-bottom: 15px;
    }

    .un-hero p {
      font-size: 1.1rem;
      color: rgba(255, 255, 255, 0.85);
      max-width: 640px;
      margin: 0 auto 30px;
      line-height: 1.6;
    }

    /* Feature Cards */
    .feature-card {
      background: #ffffff;
      border: 1px solid #edf0f5;
      border-radius: 16px;
      padding: 35px 30px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
      height: 100%;
    }

    .feature-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 16px 36px rgba(0, 0, 0, 0.08);
      border-color: #ffdede;
    }

    .feature-icon-wrapper {
      width: 72px;
      height: 72px;
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 24px;
    }

    .btn-brand-red {
      background: #ff3333;
      color: #fff !important;
      font-weight: 600;
      border-radius: 30px;
      padding: 12px 28px;
      border: none;
      transition: all 0.25s ease;
      text-decoration: none;
      display: inline-block;
    }

    .btn-brand-red:hover {
      background: #cc0000;
      color: #fff !important;
      transform: translateY(-2px);
      box-shadow: 0 6px 18px rgba(255, 51, 51, 0.3);
    }

    .btn-brand-outline {
      background: transparent;
      color: #222 !important;
      font-weight: 600;
      border-radius: 30px;
      padding: 11px 26px;
      border: 2px solid #222;
      transition: all 0.25s ease;
      text-decoration: none;
      display: inline-block;
    }

    .btn-brand-outline:hover {
      background: #222;
      color: #fff !important;
    }

    /* Step Cards */
    .step-card {
      background: #ffffff;
      border: 1px solid #f0f2f6;
      border-radius: 16px;
      padding: 32px 24px;
      text-align: center;
      height: 100%;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
      position: relative;
    }

    .step-number {
      width: 44px;
      height: 44px;
      line-height: 44px;
      border-radius: 50%;
      background: #fff0f0;
      color: #ff3333;
      font-weight: 700;
      margin: 0 auto 18px;
      font-size: 16px;
    }

    /* Product Card */
    .prod-card {
      background: #fff;
      border: 1px solid #edf0f5;
      border-radius: 14px;
      overflow: hidden;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
      display: flex;
      flex-direction: column;
      height: 100%;
    }

    .prod-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 14px 28px rgba(0, 0, 0, 0.08);
      border-color: #ffdede;
    }

    .prod-img-wrap {
      height: 200px;
      width: 100%;
      overflow: hidden;
      background: #f8f9fa;
      position: relative;
    }

    .prod-img-wrap img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.4s ease;
    }

    .prod-card:hover .prod-img-wrap img {
      transform: scale(1.05);
    }

    .badge-price {
      background: #ff3333;
      color: #fff;
      font-weight: 700;
      padding: 6px 12px;
      border-radius: 8px;
      font-size: 15px;
    }
  </style>
</head>

<body>

  <?php include_once ('includes/header2.php'); ?>

  <!-- Hero Section -->
  <section class="un-hero text-center">
    <div class="container">
      <span class="un-hero-badge">Urban Nation &bull; Pre-Loved Marketplace</span>
      <h1>Furnish Your Space for Less</h1>
      <p>
        The dedicated student & resident marketplace by UrbanNest. Buy, sell, and recycle furniture, electronics, and room essentials directly within your local community.
      </p>

      <div class="d-flex justify-content-center gap-3 flex-wrap">
        <a href="search.php" class="btn-brand-red">
          <ion-icon name="bag-handle-outline" class="me-1 align-middle"></ion-icon> Browse Marketplace
        </a>
        <?php if (!isset($_SESSION['pgasuid'])) { ?>
          <a data-bs-toggle="modal" data-bs-target="#loginModal" class="btn-brand-outline text-white border-white cursor-pointer" style="color: #fff !important;">
            <ion-icon name="add-circle-outline" class="me-1 align-middle"></ion-icon> Sell an Item
          </a>
        <?php } else { ?>
          <a href="seller.php" class="btn-brand-outline text-white border-white" style="color: #fff !important;">
            <ion-icon name="add-circle-outline" class="me-1 align-middle"></ion-icon> Sell an Item
          </a>
        <?php } ?>
      </div>
    </div>
  </section>

  <!-- Dual Action Highlights -->
  <section class="py-5">
    <div class="container py-4">
      <div class="row g-4">
        <!-- Sell Card -->
        <div class="col-lg-6">
          <div class="feature-card d-flex flex-column justify-content-between">
            <div>
              <div class="feature-icon-wrapper" style="background: #eef4ff;">
                <img src="./images/store icon.png" alt="Store Icon" style="width: 44px; height: 44px; object-fit: contain;">
              </div>
              <span class="badge bg-primary-subtle text-primary fw-semibold px-3 py-1 rounded-pill mb-2">Sell &amp; Earn</span>
              <h3 class="fw-bold mb-3" style="color: #1e293b;">Moving Out or Upgrading?</h3>
              <p class="text-muted mb-4" style="line-height: 1.7;">
                Turn your pre-loved study desks, chairs, monitors, and kitchen gadgets into instant cash. Connect with verified residents moving into your building or neighborhood.
              </p>
            </div>
            <div>
              <?php if (!isset($_SESSION['pgasuid'])) { ?>
                <button type="button" class="btn-brand-red" data-bs-toggle="modal" data-bs-target="#loginModal">
                  Start Selling for Free &rarr;
                </button>
              <?php } else { ?>
                <a href="seller.php" class="btn-brand-red">
                  Start Selling for Free &rarr;
                </a>
              <?php } ?>
            </div>
          </div>
        </div>

        <!-- Buy Card -->
        <div class="col-lg-6">
          <div class="feature-card d-flex flex-column justify-content-between">
            <div>
              <div class="feature-icon-wrapper" style="background: #fff4e5;">
                <img src="./images/buyer icon.png" alt="Buyer Icon" style="width: 44px; height: 44px; object-fit: contain;">
              </div>
              <span class="badge bg-warning-subtle text-warning fw-semibold px-3 py-1 rounded-pill mb-2">Shop &amp; Save</span>
              <h3 class="fw-bold mb-3" style="color: #1e293b;">Need Room Essentials?</h3>
              <p class="text-muted mb-4" style="line-height: 1.7;">
                Furnish your room without expensive retail markups. Explore authentic listings, chat directly with owners, and negotiate the best student-friendly prices.
              </p>
            </div>
            <div>
              <a href="search.php" class="btn-brand-outline">
                Explore Listings &rarr;
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Live Recent Listings Section -->
  <section class="py-5 bg-white border-top border-bottom border-light">
    <div class="container py-4">
      <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
        <div>
          <span class="badge bg-danger-subtle text-danger fw-semibold px-3 py-1 rounded-pill mb-2">Thrift Store</span>
          <h2 class="fw-bold m-0" style="color: #1e293b;">Recent Community Listings</h2>
        </div>
        <div>
          <a href="search.php" class="btn btn-outline-dark btn-sm rounded-pill px-3 py-2 fw-semibold">
            View All Items &rarr;
          </a>
        </div>
      </div>

      <div class="row g-4">
        <?php
        $prod_query = mysqli_query($con, "SELECT productId, productName, category, brand, price, location, images FROM product ORDER BY productId DESC LIMIT 4");
        $has_prods = mysqli_num_rows($prod_query) > 0;

        if ($has_prods) {
          while ($prod = mysqli_fetch_array($prod_query)) {
        ?>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="prod-card">
                <div class="prod-img-wrap">
                  <?php if (!empty($prod['images']) && file_exists('upload/' . $prod['images'])) { ?>
                    <img src="upload/<?= htmlspecialchars($prod['images']) ?>" alt="<?= htmlspecialchars($prod['productName']) ?>">
                  <?php } else { ?>
                    <img src="./images/Thrift store.jpg" alt="Item Image">
                  <?php } ?>
                </div>
                <div class="p-3 d-flex flex-column justify-content-between flex-grow-1">
                  <div>
                    <span class="badge bg-light text-secondary border px-2 py-1 mb-2" style="font-size: 11px;">
                      <?= htmlspecialchars($prod['category'] ?? 'Thrift') ?>
                    </span>
                    <h5 class="fw-bold mb-1 text-truncate" style="color: #1e293b; font-size: 16px;">
                      <?= htmlspecialchars($prod['productName']) ?>
                    </h5>
                    <p class="text-muted small text-truncate mb-2">
                      <ion-icon name="location-outline" class="align-middle"></ion-icon> <?= htmlspecialchars($prod['location'] ?? 'Goa') ?>
                    </p>
                  </div>
                  <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                    <span class="badge-price">₹<?= number_format((int)$prod['price']) ?></span>
                    <a href="product.php?id=<?= $prod['productId'] ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                      Details
                    </a>
                  </div>
                </div>
              </div>
            </div>
        <?php
          }
        } else {
        ?>
          <div class="col-12 text-center py-5">
            <p class="text-muted mb-3">No active items listed right now.</p>
            <a href="seller.php" class="btn-brand-red btn-sm">Be the First to List an Item</a>
          </div>
        <?php } ?>
      </div>
    </div>
  </section>

  <!-- How It Works Section -->
  <section class="py-5">
    <div class="container py-4">
      <div class="text-center mb-5">
        <span class="badge bg-danger-subtle text-danger fw-semibold px-3 py-1 rounded-pill mb-2">Simple &bull; Transparent &bull; Local</span>
        <h2 class="fw-bold" style="color: #1e293b;">How Thrift Shopping Works</h2>
        <p class="text-muted mx-auto" style="max-width: 560px;">
          Designed specifically for students and PG residents to trade locally without delivery friction or commissions.
        </p>
      </div>

      <div class="row g-4">
        <!-- Step 1 -->
        <div class="col-md-4">
          <div class="step-card">
            <div class="step-number">01</div>
            <div class="mb-3">
              <img src="./images/browse icon.png" alt="Browse" style="height: 64px; object-fit: contain;">
            </div>
            <h5 class="fw-bold mb-2" style="color: #1e293b;">Browse Listings</h5>
            <p class="text-muted small mb-0" style="line-height: 1.6;">
              Search quality pre-loved items by category, price, and location from verified residents in your city.
            </p>
          </div>
        </div>

        <!-- Step 2 -->
        <div class="col-md-4">
          <div class="step-card">
            <div class="step-number">02</div>
            <div class="mb-3">
              <img src="./images/negotiate icon.png" alt="Negotiate" style="height: 64px; object-fit: contain;">
            </div>
            <h5 class="fw-bold mb-2" style="color: #1e293b;">Chat &amp; Negotiate</h5>
            <p class="text-muted small mb-0" style="line-height: 1.6;">
              Connect via our built-in messenger to clarify condition, agree on price, and set a convenient pickup time.
            </p>
          </div>
        </div>

        <!-- Step 3 -->
        <div class="col-md-4">
          <div class="step-card">
            <div class="step-number">03</div>
            <div class="mb-3">
              <img src="./images/purchase icon.png" alt="Purchase" style="height: 64px; object-fit: contain;">
            </div>
            <h5 class="fw-bold mb-2" style="color: #1e293b;">Pick Up &amp; Save</h5>
            <p class="text-muted small mb-0" style="line-height: 1.6;">
              Inspect the item in person and pay the seller directly. Zero hidden platform fees or courier delays.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Login Modal for Guest Users (Used for Seller Flow) -->
  <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold" id="loginModalLabel" style="color: #1e293b;">Login Required</h5>
          <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center py-4">
          <div class="mb-3">
            <span class="d-inline-flex p-3 rounded-circle" style="background: #fff0f0; color: #ff3333; font-size: 32px;">
              <ion-icon name="lock-closed-outline"></ion-icon>
            </span>
          </div>
          <h5 class="fw-bold mb-2">Join Urban Nation to List Items</h5>
          <p class="text-muted small px-3">
            Please sign in with your UrbanNest account to post items for sale or chat with buyers.
          </p>
        </div>
        <div class="modal-footer border-0 pt-0 d-flex justify-content-center gap-2 pb-4">
          <button type="button" class="btn btn-light rounded-pill px-4 fw-semibold" data-bs-dismiss="modal">Cancel</button>
          <a href="../signin.php" class="btn-brand-red">Sign In Now</a>
        </div>
      </div>
    </div>
  </div>

  <?php include_once ('includes/footer2.php'); ?>

</body>
</html>
