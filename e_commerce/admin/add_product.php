<?php
session_start();
require __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/security.php';
require_admin();
if (isset($_POST['add_product'])) {
    require_csrf();
    $name = trim((string)($_POST['name'] ?? ''));
    $desc = (string)($_POST['description'] ?? '');
    $price = filter_var($_POST['price'] ?? null, FILTER_VALIDATE_FLOAT);
    $stock = filter_var($_POST['stock'] ?? null, FILTER_VALIDATE_INT);
    $category = filter_var($_POST['category_id'] ?? null, FILTER_VALIDATE_INT);
    if ($name === '' || $price === false || $price < 0 || $stock === false || $stock < 0 || !$category || $category < 1) { http_response_code(400); exit('Invalid product data'); }
    $file = $_FILES['image'] ?? null;
    if (!$file || $file['error'] !== UPLOAD_ERR_OK || $file['size'] > 5 * 1024 * 1024 || !is_uploaded_file($file['tmp_name'])) { http_response_code(400); exit('Invalid upload'); }
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $types = ['image/jpeg'=>'jpg', 'image/png'=>'png', 'image/webp'=>'webp'];
    if (!isset($types[$mime]) || !getimagesize($file['tmp_name'])) { http_response_code(400); exit('Only JPEG, PNG or WebP accepted'); }
    $path = 'assets/' . bin2hex(random_bytes(16)) . '.' . $types[$mime];
    $destination = __DIR__ . '/../' . $path;
    if (!move_uploaded_file($file['tmp_name'], $destination)) { http_response_code(500); exit('Upload failed'); }
    try {
        $stmt = $conn->prepare('INSERT INTO products (name, description, price, stock, image, category_id) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('ssdisi', $name, $desc, $price, $stock, $path, $category); $stmt->execute();
    } catch (Throwable $e) { unlink($destination); http_response_code(500); exit('Unable to save product'); }
    header('Location: products.php'); exit;
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
<input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">

    <input type="text" name="name" placeholder="Product name" required><br><br>

    <textarea name="description" placeholder="Description"></textarea><br><br>

    <input type="number" name="price" placeholder="Price" required><br><br>

    <input type="number" name="stock" placeholder="Stock" required><br><br>

    <select name="category_id" required>
        <option value="">Select category</option>
        <?php
        $cats = mysqli_query($conn, "SELECT * FROM categories");
        while ($c = mysqli_fetch_assoc($cats)) {
            echo '<option value="' . (int)$c['id'] . '">' . h($c['name']) . '</option>';
        }
        ?>
    </select>
    <br><br>

    <input type="file" name="image" required><br><br>

    <button type="submit" name="add_product">Add</button>
</form>
</div>
