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
    <div id="customer-dashboard">
    <h2>Place an Order</h2>

    <!-- <form action="place_order.php" method="post"> -->
    <!-- <div>
    <label for="vendor">Select Vendor:</label>
    <select id="vendor">
        <option value="default" selected = "selected">Please select one of the vendor:</option>
        <option value="vendora">Vendor A</option>
    </select> -->
    <div id ="products-list"></div>
    <div id="address">
        <h3>Delivery Now:</h3>
    <p><?php echo $address; ?></p>
    <a href="../edit_profile.php">Change Address</a>
</div>
    <div>
        <h3 id="cart-icon" alt="Cart">Order Details</h3>
        <div id="cart-dropdown" class="cart-dropdown">
        <!-- Cart items will be dynamically populated here -->
        </div>    
        <!-- <span id="cart-count">Total: $0.00</span> -->

    </div>
    
    <a href="../order.php">Back</a>

    <script src="../../js/script.js"></script>
</body>
</html>
