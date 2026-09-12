<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'غير مصرح']);
    exit();
}

$sender_id = $_SESSION['user_id'];
$conversation_id = $_POST['conversation_id'] ?? null;
$message_text = trim($_POST['message'] ?? '');
$image_name = null;

if (!$conversation_id) {
    echo json_encode(['status' => 'error', 'message' => 'رقم المحادثة مطلوب']);
    exit();
}

// Security Check: التأكد أن المستخدم طرف في هذه المحادثة
$check = $conn->prepare("SELECT id FROM conversations WHERE id = ? AND (user_one_id = ? OR user_two_id = ?)");
$check->execute([$conversation_id, $sender_id, $sender_id]);
if ($check->rowCount() == 0) {
    echo json_encode(['status' => 'error', 'message' => 'غير مسموح لك بالإرسال في هذه المحادثة']);
    exit();
}

// رفع الصورة إن وجدت
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $allowed = ['jpg', 'jpeg', 'png', 'jfif'];
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    
    if (in_array($ext, $allowed)) {
        $image_name = time() . '_' . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/chat/" . $image_name);
    }
}

if (empty($message_text) && !$image_name) {
    echo json_encode(['status' => 'error', 'message' => 'لا يمكن إرسال رسالة فارغة']);
    exit();
}

// حفظ الرسالة
$stmt = $conn->prepare("INSERT INTO messages (conversation_id, sender_id, message, image) VALUES (?, ?, ?, ?)");
if ($stmt->execute([$conversation_id, $sender_id, $message_text, $image_name])) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'فشل إرسال الرسالة']);
}