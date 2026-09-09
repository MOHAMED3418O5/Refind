<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About - FindIt</title>
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

  <main class="page">
    <section class="content-card">
      <div class="badge" style="background-color: #e0f2fe; color: #0369a1;">About Us</div>
      <h1>Helping people find what they lost.</h1>
      <p>FindIt is a simple lost-and-found website project. Users can browse requests and search for lost items using
        useful descriptions such as color, material, location and other characteristics.</p>

      <div class="features">
        <div>
          <h3>01</h3>
          <h2>Easy Search</h2>
          <p>Search for an item using simple keywords.</p>
        </div>
        <div>
          <h3>02</h3>
          <h2>Clear Details</h2>
          <p>Each request contains important characteristics.</p>
        </div>
        <div>
          <h3>03</h3>
          <h2>Simple Design</h2>
          <p>A clean interface that is easy for everyone to use.</p>
        </div>
      </div>
    </section>
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