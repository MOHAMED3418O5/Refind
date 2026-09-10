<?php
session_start();
include 'config/db.php';
include 'config/auth.php';

$id = $_SESSION['user_id'] ?? null;

if (!$id) {
    header("Location: auth/Log-in.php");
    exit();
}

$stmt = $conn->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->query("SELECT * FROM color");
$colors = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->query("SELECT * FROM locations");
$locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->query("SELECT * FROM sub_cat");
$sub_cat = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfCheck();

    $itemName = trim($_POST['item-name'] ?? '');
    $category = (int)($_POST['category'] ?? 0);
    $subCat = (int)($_POST['sub_cat'] ?? 0);
    $color = (int)($_POST['color'] ?? 0);
    $location = (int)($_POST['location'] ?? 0);
    $dateFound = $_POST['dateFound'] ?? '';
    $description = trim($_POST['description'] ?? '');

    $errors = [];

    if ($itemName === '') {
        $errors[] = 'من فضلك أدخل اسم العنصر.';
    }

    if ($category <= 0) {
        $errors[] = 'من فضلك اختر الفئة.';
    }

    if ($subCat <= 0) {
        $errors[] = 'من فضلك اختر الفئة الفرعية.';
    }

    if ($color <= 0) {
        $errors[] = 'من فضلك اختر اللون.';
    }

    if ($location <= 0) {
        $errors[] = 'من فضلك اختر المكان.';
    }

    if ($dateFound === '') {
        $errors[] = 'من فضلك اختر تاريخ العثور.';
    }

    if ($subCat > 0 && $category > 0) {
        $stmt = $conn->prepare("
            SELECT id
            FROM sub_cat
            WHERE id = :sub_cat
            AND cat_id = :category
            LIMIT 1
        ");

        $stmt->execute([
            ':sub_cat' => $subCat,
            ':category' => $category
        ]);

        if (!$stmt->fetch()) {
            $errors[] = 'الفئة الفرعية المختارة غير صحيحة.';
        }
    }

    $imageName = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'حدث خطأ أثناء رفع الصورة.';
        } else {
            $allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp',
                'image/gif'
            ];

            $fileType = mime_content_type($_FILES['image']['tmp_name']);

            if (!in_array($fileType, $allowedTypes, true)) {
                $errors[] = 'نوع الصورة غير مسموح به.';
            }

            if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
                $errors[] = 'حجم الصورة يجب ألا يتجاوز 5 ميجابايت.';
            }

            if (empty($errors)) {
                $uploadDir = __DIR__ . '/uploads/reports/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $extension = strtolower(
                    pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION)
                );

                $imageName = uniqid('report_', true) . '.' . $extension;
                $imagePath = $uploadDir . $imageName;

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                    $errors[] = 'تعذر حفظ الصورة.';
                    $imageName = null;
                }
            }
        }
    }

    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("INSERT INTO reports ( user_id, item_name, category_id, sub_cat_id, color_id, location_id, date_found, description,report_type,image)VALUES
                    (
                    :user_id,
                    :item_name,
                    :category_id,
                    :sub_cat_id,
                    :color_id,
                    :location_id,
                    :date_found,
                    :description,
                    :report_type,
                    :image
                )
            ");

            $stmt->execute([
                ':user_id' => $id,
                ':item_name' => $itemName,
                ':category_id' => $category,
                ':sub_cat_id' => $subCat,
                ':color_id' => $color,
                ':location_id' => $location,
                ':date_found' => $dateFound,
                ':description' => $description,
                ':report_type' => 'found',
                ':image' => $imageName
            ]);

            header("Location: Report a find.php");
            exit();
        } catch (PDOException $e) {
            if ($imageName !== null) {
                $uploadedFile = __DIR__ . '/uploads/reports/' . $imageName;

                if (file_exists($uploadedFile)) {
                    unlink($uploadedFile);
                }
            }

            $errors[] = 'حدث خطأ أثناء حفظ البلاغ.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>أبلغ عن عنصر موجود — Refind</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>

<header class="navbar">
    <div class="nav-container">
        <div class="logo-area">
            <span class="logo-icon">
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>
            <span class="logo-text">Refind</span>
        </div>

        <nav class="nav-links">
            <a href="Home.php">الرئيسية</a>
            <a href="Browseitems.php">تصفح العناصر</a>
            <a href="my Report.php">بلاغاتي</a>
            <a href="Report a find.php" class="active">أبلغ عن شيء موجود</a>
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
    <div class="page-header">
        <div class="badge" style="background-color:#e0f2fe;color:#0369a1;">
            للمن وجد شيئاً
        </div>
        <h1>أبلغ عن عنصر موجود</h1>
        <p>صِف العنصر بشكل عام، ثم سجّل تفصيلاً خاصاً لا يعرفه إلا المالك الحقيقي.</p>
    </div>

    <div class="content-grid">
        <form class="report-form" method="POST" enctype="multipart/form-data">

            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <?php foreach ($errors as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (function_exists('csrfField')): ?>
                <?= csrfField() ?>
            <?php endif; ?>

            <div class="form-group">
                <label for="item-name">ماذا وجدت؟</label>
                <input
                    type="text"
                    id="item-name"
                    name="item-name"
                    placeholder="مثال: حقيبة ظهر قماشية سوداء"
                    value="<?= htmlspecialchars($_POST['item-name'] ?? '') ?>"
                    required
                >
            </div>

            <div class="form-row">
                <div class="form-group half">
                    <label for="category">اختر الفئة</label>
                    <select id="category" name="category" required>
                        <option value="" selected disabled>اختر الفئة</option>
                        <?php foreach ($categories as $categoryItem): ?>
                            <option
                                value="<?= (int)$categoryItem['id'] ?>"
                                <?= (($_POST['category'] ?? '') == $categoryItem['id']) ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($categoryItem['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group half">
                    <label for="sub_cat">الفئة الفرعية</label>
                    <select id="sub_cat" name="sub_cat" required>
                        <option value="" selected>اختر الفئة الفرعية</option>
                        <?php foreach ($sub_cat as $sub): ?>
                            <option
                                value="<?= (int)$sub['id'] ?>"
                                data-cat="<?= (int)$sub['cat_id'] ?>"
                                <?= (($_POST['sub_cat'] ?? '') == $sub['id']) ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($sub['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group half">
                    <label for="color">اللون</label>
                    <select id="color" name="color" required>
                        <option value="" selected disabled>اختر اللون</option>
                        <?php foreach ($colors as $colorItem): ?>
                            <option
                                value="<?= (int)$colorItem['id'] ?>"
                                <?= (($_POST['color'] ?? '') == $colorItem['id']) ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($colorItem['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group half">
                    <label for="location">المكان</label>
                    <select id="location" name="location" required>
                        <option value="" selected disabled>اختر المكان</option>
                        <?php foreach ($locations as $locationItem): ?>
                            <option
                                value="<?= (int)$locationItem['id'] ?>"
                                <?= (($_POST['location'] ?? '') == $locationItem['id']) ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($locationItem['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group half">
                    <label for="date-found">تاريخ العثور</label>
                    <input
                        type="date"
                        id="date-found"
                        name="dateFound"
                        value="<?= htmlspecialchars($_POST['dateFound'] ?? '') ?>"
                        required
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="description">الوصف العام</label>
                <textarea
                    id="description"
                    rows="4"
                    name="description"
                    placeholder="صِف العنصر دون الكشف عن تفاصيل تميّزه."
                ><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label for="image">الصورة</label>
                <input
                    id="image"
                    name="image"
                    type="file"
                    accept="image/jpeg,image/png,image/webp,image/gif"
                >
                <small>الحد الأقصى لحجم الصورة 5MB.</small>
            </div>

            <div class="Refindlink">
                <button type="submit" class="btn-primary">
                    نشر العنصر الموجود
                </button>
            </div>

        </form>

        <div class="tips-sidebar">
            <div class="tips-card">
                <h3>نصائح لبلاغ جيد</h3>
                <ul>
                    <li>اجعل الوصف العام عاماً — اللون والنوع والمكان الذي وجدته فيه.</li>
                    <li>احتفظ بأكثر المميزات تميزاً لسؤال التحقق المخفي.</li>
                    <li>سَلّم العناصر الهشة أو الثمينة لمكتب قريب إن أمكن.</li>
                    <li>قابل الشخص في مكان عام عند إعادة العنصر.</li>
                </ul>
            </div>
        </div>
    </div>
</main>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-col">
            <div class="logo-area">
                <span class="logo-icon">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
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

<script>
const categorySelect = document.getElementById('category');
const subCatSelect = document.getElementById('sub_cat');

if (categorySelect && subCatSelect) {
    const rawOptions = Array.from(subCatSelect.options);
    let initialSubCat = "<?= htmlspecialchars($_POST['sub_cat'] ?? '') ?>";

    function filterSubCategories(isInitialLoad = false) {
        const selectedCatId = categorySelect.value;

        subCatSelect.innerHTML = '';

        rawOptions.forEach(option => {
            const optionCatId = option.getAttribute('data-cat');

            if (!option.value || optionCatId === selectedCatId) {
                const newOpt = option.cloneNode(true);
                newOpt.selected = false;
                subCatSelect.appendChild(newOpt);
            }
        });

        if (isInitialLoad && initialSubCat) {
            subCatSelect.value = initialSubCat;
        } else {
            subCatSelect.value = "";
        }
    }

    filterSubCategories(true);

    categorySelect.addEventListener('change', () => {
        filterSubCategories(false);
    });
}
</script>

</body>
</html>