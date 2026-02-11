<?php
/*************************************************
 * CART PAGE
 * - Shows cart items
 * - Handles 30 min expiration
 * - Restores stock on timeout
 * - Demo checkout (no real payment)
 *************************************************/

session_start();
require "../config/db.php";

/* ---------- LOGIN CHECK ---------- */
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

/* ---------- CART TIMEOUT (30 MINUTES) ---------- */
$timeout = 30 * 60; // 30 minutes in seconds
$expired = false;

if (isset($_SESSION['cart_time']) && isset($_SESSION['cart'])) {

    // If inactive for more than 30 minutes
    if (time() - $_SESSION['cart_time'] > $timeout) {

        // Restore stock for all cart products
        foreach ($_SESSION['cart'] as $product_id => $qty) {
            mysqli_query($conn,
                "UPDATE products
                 SET stock = stock + $qty
                 WHERE id = $product_id"
            );
        }

        // Clear cart session
        unset($_SESSION['cart']);
        unset($_SESSION['cart_time']);

        $expired = true;
    }
}

/* ---------- DEMO CHECKOUT ---------- */
if (isset($_POST['checkout'])) {

    // Cart already reserved stock → do NOT restore
    unset($_SESSION['cart']);
    unset($_SESSION['cart_time']);

    echo "<h2>Order placed successfully ✅</h2>";
    echo "<p>This is a demo checkout.</p>";
    echo "<a href='home.php'>Back to Home</a>";
    exit;
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
        echo $timeout - (time() - $_SESSION['cart_time']);
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

    $subtotal = $product['price'] * $qty;
    $total += $subtotal;
    ?>

    <div style="border:1px solid #ccc; padding:10px; margin:10px 0">
        <strong><?php echo $product['name']; ?></strong><br>
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
