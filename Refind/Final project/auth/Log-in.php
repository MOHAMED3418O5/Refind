<?php
session_start();
include '../config/db.php';
include '../config/auth.php';
include 'validation.php';

requireGuest();

$errors = [];
$email = trim($_POST['email'] ?? '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    csrfCheck();
    $password = $_POST['password'] ?? '';

    $errors = validateLogin($email, $password);

    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['name'];

                header("Location: ../Home.php");
                exit();
            } else {
                $errors[] = "البريد الإلكتروني أو كلمة المرور غير صحيحة.";
            }

        } catch (PDOException $e) {
            $errors[] = "خطأ في قاعدة البيانات. حاول مرة أخرى.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول — Refind</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/main.css">
</head>

<body>
    <header class="navbar">
        <div class="nav-container">
            <div class="logo-area">
                <span class="logo-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                <span class="logo-text">Refind</span>
            </div>
            <nav class="nav-links">
                <a href="../Home.php">الرئيسية</a>
                <a href="../Browseitems.php">تصفح العناصر</a>
                <a href="../my Report.php">بلاغاتي</a>
                <a href="../Report a find.php">أبلغ عن شيء موجود</a>
                <a href="../Messages.php">الرسائل</a>
                <a href="../How it works.php">كيف يعمل الموقع</a>
                <a href="../Algorithms.php">الخوارزميات</a>
                <a href="../About.php">من نحن</a>
            </nav>
            <div class="auth-buttons">
                <a href="Log-in.php" class="login-btn">تسجيل الدخول</a>
                <a href="sign-up.php" class="signup-btn">إنشاء حساب</a>
            </div>
        </div>
    </header>

    <main class="auth-page">
        <form class="auth-card" action="Log-in.php" method="POST">
            <?php echo csrfField(); ?>
            <div class="badge" style="background-color: #e0f2fe; color: #0369a1;">مرحباً بعودتك</div>
            <h1>تسجيل الدخول</h1>
            <p class="auth-subtitle">سجّل دخولك للمتابعة في Refind.</p>

            <?php if (!empty($errors)): ?>
                <div style="background-color: #fee2e2; color: #991b1b; padding: 10px; border-radius: 8px; margin-bottom: 15px; border: 1px solid #fecaca;">
                    <ul style="margin: 0; padding-right: 20px;">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <label>البريد الإلكتروني</label>
            <input id="loginEmail" name="email" type="email" placeholder="أدخل بريدك الإلكتروني" value="<?php echo htmlspecialchars($email); ?>" required>

            <label>كلمة المرور</label>
            <input id="loginPassword" name="password" type="password" placeholder="أدخل كلمة المرور" required>

            <button type="submit">تسجيل الدخول</button>
            <p class="switch">ليس لديك حساب؟ <a href="sign-up.php">أنشئ حساباً</a></p>
        </form>
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
                    <li><a href="../Browseitems.php">تصفح العناصر الموجودة</a></li>
                    <li><a href="../Report a find.php">أبلغ عن عنصر موجود</a></li>
                    <li><a href="../my Report.php">بلاغاتي</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>تعلّم</h4>
                <ul>
                    <li><a href="../How it works.php">كيف يعمل الموقع</a></li>
                    <li><a href="../Algorithms.php">الخوارزميات</a></li>
                    <li><a href="../About.php">عن Refind</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>الحساب</h4>
                <ul>
                    <li><a href="Log-in.php">تسجيل الدخول</a></li>
                    <li><a href="sign-up.php">إنشاء حساب</a></li>
                    <li><a href="../Messages.php">الرسائل</a></li>
                </ul>
            </div>
        </div>
    </footer>
    <div class="footer-bottom">
        <p>صُنع بعناية ليعود المفقود إلى بيته. © 2026 Refind</p>
    </div>
</body>

</html>