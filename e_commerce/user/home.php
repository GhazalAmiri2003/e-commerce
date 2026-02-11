<?php
session_start();
require "../config/db.php";


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home</title>
    <link rel="stylesheet" href="/e_commerce/assets/css/home.css">
</head>

<body>

<?php include "menu.php"; ?>

<!-- ===== WELCOME SECTION by username===== -->
<section class="welcome">
    <h1>
        <?php
        if (isset($_SESSION['user_id'])) {

            $uid = $_SESSION['user_id'];

            $res = mysqli_query($conn,
                "SELECT name FROM users WHERE id = $uid"
            );

            if ($row = mysqli_fetch_assoc($res)) {
                echo "Welcome, " . htmlspecialchars($row['name']) . " !";
            } else {
                echo "Welcome to our Store!";
            }

        } else {
            echo "Welcome to our Store!";
        }
        ?>
    </h1>
    <p>Discover products you’ll love</p>
</section>


<hr>

<!-- ===== CATEGORIES SECTION ===== -->
<section class="categories">
    <h2>Categories</h2>

    <div class="category-list">
        <?php
        // فقط 3 تا کتگوری
        $cats = mysqli_query($conn,
            "SELECT * FROM categories LIMIT 3"
        );

        while ($cat = mysqli_fetch_assoc($cats)) {
            ?>
            <div class="category-card">
                <a href="products.php?category=<?php echo $cat['id']; ?>">

                    <img src="../<?php echo $cat['image']; ?>" alt="">
                    <h3><?php echo $cat['name']; ?></h3>
                </a>
            </div>
        <?php } ?>
    </div>
</section>

<hr>

<!-- ===== NEW PRODUCTS SECTION ===== -->
<section class="new-products">
    <h2>New Products</h2>

    <div class="product-list">
        <?php
        // 4 محصول جدید
        $products = mysqli_query($conn,
            "SELECT * FROM products
             ORDER BY created_at DESC
             LIMIT 4"
        );

        while ($row = mysqli_fetch_assoc($products)) {
            ?>
            <div class="product-card <?php echo ($row['stock'] <= 0) ? 'out-of-stock' : ''; ?>">

                <div class="image-wrapper">
                    <img src="../<?php echo $row['image']; ?>" width="150">

                    <?php if ($row['stock'] <= 0) { ?>
                        <div class="overlay">Out of Stock</div>
                    <?php } ?>
                </div>

                <h4><?php echo $row['name']; ?></h4>
                <p>€<?php echo $row['price']; ?></p>

                <?php if ($row['stock'] > 0) { ?>
                    <button onclick="addToCart(<?php echo $row['id']; ?>)">
                        Add to Cart
                    </button>
                <?php } ?>

            </div>

        <?php } ?>
    </div>
</section>
<script src="js/cart.js"></script>

</body>
</html>
