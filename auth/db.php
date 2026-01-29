<?php
require_once dirname(__DIR__) . '/env.php';

// ERROR HANDLING (DEBUGGING KE LIYE ISE 1 RAKHEIN, BAAD MEIN 0 KAR DENA)
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);  // REMOVE

// DATABASE CONFIG
$db_host = getenv('DB_HOST');
$db_user = getenv('DB_USER');
$db_pass = getenv('DB_PASS');
$db_name = getenv('DB_NAME');
$db_port = getenv('DB_PORT') ?: 3306;

// DATABASE CONNECTION
$con = mysqli_connect($db_host, $db_user, $db_pass, $db_name, $db_port);

if (!$con) {
    error_log("Database Connection Failed: " . mysqli_connect_error());
    die("Connection failed: " . mysqli_connect_error());
}

// CHARACTER SET
mysqli_set_charset($con, "utf8mb4");
?>
