<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Customer Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../../styles.css">
</head>

<body>
    <div class="nav" style="padding-bottom:0;">
        <div class="header">
            <a href="../../index.php" class="headerLogo"><img src="../../images/logo.png" alt="Domini's Logo" height="21" width="23">Domini's</a>
        </div>
        <div class="categories">
            <a href="guest_dashboard.php" id="values-meal" class="categories-nav">Values Meal</a>
            <a href="guest_dashboard.php" class="categories-nav" id="pizza-active">Pizzas</a>
            <a href="guest_dashboard.php" class="categories-nav">Sides</a>
            <a href="guest_dashboard.php" class="categories-nav">Drinks</a>
        </div>
    </div>
    <div class="categories-choices"></div>
    <div id="customer-dashboard">

        <div id="products-list"></div>
        <div id=checkout-container>
            
            <br>
            <div id="cart-container">
                <h3 id="cart-icon" alt="Cart">Order Details <div class="arrow-down"></div>
                </h3>
                <div id="cart-dropdown" class="cart-dropdown">
                    <p>Add some items from the menu to get started!
                    <p>
                    <h5>Total Price: $0.00</h5>
                    <button id="checkout-btn" class="confirm-btn" disabled="">0 item | Total Price: $0.00 | Finish Order</button>
                </div>

            </div>

        </div>
    </div>

    <script>
        
        
    </script>
        <script src="../../js/script.js"></script>

</body>

</html>