<?php
session_start();
require __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/security.php';
if (empty($_SESSION['user_id'])) { header('Location: ../auth/login.php'); exit; }
$timeout = 1800;
$expired = isset($_SESSION['cart_time']) && time() - $_SESSION['cart_time'] >= $timeout;
if ($expired) unset($_SESSION['cart'], $_SESSION['cart_time']);
if (isset($_POST['checkout'])) {
    require_csrf();
    if (empty($_SESSION['cart'])) { http_response_code(409); exit('Cart is empty or expired.'); }
    $items = $_SESSION['cart'];
    ksort($items);
    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare('UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?');
        foreach ($items as $id => $qty) {
            $id = (int)$id; $qty = (int)$qty;
            if ($qty < 1) throw new RuntimeException('Invalid quantity');
            $stmt->bind_param('iii', $qty, $id, $qty);
            $stmt->execute();
            if ($stmt->affected_rows !== 1) throw new RuntimeException('Stock unavailable');
        }
        $conn->commit();
        unset($_SESSION['cart'], $_SESSION['cart_time']);
        echo "<h2>Demo checkout completed</h2><p>No payment was processed.</p><a href='home.php'>Back to Home</a>";
        exit;
    } catch (Throwable $e) {
        $conn->rollback();
        $checkoutError = 'Checkout failed; check product availability and retry.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <link rel="stylesheet" href="../assets/css/cart.css">

</head>
<body>
<div class="cart-container">
<h2>Your Cart 🛒</h2>

<?php
/* ---------- IF CART EXPIRED ---------- */
if (isset($checkoutError)) echo '<p>' . h($checkoutError) . '</p>';
if ($expired) {
    echo "<p style='color:red'>Your cart expired due to inactivity ⏰</p>";
    echo "<a href='home.php'>Back to Home</a>";
    exit;
}

/* ---------- IF CART EMPTY ---------- */
if (empty($_SESSION['cart'])) {
    echo "<p>Cart is empty</p>";
    echo "<a href='home.php'>Continue shopping</a>";
    exit;
}
?>

<!-- ---------- COUNTDOWN TIMER ---------- -->
<p>
    Cart expires in:
    <strong><span id="timer"></span></strong>
</p>

<script>
    /* Remaining seconds until expiration (from PHP) */
    let remaining = <?php
        echo max(0, $timeout - (time() - $_SESSION['cart_time']));
        ?>;

    /* Countdown function */
    function startTimer() {
        let minutes, seconds;

        let interval = setInterval(() => {
            minutes = Math.floor(remaining / 60);
            seconds = remaining % 60;

            document.getElementById("timer").innerText =
                minutes + ":" + (seconds < 10 ? "0" : "") + seconds;

            remaining--;

            // When time is over → reload page (PHP will expire cart)
            if (remaining < 0) {
                clearInterval(interval);
                location.reload();
            }
        }, 1000);
    }

    startTimer();
</script>

<hr>

<!-- ---------- CART ITEMS ---------- -->
<?php
$total = 0;

foreach ($_SESSION['cart'] as $product_id => $qty) {

    // Get product info
    $res = mysqli_query($conn,
        "SELECT name, price FROM products WHERE id = $product_id"
    );
    $product = mysqli_fetch_assoc($res);

    if (!$product) continue;
    $subtotal = $product['price'] * $qty;
    $total += $subtotal;
    ?>

    <div style="border:1px solid #ccc; padding:10px; margin:10px 0">
        <strong><?= h($product['name']) ?></strong><br>
        Quantity: <?php echo $qty; ?><br>
        Price: €<?php echo $product['price']; ?><br>
        Subtotal: €<?php echo $subtotal; ?>
    </div>

<?php } ?>

<hr>

<h3>Total: €<?php echo $total; ?></h3>

<!-- ---------- DEMO CHECKOUT BUTTON ---------- -->
    <?php if (isset($_SESSION['user_id'])) { ?>

        <!-- USER LOGGED IN -->
        <form method="POST">
<input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
            <button type="submit" name="checkout">
                Checkout (Demo)
            </button>
        </form>

    <?php } else { ?>

        <!-- USER NOT LOGGED IN -->
        <button onclick="loginAlert()">
            Checkout (Demo)
        </button>

    <?php } ?>


<br>
<a href="home.php">← Continue shopping</a>
</div>

</body>
</html>
