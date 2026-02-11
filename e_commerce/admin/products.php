<?php
session_start();
require "C:/xampp/htdocs/e_commerce/config/db.php";

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


    <a href="../auth/logout.php" style=" position: fixed; top: 20px; left: 20px;" >Logout</a>
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
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['category_name']; ?></td>
                    <td><?php echo $row['price']; ?></td>
                    <td><?php echo $row['stock']; ?></td>

                    <td>
                        <?php if (!empty($row['image'])) { ?>
                            <img src="../<?php echo $row['image']; ?>" width="80">
                        <?php } ?>
                    </td>

                    <td>
                        <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="icon edit">✏️</a>
                        <a href="delete_product.php?id=<?php echo $row['id']; ?>"
                           class="icon delete"
                           onclick="return confirm('Delete this product?')">🗑️</a>
                    </td>
                </tr>
            <?php } ?>

        </table>
    </div>

</div>
</body>