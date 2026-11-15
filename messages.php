<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
include_once('includes/roommate-functions.php');

if (empty($_SESSION['pgasuid'])) { header('location: signin.php'); exit; }
$uid = (int)$_SESSION['pgasuid'];
$unread = getUnreadMessageCount($con, $uid);
$conversations = getUserConversations($con, $uid);
?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Messages – UrbanNest</title>
    <meta name="description" content="Private messages with your roommate connections on UrbanNest.">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,400,300,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="css/linearicons.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .msg-hero { background: linear-gradient(135deg, #1a1a2e, #0f3460); color:#fff; padding: 50px 0 30px; }
        .conv-list { background: #fff; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,.08); overflow: hidden; }
        .conv-item { display: flex; align-items: center; gap: 14px; padding: 16px 20px; border-bottom: 1px solid #f5f5f5; text-decoration: none; color: inherit; transition: background .2s; }
        .conv-item:hover { background: #f8f9fa; }
        .conv-item.unread { background: #fff8f0; }
        .conv-avatar { width: 52px; height: 52px; border-radius: 50%; object-fit: cover; flex-shrink: 0; }
        .conv-name { font-weight: 700; font-size: .95rem; }
        .conv-last { font-size: .82rem; color: #888; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px; }
        .conv-time { font-size: .75rem; color: #aaa; white-space: nowrap; }
        .unread-dot { width: 10px; height: 10px; border-radius: 50%; background: #ff3333; flex-shrink: 0; }
        .empty-msg { text-align: center; padding: 60px 20px; }
        .empty-msg .icon { font-size: 4rem; color: #ddd; margin-bottom: 16px; }
    </style>
</head>
<body>
<?php include_once('includes/header.php'); ?>

<div class="msg-hero">
    <div class="container">
        <h1 style="font-size:2rem;font-weight:700;">💬 Messages</h1>
        <p style="opacity:.8;"><?= $unread > 0 ? "<strong style='color:#ff9800;'>$unread unread message".($unread>1?'s':'')."</strong>" : 'All messages read' ?></p>
    </div>
</div>

<section class="section-gap">
    <div class="container" style="max-width:700px;">
        <?php if (empty($conversations)): ?>
        <div class="empty-msg">
            <div class="icon">💬</div>
            <h4>No conversations yet</h4>
            <p class="text-muted">Accept or receive a roommate connection request to start messaging.</p>
            <a href="find-roommate.php" class="btn btn-danger rounded-pill px-4">Find Roommates</a>
        </div>
        <?php else: ?>
        <div class="conv-list">
            <?php foreach($conversations as $conv):
                $isUnread = $conv['unread_count'] > 0;
            ?>
            <a href="chat.php?user=<?= $conv['other_id'] ?>" class="conv-item <?= $isUnread ? 'unread' : '' ?>">
                <img src="<?= getProfilePhoto($conv['other_photo'] ?? '') ?>" class="conv-avatar" onerror="this.src='img/default-profile.png'" alt="<?= esc($conv['other_name']) ?>">
                <div class="flex-grow-1 overflow-hidden">
                    <div class="conv-name"><?= esc($conv['other_name']) ?></div>
                    <div class="conv-last"><?= $conv['last_message'] ? esc(substr($conv['last_message'], 0, 60)) : 'Start a conversation' ?></div>
                </div>
                <div class="d-flex flex-column align-items-end gap-2">
                    <span class="conv-time"><?= $conv['last_msg_time'] ? timeAgo($conv['last_msg_time']) : '' ?></span>
                    <?php if ($isUnread): ?>
                    <span class="badge rounded-pill bg-danger" style="font-size:.7rem;"><?= $conv['unread_count'] ?></span>
                    <?php endif; ?>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include_once('includes/footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/vendor/jquery-2.2.4.min.js"></script>
</body>
</html>
