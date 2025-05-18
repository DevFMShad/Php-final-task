<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Password Manager</title>
    <link href="https://github.com/DevFMShad/Php-final-task" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Welcome to Password Manager</h1>
        <a href="signup.php" class="btn btn-primary">Sign Up</a>
        <a href="login.php" class="btn btn-secondary">Login</a>
    </div>
</body>
</html>






