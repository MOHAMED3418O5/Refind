<?php
session_start();
include '../config/auth.php';
include '../config/db.php';

requireLogin();

$user = currentUser($conn);
$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    csrfCheck();

    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || $email === '' || $phone === '') {
        $errors[] = "الاسم والبريد الإلكتروني والهاتف مطلوبة.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "صيغة البريد الإلكتروني غير صحيحة.";
    } elseif ($password !== '' && strlen($password) < 6) {
        $errors[] = "كلمة المرور يجب ألا تقل عن 6 أحرف.";
    } else {
        try {
            $sql = "UPDATE users SET name = :name, email = :email, phone = :phone" . ($password ? ", password = :password" : "") . " WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            if ($password) {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $stmt->bindParam(':password', $hashedPassword);
            }
            $stmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
            $stmt->execute();

            $success = "تم تحديث بياناتك بنجاح.";
            $user = currentUser($conn);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $errors[] = "هذا البريد الإلكتروني مستخدم من حساب آخر.";
            } else {
                $errors[] = "حدث خطأ في قاعدة البيانات.";
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
    <title>تعديل الملف الشخصي — Refind</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/main.css">

    <style>
        .edit-profile-page {
            min-height: 70vh;
            padding: 50px 20px;
            background: #f8fafc;
        }

        .edit-profile-container {
            max-width: 700px;
            margin: auto;
        }

        .edit-profile-card {
            background: white;
            border-radius: 18px;
            padding: 35px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
        }

        .edit-profile-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .edit-profile-icon {
            width: 85px;
            height: 85px;
            margin: 0 auto 15px;
            border-radius: 50%;
            background: #e0f2fe;
            color: #0369a1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 35px;
        }

        .edit-profile-title h1 {
            margin: 0 0 8px;
            color: #0f172a;
        }

        .edit-profile-title p {
            margin: 0;
            color: #64748b;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #334155;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
        }

        .input-wrapper input {
            width: 100%;
            box-sizing: border-box;
            padding: 13px 45px 13px 15px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-size: 15px;
            outline: none;
            transition: 0.2s;
        }

        .input-wrapper input:focus {
            border-color: #0369a1;
            box-shadow: 0 0 0 3px rgba(3, 105, 161, 0.1);
        }

        .password-note {
            display: block;
            margin-top: 6px;
            font-size: 12px;
            color: #64748b;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 30px;
        }

        .form-btn {
            flex: 1;
            padding: 13px 20px;
            border-radius: 10px;
            text-align: center;
            text-decoration: none;
            font-weight: 600;
            border: none;
            cursor: pointer;
            font-size: 15px;
            transition: 0.2s;
        }

        .save-btn {
            background: #0369a1;
            color: white;
        }

        .save-btn:hover {
            background: #075985;
        }

        .cancel-btn {
            background: #e2e8f0;
            color: #334155;
        }

        .cancel-btn:hover {
            background: #cbd5e1;
        }

        .message-success {
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .message-error {
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        @media (max-width: 600px) {
            .edit-profile-card {
                padding: 25px 20px;
            }

            .form-actions {
                flex-direction: column;
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
                <a href="profie.php" class="profile-btn">الملف الشخصي</a>
                <a href="logout.php" class="logout-btn">تسجيل الخروج</a>
            </div>
        </div>
    </header>

    <main class="edit-profile-page">
        <div class="edit-profile-container">
            <div class="edit-profile-card">
                <div class="edit-profile-title">
                    <div class="edit-profile-icon">
                        <i class="fa-solid fa-user-pen"></i>
                    </div>
                    <h1>تعديل الملف الشخصي</h1>
                    <p>حدّث معلوماتك الشخصية.</p>
                </div>

                <?php if (!empty($success)): ?>
                    <div class="message-success">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                    <div class="message-error">
                        <?php foreach ($errors as $error): ?>
                            <div><?php echo htmlspecialchars($error); ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="Edit-profile.php">
                    <?php echo csrfField(); ?>

                    <div class="form-group">
                        <label for="name">الاسم الكامل</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-user"></i>
                            <input id="name" name="name" type="text" placeholder="أدخل اسمك الكامل" value="<?php echo htmlspecialchars($user['name']); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">البريد الإلكتروني</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-envelope"></i>
                            <input id="email" name="email" type="email" placeholder="أدخل بريدك الإلكتروني" value="<?php echo htmlspecialchars($user['email']); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone">رقم الهاتف</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-phone"></i>
                            <input id="phone" name="phone" type="tel" placeholder="أدخل رقم هاتفك" value="<?php echo htmlspecialchars($user['phone']); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">كلمة مرور جديدة</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-lock"></i>
                            <input id="password" name="password" type="password" minlength="6" placeholder="أدخل كلمة مرور جديدة">
                        </div>
                        <span class="password-note">اترك هذا الحقل فارغاً إذا كنت لا تريد تغيير كلمة المرور.</span>
                    </div>

                    <div class="form-actions">
                        <a href="profie.php" class="form-btn cancel-btn">
                            <i class="fa-solid fa-xmark"></i> إلغاء
                        </a>
                        <button type="submit" class="form-btn save-btn">
                            <i class="fa-solid fa-check"></i> حفظ التغييرات
                        </button>
                    </div>

                </form>
            </div>
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
                    <li><a href="profie.php">الملف الشخصي</a></li>
                    <li><a href="Log-in.php">تسجيل الدخول</a></li>
                    <li><a href="sign-up.php">إنشاء حساب</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>صُنع بعناية ليعود المفقود إلى بيته. © 2026 Refind</p>
        </div>
    </footer>

</body>

</html>