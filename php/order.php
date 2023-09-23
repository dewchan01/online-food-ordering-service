<?php
// Ensure the user is logged in as a customer
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: ../login.html");
    exit();
}

// Fetch customer-specific data from the database
$customerUsername = $_SESSION["username"];
// Replace this with actual database queries to fetch customer data
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Welcome Back<br><?php echo $customerUsername; ?>!</h1>
    <h2>Start Your Order</h2>
    <div>
        <h3>My Account</h3>
    </div>

    <div>
        <input type="button" value="Dine In" disabled>
        <input type="button" value="Delivery Now" onclick="window.location.href='customer/customer_dashboard.php'">
    </div>

    <a href="logout.php">Logout</a>
    <a href="edit_profile.php">Edit Profile</a>
    <a href="customer/check_history.php">Check History</a>
    <script type="text/javascript" src="../js/script.js"></script>
</body>
</html>