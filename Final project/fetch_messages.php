<?php
session_start();
include 'connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'غير مصرح']);
    exit();
}

$user_id = $_SESSION['user_id'];
$conversation_id = $_GET['conversation_id'] ?? null;
$last_message_id = $_GET['last_id'] ?? 0;

if (!$conversation_id) {
    echo json_encode(['status' => 'error', 'message' => 'رقم المحادثة مطلوب']);
    exit();
}

// Security Check: التأكد أن الجالب للرسائل هو أحد طرفي المحادثة
$check = $conn->prepare("SELECT id FROM conversations WHERE id = ? AND (user_one_id = ? OR user_two_id = ?)");
$check->execute([$conversation_id, $user_id, $user_id]);
if ($check->rowCount() == 0) {
    echo json_encode(['status' => 'error', 'message' => 'غير مسموح لك برؤية هذه المحادثة']);
    exit();
}

// جلب الرسائل الجديدة فقط ذات ID أكبر من آخر رسالة تم تحميلها
$stmt = $conn->prepare("
    SELECT m.*, u.name as sender_name 
    FROM messages m
    JOIN users u ON m.sender_id = u.id
    WHERE m.conversation_id = ? AND m.id > ?
    ORDER BY m.id ASC
");
$stmt->execute([$conversation_id, $last_message_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['status' => 'success', 'messages' => $messages, 'current_user' => $user_id]);