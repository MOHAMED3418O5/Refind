<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: Log-in.php");
    exit();
}
$id = $_SESSION['user_id'];
$sql = "SELECT * from users WHERE id = $id";
$user = $conn->prepare($sql);
$user->fetch(PDO::FETCH_ASSOC);
$user->execute();
$errors = [];
$success = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

     if($name === '' || $email === '' || $phone === '') {
        $errors[] = "Name, email, and phone are required.";
    } else {
        $sql = "UPDATE users SET name = :name, email = :email, phone = :phone" . ($password ? ", password = :password" : "") . " WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        if ($password) {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $hashedPassword);
        }
        $stmt->bindParam(':id', $_SESSION['user_id']);
        $stmt->execute();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Profile - Refind</title>

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

            left: 15px;
            top: 50%;

            transform: translateY(-50%);

            color: #64748b;
        }

        .input-wrapper input {
            width: 100%;
            box-sizing: border-box;

            padding: 13px 15px 13px 45px;

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

    <!-- Navbar -->

    <header class="navbar">

        <div class="nav-container">

            <div class="logo-area">

                <span class="logo-icon">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>

                <span class="logo-text">
                    Refind
                </span>

            </div>


            <nav class="nav-links">

                <a href="../Home.php">
                    Home
                </a>

                <a href="../Browseitems.php">
                    Browse items
                </a>

                <a href="../my Report.php">
                    My Report
                </a>

                <a href="../Report a find.php">
                    Report a found
                </a>

                <a href="../Messages.php">
                    Messages
                </a>

                <a href="../How it works.php">
                    How it works
                </a>

                <a href="../About.php">
                    About
                </a>

                <a href="Profile.php" class="active">
                    Profile
                </a>

            </nav>


            <div class="auth-buttons">

                <?php if (empty($_SESSION['user_id'])): ?>

                    <a href="Log-in.php" class="login-btn">
                        Log in
                    </a>

                    <a href="sign-up.php" class="signup-btn">
                        Sign up
                    </a>

                <?php else: ?>

                    <a href="logout.php" class="logout-btn">
                        Log out
                    </a>

                <?php endif; ?>

            </div>

        </div>

    </header>


    <!-- Edit Profile -->

    <main class="edit-profile-page">

        <div class="edit-profile-container">

            <div class="edit-profile-card">

                <div class="edit-profile-title">

                    <div class="edit-profile-icon">

                        <i class="fa-solid fa-user-pen"></i>

                    </div>

                    <h1>Edit Profile</h1>

                    <p>
                        Update your personal information.
                    </p>

                </div>


                <?php if (!empty($success)): ?>

                    <div class="message-success">

                        <?php echo htmlspecialchars($success); ?>

                    </div>

                <?php endif; ?>


                <?php if (!empty($errors)): ?>

                    <div class="message-error">

                        <?php foreach ($errors as $error): ?>

                            <div>
                                <?php echo htmlspecialchars($error); ?>
                            </div>

                        <?php endforeach; ?>

                    </div>

                <?php endif; ?>


                <form method="POST">


                    <!-- Name -->

                    <div class="form-group">

                        <label for="name">
                            Full Name
                        </label>

                        <div class="input-wrapper">

                            <i class="fa-solid fa-user"></i>

                            <input id="name" name="name" type="text" placeholder="Enter your full name" value="<?php echo $user['name']?>" >
                        </div>

                    </div>


                    <!-- Email -->

                    <div class="form-group">

                        <label for="email">
                            Email Address
                        </label>

                        <div class="input-wrapper">

                            <i class="fa-solid fa-envelope"></i>

                            <input
                                id="email"
                                name="email"
                                type="email"
                                placeholder="Enter your email"
                                value="<?php echo $user['email']?>"
                                
                            >

                        </div>

                    </div>


                    <!-- Phone -->

                    <div class="form-group">

                        <label for="phone">
                            Phone Number
                        </label>

                        <div class="input-wrapper">

                            <i class="fa-solid fa-phone"></i>

                            <input
                                id="phone"
                                name="phone"
                                type="tel"
                                placeholder="Enter your phone number"
                                value="<?php echo $user['phone']?>"
                                
                            >

                        </div>

                    </div>


                    <!-- Password -->

                    <div class="form-group">

                        <label for="password">
                            New Password
                        </label>

                        <div class="input-wrapper">

                            <i class="fa-solid fa-lock"></i>

                            <input
                                id="password"
                                name="password"
                                type="password"
                                minlength="6"
                                placeholder="Enter new password"
                            >

                        </div>

                        <span class="password-note">
                            Leave this field empty if you don't want to change your password.
                        </span>

                    </div>


                    <!-- Buttons -->

                    <div class="form-actions">

                        <a
                            href="Profile.php"
                            class="form-btn cancel-btn"
                        >
                            <i class="fa-solid fa-xmark"></i>
                            Cancel
                        </a>


                        <button
                            type="submit"
                            class="form-btn save-btn"
                        >
                            <i class="fa-solid fa-check"></i>
                            Save Changes
                        </button>

                    </div>


                </form>

            </div>

        </div>

    </main>


    <!-- Footer -->

    <footer class="footer">

        <div class="footer-container">

            <div class="footer-col">

                <div class="logo-area">

                    <span class="logo-icon">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>

                    <span class="logo-text">
                        Refind
                    </span>

                </div>

                <p class="footer-logo-desc">
                    A calm, safe way to reunite people with the things they lose.
                </p>

            </div>


            <div class="footer-col">

                <h4>Platform</h4>

                <ul>

                    <li>
                        <a href="../Browseitems.php">
                            Browse found items
                        </a>
                    </li>

                    <li>
                        <a href="../Report a find.php">
                            Report a found item
                        </a>
                    </li>

                    <li>
                        <a href="../my Report.php">
                            My Report
                        </a>
                    </li>

                </ul>

            </div>


            <div class="footer-col">

                <h4>Learn</h4>

                <ul>

                    <li>
                        <a href="../How it works.php">
                            How it works
                        </a>
                    </li>

                    <li>
                        <a href="../About.php">
                            About Refind
                        </a>
                    </li>

                </ul>

            </div>


            <div class="footer-col">

                <h4>Account</h4>

                <ul>

                    <li>
                        <a href="Profile.php">
                            Profile
                        </a>
                    </li>

                    <li>
                        <a href="Log-in.php">
                            Log in
                        </a>
                    </li>

                    <li>
                        <a href="sign-up.php">
                            Create account
                        </a>
                    </li>

                </ul>

            </div>

        </div>


        <div class="footer-bottom">

            <p>
                Made with care to bring lost things back home.
                © 2026 Refind
            </p>

        </div>

    </footer>


    <script src="../assets/js/index.js"></script>

</body>

</html>
```
