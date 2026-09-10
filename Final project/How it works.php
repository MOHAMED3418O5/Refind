<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>How Refind works — Refind</title>
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
                <a href="How it works.php" class="active">How it works</a>
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

            <div class="badge" style="background-color: #e0f2fe; color: #0369a1;">The flow</div>
            <!-- <p class="small-title">The flow</p> -->
            <h1>How Refind works</h1>
            <p>One verification step stands between a found item and its owner — nothing more, nothing less.</p>
        </div>


        <h2 class="section-title">If you lost something</h2>
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-header">
                    <span class="step-badge"><i class="fa-solid fa-magnifying-glass"></i> STEP 1</span>
                </div>
                <h3>Search the found items</h3>
                <p>Filter by category, place and date. Listings show enough to recognise your item, never enough to take
                    a claim.</p>
            </div>
            <div class="step-card">
                <div class="step-header">
                    <span class="step-badge"><i class="fa-solid fa-hand-pointer"></i> STEP 2</span>
                </div>
                <h3>Tap "I think this is mine"</h3>
                <p>You’ll be asked one question about a detail that was deliberately left out of the public listing.</p>
            </div>
            <div class="step-card">
                <div class="step-header">
                    <span class="step-badge"><i class="fa-solid fa-shield-halved"></i> STEP 3</span>
                </div>
                <h3>Pass the hidden-detail check</h3>
                <p>Match the detail and you’re verified. Three wrong attempts pause the claim and alert the finder.</p>
            </div>
            <div class="step-card">
                <div class="step-header">
                    <span class="step-badge"><i class="fa-solid fa-comments"></i> STEP 4</span>
                </div>
                <h3>Chat and collect</h3>
                <p>A private chat opens with the finder so you can agree on a public place and time.</p>
            </div>
        </div>


        <h2 class="section-title">If you found something</h2>
        <div class="steps-grid-3">
            <div class="step-card">
                <div class="step-header">
                    <span class="step-badge"><i class="fa-solid fa-upload"></i> STEP 1</span>
                </div>
                <h3>Report what you found</h3>
                <p>Add a general description, place and date.</p>
            </div>
            <div class="step-card">
                <div class="step-header">
                    <span class="step-badge"><i class="fa-solid fa-lock"></i> STEP 2</span>
                </div>
                <h3>Keep one detail private</h3>
                <p>Write a question only the owner could answer — this becomes the verification step.</p>
            </div>
            <div class="step-card">
                <div class="step-header">
                    <span class="step-badge"><i class="fa-solid fa-user-check"></i> STEP 3</span>
                </div>
                <h3>Meet the verified owner</h3>
                <p>You only get messages from someone who already passed the check.</p>
            </div>
        </div>


        <div class="demo-box">
            <h3>Try the full flow in this prototype</h3>
            <p>Open the black canvas backpack, claim it, answer the hidden-detail question, and land in the private chat
                with the finder.</p>
            <a href="Browseitems.html" class="demo-btn">Start the demo flow <i class="fa-solid fa-arrow-right"></i></a>
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