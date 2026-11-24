<?php
session_start();
require __DIR__ . "/../config/db.php";
require_once __DIR__ . '/../config/security.php';



$categoryId = filter_var($_GET["category"] ?? null, FILTER_VALIDATE_INT, ["options"=>["min_range"=>1]]);

if ($categoryId) {
    $stmt = $conn->prepare('SELECT * FROM products WHERE category_id = ?');
    $stmt->bind_param('i', $categoryId); $stmt->execute(); $result = $stmt->get_result();
} else {
    $result = mysqli_query($conn,
        "SELECT * FROM products"
    );
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta name="csrf-token" content="<?= h(csrf_token()) ?>">
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
                <img src="../<?= h($row['image']) ?>" alt="">
                <!-- تموم شدن موجودی -->

                <?php if ($row['stock'] <= 0) { ?>
                    <div class="overlay">Out of Stock</div>
                <?php } ?>
            </div>

            <div class="product-info">
                <h3><?= h($row['name']) ?></h3>
                <p><?= h($row['description']) ?></p>
                <p><strong>Price:</strong> €<?= h($row['price']) ?></p>
                <p><strong>Stock:</strong> <?= h($row['stock']) ?></p>

                <?php if ($row['stock'] > 0) { ?>
                    <button onclick="addToCart(<?= h($row['id']) ?>)">
                        Add to Cart
                    </button>
                <?php } ?>
            </div>

        </div>

    <?php } ?>
</div>

<script src="js/cart.js"></script>

</body>