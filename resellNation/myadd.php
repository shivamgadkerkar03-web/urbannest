<?php
require_once __DIR__ . '/includes/market.php';
require_once __DIR__ . '/includes/validation.php';

$stmt = mysqli_prepare($con, 'SELECT productId, productName, category, brand, size, description, price, sellersName, location, images
    FROM product WHERE userId = ? ORDER BY productId DESC');
mysqli_stmt_bind_param($stmt, 'i', $uid);
mysqli_stmt_execute($stmt);
$products = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

$jsonFlags = JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= h(csrf_token()) ?>">
    <title>My Ads &bull; Urban Nation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <style>
        .modInp {
            width: 210px;
        }

        .imgCard {
            height: 200px;
            width: 200px;
            overflow: hidden;
            object-fit: cover;
            border-radius: 1rem;
        }

        .removeBord {
            border: none !important;
            background-color: rgba(245, 206, 118, 0.5);
            border-radius: 1rem;
            width: 220px;
        }

        .removeBord:hover {
            cursor: pointer;
        }

        .parent {
            position: relative;
        }

        .modal span.input-group-text,
        .formIt {
            width: 200px !important;
        }
    </style>
    <link rel="stylesheet" href="../css/navbar.css">
</head>

<body>
    <?php include_once __DIR__ . '/includes/header2.php'; ?>

    <a href="./index.php"
        class="bg-primary text-white rounded-pill text-center p-2 m-5 d-flex justify-content-center align-items-center text-decoration-none"
        style="max-width: 20em;"><ion-icon size="large" name="arrow-back-outline" class="me-4"></ion-icon>
        <h4 class="m-0">Back</h4>
    </a>

    <div class="container-fluid">
        <h1 class="text-center mt-5">My Ads</h1>
    </div>

    <div class="container my-5" id="allPro">
        <?php if (isset($_GET['listed'])): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                Your item has been listed successfully!
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <div id="actionError" class="alert alert-danger d-none" role="alert"></div>

        <?php if (!$products): ?>
            <div class="text-center py-5">
                <p class="text-muted fs-5 mb-3">You haven't listed anything yet.</p>
                <a href="seller.php" class="btn btn-danger rounded-pill px-4 fw-semibold" style="background:#e63946; border:none;">+ Sell Item</a>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($products as $row):
                    $img = basename((string) $row['images']); ?>
                    <div class="col-3 my-2">
                        <div class="card d-flex flex-column justify-content-center align-items-center removeBord parent p-1">
                            <img src="upload/<?= h(rawurlencode($img)) ?>" alt="<?= h($row['productName']) ?>" class="card-img-top imgCard mt-1">
                            <h5 class="card-title mt-1 text-center"><strong><?= h($row['productName']) ?></strong></h5>
                            <h6 class="card-title">₹<?= number_format((int) $row['price']) ?></h6>
                            <div class="mb-2">
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                                    data-action="edit" data-product-id="<?= (int) $row['productId'] ?>"
                                    data-product="<?= h(json_encode($row, $jsonFlags)) ?>">Edit</button>
                                <button type="button" class="btn btn-danger" data-action="del"
                                    data-product-id="<?= (int) $row['productId'] ?>">Delete</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Product Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="editError" class="alert alert-danger d-none" role="alert"></div>
                    <div class="d-flex">
                        <div class="modInp">
                            <img id="imgProd" src="" alt=""
                                style="width: 200px; height: 200px; overflow: hidden; object-fit: cover; object-position: center;"
                                class="img-thumbnail">
                            <input class="mt-3 modInp" type="file" id="imageNew"
                                accept="image/jpeg,image/png,image/webp,image/gif,.jpg,.jpeg,.png,.webp,.gif">
                            <small class="text-muted d-block">JPG, PNG, WebP or GIF, max 5 MB.</small>
                        </div>
                        <div class="mx-3 container-fluid">
                            <div class="input-group mb-3">
                                <span class="input-group-text">Product Name</span>
                                <input type="text" class="form-control" id="productName" maxlength="255" aria-label="Product name">
                            </div>
                            <div class="input-group mb-3">
                                <label class="input-group-text formIt" for="category">Category</label>
                                <select class="form-select" id="category">
                                    <option value="F">Furniture</option>
                                    <option value="E">Electronics</option>
                                    <option value="L">Lifestyle</option>
                                </select>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Brand</span>
                                <input type="text" class="form-control" id="brand" maxlength="255" aria-label="Brand">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Size</span>
                                <input type="text" class="form-control" id="size" maxlength="90" aria-label="Size">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Price</span>
                                <input type="number" class="form-control" id="price" min="0" max="99999999" step="1" aria-label="Price">
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text">Description</span>
                        <input type="text" class="form-control" id="description" maxlength="255" aria-label="Description">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text">Seller's Name</span>
                        <input type="text" class="form-control" id="sellersName" aria-label="Seller's name" readonly>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text">Location</span>
                        <input type="text" class="form-control" id="location" maxlength="255" aria-label="Location">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" style="width: 100%;" class="btn btn-primary" id="editConf">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button id="confirmDeleteButton" type="button" class="btn btn-danger">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const MAX_IMAGE_BYTES = 5 * 1024 * 1024;
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const confirmModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            const fields = ['productName', 'category', 'brand', 'size', 'description', 'price', 'location'];
            let editId = 0;
            let deleteId = 0;

            function showError(boxId, message) {
                const box = document.getElementById(boxId);
                box.textContent = message;
                box.classList.remove('d-none');
            }

            function sendData(payload, errorBoxId, button) {
                if (button) button.disabled = true;
                return fetch('./php/edDel.php', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrf },
                    body: JSON.stringify(payload)
                })
                    .then(res => res.json().catch(() => ({ success: false, message: 'Unexpected server response.' })))
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            showError(errorBoxId, data.message || 'Something went wrong.');
                        }
                    })
                    .catch(() => showError(errorBoxId, 'Network error. Please try again.'))
                    .finally(() => { if (button) button.disabled = false; });
            }

            document.getElementById('allPro').addEventListener('click', (event) => {
                const btn = event.target.closest('button[data-action]');
                if (!btn) return;

                if (btn.dataset.action === 'del') {
                    deleteId = parseInt(btn.dataset.productId, 10) || 0;
                    confirmModal.show();
                } else if (btn.dataset.action === 'edit') {
                    const product = JSON.parse(btn.dataset.product);
                    editId = parseInt(btn.dataset.productId, 10) || 0;
                    document.getElementById('editError').classList.add('d-none');
                    document.getElementById('imageNew').value = '';
                    document.getElementById('imgProd').src = 'upload/' + encodeURIComponent(product.images || '');
                    fields.forEach(name => { document.getElementById(name).value = product[name] ?? ''; });
                    document.getElementById('sellersName').value = product.sellersName || '';
                }
            });

            // Bound once; uses whichever product was picked last.
            document.getElementById('confirmDeleteButton').addEventListener('click', (event) => {
                if (!deleteId) return;
                confirmModal.hide();
                sendData({ action: 'delete', product_id: deleteId }, 'actionError', event.currentTarget);
            });

            document.getElementById('editConf').addEventListener('click', (event) => {
                const button = event.currentTarget;
                const file = document.getElementById('imageNew').files[0];
                const values = {};
                fields.forEach(name => { values[name] = document.getElementById(name).value; });

                const submit = (imageData) => {
                    const payload = { action: 'edit', product_id: editId, fields: values };
                    if (imageData) payload.image = { data: imageData };
                    sendData(payload, 'editError', button);
                };

                if (!file) {
                    submit(null);
                    return;
                }
                if (file.size > MAX_IMAGE_BYTES) {
                    showError('editError', 'Image is too large (max 5 MB).');
                    return;
                }
                const reader = new FileReader();
                reader.onload = () => submit(reader.result);
                reader.onerror = () => showError('editError', 'Could not read the selected image.');
                reader.readAsDataURL(file);
            });
        })();
    </script>

    <?php include_once __DIR__ . '/includes/footer2.php'; ?>
</body>

</html>
