<?php
session_start();
require __DIR__ . "/../config/db.php";
require_once __DIR__ . '/../config/security.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$result = mysqli_query($conn,
    "SELECT products.*, categories.name AS category_name
     FROM products
     JOIN categories ON products.category_id = categories.id"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <!-- External CSS -->
    <link rel="stylesheet" href="/e_commerce/assets/css/base.css">
    <link rel="stylesheet" href="/e_commerce/assets/css/admin.css">
</head>
<body>
<div class="admin-container">


    <form action="../auth/logout.php" method="POST" style="display:inline"><input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>"><button type="submit">Logout</button></form>
    <a href="add_product.php" class="add-product-btn">Add New Product</a>

<br><br>

    <div class="table-wrapper">
        <table border="1" cellpadding="10">
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= h($row['name']) ?></td>
                    <td><?= h($row['category_name']) ?></td>
                    <td><?= h($row['price']) ?></td>
                    <td><?= h($row['stock']) ?></td>

                    <td>
                        <?php if (!empty($row['image'])) { ?>
                            <img src="../<?= h($row['image']) ?>" width="80">
                        <?php } ?>
                    </td>

                    <td>
                        <a href="edit_product.php?id=<?= h($row['id']) ?>" class="icon edit">✏️</a>
                        <form method="POST" action="delete_product.php" style="display:inline" onsubmit="return confirm('Delete this product?')"><input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$row['id'] ?>"><button class="icon delete" type="submit">🗑️</button></form>
                    </td>
                </tr>
            <?php } ?>

        </table>
    </div>

</div>
</body>