<?php

//    SECURE SESSION COOKIES

session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => false, // HTTPS pe true kar dena
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();


//    HTTP SECURITY HEADERS

header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: no-referrer");
header(
    "Content-Security-Policy: ".
    "default-src 'self'; ".
    "img-src 'self' data:; ".
    "style-src 'self' https://fonts.googleapis.com; ".
    "font-src https://fonts.gstatic.com; ".
    "script-src 'self'; ".
    "base-uri 'none'; ".
    "form-action 'self'"
);


//    LOGIN CHECK

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}


//    CSRF TOKEN (LOGOUT)

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkyCast - Dashboard</title>

    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="container fade-in">
    <!-- NAVBAR -->
    <header class="navbar">
        <div class="logo">
            <img src="images/logo.png" alt="logo" class="logo-img">
            <span>SkyCast</span>
        </div>

        <div class="search-bar">
            <input type="text" placeholder="Enter your city" class="text">
            <button type="button" class="btn">🔍</button>

            <!-- LOGOUT (POST + CSRF) -->
            <form method="post" action="auth/logout.php" class="logout-form">
                <input type="hidden" name="csrf_token"
                       value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                <button type="submit" class="logout-btn">Logout</button>
            </form>

            <span class="menu-dots">⠿</span>
        </div>
    </header>

    <!-- HERO CARD -->
    <section class="hero-card">
        <div class="top-left">
            <div class="temp-wrapper">
                <div class="temp"><span class="temp-val">--</span><span>°</span></div>
            </div>
            <div class="location">--</div>
        </div>

        <div class="bottom-right">
            <div class="time">--</div>
            <div class="sunset-label">sunset time, <span class="subtitle">--</span></div>
        </div>
    </section>

    <!-- STATS -->
    <section class="stats-grid">
        <div class="left-col">
            <div class="individual-stats">
                <div class="stat-box">
                    <small>💧 Humidity</small>
                    <p class="percent">--</p>
                </div>
                <div class="stat-box">
                    <small>🌅 Sunrise</small>
                    <p class="sunrise">--</p>
                </div>
                <div class="stat-box">
                    <small>🌬️ Wind Speed</small>
                    <p class="wind">--</p>
                </div>
                <div class="stat-box">
                    <small>🌊 Sea Level</small>
                    <p class="sea">--</p>
                </div>
            </div>

            <div class="rainfall-card">
                <div class="rain-info">
                    <small>🌡️ Max Temp</small>
                    <p class="TempMax">--</p>
                </div>
                <div class="temp-divider"></div>
                <div class="rain-info">
                    <small>❄️ Min Temp</small>
                    <p class="TempMinVal">--</p>
                </div>
            </div>
        </div>

        <div class="graph-card">
            <div class="graph-content">
                <img src="images/cloud.png" alt="Weather Icon" class="graph-img floating-icon">
                <h3 class="img-text">--</h3>
            </div>
        </div>
    </section>
</div>

<script src="app.js"></script>
</body>
</html>
