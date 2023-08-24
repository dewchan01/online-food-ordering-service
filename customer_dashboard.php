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
        <!-- Display customer's orders and other information -->
    </div>
    
    <a href="logout.php">Logout</a>
    
    <script src="script.js"></script>
</body>
</html>
