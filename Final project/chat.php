<?php
session_start();
include 'db.php';

// التأكد من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: Log in.php");
    exit();
}

$current_user_id = $_SESSION['user_id'];
$conversation_id = $_GET['conversation_id'] ?? null;

if (!$conversation_id) {
    header("Location: Messages.php");
    exit();
}

// 1. معالجة إرسال رسالة جديدة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    
    if (!empty($message)) {
        try {
            $stmt = $conn->prepare("INSERT INTO messages (conversation_id, sender_id, message) VALUES (:conversation_id, :sender_id, :message)");
            $stmt->execute([
                ':conversation_id' => $conversation_id,
                ':sender_id'       => $current_user_id,
                ':message'         => $message
            ]);
            
            header("Location: chat.php?conversation_id=" . $conversation_id);
            exit();
        } catch (PDOException $e) {
            $error = $e->getMessage();
        }
    }
}

// 2. جلب الرسائل واسم الطرف الآخر
try {
    // جلب معلومات الرسائل واسم المرسل
    $stmt = $conn->prepare("
        SELECT m.*, u.name as sender_name 
        FROM messages m 
        JOIN users u ON m.sender_id = u.id 
        WHERE m.conversation_id = :conversation_id 
        ORDER BY m.created_at ASC
    ");
    $stmt->execute([':conversation_id' => $conversation_id]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // جلب اسم الطرف الآخر في المحادثة
    $userStmt = $conn->prepare("
        SELECT u.name 
        FROM conversations c 
        JOIN users u ON (IF(c.user_one_id = :user_id, c.user_two_id, c.user_one_id) = u.id)
        WHERE c.id = :conversation_id
    ");
    $userStmt->execute([':user_id' => $current_user_id, ':conversation_id' => $conversation_id]);
    $otherUser = $userStmt->fetch(PDO::FETCH_ASSOC);
    $otherUserName = $otherUser['name'] ?? 'User';

} catch (PDOException $e) {
    $messages = [];
    $otherUserName = 'User';
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat — Refind</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="main.css">
    <style>
        .chat-page-wrapper {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .chat-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            overflow: hidden;
            max-width: 850px;
            margin: 0 auto;
        }

        .chat-header {
            padding: 20px 24px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #ffffff;
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
            transition: color 0.2s;
        }

        .back-link:hover {
            color: #0284c7;
        }

        .chat-box {
            height: 380px;
            padding: 24px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 14px;
            background-color: #fafafa;
        }

        .message-bubble {
            max-width: 65%;
            padding: 12px 18px;
            border-radius: 16px;
            font-size: 14px;
            line-height: 1.5;
            word-wrap: break-word;
            position: relative;
        }

        .message-sent {
            align-self: flex-end;
            background-color: #0f766e;
            color: #ffffff;
            border-bottom-right-radius: 4px;
        }

        .message-received {
            align-self: flex-start;
            background-color: #ffffff;
            color: #1e293b;
            border: 1px solid #e2e8f0;
            border-bottom-left-radius: 4px;
        }

        .chat-form {
            display: flex;
            padding: 16px 20px;
            background: #ffffff;
            border-top: 1px solid #f1f5f9;
            gap: 12px;
            align-items: center;
        }

        .chat-input {
            flex: 1;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            outline: none;
            font-size: 14px;
            background-color: #f8fafc;
            transition: border-color 0.2s;
        }

        .chat-input:focus {
            border-color: #0284c7;
            background-color: #ffffff;
        }

        .send-btn {
            background-color: #0f766e;
            color: #ffffff;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: background-color 0.2s;
        }

        .send-btn:hover {
            background-color: #115e59;
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
                <a href="Home.php">Home</a>
                <a href="Browseitems.php">Browse items</a>
                <a href="my Report.php">My Report</a>
                <a href="Report a find.php">Report a found</a>
                <a href="Messages.php" class="active">Messages</a>
                <a href="How it works.php">How it works</a>
                <a href="About.php">About</a>
            </nav>
            <div class="auth-buttons">
                <a href="logout.php" class="logout-btn">Log out</a>
            </div>
        </div>
    </header>

    <main class="chat-page-wrapper">
        <div class="chat-card">
            <div class="chat-header">
                <div class="chat-user-info">
                    <span class="chat-user-name"><?php echo htmlspecialchars($otherUserName); ?></span>
                    <i class="fa-solid fa-circle-check verified-icon"></i>
                </div>
                <a href="Messages.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Messages</a>
            </div>

            <div class="chat-box" id="chatBox">
                <?php if (!empty($messages)): ?>
                    <?php foreach ($messages as $msg): ?>
                        <?php $is_me = ($msg['sender_id'] == $current_user_id); ?>
                        <div class="message-bubble <?php echo $is_me ? 'message-sent' : 'message-received'; ?>">
                            <?php echo htmlspecialchars($msg['message']); ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center; color: #94a3b8; margin: auto;">No messages yet. Say hello!</p>
                <?php endif; ?>
            </div>

            <form class="chat-form" action="chat.php?conversation_id=<?php echo $conversation_id; ?>" method="POST">
                <input type="text" name="message" class="chat-input" placeholder="Type your message..." required autocomplete="off">
                <button type="submit" class="send-btn">Send <i class="fa-solid fa-paper-plane"></i></button>
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
                <p class="footer-logo-desc">A calm, safe way to reunite people with the things they lose.</p>
            </div>
            <div class="footer-col">
                <h4>Platform</h4>
                <ul>
                    <li><a href="Browseitems.php">Browse found items</a></li>
                    <li><a href="Report a find.php">Report a found item</a></li>
                    <li><a href="my Report.php">My Report</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Learn</h4>
                <ul>
                    <li><a href="How it works.php">How it works</a></li>
                    <li><a href="About.php">About Refind</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Account</h4>
                <ul>
                    <li><a href="Log in.php">Log in</a></li>
                    <li><a href="sign up.php">Create account</a></li>
                    <li><a href="Messages.php">Messages</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <div class="footer-bottom">
        <p>Made with care to bring lost things back home. © 2026 Refind</p>
    </div>

    <script>
        const chatBox = document.getElementById('chatBox');
        chatBox.scrollTop = chatBox.scrollHeight;
    </script>
</body>

</html>