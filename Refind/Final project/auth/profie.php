<?php
session_start();
include '../config/auth.php';
include '../config/db.php';

$sql = "SELECT * from users WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Profile - Refind</title>

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

    <!-- Navbar -->
    <header class="navbar">
        <div class="nav-container">

            <div class="logo-area">
                <span class="logo-icon">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>

                <span class="logo-text">Refind</span>
            </div>

            <nav class="nav-links">
                <a href="../Home.php">Home</a>
                <a href="../Browseitems.php">Browse items</a>
                <a href="../my Report.php">My Report</a>
                <a href="../Report a find.php">Report a found</a>
                <a href="../Messages.php">Messages</a>
                <a href="../How it works.php">How it works</a>
                <a href="../About.php">About</a>
                <a href="Profile.php" class="active">Profile</a>
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


    <!-- Profile -->
    <main class="profile-page">

        <div class="profile-container">

            <!-- Profile Header -->
            <section class="profile-header">

                <div class="profile-avatar">
                    <i class="fa-solid fa-user"></i>
                </div>

                <div>
                    <h1>Your Profile</h1>

                    <p>
                        Manage your Refind account information.
                    </p>
                </div>

            </section>


            <!-- Personal Information -->
            <section class="profile-card">

                <h2>
                    <i class="fa-solid fa-user"></i>
                    Personal Information
                </h2>

                <div class="profile-info">

                    <div class="info-box">
                        <span class="info-label">Full Name</span>

                        <!-- Add user's name here -->
                        <span class="info-value">
                            <?php echo htmlspecialchars($user['name']); ?>
                        </span>
                    </div>


                    <div class="info-box">
                        <span class="info-label">Email Address</span>

                        <!-- Add user's email here -->
                        <span class="info-value">
                            <?php echo htmlspecialchars($user['email']); ?>

                        </span>
                    </div>


                    <div class="info-box">
                        <span class="info-label">Phone Number</span>

                        <!-- Add user's phone here -->
                        <span class="info-value">
                            <?php echo $user['phone']; ?>

                        </span>
                    </div>


                    <div class="info-box">
                        <span class="info-label">Account Status</span>

                        <span class="info-value">
                            Active
                        </span>
                    </div>

                </div>


                <div class="profile-actions">

                    <a href="Edit-profile.php" class="profile-btn edit-btn">
                        <i class="fa-solid fa-pen"></i>
                        Edit Profile
                    </a>

                    <a href="logout.php"
                        class="profile-btn logout-btn-profile">

                        <i class="fa-solid fa-right-from-bracket"></i>
                        Log out

                    </a>

                </div>

            </section>


            <!-- Statistics -->
            <section class="profile-card">

                <h2>
                    <i class="fa-solid fa-chart-simple"></i>
                    My Activity
                </h2>

                <div class="profile-stats">

                    <div class="stat-box">

                        <i class="fa-solid fa-box"></i>

                        <span class="stat-number">
                            0
                        </span>

                        <span class="stat-label">
                            Lost Reports
                        </span>

                    </div>


                    <div class="stat-box">

                        <i class="fa-solid fa-magnifying-glass"></i>

                        <span class="stat-number">
                            0
                        </span>

                        <span class="stat-label">
                            Found Reports
                        </span>

                    </div>


                    <div class="stat-box">

                        <i class="fa-solid fa-handshake"></i>

                        <span class="stat-number">
                            0
                        </span>

                        <span class="stat-label">
                            Items Reunited
                        </span>

                    </div>

                </div>

            </section>

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

