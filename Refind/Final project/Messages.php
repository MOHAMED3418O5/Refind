<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages — Refind</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="main.css">


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

                <a href="Messages.php" class="active">Messages</a>
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
        <div class="page-header">

            <div class="badge" style="background-color: #e0f2fe; color: #0369a1;">Step 3 of the flow </div>
            <!-- <p class="small-title">Step 3 of the flow</p> -->
            <h1>Messages</h1>
            <p>A chat opens only after ownership verification passes. Sample conversations below.</p>
        </div>

        <div class="messages-list">

            <a href="#" class="message-card">
                <div class="message-info">
                    <div class="user-line">
                        Nour A. <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div class="item-sub">Black canvas backpack</div>
                    <div class="last-msg">Great — the keychain matches. When can you pick it up?</div>
                </div>
                <div class="message-time">2m</div>
            </a>

            <a href="#" class="message-card">
                <div class="message-info">
                    <div class="user-line">
                        Kareem H. <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div class="item-sub">Silver analog watch</div>
                    <div class="last-msg" style="color: #0f766e; font-weight: 500;">Verification is still pending for
                        this claim.</div>
                </div>
                <div class="message-time">1d</div>
            </a>


            <a href="#" class="message-card">
                <div class="message-info">
                    <div class="user-line">
                        Omar T. <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div class="item-sub">Grey wireless earbuds case</div>
                    <div class="last-msg">Handed over yesterday — marked as returned. Thanks!</div>
                </div>
                <div class="message-time">3d</div>
            </a>
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
                    <li><a href="Browseitems.html">Browse found items</a></li>
                    <li><a href="Report a find.html">Report a found item</a></li>
                    <li><a href="my Report.html">My Report</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Learn</h4>
                <ul>
                    <li><a href="How it works.html">How it works</a></li>
                    <li><a href="About.html">About Refind</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Account</h4>
                <ul>
                    <li><a href="Log in.html">Log in</a></li>
                    <li><a href="sign up.html">Create account</a></li>
                    <li><a href="Messages.html">Messages</a></li>
                </ul>
            </div>
        </div>
    </footer>
    <div class="footer-bottom">
        <p>Made with care to bring lost things back home. © 2026 Refind</p>
    </div>
</body>

</html>