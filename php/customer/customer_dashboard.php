<?php
// Ensure the user is logged in as a customer
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: ../../login.html");
    exit();
}

// Fetch customer-specific data from the database
$customerUsername = $_SESSION["username"];
// Replace this with actual database queries to fetch customer data
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the customer's address from the database
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
    <!-- Customer-specific dashboard content -->
    <div class="nav" style="padding-bottom:0;">
        <div class="header">
            <a href="../../index.html" class="headerLogo"><img src="../../images/logo.png" alt="Domini's Logo" height="21" width="23">Domini's</a>
        </div>
        <div class="categories">
            <a href="customer_dashboard.php" id="values-meal" class="categories-nav">Values Meal</a>
            <a href="customer_dashboard.php" class="categories-nav">Pizzas</a>
            <a href="customer_dashboard.php" class="categories-nav">Sides</a>
            <a href="customer_dashboard.php" class="categories-nav">Drinks</a>
        </div>
    </div>
    <!-- <h2>Place an Order</h2> -->
    <div class="categories-choices"></div>
    <div id="customer-dashboard">
        

    <!-- <form action="place_order.php" method="post"> -->
    <!-- <div>
    <label for="vendor">Select Vendor:</label>
    <select id="vendor">
        <option value="default" selected = "selected">Please select one of the vendor:</option>
        <option value="vendora">Vendor A</option>
    </select> -->
    <div id ="products-list"></div>
    <div id=checkout-container>
        <div id="address-container">
            <div id="van2-container">    
                <img src="../../images/van-2.png" height="60%" width="60%"/>
            </div>
            <div id="address-details">
                <h3 style="margin-bottom:3px;margin-top:3px;">Delivery Now:</h3>
                <a href="../edit_profile.php"><?php echo $address; ?></a>
            </div>
        </div>
        <br>
        <div id="cart-container">
            <h3 id="cart-icon" alt="Cart">Order Details <div class="arrow-down"></div></h3>
            <div id="cart-dropdown" class="cart-dropdown">
            <p>Add some items from the menu to get started!<p>
            <h5>Total Price: $0.00</h5>
            <button id="checkout-btn" class="confirm-btn" disabled="">0 item | Total Price: $0.00 | Finish Order</button>
        </div>
        <!-- Cart items will be dynamically populated here -->
        
        </div>    
        
    </div>
    </div>
    <!-- <span id="cart-count">Total: $0.00</span> -->
    <!-- <a href="../order.php">Back</a> -->
    

    <script src="../../js/script.js"></script>
</body>
</html>
