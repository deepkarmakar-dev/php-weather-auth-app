<?php
session_start();


    // CSRF VERIFICATION

// Only allow logout via POST with a valid token
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) 
        {
        // Log the suspicious activity
        error_log("Unauthorized logout attempt from IP: " . $_SERVER['REMOTE_ADDR']);
        header("Location: ../404.php");
        exit;
    }
} else {
    // Block direct GET access to logout.php
    header("Location: ../404.php");
    exit;
}

    // SESSION DESTRUCTION

// 1. Clear the session array
$_SESSION = [];

// 2. Invalidate the session cookie on the browser
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

//  Destroy the session on the server
session_destroy();


    // REDIRECT

// Add a success flag to show a message on the login page
header("Location: login.php?logout=1");
exit;