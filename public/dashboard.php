<?php
require_once '../classes/PasswordManager.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$errors = [];
$passwords = [];
$decryptPassword = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and validate input
    $decryptPassword = $_POST['decrypt_password'];
    if (empty($decryptPassword)) {
        $errors[] = 'Password is required to view passwords';
    }

    // Proceed if no validation errors
    if (empty($errors)) {
        try {
            $passwordManager = new PasswordManager();
            $passwords = $passwordManager->getPasswords($_SESSION['user_id'], $decryptPassword);
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Password Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
        <a href="generate_password.php" class="btn btn-primary m-2">Generate Password</a>
        <a href="save_password.php" class="btn btn-primary m-2">Save Password</a>
        <a href="logout.php" class="btn btn-secondary m-2">Logout</a>

        <h3 class="mt-4">Your Passwords</h3>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="decrypt_password">Enter Your Password to View Passwords</label>
                <input type="password" name="decrypt_password" id="decrypt_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">View Passwords</button>
        </form>
        <?php if (!empty($passwords)): ?>
            <div class="table-container">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Website</th>
                            <th>Password</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($passwords as $entry): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($entry['website']); ?></td>
                                <td><?php echo htmlspecialchars($entry['password']); ?></td>
                                <td><?php echo htmlspecialchars($entry['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>