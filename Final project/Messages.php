<?php
session_start();
include 'db.php'; // تأكدي من استخدام ملف الاتصال المناسب لديك (db.php أو connection.php)

// التحقق من تسجيل دخول المستخدم
if (!isset($_SESSION['user_id'])) {
    header("Location: Log in.php");
    exit();
}

$current_user = $_SESSION['user_id'];

// جلب المحادثات الخاصة بالمستخدم الحالي مع اسم الشخص الآخر وعنوان البلاغ وآخر رسالة
$stmt = $conn->prepare("
    SELECT 
        c.id AS conversation_id,
        r.title AS report_title,
        u.name AS other_user_name,
        (SELECT message FROM messages WHERE conversation_id = c.id ORDER BY id DESC LIMIT 1) AS last_message,
        (SELECT created_at FROM messages WHERE conversation_id = c.id ORDER BY id DESC LIMIT 1) AS last_message_time
    FROM conversations c
    LEFT JOIN reports r ON c.report_id = r.id
    JOIN users u ON u.id = IF(c.user_one_id = ?, c.user_two_id, c.user_one_id)
    WHERE c.user_one_id = ? OR c.user_two_id = ?
    ORDER BY c.created_at DESC
");

$stmt->execute([$current_user, $current_user, $current_user]);
$conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages — Refind</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="main.css">
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
                <a href="Log in.php" class="login-btn">Log in</a>
                <a href="sign up.php" class="signup-btn">Sign up</a>
                <a href="logout.php" class="logout-btn">Log out</a>
            </div>
        </div>
    </header>

    <main class="main-container">
        <div class="page-header">
            <div class="badge" style="background-color: #e0f2fe; color: #0369a1;">Step 3 of the flow </div>
            <h1>Messages</h1>
            <p>A chat opens only after ownership verification passes. Active conversations below.</p>
        </div>

        <div class="messages-list">
            <?php if (empty($conversations)): ?>
                <p style="text-align: center; color: #666; margin-top: 20px;">No messages yet.</p>
            <?php else: ?>
                <?php foreach ($conversations as $conv): ?>
                    <!-- تم استبدال # برابط صفحة الشات ومعه id المحادثة -->
                    <a href="chat.php?conversation_id=<?php echo $conv['conversation_id']; ?>" class="message-card">
                        <div class="message-info">
                            <div class="user-line">
                                <?php echo htmlspecialchars($conv['other_user_name']); ?> <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <div class="item-sub"><?php echo htmlspecialchars($conv['report_title'] ?? 'Item Chat'); ?></div>
                            <div class="last-msg">
                                <?php echo htmlspecialchars($conv['last_message'] ?? 'No messages sent yet.'); ?>
                            </div>
                        </div>
                        <div class="message-time">
                            <?php 
                                if (!empty($conv['last_message_time'])) {
                                    echo date('h:i A', strtotime($conv['last_message_time']));
                                }
                            ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
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
</body>

</html>