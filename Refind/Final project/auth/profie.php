<?php
session_start();
include '../config/auth.php';
include '../config/db.php';

requireLogin();
$user = currentUser($conn);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الملف الشخصي — Refind</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/main.css">

    <style>
        .profile-page {
            min-height: 70vh;
            padding: 50px 20px;
            background: #f8fafc;
        }

        .profile-container {
            max-width: 950px;
            margin: auto;
        }

        .profile-header {
            background: white;
            border-radius: 18px;
            padding: 35px;
            display: flex;
            align-items: center;
            gap: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
            margin-bottom: 25px;
        }

        .profile-avatar {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            background: #e0f2fe;
            color: #0369a1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 45px;
        }

        .profile-header h1 {
            margin: 0 0 8px;
            font-size: 30px;
            color: #0f172a;
        }

        .profile-header p {
            margin: 0;
            color: #64748b;
        }

        .profile-card {
            background: white;
            border-radius: 18px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
            margin-bottom: 25px;
        }

        .profile-card h2 {
            margin-top: 0;
            margin-bottom: 25px;
            color: #0f172a;
            font-size: 22px;
        }

        .profile-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .info-box {
            padding: 18px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .info-box .info-label {
            display: block;
            font-size: 13px;
            color: #64748b;
            margin-bottom: 7px;
        }

        .info-box .info-value {
            font-size: 16px;
            color: #0f172a;
            font-weight: 500;
        }

        .profile-actions {
            display: flex;
            gap: 15px;
            margin-top: 25px;
        }

        .profile-btn {
            text-decoration: none;
            padding: 12px 22px;
            border-radius: 10px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: 0.2s;
        }

        .edit-btn {
            background: #0369a1;
            color: white;
        }

        .edit-btn:hover {
            background: #075985;
        }

        .logout-btn-profile {
            background: #fee2e2;
            color: #b91c1c;
        }

        .logout-btn-profile:hover {
            background: #fecaca;
        }

        .profile-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .stat-box {
            text-align: center;
            padding: 25px 15px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .stat-box i {
            font-size: 25px;
            color: #0369a1;
            margin-bottom: 10px;
        }

        .stat-number {
            display: block;
            font-size: 25px;
            font-weight: bold;
            color: #0f172a;
        }

        .stat-label {
            color: #64748b;
            font-size: 14px;
        }

        @media (max-width: 700px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
            }

            .profile-info {
                grid-template-columns: 1fr;
            }

            .profile-stats {
                grid-template-columns: 1fr;
            }

            .profile-actions {
                flex-direction: column;
            }

            .profile-btn {
                justify-content: center;
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

    <main class="profile-page">

        <div class="profile-container">

            <section class="profile-header">
                <div class="profile-avatar">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div>
                    <h1>ملفك الشخصي</h1>
                    <p>إدارة معلومات حسابك في Refind.</p>
                </div>
            </section>

            <section class="profile-card">
                <h2><i class="fa-solid fa-user"></i> المعلومات الشخصية</h2>

                <div class="profile-info">
                    <div class="info-box">
                        <span class="info-label">الاسم الكامل</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['name']); ?></span>
                    </div>

                    <div class="info-box">
                        <span class="info-label">البريد الإلكتروني</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>

                    <div class="info-box">
                        <span class="info-label">رقم الهاتف</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['phone']); ?></span>
                    </div>

                    <div class="info-box">
                        <span class="info-label">حالة الحساب</span>
                        <span class="info-value">نشط</span>
                    </div>
                </div>

                <div class="profile-actions">
                    <a href="Edit-profile.php" class="profile-btn edit-btn">
                        <i class="fa-solid fa-pen"></i> تعديل الملف
                    </a>
                    <a href="logout.php" class="profile-btn logout-btn-profile">
                        <i class="fa-solid fa-right-from-bracket"></i> تسجيل الخروج
                    </a>
                </div>
            </section>

            <section class="profile-card">
                <h2><i class="fa-solid fa-chart-simple"></i> نشاطي</h2>
                <div class="profile-stats">
                    <div class="stat-box">
                        <i class="fa-solid fa-box"></i>
                        <span class="stat-number">0</span>
                        <span class="stat-label">بلاغات عن مفقود</span>
                    </div>
                    <div class="stat-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span class="stat-number">0</span>
                        <span class="stat-label">بلاغات عن عناصر موجودة</span>
                    </div>
                    <div class="stat-box">
                        <i class="fa-solid fa-handshake"></i>
                        <span class="stat-number">0</span>
                        <span class="stat-label">عناصر أُعيدت</span>
                    </div>
                </div>
            </section>

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