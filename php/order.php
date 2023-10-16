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
        <div class="my-account">
            <div id="my-account-container" style="cursor: pointer;">
                <a><img src="../images/acc-1.png" alt="User's Account" height="37" width="34" style="padding-top: 5px;"></a>
                <a>MY ACCOUNT</a>
            </div>
            <div class="basic-buttons-container" id="my-account-nav" style="display:none;">
                <a href="edit_profile.php" class="basic-buttons" id="edit-profile"><img src="../images/acc-3.png" alt="User's Account" width= "34"
height= "30"><p style="margin:5px 0 0 8px;">Edit Profile</p></a>
                <a href="customer/check_history.php" class="basic-buttons" id="order-history"><img src="../images/newspaper-regular-1.svg" alt="Order History" width= "34"
height= "25" style="margin-top:5px;"><p style="margin:5px 0 0 7px;">Order History</p></a>
                <a href="logout.php" class="basic-buttons" id="log-out"><img src="../images/vector.svg" alt="Order History" width= "34"
height= "20"style="margin-top:5px;" ><p style="margin:4px 0 0 7px;">Logout</p></a>
            </div> 
        </div>
    </div>
    <div id="order">
        <div id="welcome">
            <h1 style="color:#FFF">WELCOME <?php echo $customerUsername; ?>!</h1>
            <div id="line"></div>
            <div id="start-your-order">
                <h2>Start Your Order</h2>
                <div>
                    <input type="button" value="Dine In" id="dine-in" style="width:380px" disabled><br><br>
                    <input type="button" value="Delivery Now" id="delivery" style="width:380px" onclick="window.location.href='customer/customer_dashboard.php'">
                </div>
                <br><br>
            </div>
        </div>  
    </div>
    <script type="text/javascript" src="../js/script.js"></script>
</body>
</html>