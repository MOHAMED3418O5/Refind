<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كيف يعمل Refind — Refind</title>
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
                <a href="How it works.php" class="active">كيف يعمل الموقع</a>
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

        <div class="page-header">
            <div class="badge" style="background-color: #e0f2fe; color: #0369a1;">العملية</div>
            <h1>كيف يعمل Refind</h1>
            <p>خطوة تحقق واحدة تفصل بين العنصر الموجود وصاحبه — لا أكثر ولا أقل.</p>
        </div>

        <h2 class="section-title">إذا فقدت شيئاً</h2>
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-header">
                    <span class="step-badge"><i class="fa-solid fa-magnifying-glass"></i> الخطوة 1</span>
                </div>
                <h3>ابحث في العناصر الموجودة</h3>
                <p>صفِّ حسب الفئة والمكان والتاريخ. تعرض القوائم ما يكفي لتميّز عنصرك، ولا تكفي أبداً لتقديم
                    مطالبة.</p>
            </div>
            <div class="step-card">
                <div class="step-header">
                    <span class="step-badge"><i class="fa-solid fa-hand-pointer"></i> الخطوة 2</span>
                </div>
                <h3>اضغط "أعتقد أنه لي"</h3>
                <p>سيُطلب منك سؤال واحد عن تفصيل تم حذفه عمداً من القائمة العامة.</p>
            </div>
            <div class="step-card">
                <div class="step-header">
                    <span class="step-badge"><i class="fa-solid fa-shield-halved"></i> الخطوة 3</span>
                </div>
                <h3>اجتز فحص التفاصيل المخفية</h3>
                <p>طابق التفصيل وتصبح موثّقاً. ثلاث محاولات خاطئة توقف المطالبة وتُنبه من وجد العنصر.</p>
            </div>
            <div class="step-card">
                <div class="step-header">
                    <span class="step-badge"><i class="fa-solid fa-comments"></i> الخطوة 4</span>
                </div>
                <h3>تحدث واستلم</h3>
                <p>تُفتح محادثة خاصة مع من وجد العنصر للاتفاق على مكان وزمان عام.</p>
            </div>
        </div>

        <h2 class="section-title">إذا وجدت شيئاً</h2>
        <div class="steps-grid-3">
            <div class="step-card">
                <div class="step-header">
                    <span class="step-badge"><i class="fa-solid fa-upload"></i> الخطوة 1</span>
                </div>
                <h3>أبلغ بما وجدت</h3>
                <p>أضف وصفاً عاماً ومكاناً وتاريخاً.</p>
            </div>
            <div class="step-card">
                <div class="step-header">
                    <span class="step-badge"><i class="fa-solid fa-lock"></i> الخطوة 2</span>
                </div>
                <h3>احتفظ بتفصيل واحد خاص</h3>
                <p>اكتب سؤالاً لا يعرف إجابته إلا المالك — وهذا يصبح خطوة التحقق.</p>
            </div>
            <div class="step-card">
                <div class="step-header">
                    <span class="step-badge"><i class="fa-solid fa-user-check"></i> الخطوة 3</span>
                </div>
                <h3>قابل المالك الموثّق</h3>
                <p>لا تصل إليك رسائل إلا من شخص اجتاز التحقق بالفعل.</p>
            </div>
        </div>

        <div class="demo-box">
            <h3>جرّب العملية كاملة في هذا النموذج</h3>
            <p>افتح حقيبة الظهر القماشية السوداء، طالب بها، أجب عن سؤال التفاصيل المخفية، وستصل إلى المحادثة
                الخاصة مع من وجدها.</p>
            <a href="Browseitems.php" class="demo-btn">ابدأ التجربة <i class="fa-solid fa-arrow-left"></i></a>
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