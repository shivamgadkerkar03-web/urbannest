<?php
require_once __DIR__ . '/../includes/market.php';

$me = un_json_endpoint();
$req = un_json_body();

$action = isset($req['action']) && is_string($req['action']) ? $req['action'] : '';
$productId = (int) ($req['product_id'] ?? 0);
if ($productId <= 0) {
    json_fail(400, 'Missing product.');
}

/**
 * Decodes a base64 data URL, validates it as an image and stores it under a
 * random name in the upload folder. Returns [string $name, string $error].
 */
function un_store_data_url_image(string $dataUrl): array
{
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];

    if (!preg_match('#^data:image/[a-z0-9.+-]+;base64,#i', $dataUrl, $m)) {
        return ['', 'Invalid image data.'];
    }
    $encoded = substr($dataUrl, strlen($m[0]));
    if (strlen($encoded) > (int) ceil(UN_MAX_IMAGE_BYTES * 4 / 3) + 4) {
        return ['', 'Image is too large (max ' . round(UN_MAX_IMAGE_BYTES / 1048576) . ' MB).'];
    }
    $binary = base64_decode($encoded, true);
    if ($binary === false || $binary === '') {
        return ['', 'Invalid image data.'];
    }
    if (strlen($binary) > UN_MAX_IMAGE_BYTES) {
        return ['', 'Image is too large (max ' . round(UN_MAX_IMAGE_BYTES / 1048576) . ' MB).'];
    }
    $mime = (new finfo(FILEINFO_MIME_TYPE))->buffer($binary);
    if (!isset($allowed[$mime]) || @getimagesizefromstring($binary) === false) {
        return ['', 'Only JPG, PNG, WebP or GIF images are allowed.'];
    }
    if (!is_dir(UN_UPLOAD_DIR) && !mkdir(UN_UPLOAD_DIR, 0755, true)) {
        return ['', 'Upload folder is not writable.'];
    }
    protect_upload_dir(UN_UPLOAD_DIR);

    $name = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
    if (file_put_contents(UN_UPLOAD_DIR . DIRECTORY_SEPARATOR . $name, $binary) === false) {
        return ['', 'Could not save the image.'];
    }
    return [$name, ''];
}

try {
    $product = un_fetch_product($con, $productId);
    if ($product === null) {
        json_fail(404, 'Product not found.');
    }
    if ((int) $product['userId'] !== $me) {
        json_fail(403, 'You can only change your own listings.');
    }

    if ($action === 'delete') {
        $stmt = mysqli_prepare($con, 'DELETE FROM product WHERE productId = ? AND userId = ?');
        mysqli_stmt_bind_param($stmt, 'ii', $productId, $me);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        // Product ids can be reused after a restart on older MariaDB, so drop the chat too.
        $stmt = mysqli_prepare($con, 'DELETE FROM product_messages WHERE product_id = ?');
        mysqli_stmt_bind_param($stmt, 'i', $productId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        un_delete_image($product['images']);
        un_json_ok(['success' => true, 'message' => 'Listing deleted.']);
    }

    if ($action !== 'edit') {
        json_fail(400, 'Unknown action.');
    }

    $fields = is_array($req['fields'] ?? null) ? $req['fields'] : [];
    [$clean, $error] = un_validate_product($fields);
    if ($error !== '') {
        json_fail(422, $error);
    }

    $newImage = '';
    $dataUrl = $req['image']['data'] ?? null;
    if (is_string($dataUrl) && $dataUrl !== '') {
        [$newImage, $error] = un_store_data_url_image($dataUrl);
        if ($error !== '') {
            json_fail(422, $error);
        }
    }
    $image = $newImage !== '' ? $newImage : $product['images'];

    $stmt = mysqli_prepare($con, 'UPDATE product
        SET productName = ?, category = ?, brand = ?, size = ?, description = ?, price = ?, location = ?, images = ?
        WHERE productId = ? AND userId = ?');
    mysqli_stmt_bind_param(
        $stmt,
        'sssssissii',
        $clean['productName'],
        $clean['category'],
        $clean['brand'],
        $clean['size'],
        $clean['description'],
        $clean['price'],
        $clean['location'],
        $image,
        $productId,
        $me
    );
    try {
        mysqli_stmt_execute($stmt);
    } catch (Throwable $e) {
        if ($newImage !== '') {
            un_delete_image($newImage);
        }
        throw $e;
    }
    mysqli_stmt_close($stmt);

    if ($newImage !== '') {
        un_delete_image($product['images']);
    }
    un_json_ok(['success' => true, 'message' => 'Listing updated.']);
} catch (Throwable $e) {
    error_log('edDel.php: ' . $e->getMessage());
    json_fail(500, 'Something went wrong. Please try again.');
}
