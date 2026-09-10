<?php
session_start();
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
                <a href="Report a find.php">أبلغ عن شيء موجود</a>
                <a href="Messages.php" class="active">الرسائل</a>
                <a href="How it works.php">كيف يعمل الموقع</a>
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
        <div class="page-header">
            <div class="badge" style="background-color: #e0f2fe; color: #0369a1;">الخطوة 3 من العملية</div>
            <h1>الرسائل</h1>
            <p>تُفتح المحادثة فقط بعد اجتياز فحص الملكية. هذه محادثات تجريبية أدناه.</p>
        </div>

        <div class="messages-list">

            <a href="#" class="message-card">
                <div class="message-info">
                    <div class="user-line">
                        نور أ. <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div class="item-sub">حقيبة ظهر قماشية سوداء</div>
                    <div class="last-msg">ممتاز — السلسلة المفتاحية مطابقة. متى يمكنك استلامها؟</div>
                </div>
                <div class="message-time">منذ دقيقتين</div>
            </a>

            <a href="#" class="message-card">
                <div class="message-info">
                    <div class="user-line">
                        كريم ح. <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div class="item-sub">ساعة يد فضية</div>
                    <div class="last-msg" style="color: #0f766e; font-weight: 500;">التحقق ما زال معلقاً لهذه المطالبة.</div>
                </div>
                <div class="message-time">منذ يوم</div>
            </a>

            <a href="#" class="message-card">
                <div class="message-info">
                    <div class="user-line">
                        عمر ت. <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div class="item-sub">علبة سماعات أذن رمادية لاسلكية</div>
                    <div class="last-msg">تم التسليم أمس — تم تحديدها كمُعادَة. شكراً!</div>
                </div>
                <div class="message-time">منذ 3 أيام</div>
            </a>
        </div>
    </main>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-col">
                <div class="logo-area">
                    <span class="logo-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <span class="logo-text">Refind</span>
                </div>
                <p class="footer-logo-desc">طريقة هادئة وآمنة لإعادة الأغراض إلى أصحابها.</p>
            </div>
            <div class="footer-col">
                <h4>المنصة</h4>
                <ul>
                    <li><a href="Browseitems.php">تصفح العناصر الموجودة</a></li>
                    <li><a href="Report a find.php">أبلغ عن عنصر موجود</a></li>
                    <li><a href="my Report.php">بلاغاتي</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>تعلّم</h4>
                <ul>
                    <li><a href="How it works.php">كيف يعمل الموقع</a></li>
                    <li><a href="Algorithms.php">الخوارزميات</a></li>
                    <li><a href="About.php">عن Refind</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>الحساب</h4>
                <ul>
                    <li><a href="auth/Log-in.php">تسجيل الدخول</a></li>
                    <li><a href="auth/sign-up.php">إنشاء حساب</a></li>
                    <li><a href="Messages.php">الرسائل</a></li>
                </ul>
            </div>
        </div>
    </footer>
    <div class="footer-bottom">
        <p>صُنع بعناية ليعود المفقود إلى بيته. © 2026 Refind</p>
    </div>
</body>

</html>