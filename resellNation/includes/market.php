<?php
// Shared helpers for the Urban Nation marketplace pages and endpoints.
require_once __DIR__ . '/../../includes/security.php';
require_once __DIR__ . '/../connect.php';

const UN_UPLOAD_DIR = __DIR__ . '/../upload';
const UN_MAX_IMAGE_BYTES = 5242880;
const UN_CATEGORIES = ['F' => 'Furniture', 'E' => 'Electronics', 'L' => 'Lifestyle'];

// Editable product text columns and their maximum lengths (see db/urbanest.sql).
const UN_PRODUCT_FIELDS = [
    'productName' => 255,
    'category'    => 255,
    'brand'       => 255,
    'size'        => 90,
    'description' => 255,
    'location'    => 255,
];

const UN_FIELD_LABELS = [
    'productName' => 'Item title',
    'category'    => 'Category',
    'brand'       => 'Brand',
    'size'        => 'Size',
    'description' => 'Description',
    'location'    => 'Pickup location',
];

const UN_MESSAGE_MAX = 2000;

function un_category_label(?string $code): string
{
    $code = (string) $code;
    if ($code === 'Hm') {
        return 'Furniture';
    }
    return UN_CATEGORIES[$code] ?? ($code !== '' ? $code : 'Thrift');
}

function un_str_len(string $s): int
{
    return function_exists('mb_strlen') ? mb_strlen($s, 'UTF-8') : strlen($s);
}

/**
 * Validates product fields from user input.
 * Returns [array $clean, string $error]. $error is '' when valid.
 */
function un_validate_product(array $in): array
{
    $clean = [];
    foreach (UN_PRODUCT_FIELDS as $field => $max) {
        $value = trim((string) ($in[$field] ?? ''));
        if (un_str_len($value) > $max) {
            $label = UN_FIELD_LABELS[$field] ?? $field;
            return [[], "{$label} is too long (max {$max} characters)."];
        }
        $clean[$field] = $value;
    }
    foreach (['productName', 'location', 'description'] as $field) {
        if ($clean[$field] === '') {
            return [[], UN_FIELD_LABELS[$field] . ' is required.'];
        }
    }
    if (!isset(UN_CATEGORIES[$clean['category']])) {
        return [[], 'Please choose a valid category.'];
    }
    $price = filter_var($in['price'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 99999999]]);
    if ($price === false) {
        return [[], 'Please enter a valid price.'];
    }
    $clean['price'] = $price;
    return [$clean, ''];
}

function un_delete_image(?string $name): void
{
    $name = basename((string) $name);
    if ($name === '' || $name === '.' || $name === '..') {
        return;
    }
    $path = UN_UPLOAD_DIR . DIRECTORY_SEPARATOR . $name;
    if (is_file($path)) {
        @unlink($path);
    }
}

function un_fetch_product(mysqli $con, int $productId): ?array
{
    if ($productId <= 0) {
        return null;
    }
    $stmt = mysqli_prepare($con, 'SELECT * FROM product WHERE productId = ?');
    mysqli_stmt_bind_param($stmt, 'i', $productId);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $row ?: null;
}

function un_user_name(mysqli $con, int $userId): string
{
    $stmt = mysqli_prepare($con, 'SELECT FullName FROM tbluser WHERE ID = ?');
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $row ? (string) $row['FullName'] : 'User #' . $userId;
}

/**
 * Conversations for a user, one per (product, other user), newest first.
 */
function un_conversations(mysqli $con, int $me): array
{
    $sql = "SELECT t.product_id, t.other_id, MAX(t.created_at) AS last_at,
                   SUM(CASE WHEN t.receiver_id = ? AND t.is_read = 0 THEN 1 ELSE 0 END) AS unread,
                   p.productName, p.images, p.price, p.userId AS seller_id, u.FullName AS other_name
            FROM (
                SELECT product_id, receiver_id, is_read, created_at,
                       CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END AS other_id
                FROM product_messages
                WHERE sender_id = ? OR receiver_id = ?
            ) t
            LEFT JOIN product p ON p.productId = t.product_id
            LEFT JOIN tbluser u ON u.ID = t.other_id
            GROUP BY t.product_id, t.other_id, p.productName, p.images, p.price, p.userId, u.FullName
            ORDER BY last_at DESC";
    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        return [];
    }
    mysqli_stmt_bind_param($stmt, 'iiii', $me, $me, $me, $me);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $out = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $sellerId = $row['seller_id'] !== null ? (int) $row['seller_id'] : null;
        $out[] = [
            'product_id'    => (int) $row['product_id'],
            'product_name'  => $row['productName'] ?? 'Removed listing',
            'product_image' => $row['images'] ?? '',
            'product_price' => $row['price'] !== null ? (int) $row['price'] : null,
            'other_id'      => (int) $row['other_id'],
            'other_name'    => $row['other_name'] ?? ('User #' . $row['other_id']),
            'role'          => $sellerId === $me ? 'seller' : 'buyer',
            'unread'        => (int) $row['unread'],
            'last_at'       => $row['last_at'],
        ];
    }
    mysqli_stmt_close($stmt);
    return $out;
}

function un_unread_total(mysqli $con, int $me): int
{
    $stmt = mysqli_prepare($con, 'SELECT COUNT(*) FROM product_messages WHERE receiver_id = ? AND is_read = 0');
    if (!$stmt) {
        return 0;
    }
    mysqli_stmt_bind_param($stmt, 'i', $me);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    return (int) $count;
}

/**
 * Works out who the current user is talking to about a product.
 * Buyers always talk to the seller; sellers must name the buyer.
 * Returns [int $otherId, string $error].
 */
function un_resolve_partner(mysqli $con, int $me, int $productId, ?array $product, int $requestedOther): array
{
    if ($product !== null) {
        $sellerId = (int) $product['userId'];
        if ($sellerId !== $me) {
            return [$sellerId, ''];
        }
        if ($requestedOther <= 0 || $requestedOther === $me) {
            return [0, 'Choose a conversation to open.'];
        }
        // A seller may only reply to someone who has already asked about this item.
        $stmt = mysqli_prepare($con, 'SELECT 1 FROM product_messages WHERE product_id = ? AND sender_id = ? AND receiver_id = ? LIMIT 1');
        if (!$stmt) {
            return [0, 'Unable to verify buyer.'];
        }
        mysqli_stmt_bind_param($stmt, 'iii', $productId, $requestedOther, $me);
        mysqli_stmt_execute($stmt);
        $ok = (bool) mysqli_fetch_row(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);
        return $ok ? [$requestedOther, ''] : [0, 'This buyer has not contacted you about this item.'];
    }

    // Listing was removed: still allow reading an existing thread.
    if ($requestedOther <= 0 || $requestedOther === $me) {
        return [0, 'This listing is no longer available.'];
    }
    return [$requestedOther, ''];
}

function un_thread(mysqli $con, int $me, int $other, int $productId): array
{
    $stmt = mysqli_prepare($con, 'SELECT id, sender_id, receiver_id, body, is_read, created_at
        FROM product_messages
        WHERE product_id = ? AND ((sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?))
        ORDER BY created_at ASC, id ASC');
    if (!$stmt) {
        return [];
    }
    mysqli_stmt_bind_param($stmt, 'iiiii', $productId, $me, $other, $other, $me);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $messages = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $messages[] = [
            'id'         => (int) $row['id'],
            'sender_id'  => (int) $row['sender_id'],
            'body'       => $row['body'],
            'is_read'    => (bool) $row['is_read'],
            'created_at' => $row['created_at'],
        ];
    }
    mysqli_stmt_close($stmt);
    return $messages;
}

function un_mark_read(mysqli $con, int $me, int $other, int $productId): void
{
    $stmt = mysqli_prepare($con, 'UPDATE product_messages SET is_read = 1 WHERE product_id = ? AND sender_id = ? AND receiver_id = ? AND is_read = 0');
    if (!$stmt) {
        return;
    }
    mysqli_stmt_bind_param($stmt, 'iii', $productId, $other, $me);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function un_thread_payload(mysqli $con, int $me, int $other, int $productId, ?array $product): array
{
    return [
        'success'  => true,
        'me'       => $me,
        'other'    => ['id' => $other, 'name' => un_user_name($con, $other)],
        'product'  => $product === null ? null : [
            'id'        => (int) $product['productId'],
            'name'      => $product['productName'],
            'price'     => (int) $product['price'],
            'image'     => $product['images'],
            'seller_id' => (int) $product['userId'],
        ],
        'can_send' => $product !== null,
        'messages' => un_thread($con, $me, $other, $productId),
    ];
}

/** Common setup for JSON endpoints: no HTML errors, login + CSRF required. */
function un_json_endpoint(): int
{
    ini_set('display_errors', '0');
    header('Content-Type: application/json');
    header('X-Content-Type-Options: nosniff');
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        json_fail(405, 'Only POST requests are allowed.');
    }
    $me = current_user_id();
    if ($me <= 0) {
        json_fail(401, 'Please sign in to continue.');
    }
    if (!csrf_valid()) {
        json_fail(403, 'Your session has expired. Please refresh the page.');
    }
    return $me;
}

function un_json_body(): array
{
    $data = json_decode((string) file_get_contents('php://input'), true);
    return is_array($data) ? $data : [];
}

function un_json_ok(array $payload): void
{
    echo json_encode($payload);
    exit;
}
