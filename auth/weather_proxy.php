<?php
require_once dirname(__DIR__) . '/env.php';

// JSON RESPONSE
header('Content-Type: application/json; charset=utf-8');

// BASIC SECURITY
ini_set('display_errors', 0);
error_reporting(0);

// CONFIG
$API_KEY  = getenv('OPENWEATHER_API_KEY');
$BASE_URL = "https://api.openweathermap.org/data/2.5/weather";

if (!$API_KEY) {
    http_response_code(500);
    echo json_encode(["message" => "API key not configured"]);
    exit;
}

// INPUT CHECK
if (!isset($_GET['city']) || trim($_GET['city']) === '') {
    http_response_code(400);
    echo json_encode(["message" => "City is required"]);
    exit;
}

// CLEAN INPUT (NO REGEX, NO COUNTRY FORCE)
$city = trim($_GET['city']);
$city = urlencode($city);

// API REQUEST
$url = $BASE_URL . "?q={$city}&appid={$API_KEY}&units=metric";

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL            => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 10,
    CURLOPT_SSL_VERIFYPEER => true
]);

$response  = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// PASS THROUGH OPENWEATHER RESPONSE
http_response_code($http_code);
echo $response;
