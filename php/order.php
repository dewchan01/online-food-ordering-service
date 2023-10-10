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
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>
    <div class="nav">
        <div class="header">
            <a href="../index.html" class="headerLogo"><img src="../images/logo.png" alt="Domini's Logo" height="21" width="23">Domini's</a>
        </div>
        <div class="menu">
            <a href="customer/customer_dashboard.php">MENU</a>
        </div>
        <div class="myAccount">
            <a href="edit_profile.php"><img src="../images/acc-1.png" alt="User's Account" height="37" width="34" style="padding-top: 5px;"></a>
            <a href="edit_profile.php">MY ACCOUNT</a>
        </div>
    </div>
    <div id="order">
        <div>
            <img src="../images/order-image.jpg" style="width: 100%; height: 100%; object-fit: cover; object-position: center"></img>
        </div>
        <h1>WELCOME <?php echo $customerUsername; ?>!</h1>
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
    </div>
    <script type="text/javascript" src="../js/script.js"></script>
</body>
</html>