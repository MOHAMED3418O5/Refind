<?php
session_start();
include 'db.php'; // أو اسم ملف الاتصال عندك
include 'validation.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'غير مصرح للوصول']);
    exit();
}

$current_user = $_SESSION['user_id'];
$receiver_id = $_POST['receiver_id'] ?? null;
$report_id = $_POST['report_id'] ?? null;

if (!$receiver_id || !$report_id) {
    echo json_encode(['status' => 'error', 'message' => 'بيانات ناقصة']);
    exit();
}

// 1. البحث عن محادثة قائمة بالفعل بين الشخصين لنفس البلاغ
$stmt = $conn->prepare("
    SELECT id FROM conversations 
    WHERE report_id = ? 
    AND ((user_one_id = ? AND user_two_id = ?) OR (user_one_id = ? AND user_two_id = ?))
");
$stmt->execute([$report_id, $current_user, $receiver_id, $receiver_id, $current_user]);
$conversation = $stmt->fetch(PDO::FETCH_ASSOC);

if ($conversation) {
    echo json_encode(['status' => 'success', 'conversation_id' => $conversation['id']]);
} else {
    // 2. إنشاء محادثة جديدة
    $insert = $conn->prepare("INSERT INTO conversations (user_one_id, user_two_id, report_id) VALUES (?, ?, ?)");
    if ($insert->execute([$current_user, $receiver_id, $report_id])) {
        echo json_encode(['status' => 'success', 'conversation_id' => $conn->lastInsertId()]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'فشل إنشاء المحادثة']);
    }
}