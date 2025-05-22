<?php
require_once '../classes/PasswordGenerator.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$errors = [];
$generatedPassword = '';
$length = 9;
$uppercase = 3;
$lowercase = 2;
$numbers = 2;
$special = 2;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and validate inputs
    $length = filter_input(INPUT_POST, 'length', FILTER_VALIDATE_INT);
    $uppercase = filter_input(INPUT_POST, 'uppercase', FILTER_VALIDATE_INT);
    $lowercase = filter_input(INPUT_POST, 'lowercase', FILTER_VALIDATE_INT);
    $numbers = filter_input(INPUT_POST, 'numbers', FILTER_VALIDATE_INT);
    $special = filter_input(INPUT_POST, 'special', FILTER_VALIDATE_INT);

    // Server-side validation
    if ($length === false || $length <= 0) {
        $errors[] = 'Password length must be a positive number';
    }
    if ($uppercase === false || $uppercase < 0) {
        $errors[] = 'Uppercase count cannot be negative';
    }
    if ($lowercase === false || $lowercase < 0) {
        $errors[] = 'Lowercase count cannot be negative';
    }
    if ($numbers === false || $numbers < 0) {
        $errors[] = 'Numbers count cannot be negative';
    }
    if ($special === false || $special < 0) {
        $errors[] = 'Special characters count cannot be negative';
    }
    if ($length !== false && ($uppercase + $lowercase + $numbers + $special) > $length) {
        $errors[] = 'Sum of character counts cannot exceed password length';
    }

    // Proceed if no validation errors
    if (empty($errors)) {
        try {
            $generator = new PasswordGenerator();
            $generatedPassword = $generator->generate($length, $uppercase, $lowercase, $numbers, $special);
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
    <title>Generate Password - Password Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Generate Password</h2>
        <a href="dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if ($generatedPassword): ?>
            <div class="alert alert-success">
                Generated Password: <strong><?php echo htmlspecialchars($generatedPassword); ?></strong>
                <br>
                <a href="save_password.php?password=<?php echo urlencode($generatedPassword); ?>" class="btn btn-primary mt-2">Save This Password</a>
            </div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="length">Password Length</label>
                <input type="number" name="length" id="length" class="form-control" value="<?php echo htmlspecialchars($length); ?>" min="1" required>
            </div>
            <div class="form-group">
                <label for="uppercase">Uppercase Letters</label>
                <input type="number" name="uppercase" id="uppercase" class="form-control" value="<?php echo htmlspecialchars($uppercase); ?>" min="0" required>
            </div>
            <div class="form-group">
                <label for="lowercase">Lowercase Letters</label>
                <input type="number" name="lowercase" id="lowercase" class="form-control" value="<?php echo htmlspecialchars($lowercase); ?>" min="0" required>
            </div>
            <div class="form-group">
                <label for="numbers">Numbers</label>
                <input type="number" name="numbers" id="numbers" class="form-control" value="<?php echo htmlspecialchars($numbers); ?>" min="0" required>
            </div>
            <div class="form-group">
                <label for="special">Special Characters</label>
                <input type="number" name="special" id="special" class="form-control" value="<?php echo htmlspecialchars($special); ?>" min="0" required>
            </div>
            <button type="submit" class="btn btn-primary">Generate Password</button>
        </form>
    </div>
</body>
</html>