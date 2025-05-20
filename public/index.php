<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container text-center">
        <h1>Welcome to Password Manager</h1>
        <p>Securely generate and store your passwords.</p>
        <a href="signup.php" class="btn btn-primary m-2">Sign Up</a>
        <a href="login.php" class="btn btn-secondary m-2">Login</a>
    </div>
</body>
</html>