<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refind — تصفح العناصر المفقودة</title>
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
                    <label for="categoryFilter">الفئة</label>
                    <select id="categoryFilter" name="category">
                        <option value="all">كل الفئات</option>
                        <option value="إكسسوارات">إكسسوارات</option>
                        <option value="إلكترونيات">إلكترونيات</option>
                        <option value="حقائب">حقائب ومحافظ</option>
                        <option value="مفاتيح">مفاتيح وبطاقات</option>
                        <option value="ملابس">ملابس</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="typeFilter">نوع العنصر</label>
                    <select id="typeFilter" name="item_type">
                        <option value="all">كل الأنواع</option>
                        <option value="هاتف ذكي">هاتف ذكي</option>
                        <option value="لابتوب">لابتوب</option>
                        <option value="حقيبة ظهر">حقيبة ظهر</option>
                        <option value="مفاتيح سيارة">مفاتيح سيارة</option>
                        <option value="محفظة">محفظة</option>
                        <option value="ساعة يد">ساعة يد</option>
                        <option value="نظارة شمسية">نظارة شمسية</option>
                        <option value="سماعات أذن">سماعات أذن</option>
                        <option value="سترة">سترة</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="colorFilter">اللون</label>
                    <select id="colorFilter" name="color">
                        <option value="all">كل الألوان</option>
                        <option value="أسود">أسود</option>
                        <option value="فضي">فضي</option>
                        <option value="أزرق">أزرق</option>
                        <option value="بني">بني</option>
                        <option value="أحمر">أحمر</option>
                        <option value="أبيض">أبيض</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="locationFilter">المكان</label>
                    <select id="locationFilter" name="location">
                        <option value="all">كل الأماكن</option>
                        <option value="وسط البلد">وسط البلد</option>
                        <option value="المكتبة المركزية">المكتبة المركزية</option>
                        <option value="الحرم الجامعي">الحرم الجامعي</option>
                        <option value="الحديقة العامة">الحديقة العامة</option>
                        <option value="بوابة الجامعة">بوابة الجامعة</option>
                        <option value="الشارع الرئيسي">الشارع الرئيسي</option>
                        <option value="المكتبة">المكتبة</option>
                        <option value="الكافيتيريا">الكافيتيريا</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="dateFilter">التاريخ (تقريباً)</label>
                    <input id="dateFilter" name="date" type="date">
                </div>
            </div>

            <div class="filter-actions">
                <button onclick="searchItems()" class="search-btn"><i class="fa-solid fa-filter"></i> تطبيق
                    الفلاتر</button>
                <button onclick="resetFilters()" class="reset-btn">إعادة تعيين</button>
            </div>
        </section>

        <div id="searchStats" class="search-stats" style="display:none;">
            <i class="fa-solid fa-microchip"></i>
            <span id="searchCount"></span>
        </div>

        <section class="request-box">
            <h2>النتائج المطابقة</h2>
            <p>راجع النتائج المحتملة واضغط على "أعتقد أنه لي" للتواصل مع من وجد العنصر.</p>
        </section>

        <section id="itemsContainer" class="items-grid"></section>
           <div class="item-card-body">
        <span class="item-tag">${item.category}</span>
        <h3 class="item-title">${item.type} (${item.color})</h3>
        <div class="item-details">
          <span><i class="fa-solid fa-location-dot"></i> ${item.location}</span>
          <span><i class="fa-regular fa-calendar"></i> ${item.date}</span>
          <p>${item.description}</p>
        </div>
        <button class="match-btn" onclick="claimItem(${item.id})">أعتقد أنه لي</button>
      </div>
        <p id="noItems" class="no-items">لا توجد عناصر مطابقة لهذه الخيارات. جرّب تعديل الفلاتر.</p>
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
    <script src="assets/js/index.js"></script>
</body>

</html>