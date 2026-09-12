<?php
include 'config/db.php';
include 'config/auth.php';

$sql = "SELECT r.*, c.name AS category_name, l.name AS location_name, cl.name AS color_name
        FROM reports r
        JOIN color cl ON r.color = cl.id
        JOIN categories c ON r.category_id = c.id
        JOIN locations l ON r.location_id = l.id
        WHERE r.status = 'active'
        ORDER BY r.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

$categories = $conn->query("SELECT * FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$colors = $conn->query("SELECT * FROM color ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$locations = $conn->query("SELECT * FROM locations ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refind — تصفح العناصر الموجودة</title>
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
            <a href="Browseitems.php" class="active">تصفح العناصر</a>
            <a href="my Report.php">بلاغاتي</a>
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

<main>

    <section class="hero">
        <div class="badge">اعثر على ما فقدته</div>
        <h1>تصفح العناصر الموجودة</h1>
    </section>

    <section class="search-area">
        <div class="filter-grid">

            <div class="filter-group">
                <label>الفئة</label>
                <select id="categoryFilter">
                    <option value="all">كل الفئات</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>">
                            <?= htmlspecialchars($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label>اللون</label>
                <select id="colorFilter">
                    <option value="all">كل الألوان</option>
                    <?php foreach ($colors as $color): ?>
                        <option value="<?= htmlspecialchars($color['name']) ?>">
                            <?= htmlspecialchars($color['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label>المكان</label>
                <select id="locationFilter">
                    <option value="all">كل الأماكن</option>
                    <?php foreach ($locations as $location): ?>
                        <option value="<?= $location['id'] ?>">
                            <?= htmlspecialchars($location['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label>التاريخ</label>
                <input type="date" id="dateFilter">
            </div>

        </div>

        <div class="filter-actions">
            <button type="button" onclick="searchItems()" class="search-btn">
                <i class="fa-solid fa-filter"></i> تطبيق الفلاتر
            </button>

            <button type="button" onclick="resetFilters()" class="reset-btn">
                إعادة تعيين
            </button>
        </div>
    </section>

    <div id="searchStats" class="search-stats" style="display:none;">
        <i class="fa-solid fa-magnifying-glass"></i>
        <span id="searchCount"></span>
    </div>

    <section class="request-box">
        <h2>النتائج المطابقة</h2>
        <p>راجع العناصر الموجودة واضغط على عرض التفاصيل لمعرفة المزيد.</p>
    </section>

    <section id="itemsContainer" class="items-grid">

        <?php foreach ($reports as $report): ?>

            <div class="item-card"
                 data-category="<?= $report['category_id'] ?>"
                 data-color="<?= htmlspecialchars($report['color_name'] ?? '') ?>"
                 data-location="<?= $report['location_id'] ?>"
                 data-date="<?= $report['item_date'] ?>">

                <?php if (!empty($report['image'])): ?>
                    <div class="item-card-image " >
                        <img style="width: 200px;" src="uploads/reports/<?= htmlspecialchars($report['image']) ?>"
                             alt="<?= htmlspecialchars($report['title']) ?>">
                    </div>
                <?php else: ?>
                    <div class="item-card-image">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                <?php endif; ?>

                <div class="item-card-body">

                    <span class="item-tag">
                        <?= htmlspecialchars($report['category_name']) ?>
                    </span>

                    <h3 class="item-title">
                        <?= htmlspecialchars($report['title']) ?>
                    </h3>

                    <div class="item-details">
                        <span>
                            <i class="fa-solid fa-palette"></i>
                            <?= htmlspecialchars($report['color_name'] ?: 'غير محدد') ?>
                        </span>

                        <span>
                            <i class="fa-solid fa-location-dot"></i>
                            <?= htmlspecialchars($report['location_name']) ?>
                        </span>

                        <span>
                            <i class="fa-regular fa-calendar"></i>
                            <?= htmlspecialchars($report['item_date'] ?: 'غير محدد') ?>
                        </span>
                    </div>

                    <?php if (!empty($report['description'])): ?>
                        <p class="item-description">
                            <?= htmlspecialchars($report['description']) ?>
                        </p>
                    <?php endif; ?>

                    <a href="view-report.php?id=<?= $report['id'] ?>" class="search-btn">
                        <i class="fa-solid fa-eye"></i>
                        عرض التفاصيل
                    </a>

                </div>
            </div>

        <?php endforeach; ?>

    </section>

    <p id="noItems" class="no-items" style="display:none;">
        لا توجد عناصر مطابقة لهذه الخيارات.
    </p>

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

<script>
const cards = document.querySelectorAll('.item-card');
const noItems = document.getElementById('noItems');
const searchStats = document.getElementById('searchStats');
const searchCount = document.getElementById('searchCount');

function searchItems() {
    let category = document.getElementById('categoryFilter').value;
    let color = document.getElementById('colorFilter').value;
    let location = document.getElementById('locationFilter').value;
    let date = document.getElementById('dateFilter').value;
    let count = 0;

    cards.forEach(card => {
        let show =
            (category === 'all' || card.dataset.category === category) &&
            (color === 'all' || card.dataset.color === color) &&
            (location === 'all' || card.dataset.location === location) &&
            (date === '' || card.dataset.date === date);

        card.style.display = show ? '' : 'none';

        if (show) count++;
    });

    searchCount.textContent = 'تم العثور على ' + count + ' عنصر';
    searchStats.style.display = 'block';
    noItems.style.display = count === 0 ? 'block' : 'none';
}

function resetFilters() {
    document.getElementById('categoryFilter').value = 'all';
    document.getElementById('colorFilter').value = 'all';
    document.getElementById('locationFilter').value = 'all';
    document.getElementById('dateFilter').value = '';

    cards.forEach(card => card.style.display = '');

    searchStats.style.display = 'none';
    noItems.style.display = 'none';
}
</script>

</body>
</html>