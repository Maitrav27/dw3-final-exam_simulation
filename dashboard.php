<?php
require 'session.php';
require 'db.php';


$user_id = $_SESSION['user_id'];


$stmt = $pdo->prepare("SELECT * FROM products WHERE user_id = ?");
$stmt->execute([$user_id]);
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Product Catalog</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Your Product Catalog</h1>
        </header>

        <div class="actions">
            <a href="add_product.php" class="btn add-product">Add New Product</a>
            <a href="logout.php" class="btn logout">Logout</a>
        </div>

        <table class="product-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><img src="<?= $product['image_path'] ?>" alt="Product Image" width="100"></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= htmlspecialchars($product['description']) ?></td>
                        <td>$<?= number_format($product['price'], 2) ?></td>
                        <td><a href="delete_product.php?id=<?= $product['id'] ?>" class="btn delete">Delete</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
