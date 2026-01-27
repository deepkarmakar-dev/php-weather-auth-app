<?php

//    SESSION START

session_start();


//    DB CONNECTION

require_once "db.php";

$msg = "";


//    CSRF TOKEN

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


//    REGISTER PROCESS

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //CSRF CHECK
    if (
        !isset($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        $msg = "Invalid request";
    } else {

        $name      = trim($_POST['name']);
        $email     = trim($_POST['email']);
        $password  = $_POST['password'];
        $cpassword = $_POST['cpassword'];

        //VALIDATION
        if (
            strlen($name) < 3 ||
            !filter_var($email, FILTER_VALIDATE_EMAIL) ||
            strlen($password) < 8 ||
            $password !== $cpassword
        ) {
            $msg = "Registration failed";
        } else {

            //  CHECK EMAIL EXISTS 
            $stmt = $con->prepare("SELECT id FROM user_table WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $msg = "Registration failed";
            } else {

                //  PASSWORD HASH 
                $hash = password_hash($password, PASSWORD_DEFAULT);

                //INSERT USER
                $stmt = $con->prepare(
                    "INSERT INTO user_table (name, email, password, failed_attempts, lock_until)
                     VALUES (?, ?, ?, 0, NULL)"
                );
                $stmt->bind_param("sss", $name, $email, $hash);

                if ($stmt->execute()) {
                    unset($_SESSION['csrf_token']);
                    header("Location: login.php");
                    exit;
                } else {
                    $msg = "Registration failed";
                }
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="loginstyle.css">
</head>
<body>

<div class="container">
    <h1>Register</h1>

    <?php if (!empty($msg)): ?>
        <div class="error-msg"><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>

    <form method="post" autocomplete="off">
        <input type="hidden" name="csrf_token"
               value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

        <label>Name</label>
        <input type="text" name="name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Confirm Password</label>
        <input type="password" name="cpassword" required>

        <input type="submit" value="Register">
        <p>Already have an account? <a href="login.php">Login</a></p>
    </form>
</div>

</body>
</html>
