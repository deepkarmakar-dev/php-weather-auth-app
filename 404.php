<?php
// Send proper HTTP status
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 – Page Not Found</title>
    <meta name="robots" content="noindex, nofollow">
    <style>
        body {
            margin: 0;
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            background: #f5f7fa;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .card {
            background: #fff;
            max-width: 420px;
            width: 100%;
            padding: 32px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            text-align: center;
        }
        h1 {
            font-size: 56px;
            margin: 0;
            color: #dc3545;
        }
        h2 {
            margin: 10px 0;
            font-weight: 600;
        }
        p {
            color: #666;
            font-size: 15px;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 18px;
            background: #0d6efd;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
        }
        a:hover {
            background: #0b5ed7;
        }
        .footer {
            margin-top: 18px;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
<body>

<div class="card">
    <h1>404</h1>
    <h2>Page Not Found</h2>
    <p>
        The page you’re trying to access doesn’t exist or has been moved.
        Please check the URL or return to a safe page.
    </p>
    <a href="login.php">Back to Login</a>
    <div class="footer">
        © <?php echo date('Y'); ?> Secure Auth System
    </div>
</div>

</body>
</html>
