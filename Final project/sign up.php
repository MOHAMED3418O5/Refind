<?php
include 'db.php';
include 'validation.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    $errors = validateSignUp($name, $email, $phone, $password, $conn);

    if (empty($errors)) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $sql = "INSERT INTO users (name, email, phone, password) VALUES (:name, :email, :phone, :password)";
            
            // استخدام prepare بدلاً من query
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name'     => $name,
                ':email'    => $email,
                ':phone'    => $phone,
                ':password' => $hashedPassword
            ]);

            header("Location: Home.php");
            exit();

        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $errors[] = "This email is already registered.";
            } else {
                // إظهار نص الخطأ للتأكد إذا كان هناك اختلال آخر في أسماء الأعمدة
                $errors[] = "Database Error: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>

<html>



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

                <a href="Browseitems.php" class="active">Browse items</a>

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

        <form class="auth-card" action="sign up.php" method="POST">

            <div class="badge" style="background-color: #e0f2fe; color: #0369a1;">Join Find</div>

            <h1>Create Account</h1>

            <p class="auth-subtitle">Create an account to post and find lost items.</p>



            <?php if (!empty($errors)): ?>

                <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #f5c6cb;">

                    <ul style="margin: 0; padding-left: 20px;">

                        <?php foreach ($errors as $error): ?>

                            <li><?php echo htmlspecialchars($error); ?></li>

                        <?php endforeach; ?>

                    </ul>

                </div>

            <?php endif; ?>



            <label>Full Name</label>

            <input id="signupName" name="name" type="text" placeholder="Enter your name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>

           

            <label>Email</label>

            <input id="signupEmail" name="email" type="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>

           

            <label>Password</label>

            <input id="signupPassword" name="password" type="password" minlength="6" placeholder="At least 6 characters" required>

            <label>Phone Number</label>

               <input id="signupPhone" name="phone" type="tel" placeholder="Enter your phone number" required>

           

            <button type="submit">Create Account</button>

            <p class="switch">Already have an account? <a href="Log in.php">Log In</a></p>

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

