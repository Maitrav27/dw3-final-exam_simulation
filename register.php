<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);

        if ($stmt->rowCount() > 0) {
            $error = "Username already taken.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            $stmt->execute([
                'username' => $username,
                'password' => $hashed_password
            ]);

            header("Location: login.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <header>
        <h1>Mini Product Catalog</h1>
    </header>

    <div class="form-container">
        <h2>Register</h2>

        <?php if (isset($error)) { ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php } ?>

        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required placeholder="Enter your username">
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required placeholder="Enter your password">
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required placeholder="Confirm your password">
            </div>

            <div class="form-group">
                <button type="submit">Register</button>
            </div>
        </form>

        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</div>

</body>
</html>
