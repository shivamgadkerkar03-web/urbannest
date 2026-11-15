<?php
require_once __DIR__ . '/includes/security.php';
include('includes/dbconnection.php');
include_once('includes/roommate-functions.php');

$isAjax = isset($_GET['poll']) || ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_send']));
if ($isAjax && current_user_id() <= 0) {
    json_fail(401, 'Please log in again.');
}
$uid = require_user('signin.php');
$other_id = (int)($_GET['user'] ?? 0);

if (!$other_id || $other_id === $uid) {
    if ($isAjax) json_fail(400, 'Invalid conversation.');
    redirect_to('messages.php');
}

// Only connected users can chat
if (!areConnected($con, $uid, $other_id)) {
    if ($isAjax) json_fail(403, 'Not connected');
    redirect_to('messages.php?error=not_connected');
}

// Get other user info
$stmt = mysqli_prepare($con, "SELECT u.FullName, rp.profile_photo FROM tbluser u LEFT JOIN roommate_profiles rp ON rp.user_id = u.ID WHERE u.ID = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, 'i', $other_id);
mysqli_stmt_execute($stmt);
$or = mysqli_stmt_get_result($stmt);
$otherUser = mysqli_fetch_assoc($or);
if (!$otherUser) {
    if ($isAjax) json_fail(404, 'User not found.');
    redirect_to('messages.php');
}

// Get/create conversation
$conv_id = getOrCreateConversation($con, $uid, $other_id);

// Handle AJAX send message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_send'])) {
    if (!csrf_valid()) {
        json_fail(400, 'Session expired. Please refresh the page.');
    }
    header('Content-Type: application/json');
    $msg_text = trim($_POST['message'] ?? '');
    if (!$msg_text || strlen($msg_text) > 2000) {
        echo json_encode(['success' => false]);
        exit;
    }
    // Verify connection again
    if (!areConnected($con, $uid, $other_id)) {
        echo json_encode(['success' => false, 'error' => 'Not connected']);
        exit;
    }
    $stmt2 = mysqli_prepare($con, "INSERT INTO roommate_messages (conversation_id, sender_id, message) VALUES (?,?,?)");
    mysqli_stmt_bind_param($stmt2, 'iis', $conv_id, $uid, $msg_text);
    if (mysqli_stmt_execute($stmt2)) {
        $new_msg_id = mysqli_stmt_insert_id($stmt2);
        if (!$new_msg_id) {
            $new_msg_id = mysqli_insert_id($con);
        }
        // Update conversation last_message_at
        mysqli_query($con, "UPDATE roommate_conversations SET last_message_at = NOW() WHERE id = $conv_id");
        // Use the database timestamp so the bubble matches messages loaded later
        $created = date('Y-m-d H:i:s');
        $tq = mysqli_prepare($con, "SELECT created_at FROM roommate_messages WHERE id = ?");
        mysqli_stmt_bind_param($tq, 'i', $new_msg_id);
        mysqli_stmt_execute($tq);
        $trow = mysqli_fetch_assoc(mysqli_stmt_get_result($tq));
        if ($trow) {
            $created = $trow['created_at'];
        }
        echo json_encode([
            'success' => true,
            'id' => (int)$new_msg_id,
            'timestamp' => $created,
            'time' => date('h:i A', strtotime($created))
        ]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}

// Handle AJAX poll for new messages
if (isset($_GET['poll'])) {
    header('Content-Type: application/json');
    $since_id = (int)($_GET['since'] ?? 0);
    $pstmt = mysqli_prepare($con, "SELECT rm.*, u.FullName as sender_name FROM roommate_messages rm JOIN tbluser u ON rm.sender_id = u.ID WHERE rm.conversation_id = ? AND rm.id > ? ORDER BY rm.created_at ASC, rm.id ASC LIMIT 50");
    mysqli_stmt_bind_param($pstmt, 'ii', $conv_id, $since_id);
    mysqli_stmt_execute($pstmt);
    $pres = mysqli_stmt_get_result($pstmt);
    $msgs = [];
    while ($row = mysqli_fetch_assoc($pres)) {
        $msgs[] = [
            'id' => (int)$row['id'],
            'sender_id' => (int)$row['sender_id'],
            'message' => htmlspecialchars($row['message'], ENT_QUOTES, 'UTF-8'),
            'created_at' => $row['created_at'],
            'time' => date('h:i A', strtotime($row['created_at'])),
            'sender_name' => htmlspecialchars($row['sender_name'], ENT_QUOTES, 'UTF-8')
        ];
    }
    // Mark as read
    mysqli_query($con, "UPDATE roommate_messages SET is_read = 1 WHERE conversation_id = $conv_id AND sender_id != $uid AND is_read = 0");
    echo json_encode($msgs);
    exit;
}

// Load messages
$msgs_q = mysqli_prepare($con, "SELECT rm.*, u.FullName as sender_name FROM roommate_messages rm JOIN tbluser u ON rm.sender_id = u.ID WHERE rm.conversation_id = ? ORDER BY rm.created_at ASC, rm.id ASC LIMIT 100");
mysqli_stmt_bind_param($msgs_q, 'i', $conv_id);
mysqli_stmt_execute($msgs_q);
$msgs_r = mysqli_stmt_get_result($msgs_q);
$messages = [];
$last_id = 0;
while ($row = mysqli_fetch_assoc($msgs_r)) {
    $messages[] = $row;
    $last_id = max($last_id, $row['id']);
}

// Mark received as read
mysqli_query($con, "UPDATE roommate_messages SET is_read = 1 WHERE conversation_id = $conv_id AND sender_id != $uid AND is_read = 0");

$myName = $_SESSION['FullName'] ?? 'You';
?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
	<?php include __DIR__ . '/includes/head.php'; ?>
    <title>Chat with <?= esc($otherUser['FullName']) ?> – UrbanNest</title>
    <style>
        .chat-wrapper { display: flex; flex-direction: column; height: calc(100vh - 200px); min-height: 520px; max-width: 750px; margin: 30px auto; background:#fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,.1); overflow: hidden; }
        .chat-header { background: linear-gradient(135deg, #1a1a2e, #0f3460); color:#fff; padding: 16px 20px; display: flex; align-items: center; gap: 12px; flex-shrink: 0; }
        .chat-header img { width:44px;height:44px;border-radius:50%;object-fit:cover; }
        .chat-header h5 { margin:0;font-size:1rem;font-weight:700; }
        .chat-body { flex: 1; overflow-y: auto; padding: 20px; background: #f8f9fa; display: flex; flex-direction: column; gap: 10px; }
        .msg-bubble { display: flex; flex-direction: column; width: fit-content; max-width: 75%; word-break: break-word; }
        .msg-bubble.me { align-self: flex-end; align-items: flex-end; margin-left: auto; }
        .msg-bubble.them { align-self: flex-start; align-items: flex-start; margin-right: auto; }
        .msg-bubble .bubble { padding: 10px 16px; border-radius: 18px; font-size: .92rem; line-height: 1.45; display: inline-block; }
        .msg-bubble.me .bubble { background: #ff3333; color: #fff; border-bottom-right-radius: 4px; box-shadow: 0 2px 8px rgba(255,51,51,0.2); }
        .msg-bubble.them .bubble { background: #fff; color: #222; border: 1px solid #e2e8f0; border-bottom-left-radius: 4px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); }
        .msg-bubble .time { font-size: .72rem; color: #8c98a4; margin-top: 4px; padding: 0 4px; }
        .msg-bubble.me .time { text-align: right; }
        .chat-footer { padding: 14px 16px; background: #fff; border-top: 1px solid #eee; flex-shrink: 0; }
        .chat-footer form { display: flex; gap: 10px; }
        .chat-footer input { border-radius: 30px; border: 1px solid #ddd; padding: 10px 18px; font-size: .9rem; flex: 1; outline: none; }
        .chat-footer input:focus { border-color: #ff3333; }
        .chat-footer button { border-radius: 30px; background: #ff3333; color: #fff; border: none; padding: 10px 22px; font-weight: 700; flex-shrink: 0; }
        .chat-footer button:hover { background: #c0392b; }
        .date-divider { align-self: center; text-align: center; margin: 12px 0 6px; }
        .date-divider span { background: #e9ecef; color: #6c757d; font-size: .72rem; padding: 4px 14px; border-radius: 20px; font-weight: 500; }
    </style>
</head>
<body>
<?php include_once('includes/header.php'); ?>

<div class="container" style="padding-top:10px;">
    <div class="chat-wrapper">
        <!-- Header -->
        <div class="chat-header">
            <a href="messages.php" class="text-white me-2" style="font-size:1.2rem;">&#8592;</a>
            <img src="<?= getProfilePhoto($otherUser['profile_photo'] ?? '') ?>" onerror="this.src='img/default-profile.png'" alt="<?= esc($otherUser['FullName']) ?>">
            <div>
                <h5><?= esc($otherUser['FullName']) ?></h5>
                <small style="opacity:.7;">Connected roommate</small>
            </div>
        </div>

        <!-- Messages -->
        <div class="chat-body" id="chatBody">
            <?php
            $prevDate = '';
            foreach ($messages as $m):
                $isMe = $m['sender_id'] == $uid;
                $msgDate = date('D, d M Y', strtotime($m['created_at']));
                if ($msgDate !== $prevDate): $prevDate = $msgDate; ?>
            <div class="date-divider"><span><?= $msgDate ?></span></div>
            <?php endif; ?>
            <div class="msg-bubble <?= $isMe ? 'me' : 'them' ?>" data-id="<?= (int)$m['id'] ?>">
                <div class="bubble"><?= nl2br(esc($m['message'])) ?></div>
                <div class="time"><?= date('h:i A', strtotime($m['created_at'])) ?></div>
            </div>
            <?php endforeach; ?>
            <?php if (empty($messages)): ?>
            <div class="text-center text-muted py-4">
                <p>No messages yet. Say hello! 👋</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Input -->
        <div class="chat-footer">
            <form id="msgForm" autocomplete="off">
                <input type="text" id="msgInput" placeholder="Type a message..." maxlength="2000" autofocus>
                <button type="submit"><i class="fa fa-paper-plane"></i></button>
            </form>
        </div>
    </div>
</div>

<?php include_once('includes/footer.php'); ?>
<script>
const ME_ID = <?= $uid ?>;
const OTHER_ID = <?= $other_id ?>;
let lastId = <?= (int)$last_id ?>;
const CSRF_TOKEN = <?= json_encode(csrf_token()) ?>;
const chatBody = document.getElementById('chatBody');

function scrollBottom() {
    chatBody.scrollTop = chatBody.scrollHeight;
}
scrollBottom();

function formatMessageTime(rawTime, rawDate) {
    if (rawTime) return rawTime;
    if (rawDate) {
        try {
            const clean = String(rawDate).replace(/-/g, '/').replace('T', ' ');
            const d = new Date(clean);
            if (!isNaN(d.getTime())) {
                return d.toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'});
            }
        } catch (e) {}
    }
    return new Date().toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'});
}

function addBubble(msg, isMe) {
    if (!msg || typeof msg.id === 'undefined' || msg.id === null) return;
    // Prevent duplicate bubbles in DOM
    if (chatBody.querySelector(`.msg-bubble[data-id="${msg.id}"]`)) return;

    // Remove empty placeholder if any
    const emptyMsg = chatBody.querySelector('.text-muted');
    if (emptyMsg) emptyMsg.remove();

    const timeStr = formatMessageTime(msg.time, msg.created_at);
    const div = document.createElement('div');
    div.className = 'msg-bubble ' + (isMe ? 'me' : 'them');
    div.setAttribute('data-id', msg.id);
    div.innerHTML = `<div class="bubble">${msg.message.replace(/\n/g,'<br>')}</div><div class="time">${timeStr}</div>`;
    chatBody.appendChild(div);
    scrollBottom();
}

// Send message
document.getElementById('msgForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const input = document.getElementById('msgInput');
    const text = input.value.trim();
    if (!text) return;
    input.value = '';
    
    fetch('chat.php?user=<?= $other_id ?>', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'ajax_send=1&csrf_token=' + encodeURIComponent(CSRF_TOKEN) + '&message=' + encodeURIComponent(text)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Server-side already escapes via htmlspecialchars in poll responses,
            // so we do the same here for consistency with addBubble's innerHTML usage
            const safeText = text.replace(/&/g, '&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
            addBubble({
                id: data.id,
                message: safeText,
                created_at: data.timestamp,
                time: data.time || ''
            }, true);
            lastId = Math.max(lastId, data.id);
        } else {
            // Re-fill the input so the user can retry
            input.value = text;
            console.error('Send failed:', data.error || 'Unknown error');
        }
    })
    .catch(err => {
        input.value = text;
        console.error('Network error:', err);
    });
});

// Poll for new messages every 3 seconds
function pollMessages() {
    // Keep lastId synchronized with DOM
    const bubbles = chatBody.querySelectorAll('.msg-bubble[data-id]');
    let maxId = lastId;
    bubbles.forEach(b => {
        const id = parseInt(b.getAttribute('data-id'), 10);
        if (id > maxId) maxId = id;
    });
    lastId = maxId;

    fetch(`chat.php?user=<?= $other_id ?>&poll=1&since=${lastId}`)
    .then(r => r.json())
    .then(msgs => {
        if (!Array.isArray(msgs)) return;
        msgs.forEach(m => {
            const isMe = parseInt(m.sender_id, 10) === ME_ID;
            if (!isMe) {
                addBubble(m, false);
            }
            lastId = Math.max(lastId, parseInt(m.id, 10));
        });
    })
    .catch(() => {});
}
setInterval(pollMessages, 3000);
</script>
</body>
</html>
