<?php
// Ensure the user is logged in as a customer
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: user_login.php");
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
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Welcome, <?php echo $customerUsername; ?>!</h1>
    
    <!-- Customer-specific dashboard content -->
    <div id="customer-dashboard">
    <h2>Place an Order</h2>

    <form action="place_order.php" method="post">
    <label for="vendor">Select Vendor:</label>
    <select id="vendor">
        <option value="default" selected = "selected">Please select one of the vendor:</option>
        <option value="vendora">Vendor A</option>
        <option value="vendorb">Vendor B</option>
    </select>
    <div id ="products-list"></div>
        
    <h2>Your Cart</h2>
    <div id="cart">
        <!-- Cart items will be dynamically populated here -->
    </div>
    
    <button id="checkout-btn" disabled>Checkout</button>
    </form>
    
    <!-- Display customer's orders and other information -->
</div>



    
    <a href="logout.php">Logout</a>
    
    <script src="script.js"></script>
</body>
</html>
