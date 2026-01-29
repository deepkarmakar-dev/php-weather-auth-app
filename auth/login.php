<?php
// SESSION + BASIC SECURITY
session_start();

// DB CONNECTION
require_once "db.php";

$msg = "";

// CSRF TOKEN
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// LOGIN PROCESS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF CHECK
    if (
        !isset($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        $msg = "Invalid request";
    } else {

        $email    = trim($_POST['email']);
        $password = $_POST['password'];

        // BASIC VALIDATION
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($password)) {
            $msg = "Invalid email or password";
        } else {

            // FETCH USER
            $stmt = $con->prepare(
                "SELECT id, name, password, failed_attempts, lock_until 
                 FROM user_table WHERE email = ?"
            );
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user) {

                $current_time = time();

                // ACCOUNT LOCK CHECK
                if (!empty($user['lock_until']) && $user['lock_until'] > $current_time) {
                    $wait = ceil(($user['lock_until'] - $current_time) / 60);
                    $msg = "Account locked. Try after {$wait} minute(s).";
                } else {

                    // PASSWORD VERIFY
                    if (password_verify($password, $user['password'])) {

                        // RESET FAILED ATTEMPTS
                        $reset = $con->prepare(
                            "UPDATE user_table 
                             SET failed_attempts = 0, lock_until = NULL 
                             WHERE id = ?"
                        );
                        $reset->execute([$user['id']]);

                        // SECURE SESSION
                        session_regenerate_id(true);
                        unset($_SESSION['csrf_token']);

                        $_SESSION['user_id']   = $user['id'];
                        $_SESSION['user_name'] = $user['name'];

                        header("Location: ../index.php");
                        exit;

                    } else {

                        // WRONG PASSWORD
                        $attempts = $user['failed_attempts'] + 1;
                        $lock_until = null;

                        if ($attempts >= 5) {
                            $lock_until = time() + (15 * 60); // 15 minutes
                        }

                        $update = $con->prepare(
                            "UPDATE user_table 
                             SET failed_attempts = ?, lock_until = ? 
                             WHERE id = ?"
                        );
                        $update->execute([$attempts, $lock_until, $user['id']]);

                        $msg = "Invalid email or password";
                    }
                }

            } else {
                $msg = "Invalid email or password";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="loginstyle.css">
</head>
<body>

<div class="container">
    <h1>Login</h1>

    <?php if (!empty($msg)): ?>
        <div class="error-msg"><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>

    <form method="post" autocomplete="off">
        <input type="hidden" name="csrf_token"
               value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <input type="submit" value="Login">
        <p>Don't have an account? <a href="register.php">Register</a></p>
    </form>
</div>

</body>
</html>
