<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: ../../login.html");
    exit();
}

$customerUsername = $_SESSION["username"];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$address = "";
$sql = "SELECT address FROM users WHERE username = '$customerUsername'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $address = $row["address"];
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
            <a href="customer_dashboard.php" id="values-meal" class="categories-nav">Values Meal</a>
            <a href="customer_dashboard.php" class="categories-nav" id="pizza-active">Pizzas</a>
            <a href="customer_dashboard.php" class="categories-nav">Sides</a>
            <a href="customer_dashboard.php" class="categories-nav">Drinks</a>
        </div>
    </div>
    <div class="categories-choices"></div>
    <div id="customer-dashboard">

        <div id="products-list"></div>
        <div id=checkout-container>
            <div id="address-container">
                <div id="van2-container">
                    <img src="../../images/van-2.png" height="60%" width="60%" />
                </div>
                <div id="address-details">
                    <h3 style="margin-bottom:3px;margin-top:3px;">Delivery Now:</h3>
                    <span><?php echo $address; ?></span>
                </div>
            </div>
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

    <script src="../../js/script.js"></script>
</body>

</html>