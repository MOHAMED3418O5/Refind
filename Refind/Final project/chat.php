<?php
include 'config/db.php';
include 'config/auth.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/Log-in.php");
    exit();
}

$current_user_id = (int)$_SESSION['user_id'];
$conversation_id = isset($_GET['conversation_id']) ? (int)$_GET['conversation_id'] : 0;
$report_id = isset($_GET['report_id']) ? (int)$_GET['report_id'] : 0;

/* فتح الشات من التقرير */
if ($report_id > 0 && $conversation_id <= 0) {

    $stmt = $conn->prepare("
        SELECT r.id, r.user_id, r.title, u.name
        FROM reports r
        JOIN users u ON u.id = r.user_id
        WHERE r.id = :report_id
        LIMIT 1
    ");

    $stmt->execute([':report_id' => $report_id]);
    $report = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$report) {
        die('البلاغ غير موجود.');
    }

    $other_user_id = (int)$report['user_id'];

    if ($other_user_id === $current_user_id) {
        die('لا يمكنك مراسلة نفسك.');
    }

    /* البحث عن محادثة موجودة */
    $stmt = $conn->prepare("
        SELECT id
        FROM conversations
        WHERE report_id = :report_id
        AND (
            (user_one_id = :me1 AND user_two_id = :other1)
            OR
            (user_one_id = :other2 AND user_two_id = :me2)
        )
        LIMIT 1
    ");

    $stmt->execute([
        ':report_id' => $report_id,
        ':me1' => $current_user_id,
        ':other1' => $other_user_id,
        ':other2' => $other_user_id,
        ':me2' => $current_user_id
    ]);

    $conversation = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($conversation) {
        $conversation_id = (int)$conversation['id'];
    } else {
        $stmt = $conn->prepare("
            INSERT INTO conversations (user_one_id, user_two_id, report_id)
            VALUES (:me, :other, :report_id)
        ");
        $stmt->execute([
            ':me' => $current_user_id,
            ':other' => $other_user_id,
            ':report_id' => $report_id
        ]);
        $conversation_id = (int)$conn->lastInsertId();
    }

    header("Location: chat.php?conversation_id=" . $conversation_id);
    exit();
}

/* إذا لم توجد محادثة */
if ($conversation_id <= 0) {
    header("Location: Messages.php");
    exit();
}

/* التأكد أن المستخدم داخل المحادثة */
$stmt = $conn->prepare("
    SELECT c.*, u.name AS other_user_name
    FROM conversations c
    JOIN users u ON u.id = IF(c.user_one_id = :me1, c.user_two_id, c.user_one_id)
    WHERE c.id = :conversation_id
    AND (c.user_one_id = :me2 OR c.user_two_id = :me3)
    LIMIT 1
");

$stmt->execute([
    ':me1' => $current_user_id,
    ':conversation_id' => $conversation_id,
    ':me2' => $current_user_id,
    ':me3' => $current_user_id
]);

$conversation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$conversation) {
    header("Location: Messages.php");
    exit();
}

$otherUserName = $conversation['other_user_name'];

/* معالجة إرسال الرسالة عبر الـ Fetch (AJAX) لمنع حدوث Refresh */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_send'])) {
    header('Content-Type: application/json');
    $message = trim($_POST['message'] ?? '');

    if ($message !== '') {
        $stmt = $conn->prepare("
            INSERT INTO messages (conversation_id, sender_id, message)
            VALUES (:conversation_id, :sender_id, :message)
        ");
        $stmt->execute([
            ':conversation_id' => $conversation_id,
            ':sender_id' => $current_user_id,
            ':message' => $message
        ]);
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'الرسالة فارغة']);
    }
    exit();
}

/* جلب الرسائل الأولية لتحميل الصفحة */
$stmt = $conn->prepare("
    SELECT m.*, u.name AS sender_name
    FROM messages m
    JOIN users u ON u.id = m.sender_id
    WHERE m.conversation_id = :conversation_id
    ORDER BY m.created_at ASC
");
$stmt->execute([':conversation_id' => $conversation_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// تحديد معرف آخر رسالة مرسومة حالياً لتحديث الـ Polling بناءً عليها
$last_msg_id = !empty($messages) ? end($messages)['id'] : 0;
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المحادثة — Refind</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/main.css">

    <style>
        .chat-page-wrapper {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .chat-card {
            max-width: 850px;
            margin: auto;
            background: #fff;
            border: 1px solid #f1f5f9;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,.03);
            overflow: hidden;
        }

        .chat-header {
            padding: 18px 24px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .chat-user-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .chat-user-name {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
        }

        .verified-icon {
            color: #0284c7;
            font-size: 15px;
        }

        .back-link {
            color: #64748b;
            text-decoration: none;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .chat-box {
            height: 380px;
            padding: 24px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 14px;
            background: #fafafa;
        }

        .message-bubble {
            max-width: 65%;
            padding: 12px 18px;
            border-radius: 16px;
            font-size: 14px;
            line-height: 1.5;
            word-wrap: break-word;
        }

        .message-sent {
            align-self: flex-start;
            background: #0f766e;
            color: #fff;
            border-bottom-left-radius: 4px;
        }

        .message-received {
            align-self: flex-end;
            background: #8b8b8b;
            color: #fbfbfc;
            border: 1px solid #e2e8f0;
            border-bottom-right-radius: 4px;
        }

        .message-time {
            display: block;
            font-size: 11px;
            margin-top: 5px;
            opacity: .7;
            color: #f1f5f9;
        }

        .message-sent .message-time {
            text-align: left;
        }

        .message-received .message-time {
            text-align: right;
        }

        .chat-form {
            display: flex;
            padding: 16px 20px;
            background: #fff;
            border-top: 1px solid #f1f5f9;
            gap: 12px;
        }

        .chat-input {
            flex: 1;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            outline: none;
            font-size: 14px;
            background: #f8fafc;
        }

        .chat-input:focus {
            border-color: #0284c7;
            background: #fff;
        }

        .send-btn {
            background: #0f766e;
            color: #fff;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .send-btn:hover {
            background: #115e59;
        }

        @media (max-width: 600px) {
            .chat-page-wrapper {
                margin: 20px auto;
                padding: 0 10px;
            }

            .chat-box {
                height: 400px;
                padding: 15px;
            }

            .message-bubble {
                max-width: 80%;
            }

            .chat-form {
                padding: 12px;
            }

            .send-btn {
                padding: 12px 16px;
            }
        }
    </style>
</head>

<body>

<header class="navbar">
    <div class="nav-container">
        <div class="logo-area">
            <span class="logo-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
            <span class="logo-text">Refind</span>
        </div>

        <nav class="nav-links">
            <a href="Home.php">الرئيسية</a>
            <a href="Browseitems.php">تصفح العناصر</a>
            <a href="my Report.php">بلاغاتي</a>
            <a href="Report a find.php">الإبلاغ عن معثور عليه</a>
            <a href="Messages.php" class="active">الرسائل</a>
            <a href="How it works.php">كيف يعمل الموقع</a>
            <a href="About.php">عن الموقع</a>
        </nav>

        <div class="auth-buttons">
            <a href="auth/profie.php" class="profile-btn">الملف الشخصي</a>
            <a href="auth/logout.php" class="logout-btn">تسجيل الخروج</a>
        </div>
    </div>
</header>

<main class="chat-page-wrapper">
    <div class="chat-card">
        <div class="chat-header">
            <div class="chat-user-info">
                <span class="chat-user-name"><?= htmlspecialchars($otherUserName) ?></span>
                <i class="fa-solid fa-circle-check verified-icon"></i>
            </div>
            <a href="Messages.php" class="back-link">
                <i class="fa-solid fa-arrow-right"></i> العودة للرسائل
            </a>
        </div>

        <div class="chat-box" id="chatBox">
            <?php if ($messages): ?>
                <?php foreach ($messages as $msg): ?>
                    <div class="message-bubble <?= $msg['sender_id'] == $current_user_id ? 'message-sent' : 'message-received' ?>">
                        <div><?= htmlspecialchars($msg['message']) ?></div>
                        <small class="message-time"><?= date('h:i', strtotime($msg['created_at'])) ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p id="no-msg" style="text-align:center;color:#94a3b8;margin:auto;">
                    لا توجد رسائل بعد. ابدأ المحادثة الآن!
                </p>
            <?php endif; ?>
        </div>

        <form class="chat-form" id="chatForm">
            <input
                type="text"
                id="messageInput"
                name="message"
                class="chat-input"
                placeholder="اكتب رسالتك..."
                required
                autocomplete="off"
            >
            <button type="submit" class="send-btn">
                إرسال <i class="fa-solid fa-paper-plane"></i>
            </button>
        </form>
    </div>
</main>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-col">
            <div class="logo-area">
                <span class="logo-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                <span class="logo-text">Refind</span>
            </div>
            <p class="footer-logo-desc">طريقة هادئة وآمنة لإعادة المفقودات إلى أصحابها.</p>
        </div>
        <div class="footer-col">
            <h4>المنصة</h4>
            <ul>
                <li><a href="Browseitems.php">تصفح المعثورات</a></li>
                <li><a href="Report a find.php">الإبلاغ عن معثور عليه</a></li>
                <li><a href="my Report.php">بلاغاتي</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>تعرّف علينا</h4>
            <ul>
                <li><a href="How it works.php">كيف يعمل الموقع</a></li>
                <li><a href="About.php">عن Refind</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>الحساب</h4>
            <ul>
                <li><a href="auth/profie.php">الملف الشخصي</a></li>
                <li><a href="auth/logout.php">تسجيل الخروج</a></li>
                <li><a href="Messages.php">الرسائل</a></li>
            </ul>
        </div>
    </div>
</footer>

<div class="footer-bottom">
    <p>صُنع بعناية لإعادة المفقودات لأصحابها. © 2026 Refind</p>
</div>

<script>
const chatBox = document.getElementById('chatBox');
const chatForm = document.getElementById('chatForm');
const messageInput = document.getElementById('messageInput');
const conversationId = <?= $conversation_id; ?>;
let lastMessageId = <?= $last_msg_id; ?>;
const currentUserId = <?= $current_user_id; ?>;

// النزول لآخر الشات عند الفتح
function scrollToBottom() {
    chatBox.scrollTop = chatBox.scrollHeight;
}
scrollToBottom();

// دالة أمان لمنع الثغرات (XSS)
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

// دالة رسم الرسالة داخل الشات
function appendMessage(msgText, senderId, createdAt) {
    const noMsg = document.getElementById('no-msg');
    if(noMsg) noMsg.remove();

    const isSent = parseInt(senderId) === currentUserId;
    const bubbleClass = isSent ? 'message-sent' : 'message-received';
    
    // استخراج الوقت (ساعات ودقائق)
    let timeStr = '';
    if (createdAt) {
        timeStr = createdAt.includes(' ') ? createdAt.split(' ')[1].substring(0, 5) : createdAt;
    } else {
        const now = new Date();
        timeStr = String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');
    }

    const messageDiv = document.createElement('div');
    messageDiv.className = `message-bubble ${bubbleClass}`;
    messageDiv.innerHTML = `
        <div>${escapeHtml(msgText)}</div>
        <small class="message-time">${timeStr}</small>
    `;
    chatBox.appendChild(messageDiv);
    scrollToBottom();
}

chatForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const messageText = messageInput.value.trim();
    if (!messageText) return;

    const formData = new FormData();
    formData.append('message', messageText);
    formData.append('ajax_send', '1');

    // تفريغ الحقل فوراً لسرعة الاستجابة للمستخدم
    messageInput.value = '';

    // عرض الرسالة فوراً للمستخدم الحالي (لضمان ظهورها بدون انتظار)
    appendMessage(messageText, currentUserId, null);

    fetch(`chat.php?conversation_id=${conversationId}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            fetchNewMessages();
        } else {
            alert(data.message || 'حدث خطأ أثناء إرسال الرسالة');
        }
    })
    .catch(error => console.error('Error sending message:', error));
});
// 2. نظام الـ Polling لجلب الرسائل الجديدة من fetch_messages.php
function fetchNewMessages() {
    fetch(`fetch_messages.php?conversation_id=${conversationId}&last_id=${lastMessageId}`)
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success' && data.messages.length > 0) {
            data.messages.forEach(msg => {
                if (parseInt(msg.id) > lastMessageId) {
                    lastMessageId = parseInt(msg.id);
                    // عرض الرسالة (سواء منك أو من الطرف الآخر)
                    appendMessage(msg.message, msg.sender_id, msg.created_at);
                }
            });
        }
    })
    .catch(error => console.error('Error fetching messages:', error));
}

// تفعيل التحديث التلقائي كل ثانيين (2000 مللي ثانية)
setInterval(fetchNewMessages, 2000);
</script>
</body>
</html>