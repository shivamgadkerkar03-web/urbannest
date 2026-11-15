<?php
require_once __DIR__ . '/../includes/market.php';

$me = un_json_endpoint();
$req = un_json_body();

$productId = (int) ($req['product_id'] ?? 0);
$body = trim(is_string($req['body'] ?? null) ? $req['body'] : '');

if ($productId <= 0) {
    json_fail(400, 'Missing product.');
}
if ($body === '') {
    json_fail(400, 'Message cannot be empty.');
}
if (un_str_len($body) > UN_MESSAGE_MAX) {
    json_fail(400, 'Message is too long (max ' . UN_MESSAGE_MAX . ' characters).');
}

try {
    $product = un_fetch_product($con, $productId);
    if ($product === null) {
        json_fail(404, 'This listing is no longer available.');
    }
    [$receiver, $error] = un_resolve_partner($con, $me, $productId, $product, (int) ($req['receiver_id'] ?? 0));
    if ($error !== '') {
        json_fail(403, $error);
    }
    if ($receiver === $me) {
        json_fail(400, 'You cannot message yourself.');
    }

    $stmt = mysqli_prepare($con, 'INSERT INTO product_messages (product_id, sender_id, receiver_id, body) VALUES (?, ?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'iiis', $productId, $me, $receiver, $body);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    un_mark_read($con, $me, $receiver, $productId);

    un_json_ok(un_thread_payload($con, $me, $receiver, $productId, $product));
} catch (Throwable $e) {
    error_log('sendMesssage.php: ' . $e->getMessage());
    json_fail(500, 'Could not send the message. Please try again.');
}
