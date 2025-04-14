<?php
require 'session.php';
require 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $desc = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $user_id = $_SESSION['user_id'];
    $imagePath = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $file_name = $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_size = $_FILES['image']['size'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $message = "Only JPG, JPEG, PNG files are allowed.";
        } elseif ($file_size > 2 * 1024 * 1024) {
            $message = "File size must be less than 2MB.";
        } else {
            $new_name = uniqid("img_", true) . '.' . $ext;
            $upload_path = "uploads/" . $new_name;
            move_uploaded_file($file_tmp, $upload_path);
            $imagePath = $upload_path;

        
            $stmt = $pdo->prepare("INSERT INTO products (user_id, name, description, price, image_path) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $name, $desc, $price, $imagePath]);
            header("Location: dashboard.php");
            exit;
        }
    } else {
        $message = "Please upload an image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <header>
        <h1>Add New Product</h1>
    </header>

    <a href="dashboard.php" class="btn back">🔙 Back to Dashboard</a>

    <hr>

    <?php if ($message): ?>
        <div class="error-message"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="product-form">
        <div class="form-group">
            <label for="name">Product Name:</label>
            <input type="text" name="name" id="name" required>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea>
        </div>

        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" step="0.01" name="price" id="price" required>
        </div>

        <div class="form-group">
            <label for="image">Image:</label>
            <input type="file" name="image" id="image" accept=".jpg,.jpeg,.png" required>
        </div>

        <button type="submit" class="btn submit">Add Product</button>
    </form>
</div>

</body>
</html>
