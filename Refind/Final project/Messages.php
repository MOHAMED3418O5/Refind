<?php
session_start();
include 'config/db.php'; // تأكدي من استخدام ملف الاتصال المناسب لديك (db.php أو connection.php)

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
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الرسائل — Refind</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
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
                <a href="logout.php" class="logout-btn">تسجيل الخروج</a>
            </div>
        </div>
    </header>

    <main class="main-container">
        <div class="page-header">
            <div class="badge" style="background-color: #e0f2fe; color: #0369a1;">الخطوة 3 من العملية</div>
            <h1>الرسائل</h1>
            <p>يتم فتح المحادثة فقط بعد اجتياز التحقق من ملكية العنصر. المحادثات النشطة أدناه.</p>
        </div>

        <div class="messages-list">
            <?php if (empty($conversations)): ?>
                <p style="text-align: center; color: #666; margin-top: 20px;">لا توجد رسائل حتى الآن.</p>
            <?php else: ?>
                <?php foreach ($conversations as $conv): ?>
                    <a href="chat.php?conversation_id=<?php echo $conv['conversation_id']; ?>" class="message-card">
                        <div class="message-info">
                            <div class="user-line">
                                <?php echo htmlspecialchars($conv['other_user_name']); ?> <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <div class="item-sub"><?php echo htmlspecialchars($conv['report_title'] ?? 'محادثة حول عنصر'); ?></div>
                            <div class="last-msg">
                                <?php echo htmlspecialchars($conv['last_message'] ?? 'لم يتم إرسال رسائل بعد.'); ?>
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
                    <li><a href="Log in.php">تسجيل الدخول</a></li>
                    <li><a href="sign up.php">إنشاء حساب</a></li>
                    <li><a href="Messages.php">الرسائل</a></li>
                </ul>
            </div>
        </div>
    </footer>
    <div class="footer-bottom">
        <p>صُنع بعناية لإعادة المفقودات لأصحابها. © 2026 Refind</p>
    </div>
</body>

</html>