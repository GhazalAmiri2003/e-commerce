<?php
session_start();
require "../config/db.php";



$categoryId = isset($_GET['category']) ? $_GET['category'] : null;

if ($categoryId) {
    $result = mysqli_query($conn,
        "SELECT * FROM products WHERE category_id = $categoryId"
    );
} else {
    $result = mysqli_query($conn,
        "SELECT * FROM products"
    );
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <!-- External CSS -->
    <link rel="stylesheet" href="/e_commerce/assets/css/products.css">


</head>
<body>
<?php include "menu.php"; ?>


<div class="products-list">
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

        <div class="product-row <?php echo ($row['stock'] <= 0) ? 'out-of-stock' : ''; ?>">

            <div class="product-image">
                <img src="../<?php echo $row['image']; ?>" alt="">
                <!-- تموم شدن موجودی -->

                <?php if ($row['stock'] <= 0) { ?>
                    <div class="overlay">Out of Stock</div>
                <?php } ?>
            </div>

            <div class="product-info">
                <h3><?php echo $row['name']; ?></h3>
                <p><?php echo $row['description']; ?></p>
                <p><strong>Price:</strong> €<?php echo $row['price']; ?></p>
                <p><strong>Stock:</strong> <?php echo $row['stock']; ?></p>

                <?php if ($row['stock'] > 0) { ?>
                    <button onclick="addToCart(<?php echo $row['id']; ?>)">
                        Add to Cart
                    </button>
                <?php } ?>
            </div>

        </div>

    <?php } ?>
</div>

<script src="js/cart.js"></script>

</body>