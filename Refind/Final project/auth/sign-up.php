<?php
session_start();
include '../config/db.php';
include '../config/auth.php';
include 'validation.php';

requireGuest();

$errors = [];
$name  = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    csrfCheck();
    $password = $_POST['password'] ?? '';

    $errors = validateSignUp($name, $email, $phone, $password, $conn);

    if (empty($errors)) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $sql = "INSERT INTO users (name, email, phone, password)
                    VALUES (:name, :email, :phone, :password)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name'     => $name,
                ':email'    => $email,
                ':phone'    => $phone,
                ':password' => $hashedPassword
            ]);

            $id = $conn->lastInsertId();
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;

            header("Location: ../Home.php");
            exit();

        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $errors[] = "هذا البريد الإلكتروني مسجل بالفعل.";
            } else {
                $errors[] = "حدث خطأ غير متوقع. حاول مرة أخرى.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب — Refind</title>
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
                <a href="../About.php">من نحن</a>
            </nav>
            <div class="auth-buttons">
                <a href="Log-in.php" class="login-btn">تسجيل الدخول</a>
                <a href="sign-up.php" class="signup-btn">إنشاء حساب</a>
            </div>
        </div>
    </header>

    <main class="auth-page">
        <form class="auth-card" action="sign-up.php" method="POST">
            <?php echo csrfField(); ?>
            <div class="badge" style="background-color: #e0f2fe; color: #0369a1;">انضم إلى Refind</div>
            <h1>إنشاء حساب</h1>
            <p class="auth-subtitle">أنشئ حساباً لنشر والبحث عن العناصر المفقودة.</p>

            <?php if (!empty($errors)): ?>
                <div style="background-color: #fee2e2; color: #991b1b; padding: 10px; border-radius: 8px; margin-bottom: 15px; border: 1px solid #fecaca;">
                    <ul style="margin: 0; padding-right: 20px;">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <label>الاسم الكامل</label>
            <input id="signupName" name="name" type="text" placeholder="أدخل اسمك الكامل" value="<?php echo htmlspecialchars($name); ?>" required>

            <label>البريد الإلكتروني</label>
            <input id="signupEmail" name="email" type="email" placeholder="أدخل بريدك الإلكتروني" value="<?php echo htmlspecialchars($email); ?>" required>

            <label>رقم الهاتف</label>
            <input id="signupPhone" name="phone" type="tel" placeholder="أدخل رقم هاتفك" value="<?php echo htmlspecialchars($phone); ?>" required>

            <label>كلمة المرور</label>
            <input id="signupPassword" name="password" type="password" minlength="6" placeholder="6 أحرف على الأقل" required>

            <button type="submit">إنشاء الحساب</button>
            <p class="switch">لديك حساب بالفعل؟ <a href="Log-in.php">تسجيل الدخول</a></p>
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