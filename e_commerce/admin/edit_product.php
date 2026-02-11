<?php
session_start();
require "C:/xampp/htdocs/e_commerce/config/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'];

// گرفتن اطلاعات محصول
$product = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");
$data = mysqli_fetch_assoc($product);
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

<form method="POST" class="edit-form"">

    <label>Name</label><br>
    <input type="text" name="name"
           value="<?php echo $data['name']; ?>" required>
    <br><br>

    <label>Description</label><br>
    <textarea name="description"><?php echo $data['description']; ?></textarea>
    <br><br>

    <label>Price</label><br>
    <input type="number" name="price"
           value="<?php echo $data['price']; ?>" required>
    <br><br>

    <label>Stock</label><br>
    <input type="number" name="stock"
           value="<?php echo $data['stock']; ?>" required>
    <br><br>

    <!-- Category dropdown (فقط برای تغییر دسته‌بندی) -->
    <label>Category</label><br>
    <select name="category_id" required>
        <?php
        $cats = mysqli_query($conn, "SELECT * FROM categories");
        while ($c = mysqli_fetch_assoc($cats)) {
            $selected = ($c['id'] == $data['category_id']) ? "selected" : "";
            echo "<option value='{$c['id']}' $selected>{$c['name']}</option>";
        }
        ?>
    </select>
    <br><br>

    <button type="submit" name="update">Update</button>

</form>
</div>
</body>
<?php
if (isset($_POST['update'])) {

    $name     = $_POST['name'];
    $desc     = $_POST['description'];
    $price    = $_POST['price'];
    $stock    = $_POST['stock'];
    $category = $_POST['category_id'];

    mysqli_query($conn,
        "UPDATE products
         SET name='$name',
             description='$desc',
             price='$price',
             stock='$stock',
             category_id='$category'
         WHERE id=$id"
    );

    header("Location: products.php");
    exit;
}
?>
