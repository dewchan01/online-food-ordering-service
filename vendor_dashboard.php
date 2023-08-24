<?php
// Ensure the user is logged in as a vendor
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: user_login.php");
    exit();
}

// Fetch vendor-specific data from the database
$vendorUsername = $_SESSION["username"];
// Replace this with actual database queries to fetch vendor data
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vendor Dashboard</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Welcome, <?php echo $vendorUsername; ?>!</h1>
    
    <!-- Vendor-specific dashboard content -->
    <div id="vendor-dashboard">
        <!-- Display vendor's menu, orders, and other information -->
    </div>
    
    <a href="logout.php">Logout</a>
    
    <script src="script.js"></script>
</body>
</html>
