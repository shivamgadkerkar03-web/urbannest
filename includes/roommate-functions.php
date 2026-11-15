<?php
/**
 * UrbanNest - Roommate Feature Shared Functions
 * Include this file in roommate pages: require_once('includes/roommate-functions.php');
 * Requires: $con (mysqli connection), $_SESSION['pgasuid'] (user id)
 */

require_once __DIR__ . '/security.php';

/**
 * Get roommate profile for a user
 */
function getRoommateProfile($con, $user_id) {
    $uid = (int)$user_id;
    $stmt = mysqli_prepare($con, "SELECT rp.*, u.FullName, u.Email, u.MobileNumber, u.dob FROM roommate_profiles rp JOIN tbluser u ON rp.user_id = u.ID WHERE rp.user_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $uid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

/**
 * Get all active roommate listings with optional filters
 */
function getRoommateListings($con, $filters = []) {
    $where = ["rl.is_active = 1"];
    $params = [];
    $types = "";

    if (!empty($filters['city'])) {
        $where[] = "rl.city LIKE ?";
        $params[] = "%" . $filters['city'] . "%";
        $types .= "s";
    }
    if (!empty($filters['accommodation_type'])) {
        $where[] = "rl.accommodation_type = ?";
        $params[] = $filters['accommodation_type'];
        $types .= "s";
    }
    if (!empty($filters['gender_preference'])) {
        $where[] = "(rl.gender_preference = ? OR rl.gender_preference = 'Any')";
        $params[] = $filters['gender_preference'];
        $types .= "s";
    }
    if (!empty($filters['budget_max'])) {
        $where[] = "rl.monthly_rent <= ?";
        $params[] = (int)$filters['budget_max'];
        $types .= "i";
    }
    if (!empty($filters['room_type'])) {
        $where[] = "rl.room_type = ?";
        $params[] = $filters['room_type'];
        $types .= "s";
    }

    $sql = "SELECT rl.*, u.FullName, u.Email FROM roommate_listings rl 
            JOIN tbluser u ON rl.user_id = u.ID 
            WHERE " . implode(" AND ", $where) . " 
            ORDER BY rl.created_at DESC";

    $stmt = mysqli_prepare($con, $sql);
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

/**
 * Get all active roommate seekers (need-room preferences)
 */
function getRoommatePreferences($con, $filters = []) {
    $where = ["rp.is_active = 1"];
    $params = [];
    $types = "";

    if (!empty($filters['city'])) {
        $where[] = "rp.preferred_city LIKE ?";
        $params[] = "%" . $filters['city'] . "%";
        $types .= "s";
    }
    if (!empty($filters['budget_max'])) {
        $where[] = "rp.budget_max <= ?";
        $params[] = (int)$filters['budget_max'];
        $types .= "i";
    }
    if (!empty($filters['accommodation_type']) && $filters['accommodation_type'] != 'Any') {
        $where[] = "(rp.accommodation_type = ? OR rp.accommodation_type = 'Any')";
        $params[] = $filters['accommodation_type'];
        $types .= "s";
    }
    if (!empty($filters['gender_preference'])) {
        $where[] = "(rp.gender_preference = ? OR rp.gender_preference = 'Any')";
        $params[] = $filters['gender_preference'];
        $types .= "s";
    }
    if (!empty($filters['room_type'])) {
        $where[] = "(rp.room_type = ? OR rp.room_type = 'Any')";
        $params[] = $filters['room_type'];
        $types .= "s";
    }

    $sql = "SELECT rp.*, u.FullName, u.Email, prof.profile_photo, prof.age, prof.gender, prof.food_preference, prof.smoking, prof.drinking 
            FROM roommate_preferences rp 
            JOIN tbluser u ON rp.user_id = u.ID 
            LEFT JOIN roommate_profiles prof ON prof.user_id = rp.user_id
            WHERE " . implode(" AND ", $where) . " 
            ORDER BY rp.created_at DESC";

    $stmt = mysqli_prepare($con, $sql);
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

/**
 * Get request status between two users (any direction)
 * Returns the request row or null
 */
function getRequestStatus($con, $user1_id, $user2_id) {
    $u1 = (int)$user1_id;
    $u2 = (int)$user2_id;
    $stmt = mysqli_prepare($con, "SELECT * FROM roommate_requests WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) LIMIT 1");
    mysqli_stmt_bind_param($stmt, 'iiii', $u1, $u2, $u2, $u1);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

/**
 * Check if two users are connected (mutually accepted)
 */
function areConnected($con, $user1_id, $user2_id) {
    $u1 = (int)$user1_id;
    $u2 = (int)$user2_id;
    $stmt = mysqli_prepare($con, "SELECT id FROM roommate_requests WHERE status = 'Accepted' AND ((sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)) LIMIT 1");
    mysqli_stmt_bind_param($stmt, 'iiii', $u1, $u2, $u2, $u1);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_num_rows($result) > 0;
}

/**
 * Get or create a conversation between two connected users
 */
function getOrCreateConversation($con, $user1_id, $user2_id) {
    $u1 = min((int)$user1_id, (int)$user2_id);
    $u2 = max((int)$user1_id, (int)$user2_id);
    
    $stmt = mysqli_prepare($con, "SELECT id FROM roommate_conversations WHERE user1_id = ? AND user2_id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 'ii', $u1, $u2);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    
    if ($row) return $row['id'];
    
    $stmt2 = mysqli_prepare($con, "INSERT INTO roommate_conversations (user1_id, user2_id) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt2, 'ii', $u1, $u2);
    mysqli_stmt_execute($stmt2);
    return mysqli_insert_id($con);
}

/**
 * Get unread message count for a user
 */
function getUnreadMessageCount($con, $user_id) {
    $uid = (int)$user_id;
    $stmt = mysqli_prepare($con, "SELECT COUNT(*) as cnt FROM roommate_messages rm 
        JOIN roommate_conversations rc ON rm.conversation_id = rc.id 
        WHERE (rc.user1_id = ? OR rc.user2_id = ?) AND rm.sender_id != ? AND rm.is_read = 0");
    mysqli_stmt_bind_param($stmt, 'iii', $uid, $uid, $uid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return (int)($row['cnt'] ?? 0);
}

/**
 * Get all conversations for a user with last message info
 */
function getUserConversations($con, $user_id) {
    $uid = (int)$user_id;
    $stmt = mysqli_prepare($con, "
        SELECT rc.*, 
               u1.FullName as user1_name, u2.FullName as user2_name,
               p1.profile_photo as user1_photo, p2.profile_photo as user2_photo,
               (SELECT message FROM roommate_messages WHERE conversation_id = rc.id ORDER BY created_at DESC LIMIT 1) as last_message,
               (SELECT created_at FROM roommate_messages WHERE conversation_id = rc.id ORDER BY created_at DESC LIMIT 1) as last_msg_time,
               (SELECT COUNT(*) FROM roommate_messages WHERE conversation_id = rc.id AND sender_id != ? AND is_read = 0) as unread_count
        FROM roommate_conversations rc
        JOIN tbluser u1 ON rc.user1_id = u1.ID
        JOIN tbluser u2 ON rc.user2_id = u2.ID
        LEFT JOIN roommate_profiles p1 ON p1.user_id = rc.user1_id
        LEFT JOIN roommate_profiles p2 ON p2.user_id = rc.user2_id
        WHERE rc.user1_id = ? OR rc.user2_id = ?
        ORDER BY rc.last_message_at DESC, rc.created_at DESC
    ");
    mysqli_stmt_bind_param($stmt, 'iii', $uid, $uid, $uid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Determine "the other user"
        if ($row['user1_id'] == $uid) {
            $row['other_id']    = $row['user2_id'];
            $row['other_name']  = $row['user2_name'];
            $row['other_photo'] = $row['user2_photo'];
        } else {
            $row['other_id']    = $row['user1_id'];
            $row['other_name']  = $row['user1_name'];
            $row['other_photo'] = $row['user1_photo'];
        }
        $rows[] = $row;
    }
    return $rows;
}

/**
 * Check if a user is blocked
 */
function isBlocked($con, $blocker_id, $blocked_id) {
    $b1 = (int)$blocker_id;
    $b2 = (int)$blocked_id;
    $stmt = mysqli_prepare($con, "SELECT id FROM roommate_blocks WHERE (blocker_id = ? AND blocked_id = ?) OR (blocker_id = ? AND blocked_id = ?) LIMIT 1");
    mysqli_stmt_bind_param($stmt, 'iiii', $b1, $b2, $b2, $b1);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_num_rows($result) > 0;
}

/**
 * Safe escape for HTML output
 */
function esc($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Handle roommate image upload (returns filename or false)
 * Saves to roommate-uploads/ directory
 */
function uploadRoommateImage($file, $prefix = 'rm') {
    $upload_dir = __DIR__ . '/../roommate-uploads';
    $result = store_uploaded_image($file, $upload_dir, 5 * 1024 * 1024);
    if (!$result['ok']) {
        return false;
    }
    protect_upload_dir($upload_dir);
    return $result['name'];
}

/**
 * Get profile photo URL
 */
function getProfilePhoto($photo) {
    if (empty($photo) || $photo === 'default-profile.png') {
        return 'img/default-profile.png';
    }
    return 'roommate-uploads/' . esc($photo);
}

/**
 * Format date nicely
 */
function fmtDate($date) {
    if (empty($date) || $date === '0000-00-00') return 'Not specified';
    return date('d M Y', strtotime($date));
}

/**
 * Time ago helper
 */
function timeAgo($datetime) {
    $time = time() - strtotime($datetime);
    if ($time < 60) return 'Just now';
    if ($time < 3600) return floor($time/60) . 'm ago';
    if ($time < 86400) return floor($time/3600) . 'h ago';
    if ($time < 604800) return floor($time/86400) . 'd ago';
    return date('d M', strtotime($datetime));
}
