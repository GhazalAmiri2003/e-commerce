<?php
session_start();
require __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/security.php';
require_admin();
$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, ['options'=>['min_range'=>1]]);
if (!$id) { http_response_code(400); exit('Invalid product'); }
if (isset($_POST['update'])) {
    require_csrf();
    $name = trim((string)($_POST['name'] ?? ''));
    $desc = (string)($_POST['description'] ?? '');
    $price = filter_var($_POST['price'] ?? null, FILTER_VALIDATE_FLOAT);
    $stock = filter_var($_POST['stock'] ?? null, FILTER_VALIDATE_INT);
    $category = filter_var($_POST['category_id'] ?? null, FILTER_VALIDATE_INT);
    if ($name === '' || $price === false || $price < 0 || $stock === false || $stock < 0 || !$category || $category < 1) { http_response_code(400); exit('Invalid product data'); }
    $stmt = $conn->prepare('UPDATE products SET name=?, description=?, price=?, stock=?, category_id=? WHERE id=?');
    $stmt->bind_param('ssdiii', $name, $desc, $price, $stock, $category, $id); $stmt->execute();
    header('Location: products.php'); exit;
}
$stmt = $conn->prepare('SELECT * FROM products WHERE id=?');
$stmt->bind_param('i', $id); $stmt->execute(); $data = $stmt->get_result()->fetch_assoc();
if (!$data) { http_response_code(404); exit('Product not found'); }
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

<form method="POST" class="edit-form">
<input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">

    <label>Name</label><br>
    <input type="text" name="name"
           value="<?= h($data['name']) ?>" required>
    <br><br>

    <label>Description</label><br>
    <textarea name="description"><?= h($data['description']) ?></textarea>
    <br><br>

    <label>Price</label><br>
    <input type="number" name="price"
           value="<?= h($data['price']) ?>" required>
    <br><br>

    <label>Stock</label><br>
    <input type="number" name="stock"
           value="<?= h($data['stock']) ?>" required>
    <br><br>

    <!-- Category dropdown (فقط برای تغییر دسته‌بندی) -->
    <label>Category</label><br>
    <select name="category_id" required>
        <?php
        $cats = mysqli_query($conn, "SELECT * FROM categories");
        while ($c = mysqli_fetch_assoc($cats)) {
            $selected = ($c['id'] == $data['category_id']) ? "selected" : "";
            echo '<option value="' . (int)$c['id'] . '" ' . $selected . '>' . h($c['name']) . '</option>';
        }
        ?>
    </select>
    <br><br>

    <button type="submit" name="update">Update</button>

</form>
</div>
</body>
