<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تم البلاغ بنجاح — Refind</title>
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
                <a href="Report a find.php">أبلغ عن شيء موجود</a>
                <a href="Messages.php">الرسائل</a>
                <a href="How it works.php">كيف يعمل الموقع</a>
                <a href="Algorithms.php">الخوارزميات</a>
                <a href="About.php">من نحن</a>
            </nav>
            <div class="auth-buttons">
                <?php if (empty($_SESSION['user_id'])): ?>
                    <a href="auth/Log-in.php" class="login-btn">تسجيل الدخول</a>
                    <a href="auth/sign-up.php" class="signup-btn">إنشاء حساب</a>
                <?php else: ?>
                    <a href="auth/profie.php" class="profile-btn">الملف الشخصي</a>
                    <a href="auth/logout.php" class="logout-btn">تسجيل الخروج</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="main-container">
        <div class="success-container">
            <div class="success-card">
                <div class="success-icon-box">
                    <i class="fa-solid fa-check"></i>
                </div>
                <h2>شكراً لك على البلاغ</h2>
                <p>إعلانك أصبح ظاهراً الآن في صفحة التصفح. سنخطرك عندما يطالب به أحد ويعبر فحص التفاصيل المخفية.</p>

                <div class="success-actions">
                    <a href="my Report.php" class="action-btn-primary">عرض بلاغاتي</a>
                    <a href="Report a find.php" class="action-btn-outline">أبلغ عن عنصر آخر</a>
                </div>
            </div>
        </div>
    </main>
    <div class="footer-bottom">
        <p>صُنع بعناية ليعود المفقود إلى بيته. © 2026 Refind</p>
    </div>
</body>

</html>