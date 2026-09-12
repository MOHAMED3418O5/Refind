<?php
include 'config/db.php';
include 'config/auth.php';

$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    header("Location: auth/Log-in.php");
    exit();
}

$reportId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$reportId) {
    header("Location: Browseitems.php");
    exit();
}

$sql = "SELECT r.*, u.name AS user_name, u.email AS user_email, c.name AS category_name, l.name AS location_name
        FROM reports r
        LEFT JOIN users u ON r.user_id = u.id
        LEFT JOIN categories c ON r.category_id = c.id
        LEFT JOIN locations l ON r.location_id = l.id
        WHERE r.id = :id
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $reportId]);
$report = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$report) {
    http_response_code(404);
    $pageError = 'البلاغ الذي تبحث عنه غير موجود.';
}

function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

if ($report) {
    if ($report['report_type'] === 'found') {
        $reportType = 'عنصر موجود';
        $reportTypeIcon = 'fa-magnifying-glass';
        $reportTypeClass = 'found';
    } else {
        $reportType = 'عنصر مفقود';
        $reportTypeIcon = 'fa-box';
        $reportTypeClass = 'lost';
    }

    if ($report['status'] === 'claimed') {
        $statusText = 'تمت المطالبة به';
        $statusClass = 'claimed';
        $statusIcon = 'fa-hand';
    } elseif ($report['status'] === 'closed') {
        $statusText = 'مغلق';
        $statusClass = 'closed';
        $statusIcon = 'fa-circle-check';
    } else {
        $statusText = 'نشط';
        $statusClass = 'active';
        $statusIcon = 'fa-circle';
    }

    $imagePath = '';

    if (!empty($report['image'])) {
        $imagePath = 'uploads/reports/' . basename($report['image']);
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $report ? e($report['title']) . ' — Refind' : 'البلاغ غير موجود — Refind' ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/main.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f8fafc;
        }

        .view-report-page {
            min-height: 75vh;
            padding: 50px 20px 70px;
            background: #f8fafc;
        }

        .view-report-container {
            width: 100%;
            max-width: 1050px;
            margin: auto;
        }

        .report-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 22px;
            color: #64748b;
            font-size: 14px;
        }

        .report-breadcrumb a {
            color: #0369a1;
            text-decoration: none;
        }

        .report-breadcrumb i {
            font-size: 11px;
            color: #94a3b8;
        }

        .report-main-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(15,23,42,.07);
            border: 1px solid #e2e8f0;
        }

        .report-top {
            padding: 30px 35px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
        }

        .report-title-area {
            flex: 1;
        }

        .report-type {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 13px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 13px;
        }

        .report-type.found {
            background: #dcfce7;
            color: #166534;
        }

        .report-type.lost {
            background: #fee2e2;
            color: #b91c1c;
        }

        .report-title {
            margin: 0;
            font-size: 30px;
            line-height: 1.4;
            color: #0f172a;
        }

        .report-date-created {
            margin-top: 9px;
            color: #64748b;
            font-size: 13px;
        }

        .report-status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 15px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
        }

        .report-status.active {
            background: #dcfce7;
            color: #166534;
        }

        .report-status.claimed {
            background: #fef3c7;
            color: #92400e;
        }

        .report-status.closed {
            background: #e2e8f0;
            color: #475569;
        }

        .report-content {
            display: grid;
            grid-template-columns: .95fr 1.05fr;
        }

        .report-image-section {
            padding: 35px;
            background: #f8fafc;
            border-left: 1px solid #e2e8f0;
        }

        .report-image-wrapper {
            width: 100%;
            aspect-ratio: 1;
            max-height: 500px;
            border-radius: 16px;
            overflow: hidden;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .report-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .no-report-image {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            color: #94a3b8;
            height: 100%;
            width: 100%;
        }

        .no-report-image i {
            font-size: 55px;
        }

        .report-details-section {
            padding: 35px;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0 0 22px;
            color: #0f172a;
            font-size: 21px;
        }

        .section-title i {
            color: #0369a1;
        }

        .report-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .report-info-box {
            padding: 17px;
            border-radius: 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .report-info-label {
            display: block;
            color: #64748b;
            font-size: 13px;
            margin-bottom: 7px;
        }

        .report-info-value {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #0f172a;
            font-size: 15px;
            font-weight: 600;
        }

        .report-info-value i {
            color: #0369a1;
            width: 17px;
        }

        .description-box {
            margin-top: 25px;
            padding: 20px;
            border-radius: 13px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .description-box h3 {
            margin: 0 0 12px;
            font-size: 16px;
            color: #0f172a;
        }

        .description-box p {
            margin: 0;
            color: #475569;
            line-height: 1.9;
            white-space: pre-line;
        }

        .report-user-card {
            margin-top: 25px;
            padding: 20px;
            border-radius: 14px;
            background: #eff6ff;
            border: 1px solid #dbeafe;
        }

        .report-user-title {
            margin: 0 0 15px;
            color: #0f172a;
            font-size: 17px;
        }

        .report-user-info {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .report-user-avatar {
            width: 50px;
            height: 50px;
            min-width: 50px;
            border-radius: 50%;
            background: #dbeafe;
            color: #0369a1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .report-user-name {
            margin: 0 0 4px;
            color: #0f172a;
            font-size: 15px;
            font-weight: 700;
        }

        .report-user-email {
            margin: 0;
            color: #64748b;
            font-size: 13px;
            direction: ltr;
            text-align: right;
        }

        .report-actions {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        .report-action-btn {
            flex: 1;
            text-decoration: none;
            padding: 13px 18px;
            border-radius: 10px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            font-weight: 700;
        }

        .contact-btn {
            background: #0369a1;
            color: #fff;
        }

        .back-btn {
            background: #e2e8f0;
            color: #334155;
        }

        .report-error {
            background: #fff;
            border-radius: 18px;
            padding: 60px 30px;
            text-align: center;
            box-shadow: 0 8px 30px rgba(15,23,42,.06);
            border: 1px solid #e2e8f0;
        }

        .report-error i {
            font-size: 55px;
            color: #ef4444;
            margin-bottom: 20px;
        }

        .report-error h2 {
            margin: 0 0 10px;
            color: #0f172a;
        }

        .report-error p {
            color: #64748b;
            margin-bottom: 25px;
        }

        @media (max-width: 800px) {
            .view-report-page {
                padding: 30px 15px 50px;
            }

            .report-top {
                padding: 25px;
                flex-direction: column;
            }

            .report-title {
                font-size: 25px;
            }

            .report-content {
                grid-template-columns: 1fr;
            }

            .report-image-section {
                border-left: none;
                border-bottom: 1px solid #e2e8f0;
                padding: 25px;
            }

            .report-details-section {
                padding: 25px;
            }
        }

        @media (max-width: 550px) {
            .report-info-grid {
                grid-template-columns: 1fr;
            }

            .report-actions {
                flex-direction: column;
            }

            .report-action-btn {
                width: 100%;
            }

            .report-title {
                font-size: 22px;
            }

            .report-top,
            .report-image-section,
            .report-details-section {
                padding: 20px;
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
            <a href="auth/profie.php" class="profile-btn">الملف الشخصي</a>
            <a href="auth/logout.php" class="logout-btn">تسجيل الخروج</a>
        </div>
    </div>
</header>

<main class="view-report-page">
    <div class="view-report-container">

        <?php if ($report): ?>

            <div class="report-breadcrumb">
                <a href="Browseitems.php">تصفح العناصر</a>
                <i class="fa-solid fa-chevron-left"></i>
                <span>تفاصيل البلاغ</span>
            </div>

            <div class="report-main-card">

                <div class="report-top">
                    <div class="report-title-area">
                        <span class="report-type <?= e($reportTypeClass) ?>">
                            <i class="fa-solid <?= e($reportTypeIcon) ?>"></i>
                            <?= e($reportType) ?>
                        </span>

                        <h1 class="report-title"><?= e($report['title']) ?></h1>

                        <?php if (!empty($report['created_at'])): ?>
                            <p class="report-date-created">
                                <i class="fa-regular fa-clock"></i>
                                نُشر في <?= e(date('Y/m/d', strtotime($report['created_at']))) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <span class="report-status <?= e($statusClass) ?>">
                        <i class="fa-solid <?= e($statusIcon) ?>"></i>
                        <?= e($statusText) ?>
                    </span>
                </div>

                <div class="report-content">

                    <div class="report-image-section">
                        <div class="report-image-wrapper">
                            <?php if ($imagePath): ?>
                                <img src="<?= e($imagePath) ?>" alt="<?= e($report['title']) ?>">
                            <?php else: ?>
                                <div class="no-report-image">
                                    <i class="fa-regular fa-image"></i>
                                    <span>لا توجد صورة لهذا العنصر</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="report-details-section">

                        <h2 class="section-title">
                            <i class="fa-solid fa-circle-info"></i>
                            تفاصيل العنصر
                        </h2>

                        <div class="report-info-grid">

                            <div class="report-info-box">
                                <span class="report-info-label">الفئة</span>
                                <span class="report-info-value">
                                    <i class="fa-solid fa-layer-group"></i>
                                    <?= e($report['category_name'] ?? 'غير محدد') ?>
                                </span>
                            </div>

                            <div class="report-info-box">
                                <span class="report-info-label">المكان</span>
                                <span class="report-info-value">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <?= e($report['location_name'] ?? 'غير محدد') ?>
                                </span>
                            </div>

                            <div class="report-info-box">
                                <span class="report-info-label">اللون</span>
                                <span class="report-info-value">
                                    <i class="fa-solid fa-palette"></i>
                                    <?= e($report['color'] ?: 'غير محدد') ?>
                                </span>
                            </div>

                            <div class="report-info-box">
                                <span class="report-info-label">التاريخ</span>
                                <span class="report-info-value">
                                    <i class="fa-regular fa-calendar"></i>
                                    <?= !empty($report['item_date']) ? e(date('Y/m/d', strtotime($report['item_date']))) : 'غير محدد' ?>
                                </span>
                            </div>

                        </div>

                        <div class="description-box">
                            <h3>
                                <i class="fa-solid fa-align-right"></i>
                                الوصف
                            </h3>
                            <p><?= !empty($report['description']) ? e($report['description']) : 'لا يوجد وصف مضاف لهذا البلاغ.' ?></p>
                        </div>

                        <div class="report-user-card">
                            <h3 class="report-user-title">
                                <i class="fa-solid fa-user"></i>
                                صاحب البلاغ
                            </h3>

                            <div class="report-user-info">
                                <div class="report-user-avatar">
                                    <i class="fa-solid fa-user"></i>
                                </div>

                                <div>
                                    <p class="report-user-name">
                                        <?= e($report['user_name'] ?? 'مستخدم Refind') ?>
                                    </p>

                                    <?php if (!empty($report['user_email'])): ?>
                                        <p class="report-user-email"><?= e($report['user_email']) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="report-actions">

                            <?php if ($report['user_id'] != $userId && $report['status'] === 'active'): ?>
                                <a href="chat.php?report_id=<?= (int)$report['id'] ?>" class="report-action-btn contact-btn">
                                    <i class="fa-solid fa-message"></i>
                                    التواصل مع صاحب البلاغ
                                </a>
                            <?php endif; ?>

                            <a href="Browseitems.php" class="report-action-btn back-btn">
                                <i class="fa-solid fa-arrow-right"></i>
                                العودة للعناصر
                            </a>

                        </div>

                    </div>
                </div>
            </div>

        <?php else: ?>

            <div class="report-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <h2>البلاغ غير موجود</h2>
                <p><?= e($pageError ?? 'تعذر العثور على البلاغ المطلوب.') ?></p>

                <a href="Browseitems.php" class="report-action-btn contact-btn" style="display:inline-flex;max-width:220px;">
                    <i class="fa-solid fa-arrow-right"></i>
                    العودة للعناصر
                </a>
            </div>

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
                <li><a href="auth/profie.php">الملف الشخصي</a></li>
                <li><a href="auth/logout.php">تسجيل الخروج</a></li>
            </ul>
        </div>

    </div>

    <div class="footer-bottom">
        <p>صُنع بعناية ليعود المفقود إلى بيته. © 2026 Refind</p>
    </div>
</footer>

</body>
</html>