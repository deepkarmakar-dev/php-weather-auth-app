<?php
require_once dirname(__DIR__) . '/env.php';

// ERROR HANDLING (PRODUCTION SAFE)
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

mysqli_report(MYSQLI_REPORT_OFF);

// DATABASE CONFIG (FROM .env)
$db_host = getenv('DB_HOST');
$db_user = getenv('DB_USER');
$db_pass = getenv('DB_PASS');
$db_name = getenv('DB_NAME');

// DATABASE CONNECTION
$con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$con) {
    error_log("Database Connection Failed: " . mysqli_connect_error());
    die("Service temporarily unavailable");
}

// CHARACTER SET
mysqli_set_charset($con, "utf8mb4");
