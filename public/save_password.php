<?php
require_once '../classes/PasswordManager.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$errors = [];
$success = '';
$website = '';
$password = isset($_GET['password']) ? urldecode($_GET['password']) : '';
$userPassword = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize inputs
    $website = filter_input(INPUT_POST, 'website', FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    $userPassword = $_POST['user_password'];

    // Server-side validation
    if (empty($website)) {
        $errors[] = 'Website name is required';
    }
    if (empty($password)) {
        $errors[] = 'Password is required';
    }
    if (empty($userPassword)) {
        $errors[] = 'Your login password is required for encryption';
    }

    // Proceed if no validation errors
    if (empty($errors)) {
        try {
            $passwordManager = new PasswordManager();
            $passwordManager->savePassword($_SESSION['user_id'], $website, $password, $userPassword);
            $success = 'Password saved successfully!';
            $website = ''; // Clear form after success
            $password = '';
            $userPassword = '';
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
    <title>Save Password - Password Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Save Password</h2>
        <a href="dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="website">Website/Program Name</label>
                <input type="text" name="website" id="website" class="form-control" value="<?php echo htmlspecialchars($website); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="text" name="password" id="password" class="form-control" value="<?php echo htmlspecialchars($password); ?>" required>
            </div>
            <div class="form-group">
                <label for="user_password">Your Login Password (for encryption)</label>
                <input type="password" name="user_password" id="user_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Password</button>
        </form>
    </div>
</body>
</html>