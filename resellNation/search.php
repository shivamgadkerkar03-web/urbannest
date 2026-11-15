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
            color: #ff3333;
        }

        .chat-sidebar {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }
    </style>
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
                <?php if (empty($_SESSION['pgasuid'])) { ?>
                    <a href="../signin.php" class="btn btn-danger rounded-pill px-4 fw-semibold" style="background: #ff3333; border: none;">
                        + Sell an Item
                    </a>
                <?php } else { ?>
                    <a href="seller.php" class="btn btn-danger rounded-pill px-4 fw-semibold" style="background: #ff3333; border: none;">
                        + Sell an Item
                    </a>
                <?php } ?>
            </div>
        </div>

        <div class="row g-4">
            <!-- Sidebar: Messages (if logged in) or Quick Info (if guest) -->
            <div class="col-lg-3 col-md-4">
                <div class="chat-sidebar mb-4">
                    <?php if (!empty($_SESSION['pgasuid'])) { ?>
                        <h5 class="fw-bold mb-3 d-flex align-items-center" style="color: #0f172a;">
                            <ion-icon name="chatbubbles-outline" class="me-2 text-danger"></ion-icon> Messages
                        </h5>
                        <div class="list-group list-group-flush small" id="allMsgView">
                            <?php
                            $uid = (int)$_SESSION['pgasuid'];
                            $ret = mysqli_query($con, "SELECT * FROM tbluser WHERE ID = $uid");
                            $has_msgs = false;
                            while ($row = mysqli_fetch_array($ret)) {
                                if (!empty($row['comm'])) {
                                    $data = json_decode($row['comm'], true);
                                    if (is_array($data)) {
                                        foreach ($data as $key => $value) {
                                            $has_msgs = true;
                                            $count = 0;
                                            foreach ($value as $item) {
                                                if (!empty($item['read']) && $item['read'] == false && (!empty($item['sender']) && $item['sender'] == "sell")) {
                                                    $count++;
                                                }
                                            }
                                            $seller_q = mysqli_query($con, "SELECT FullName FROM tbluser WHERE ID = " . (int)$key);
                                            $s_user = mysqli_fetch_array($seller_q);
                                            $s_name = $s_user ? $s_user['FullName'] : "Seller #" . $key;
                                            ?>
                                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-2 py-2 rounded mb-1"
                                                sellerId="<?= (int)$key ?>" userId="<?= $uid ?>" data-bs-toggle="modal" data-bs-target="#staticBackdrop" type="yes">
                                                <span><?= htmlspecialchars($s_name) ?></span>
                                                <?php if ($count > 0): ?>
                                                    <span class="badge bg-danger rounded-pill"><?= $count ?></span>
                                                <?php endif; ?>
                                            </a>
                                            <?php
                                        }
                                    }
                                }
                            }
                            if (!$has_msgs) {
                                echo '<p class="text-muted small mb-0">No active conversations yet.</p>';
                            }
                            ?>
                        </div>

                        <!-- Customer Inquiries -->
                        <h6 class="fw-bold mt-4 mb-2 text-muted small text-uppercase" style="letter-spacing: 0.5px;">Your Inquiries</h6>
                        <div class="list-group list-group-flush small" id="customerInquiries">
                            <?php
                            $cust_q = mysqli_query($con, "SELECT ID, FullName, comm FROM tbluser WHERE ID != $uid");
                            $has_inq = false;
                            while ($c_row = mysqli_fetch_array($cust_q)) {
                                if (!empty($c_row['comm'])) {
                                    $c_data = json_decode($c_row['comm'], true);
                                    if (is_array($c_data) && isset($c_data[$uid])) {
                                        $has_inq = true;
                                        ?>
                                        <a href="#" class="list-group-item list-group-item-action px-2 py-2 rounded mb-1"
                                            sellerId="<?= (int)$c_row['ID'] ?>" userId="<?= $uid ?>" data-bs-toggle="modal" data-bs-target="#staticBackdrop" type="no">
                                            <?= htmlspecialchars($c_row['FullName']) ?>
                                        </a>
                                        <?php
                                    }
                                }
                            }
                            if (!$has_inq) {
                                echo '<p class="text-muted small mb-0">No buyer inquiries yet.</p>';
                            }
                            ?>
                        </div>
                    <?php } else { ?>
                        <div class="text-center py-3">
                            <span class="d-inline-flex p-3 rounded-circle mb-2" style="background: #fff0f0; color: #ff3333; font-size: 24px;">
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
                                <option value="Hm">Furniture &amp; Home</option>
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
                    $prod_q = mysqli_query($con, "SELECT * FROM product ORDER BY productId DESC");
                    $count = 0;
                    while ($row = mysqli_fetch_array($prod_q)) {
                        $count++;
                        $is_mine = (!empty($_SESSION['pgasuid']) && $row['userId'] == $_SESSION['pgasuid']);
                        $cat_text = $row['category'];
                        if ($cat_text == 'Hm' || $cat_text == 'F') $cat_text = 'Furniture';
                        elseif ($cat_text == 'E') $cat_text = 'Electronics';
                        elseif ($cat_text == 'L') $cat_text = 'Lifestyle';
                    ?>
                        <div class="col-lg-4 col-md-6 col-sm-6 prod-item"
                            data-name="<?= strtolower(htmlspecialchars($row['productName'] . ' ' . $row['brand'] . ' ' . $row['description'])) ?>"
                            data-category="<?= htmlspecialchars($row['category']) ?>">
                            <div class="prod-search-card">
                                <div class="prod-img-box">
                                    <?php if (!empty($row['images']) && file_exists('upload/' . $row['images'])) { ?>
                                        <img src="upload/<?= htmlspecialchars($row['images']) ?>" alt="<?= htmlspecialchars($row['productName']) ?>">
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
                                            <?= htmlspecialchars($cat_text) ?>
                                        </span>
                                        <h5 class="fw-bold mb-1 text-truncate" style="color: #0f172a; font-size: 16px;">
                                            <?= htmlspecialchars($row['productName']) ?>
                                        </h5>
                                        <p class="text-muted small text-truncate mb-2">
                                            <ion-icon name="location-outline" class="align-middle"></ion-icon> <?= htmlspecialchars($row['location']) ?>
                                        </p>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                        <span class="price-tag">₹<?= number_format((int)$row['price']) ?></span>
                                        <a href="product.php?id=<?= $row['productId'] ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-semibold">
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
                            <a href="seller.php" class="btn btn-danger rounded-pill px-4">Post the First Item</a>
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
                        <button myId="" sellerId="" Atype="" id="sendChat" class="btn btn-danger rounded-end-pill px-4" style="background:#ff3333; border:none;" type="button">
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
        // Setup Chat Click Handlers if elements exist
        const msgContainer = document.getElementById("allMsgView");
        const inqContainer = document.getElementById("customerInquiries");

        function handleChatClick(event) {
            const link = event.target.closest('a');
            if (!link) return;
            event.preventDefault();

            const type = link.getAttribute("type");
            const sellerId = link.getAttribute("sellerId");
            const userId = link.getAttribute("userId");

            let postData = {};
            if (type === "yes") {
                postData = { ID: userId, action: "get", sender: "buy", sellerId: sellerId };
                document.getElementById("sendChat").setAttribute("myId", userId);
                document.getElementById("sendChat").setAttribute("sellerId", sellerId);
                document.getElementById("sendChat").setAttribute("Atype", "yes");
            } else {
                postData = { ID: sellerId, action: "get", sender: "sell", sellerId: userId };
                document.getElementById("sendChat").setAttribute("myId", sellerId);
                document.getElementById("sendChat").setAttribute("sellerId", userId);
                document.getElementById("sendChat").setAttribute("Atype", "no");
            }

            fetch('./php/chat.php', {
                method: 'POST',
                body: JSON.stringify(postData),
                headers: { 'Content-Type': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const parentElement = document.getElementById('allMessage');
                    if (type === "yes") {
                        if (typeof loadWhatsAppChat === 'function') loadWhatsAppChat(data.comm, parentElement);
                    } else {
                        if (typeof loadWhatsAppChat2 === 'function') loadWhatsAppChat2(data.comm, parentElement);
                    }
                }
            })
            .catch(err => console.error(err));
        }

        if (msgContainer) msgContainer.addEventListener('click', handleChatClick);
        if (inqContainer) inqContainer.addEventListener('click', handleChatClick);

        const textInput = document.getElementById("newMessage");
        const sendBtn = document.getElementById("sendChat");
        if (textInput && sendBtn) {
            textInput.addEventListener("keypress", function (e) {
                if (e.key === "Enter") {
                    sendBtn.click();
                }
            });

            sendBtn.addEventListener('click', () => {
                const val = textInput.value.trim();
                if (!val) return;
                const now = new Date().toISOString().slice(0, 19).replace('T', ' ');
                const isBuy = sendBtn.getAttribute("atype") === "yes";

                const postData = {
                    ID: sendBtn.getAttribute("myId"),
                    sellerId: sendBtn.getAttribute("sellerId"),
                    newMessage: {
                        content: val,
                        timestamp: now,
                        read: false,
                        sender: isBuy ? "buy" : "sell"
                    }
                };

                fetch('./php/sendMesssage.php', {
                    method: 'POST',
                    body: JSON.stringify(postData),
                    headers: { 'Content-Type': 'application/json' }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const parentElement = document.getElementById('allMessage');
                        if (isBuy) {
                            if (typeof loadWhatsAppChat === 'function') loadWhatsAppChat(data.updatedCommData, parentElement);
                        } else {
                            if (typeof loadWhatsAppChat2 === 'function') loadWhatsAppChat2(data.updatedCommData, parentElement);
                        }
                        textInput.value = "";
                    }
                })
                .catch(err => console.error(err));
            });
        }
    </script>

    <?php include_once ('includes/footer2.php'); ?>
</body>
</html>
