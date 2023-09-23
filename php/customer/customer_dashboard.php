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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../../styles.css">
</head>
<body>    
    <!-- Customer-specific dashboard content -->
    <div id="customer-dashboard">
    <h2>Place an Order</h2>

    <!-- <form action="place_order.php" method="post"> -->
    <div>
    <label for="vendor">Select Vendor:</label>
    <select id="vendor">
        <option value="default" selected = "selected">Please select one of the vendor:</option>
        <option value="vendora">Vendor A</option>
    </select>
    <div id ="products-list"></div>
        
    <div>
        <img id="cart-icon" src="../../images/cart-icon.png" alt="Cart">
        <span id="cart-count">0</span>
        <div id="cart-dropdown" class="cart-dropdown">
        <!-- Cart items will be dynamically populated here -->
        </div>    
    </div>
    
    <a href="../order.php">Back</a>

    <script src="../../js/script.js"></script>
</body>
</html>
