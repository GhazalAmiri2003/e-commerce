<?php
session_start();
require "C:/xampp/htdocs/e_commerce/config/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/base.css">
</head>
<body>

<div class="admin-container">





<form method="POST" enctype="multipart/form-data" class="admin-form">

    <input type="text" name="name" placeholder="Product name" required><br><br>

    <textarea name="description" placeholder="Description"></textarea><br><br>

    <input type="number" name="price" placeholder="Price" required><br><br>

    <input type="number" name="stock" placeholder="Stock" required><br><br>

    <select name="category_id" required>
        <option value="">Select category</option>
        <?php
        $cats = mysqli_query($conn, "SELECT * FROM categories");
        while ($c = mysqli_fetch_assoc($cats)) {
            echo "<option value='{$c['id']}'>{$c['name']}</option>";
        }
        ?>
    </select>
    <br><br>

    <input type="file" name="image" required><br><br>

    <button type="submit" name="add_product">Add</button>
</form>
</div>
<?php
if (isset($_POST['add_product'])) {

    $name     = $_POST['name'];
    $desc     = $_POST['description'];
    $price    = $_POST['price'];
    $stock    = $_POST['stock'];
    $category = $_POST['category_id'];

    $imageName = $_FILES['image']['name'];
    $tmpName   = $_FILES['image']['tmp_name'];

    $path = "assets/" . $imageName;
    move_uploaded_file($tmpName, "../" . $path);

    mysqli_query($conn,
        "INSERT INTO products
         (name, description, price, stock, image, category_id)
         VALUES
         ('$name','$desc','$price','$stock','$path','$category')"
    );

    header("Location: products.php");
    exit;
}
?>
