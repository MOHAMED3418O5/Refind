<?php
include 'config/db.php'; 
include 'config/auth.php';
$id = $_SESSION['user_id']; 
$sql = "SELECT * FROM reports WHERE user_id = :user_id ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute([':user_id' => $id]);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بلاغاتي — Refind</title>
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
                <a href="my Report.php" class="active">بلاغاتي</a>
                <a href="Report a find.php">أبلغ عن شيء موجود</a>
                <a href="Messages.php">الرسائل</a>
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

        <div class="dashboard-header">
            <div class="page-header" style="margin-bottom: 0;">
                <span class="badge" style="background-color: #e0f2fe; color: #0369a1;">حسابك</span>
                <h1>بلاغاتي</h1>
                <p>كل ما بلغت عنه كعنصر موجود، مع حالة كل مطالبة.</p>
            </div>
            <a href="Report a find.php" class="new-report-btn">
                <i class="fa-solid fa-plus"></i> بلاغ جديد
            </a>
        </div>

        <div class="filter-tabs">
            <button class="filter-btn active">الكل</button>
            <button class="filter-btn">لم تتم المطالبة</button>
            <button class="filter-btn">قيد التحقق</button>
            <button class="filter-btn">تمت الإعادة</button>
        </div>

        <div class="reports-list">

            <?php foreach ($reports as $report): ?>

           
<!-- for each report -->
            <div class="report-card">
                <div class="report-info">
                    <div class="item-icon-box">
                        <i class="fa-solid fa-headphones"></i>
                    </div>
                    <div class="item-details">
                        <h3>
<!-- title -->      
                            <?= htmlspecialchars($report['title']) ?>
                        <span class="status-badge status-returned">status</span>
                        </h3>
                        <p><?= $report['item_date'] ?></p>
                    </div>
                </div>
                <div class="report-actions">
                    <a href="view-report.php?id=<?= $report['id'] ?>" class="action-btn">عرض الإعلان</a>

                </div>
            </div>

        </div>
        <?php endforeach; ?>
<!-- end for each report -->
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