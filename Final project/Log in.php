
<?php

session_start();
include 'db.php';
include 'validation.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $errors = validateLogin($email, $password);

    if (empty($errors)) {
        try {
            // التعديل الرئيسي: استخدام prepare بدلاً من query
            $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // تحقق من وجود المستخدم وكلمة المرور
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['name'];

                header("Location: Home.php");
                exit();
            } else {
                $errors[] = "Invalid email or password.";
            }

        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refind — Reunite lost things with owners</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="main.css">
</head>

<body>
    <header class="navbar">
        <div class="nav-container">
            <div class="logo-area">
                <span class="logo-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                <span class="logo-text">Refind</span>
            </div>
            <nav class="nav-links">
                <a href="Home.php">Home</a>
                <a href="Browseitems.php">Browse items</a>
                <a href="my Report.php">My Report</a>
                <a href="Report a find.php">Report a found</a>
                <a href="Messages.php">Messages</a>
                <a href="How it works.php">How it works</a>
                <a href="About.php">About</a>
            </nav>
            <div class="auth-buttons">
                <a href="Log in.php" class="login-btn">Log in</a>
                <a href="sign up.php" class="signup-btn">Sign up</a>
                <a href="logout.php" class="logout-btn">Log out</a>
            </div>
        </div>
    </header>

    <main class="auth-page">
        <form class="auth-card" action="Log in.php" method="POST">
            <div class="badge" style="background-color: #e0f2fe; color: #0369a1;">Welcome Back </div>
            <h1>Log In</h1>
            <p class="auth-subtitle">Log in to continue to FindIt.</p>

            <!-- عرض أخطاء تسجيل الدخول إن وجدت -->
            <?php if (!empty($errors)): ?>
                <div style="background-color: #fee2e2; color: #dc2626; padding: 10px; border-radius: 6px; margin-bottom: 15px;">
                    <?php foreach ($errors as $error): ?>
                        <p style="margin: 0; font-size: 14px;"><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <label>Email</label>
            <input id="loginEmail" name="email" type="email" placeholder="Enter your email" required>
            
            <label>Password</label>
            <input id="loginPassword" name="password" type="password" placeholder="Enter your password" required>
            
            <button type="submit">Log In</button>
            <p class="switch">Don't have an account? <a href="sign up.php">Sign Up</a></p>
        </form>
    </main>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-col">
                <div class="logo-area">
                    <span class="logo-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <span class="logo-text">Refind</span>
                </div>
                <p class="footer-logo-desc">A calm, safe way to reunite people with the things they lose.</p>
            </div>
            <div class="footer-col">
                <h4>Platform</h4>
                <ul>
                    <li><a href="Browseitems.php">Browse found items</a></li>
                    <li><a href="Report a find.php">Report a found item</a></li>
                    <li><a href="my Report.php">My Report</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Learn</h4>
                <ul>
                    <li><a href="How it works.php">How it works</a></li>
                    <li><a href="About.php">About Refind</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Account</h4>
                <ul>
                    <li><a href="Log in.php">Log in</a></li>
                    <li><a href="sign up.php">Create account</a></li>
                    <li><a href="Messages.php">Messages</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <script src="index.js"></script>
    <div class="footer-bottom">
        <p>Made with care to bring lost things back home. © 2026 Refind</p>
    </div>
</body>

</html>