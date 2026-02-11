function addToCart(productId) {

    fetch("cart_action.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "product_id=" + productId
    })
        .then(res => res.text())
        .then(data => {
            if (data === "added") {
                alert("Added to cart!");
            }
            else if (data === "out_of_stock") {
                alert("Out of stock!");
            }
            else if (data === "login") {
                window.location.href = "../auth/login.php";
            }
        });
}
