<!DOCTYPE html>
<html="en" dir="ltr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>My reports — Refind</title>
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

        <main class="main-container">

            <div class="dashboard-header">
                <div class="page-header" style="margin-bottom: 0;">
                    <span class="badge" style="background-color: #e0f2fe; color: #0369a1;">Your account</span>
                    <!-- <p class="small-title">Your account</p> -->
                    <h1>My reports</h1>
                    <p>Everything you've reported as found, with the status of each claim.</p>
                </div>
                <a href="Report a find.html" class="new-report-btn">
                    <i class="fa-solid fa-plus"></i> New report
                </a>
            </div>

            <div class="filter-tabs">
                <button class="filter-btn active">All</button>
                <button class="filter-btn">Unclaimed</button>
                <button class="filter-btn">In verification</button>
                <button class="filter-btn">Returned</button>
            </div>

            <div class="reports-list">

                <div class="report-card">
                    <div class="report-info">
                        <div class="item-icon-box">
                            <i class="fa-solid fa-backpack"></i>
                        </div>
                        <div class="item-details">
                            <h3>
                                Black canvas backpack
                                <span class="status-badge status-unclaimed">Unclaimed</span>
                            </h3>
                            <p>Reported 12 Aug 2026 · 0 claims received</p>
                        </div>
                    </div>
                    <div class="report-actions">
                        <a href="#" class="action-btn">View listing</a>
                    </div>
                </div>

                <div class="report-card">
                    <div class="report-info">
                        <div class="item-icon-box">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div class="item-details">
                            <h3>
                                Silver analog watch
                                <span class="status-badge status-verification">In verification</span>
                            </h3>
                            <p>Reported 9 Aug 2026 · 2 claims received</p>
                        </div>
                    </div>
                    <div class="report-actions">
                        <a href="#" class="action-btn">View listing</a>
                        <a href="#" class="action-btn primary">Review claims</a>
                    </div>
                </div>

                <div class="report-card">
                    <div class="report-info">
                        <div class="item-icon-box">
                            <i class="fa-solid fa-headphones"></i>
                        </div>
                        <div class="item-details">
                            <h3>
                                Grey wireless earbuds case
                                <span class="status-badge status-returned">Returned</span>
                            </h3>
                            <p>Reported 3 Aug 2026 · 1 claim received</p>
                        </div>
                    </div>
                    <div class="report-actions">
                        <a href="#" class="action-btn">View listing</a>
                        <a href="Messages.php" class="action-btn primary">Review claims</a>
                    </div>
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
                    <p class="footer-logo-desc">A calm, safe way to reunite people with the things they lose.</p>
                </div>
                <div class="footer-col">
                    <h4>Platform</h4>
                    <ul>
                        <li><a href="Browseitems.php">Browse found items</a></li>
                        <li><a href="Report a find.php">Report a found item</a></li>
                        <li><a href="My reports.php">My reports</a></li>
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
        <div class="footer-bottom">
            <p>Made with care to bring lost things back home. © 2026 Refind</p>
        </div>
    </body>

    </html>
    <!-- <script>
        document.addEventListener("DOMContentLoaded", function () {
            // تحديد الفورم الخاص بإضافة البلاغ (افترضي إن الـ form عندك له id="reportForm" أو زرار النشر جوه فورم)
            const reportForm = document.querySelector("form"); // لو الفورم مالوش ID، هيجيب أول فورم في الصفحة

            if (reportForm) {
                reportForm.addEventListener("submit", function (event) {
                    // منع السلوك الافتراضي للفورم (عشان الصفحة ما تعملش اعادة تحميل للصفحة الحالية)
                    event.preventDefault();

                    // هنا ممكن تضيفي أي كود لتحقق من البيانات لو حابة (Validation)

                    // الانتقال لصفحة الشكر والتأكيد (تأكدي من مطابقة اسم الملف عندك)
                    window.location.href = "scuss.html";
                });
            }
        });
    </script> -->