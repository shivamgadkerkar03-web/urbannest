<?php
require_once __DIR__ . '/../includes/market.php';

$me = un_json_endpoint();
$req = un_json_body();
$action = isset($req['action']) && is_string($req['action']) ? $req['action'] : '';

try {
    switch ($action) {
        case 'list':
            un_json_ok([
                'success'       => true,
                'me'            => $me,
                'unread'        => un_unread_total($con, $me),
                'conversations' => un_conversations($con, $me),
            ]);
            break;

        case 'unread':
            un_json_ok(['success' => true, 'unread' => un_unread_total($con, $me)]);
            break;

        case 'thread':
            $productId = (int) ($req['product_id'] ?? 0);
            if ($productId <= 0) {
                json_fail(400, 'Missing product.');
            }
            $product = un_fetch_product($con, $productId);
            [$other, $error] = un_resolve_partner($con, $me, $productId, $product, (int) ($req['other_id'] ?? 0));
            if ($error !== '') {
                json_fail($product === null ? 404 : 403, $error);
            }
            un_mark_read($con, $me, $other, $productId);
            un_json_ok(un_thread_payload($con, $me, $other, $productId, $product));
            break;

        default:
            json_fail(400, 'Unknown action.');
    }
} catch (Throwable $e) {
    error_log('chat.php: ' . $e->getMessage());
    json_fail(500, 'Could not load messages. Please try again.');
}
