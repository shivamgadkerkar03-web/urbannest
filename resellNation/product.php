<?php
require_once __DIR__ . '/includes/market.php';

$uid = current_user_id();
$rawId = $_GET['id'] ?? null;
$productId = is_string($rawId) ? filter_var($rawId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) : false;
$row = $productId ? un_fetch_product($con, $productId) : null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= h(csrf_token()) ?>">
    <title>Product Details &bull; Urban Nation</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="./css/chatStyle.css">

    <style>
        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f8fafc;
            color: #334155;
        }

        .prod-display-box {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        }

        .prod-display-img {
            max-height: 420px;
            width: 100%;
            object-fit: contain;
            border-radius: 12px;
        }

        .details-box {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        }

        .prod-price-badge {
            font-size: 1.8rem;
            font-weight: 700;
            color: #e63946;
        }

        .seller-info-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 18px;
        }
    </style>
    <link rel="stylesheet" href="../css/navbar.css">
</head>

<body>
    <?php include_once ('includes/header2.php'); ?>

    <div class="container py-4">
        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="../index.php" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item"><a href="./index.php" class="text-decoration-none text-muted">Urban Nation</a></li>
                <li class="breadcrumb-item"><a href="search.php" class="text-decoration-none text-muted">Marketplace</a></li>
                <li class="breadcrumb-item active" aria-current="page">Product Details</li>
            </ol>
        </nav>

        <?php
        if ($row !== null) {
                $is_logged_in = $uid > 0;
                $is_owner = ($is_logged_in && (int) $row['userId'] === $uid);
                $cat_text = un_category_label($row['category']);
                $img = basename((string) $row['images']);
        ?>
                <div class="row g-4">
                    <!-- Left: Product Photo -->
                    <div class="col-lg-6 col-md-12">
                        <div class="prod-display-box">
                            <?php if ($img !== '' && is_file(UN_UPLOAD_DIR . '/' . $img)) { ?>
                                <img src="upload/<?= h(rawurlencode($img)) ?>" alt="<?= h($row['productName']) ?>" class="prod-display-img">
                            <?php } else { ?>
                                <img src="./images/Thrift store.jpg" alt="Item Image" class="prod-display-img">
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Right: Product Information & Actions -->
                    <div class="col-lg-6 col-md-12">
                        <div class="details-box">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-light text-secondary border px-3 py-1 rounded-pill" style="font-size: 12px;">
                                    <?= h($cat_text) ?>
                                </span>
                                <?php if (!empty($row['brand'])): ?>
                                    <span class="text-muted small fw-semibold text-uppercase"><?= h($row['brand']) ?></span>
                                <?php endif; ?>
                            </div>

                            <h2 class="fw-bold mb-2" style="color: #0f172a;"><?= h($row['productName']) ?></h2>
                            <p class="text-muted small mb-3">
                                <ion-icon name="location-outline" class="align-middle text-danger"></ion-icon>
                                <?= h($row['location']) ?>
                            </p>

                            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                                <span class="prod-price-badge">₹<?= number_format((int)$row['price']) ?></span>
                                <?php if (!empty($row['size'])): ?>
                                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">Size: <?= h($row['size']) ?></span>
                                <?php endif; ?>
                            </div>

                            <!-- Description -->
                            <h6 class="fw-bold text-uppercase small text-muted mb-2" style="letter-spacing: 0.5px;">Item Description</h6>
                            <p class="text-muted mb-4" style="line-height: 1.7; white-space: pre-line;">
                                <?= h($row['description']) ?>
                            </p>

                            <!-- Seller Info -->
                            <div class="seller-info-card mb-4">
                                <h6 class="fw-bold mb-2 small text-uppercase" style="color: #0f172a; letter-spacing: 0.5px;">Seller Information</h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="fw-semibold mb-0" style="color: #1e293b;"><?= h($row['sellersName']) ?></p>
                                        <small class="text-muted"><ion-icon name="checkmark-circle-outline" class="text-success align-middle"></ion-icon> Verified Community Resident</small>
                                    </div>
                                    <span class="badge bg-white border text-secondary px-3 py-2 rounded-pill">Local Pickup</span>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <?php if ($is_owner) { ?>
                                <div class="alert alert-warning text-center rounded-pill mb-0 py-2">
                                    You listed this item. Manage it via <a href="myadd.php" class="fw-bold text-dark">My Ads</a>.
                                </div>
                            <?php } elseif ($is_logged_in) { ?>
                                <button type="button" class="btn btn-danger btn-lg w-100 rounded-pill py-3 fw-semibold shadow-sm"
                                    data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                                    data-product-id="<?= (int) $row['productId'] ?>" id="openChat"
                                    style="background: #e63946; border: none;">
                                    <ion-icon name="chatbubbles-outline" class="align-middle me-2"></ion-icon> Chat with Seller
                                </button>
                            <?php } else { ?>
                                <button type="button" class="btn btn-danger btn-lg w-100 rounded-pill py-3 fw-semibold shadow-sm"
                                    data-bs-toggle="modal" data-bs-target="#loginPromptModal"
                                    style="background: #e63946; border: none;">
                                    <ion-icon name="chatbubbles-outline" class="align-middle me-2"></ion-icon> Chat with Seller
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- Chat Modal (for logged-in buyer) -->
                <?php if ($is_logged_in && !$is_owner): ?>
                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                        aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                <div class="modal-header bg-dark text-white border-0 py-3">
                                    <h5 class="modal-title fw-bold" id="staticBackdropLabel">
                                        Chat with <?= h($row['sellersName']) ?>
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4 bg-light">
                                    <div class="container bg-white rounded-3 p-3 shadow-sm mb-3" style="height: 400px; overflow-y: auto;" id="allMessage">
                                    </div>
                                    <div class="d-flex flex-column gap-2">
                                        <div class="input-group">
                                            <input type="text" class="form-control shadow-none rounded-start-pill px-3" placeholder="Write your message..." id="newMessage">
                                            <button id="sendChat"
                                                class="btn btn-danger rounded-end-pill px-4" style="background:#e63946; border:none;" type="button">
                                                Send
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

        <?php
        } elseif ($rawId === null || $rawId === '') {
            echo '<div class="alert alert-danger text-center py-5">No product selected. <a href="search.php" class="fw-bold">Browse Items</a></div>';
        } else {
            echo '<div class="alert alert-warning text-center py-5">Product not found. <a href="search.php" class="fw-bold">Return to Marketplace</a></div>';
        }
        ?>
    </div>

    <!-- Guest Login Modal -->
    <div class="modal fade" id="loginPromptModal" tabindex="-1" aria-labelledby="loginPromptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="loginPromptModalLabel">Sign In Required</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <span class="d-inline-flex p-3 rounded-circle" style="background: #fff0f0; color: #e63946; font-size: 32px;">
                            <ion-icon name="chatbubbles-outline"></ion-icon>
                        </span>
                    </div>
                    <h5 class="fw-bold mb-2">Message the Seller Directly</h5>
                    <p class="text-muted small px-3">
                        Sign in to your UrbanNest account to start a live negotiation and arrange pickup with this seller.
                    </p>
                </div>
                <div class="modal-footer border-0 pt-0 d-flex justify-content-center gap-2 pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <a href="../signin.php" class="btn btn-danger rounded-pill px-4 fw-semibold" style="background: #e63946; border: none;">Sign In</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Scripts -->
    <script src="./script/message.js"></script>
    <script>
        (function () {
            const openChatBtn = document.getElementById('openChat');
            const parentElement = document.getElementById('allMessage');
            if (!openChatBtn || !parentElement) return;

            const state = { productId: parseInt(openChatBtn.dataset.productId, 10) || 0, otherId: 0 };

            openChatBtn.addEventListener('click', () => {
                parentElement.replaceChildren();
                UNChat.loadThread(state.productId, 0)
                    .then(data => {
                        if (!data.success) {
                            UNChat.showError(parentElement, data.message);
                            return;
                        }
                        state.otherId = data.other.id;
                        UNChat.render(data, parentElement);
                    })
                    .catch(() => UNChat.showError(parentElement));
            });

            UNChat.bindComposer(document.getElementById('newMessage'), document.getElementById('sendChat'), parentElement, state);
        })();
    </script>

    <?php include_once ('includes/footer2.php'); ?>
</body>
</html>
