<?php
require_once __DIR__ . '/includes/market.php';

$uid = current_user_id();
$buying = [];
$selling = [];
if ($uid > 0) {
    foreach (un_conversations($con, $uid) as $conv) {
        if ($conv['role'] === 'seller') {
            $selling[] = $conv;
        } else {
            $buying[] = $conv;
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
    <meta name="csrf-token" content="<?= h(csrf_token()) ?>">
    <title>Browse Marketplace &bull; Urban Nation</title>

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

        .prod-search-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            overflow: hidden;
            transition: all 0.25s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .prod-search-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
            border-color: #ffc9c9;
        }

        .prod-img-box {
            height: 190px;
            width: 100%;
            overflow: hidden;
            background: #f1f5f9;
            position: relative;
        }

        .prod-img-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .prod-search-card:hover .prod-img-box img {
            transform: scale(1.05);
        }

        .price-tag {
            font-size: 1.15rem;
            font-weight: 700;
            color: #e63946;
        }

        .chat-sidebar {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }
    </style>
    <link rel="stylesheet" href="../css/navbar.css">
</head>

<body>
    <?php include_once ('includes/header2.php'); ?>

    <div class="container py-4">
        <!-- Breadcrumb & Top Bar -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small">
                        <li class="breadcrumb-item"><a href="../index.php" class="text-decoration-none text-muted">Home</a></li>
                        <li class="breadcrumb-item"><a href="./index.php" class="text-decoration-none text-muted">Urban Nation</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Marketplace</li>
                    </ol>
                </nav>
                <h2 class="fw-bold m-0" style="color: #0f172a;">Pre-Loved Marketplace</h2>
            </div>
            <div>
                <?php if ($uid <= 0) { ?>
                    <a href="../signin.php?redirect=resellNation/seller.php" class="btn btn-danger rounded-pill px-4 fw-semibold" style="background: #e63946; border: none;">
                        + Sell an Item
                    </a>
                <?php } else { ?>
                    <a href="seller.php" class="btn btn-danger rounded-pill px-4 fw-semibold" style="background: #e63946; border: none;">
                        + Sell an Item
                    </a>
                <?php } ?>
            </div>
        </div>

        <div class="row g-4">
            <!-- Sidebar: Messages (if logged in) or Quick Info (if guest) -->
            <div class="col-lg-3 col-md-4">
                <div class="chat-sidebar mb-4">
                    <?php if ($uid > 0) { ?>
                        <h5 class="fw-bold mb-3 d-flex align-items-center" style="color: #0f172a;">
                            <ion-icon name="chatbubbles-outline" class="me-2 text-danger"></ion-icon> Messages
                        </h5>
                        <div class="list-group list-group-flush small" id="allMsgView">
                            <?php foreach ($buying as $conv): ?>
                                <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-2 py-2 rounded mb-1"
                                    data-product-id="<?= (int) $conv['product_id'] ?>" data-other-id="<?= (int) $conv['other_id'] ?>"
                                    data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                    <span class="text-truncate">
                                        <?= h($conv['other_name']) ?>
                                        <small class="d-block text-muted text-truncate"><?= h($conv['product_name']) ?></small>
                                    </span>
                                    <?php if ($conv['unread'] > 0): ?>
                                        <span class="badge bg-danger rounded-pill"><?= (int) $conv['unread'] ?></span>
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; ?>
                            <?php if (!$buying): ?>
                                <p class="text-muted small mb-0">No active conversations yet.</p>
                            <?php endif; ?>
                        </div>

                        <!-- Customer Inquiries -->
                        <h6 class="fw-bold mt-4 mb-2 text-muted small text-uppercase" style="letter-spacing: 0.5px;">Your Inquiries</h6>
                        <div class="list-group list-group-flush small" id="customerInquiries">
                            <?php foreach ($selling as $conv): ?>
                                <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-2 py-2 rounded mb-1"
                                    data-product-id="<?= (int) $conv['product_id'] ?>" data-other-id="<?= (int) $conv['other_id'] ?>"
                                    data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                    <span class="text-truncate">
                                        <?= h($conv['other_name']) ?>
                                        <small class="d-block text-muted text-truncate"><?= h($conv['product_name']) ?></small>
                                    </span>
                                    <?php if ($conv['unread'] > 0): ?>
                                        <span class="badge bg-danger rounded-pill"><?= (int) $conv['unread'] ?></span>
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; ?>
                            <?php if (!$selling): ?>
                                <p class="text-muted small mb-0">No buyer inquiries yet.</p>
                            <?php endif; ?>
                        </div>
                    <?php } else { ?>
                        <div class="text-center py-3">
                            <span class="d-inline-flex p-3 rounded-circle mb-2" style="background: #fff0f0; color: #e63946; font-size: 24px;">
                                <ion-icon name="chatbubble-ellipses-outline"></ion-icon>
                            </span>
                            <h6 class="fw-bold mb-1">Direct Seller Chat</h6>
                            <p class="text-muted small mb-3">Sign in to negotiate prices and message owners directly.</p>
                            <a href="../signin.php" class="btn btn-outline-danger btn-sm rounded-pill w-100 fw-semibold">Sign In</a>
                        </div>
                    <?php } ?>
                </div>

                <!-- Community Trust Card -->
                <div class="chat-sidebar bg-light border-0">
                    <h6 class="fw-bold mb-2" style="color: #0f172a;">Community Guidelines</h6>
                    <ul class="list-unstyled small text-muted mb-0" style="line-height: 1.8;">
                        <li>&bull; Inspect items in person before paying.</li>
                        <li>&bull; Prefer daylight meetups in building lobbies.</li>
                        <li>&bull; 100% free with zero platform commissions.</li>
                    </ul>
                </div>
            </div>

            <!-- Main Products Feed -->
            <div class="col-lg-9 col-md-8">
                <!-- Search & Filter Controls -->
                <div class="card border-0 shadow-sm rounded-3 p-3 mb-4 bg-white">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-7 col-sm-12">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted">
                                    <ion-icon name="search-outline"></ion-icon>
                                </span>
                                <input type="text" class="form-control border-start-0 ps-0 shadow-none" id="searchText"
                                    placeholder="Search by title, brand, or description...">
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-12">
                            <select class="form-select shadow-none" id="categoryFilter">
                                <option value="">All Categories</option>
                                <option value="F">Furniture</option>
                                <option value="E">Electronics &amp; Appliances</option>
                                <option value="L">Lifestyle &amp; Study</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="row g-4" id="productsContainer">
                    <?php
                    $prod_q = mysqli_query($con, "SELECT productId, userId, productName, category, brand, description, price, location, images FROM product ORDER BY productId DESC");
                    $count = 0;
                    while ($row = mysqli_fetch_assoc($prod_q)) {
                        $count++;
                        $is_mine = ($uid > 0 && (int) $row['userId'] === $uid);
                        $cat_text = un_category_label($row['category']);
                        $img = basename((string) $row['images']);
                    ?>
                        <div class="col-lg-4 col-md-6 col-sm-6 prod-item"
                            data-name="<?= h(mb_strtolower($row['productName'] . ' ' . $row['brand'] . ' ' . $row['description'], 'UTF-8')) ?>"
                            data-category="<?= h($row['category']) ?>">
                            <div class="prod-search-card">
                                <div class="prod-img-box">
                                    <?php if ($img !== '' && is_file(UN_UPLOAD_DIR . '/' . $img)) { ?>
                                        <img src="upload/<?= h(rawurlencode($img)) ?>" alt="<?= h($row['productName']) ?>">
                                    <?php } else { ?>
                                        <img src="./images/Thrift store.jpg" alt="Item Image">
                                    <?php } ?>
                                    <?php if ($is_mine): ?>
                                        <span class="position-absolute top-0 end-0 m-2 badge bg-warning text-dark">Your Item</span>
                                    <?php endif; ?>
                                </div>
                                <div class="p-3 d-flex flex-column justify-content-between flex-grow-1">
                                    <div>
                                        <span class="badge bg-light text-secondary border px-2 py-1 mb-2" style="font-size: 11px;">
                                            <?= h($cat_text) ?>
                                        </span>
                                        <h5 class="fw-bold mb-1 text-truncate" style="color: #0f172a; font-size: 16px;">
                                            <?= h($row['productName']) ?>
                                        </h5>
                                        <p class="text-muted small text-truncate mb-2">
                                            <ion-icon name="location-outline" class="align-middle"></ion-icon> <?= h($row['location']) ?>
                                        </p>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                        <span class="price-tag">₹<?= number_format((int)$row['price']) ?></span>
                                        <a href="product.php?id=<?= (int) $row['productId'] ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-semibold">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ($count === 0): ?>
                        <div class="col-12 text-center py-5">
                            <p class="text-muted mb-3">No items listed yet.</p>
                            <a href="<?= $uid > 0 ? 'seller.php' : '../signin.php?redirect=resellNation/seller.php' ?>" class="btn btn-danger rounded-pill px-4">Post the First Item</a>
                        </div>
                    <?php endif; ?>
                </div>

                <div id="noMatchAlert" class="alert alert-light text-center py-5 d-none">
                    <p class="text-muted m-0">No matching items found. Try a different search term or category.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Chat Modal (for logged-in messaging) -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-dark text-white border-0 py-3">
                    <h5 class="modal-title fw-bold" id="staticBackdropLabel">
                        <ion-icon name="chatbubbles-outline" class="align-middle me-1 text-danger"></ion-icon> In-App Chat
                    </h5>
                    <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="container bg-white rounded-3 p-3 shadow-sm mb-3" style="height: 340px; overflow-y: auto;" id="allMessage">
                    </div>
                    <div class="input-group">
                        <input type="text" class="form-control shadow-none rounded-start-pill px-3" placeholder="Type your message..." id="newMessage">
                        <button id="sendChat" class="btn btn-danger rounded-end-pill px-4" style="background:#e63946; border:none;" type="button">
                            Send
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Client-side Fast Filter JS -->
    <script>
        const searchInput = document.getElementById('searchText');
        const categoryFilter = document.getElementById('categoryFilter');
        const prodItems = document.querySelectorAll('.prod-item');
        const noMatchAlert = document.getElementById('noMatchAlert');

        function filterProducts() {
            const query = searchInput.value.toLowerCase().trim();
            const selectedCat = categoryFilter.value;
            let visibleCount = 0;

            prodItems.forEach(item => {
                const name = item.getAttribute('data-name');
                const cat = item.getAttribute('data-category');

                const matchesQuery = !query || name.includes(query);
                const matchesCat = !selectedCat || cat === selectedCat || (selectedCat === 'F' && cat === 'Hm');

                if (matchesQuery && matchesCat) {
                    item.classList.remove('d-none');
                    visibleCount++;
                } else {
                    item.classList.add('d-none');
                }
            });

            if (visibleCount === 0 && prodItems.length > 0) {
                noMatchAlert.classList.remove('d-none');
            } else {
                noMatchAlert.classList.add('d-none');
            }
        }

        if (searchInput) searchInput.addEventListener('input', filterProducts);
        if (categoryFilter) categoryFilter.addEventListener('change', filterProducts);
    </script>

    <!-- Chat Handlers -->
    <script src="./script/message.js"></script>
    <script>
        (function () {
            const parentElement = document.getElementById('allMessage');
            const sendBtn = document.getElementById('sendChat');
            const state = { productId: 0, otherId: 0 };

            function openConversation(event) {
                const link = event.target.closest('a[data-product-id]');
                if (!link) return;
                event.preventDefault();
                state.productId = parseInt(link.dataset.productId, 10) || 0;
                state.otherId = parseInt(link.dataset.otherId, 10) || 0;
                parentElement.replaceChildren();

                UNChat.loadThread(state.productId, state.otherId)
                    .then(data => {
                        if (!data.success) {
                            UNChat.showError(parentElement, data.message);
                            return;
                        }
                        state.otherId = data.other.id;
                        UNChat.render(data, parentElement);
                        const badge = link.querySelector('.badge');
                        if (badge) badge.remove();
                        sendBtn.disabled = !data.can_send;
                    })
                    .catch(() => UNChat.showError(parentElement));
            }

            ['allMsgView', 'customerInquiries'].forEach(id => {
                const box = document.getElementById(id);
                if (box) box.addEventListener('click', openConversation);
            });

            UNChat.bindComposer(document.getElementById('newMessage'), sendBtn, parentElement, state);
        })();
    </script>

    <?php include_once ('includes/footer2.php'); ?>
</body>
</html>
