<?php
// Ensure the user is logged in as a customer
session_start();
if (!isset($_SESSION["username"])) {
    $login=FALSE;
}
else{
    $login=TRUE;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domini's Pizza House</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>
    <div class="nav">
        <div class="header">
            <a href="index.php" class="headerLogo"><img src="images/logo.png" alt="Domini's Logo" height="21" width="23">Domini's</a>
        </div>
        <div class="menu">
            <a href="php/order.php">MENU</a>
        </div>
        <div class="my-account">
            <div id="my-account-container" style="cursor: pointer;">
                <a><img src="images/acc-1.png" alt="User's Account" height="37" width="34" style="padding-top: 5px;"></a>
                <a>MY ACCOUNT</a>
            </div>
            <div class="basic-buttons-container" id="my-account-nav">
                <?php
                    if (!$login) {
                        echo "<a href='login.html' class='basic-buttons' id='login-btn'><p style='margin:5px 0 0 8px;'>Log In</p></a>";
                    }
                    else{
                        echo "<a href='php/edit_profile.php' class='basic-buttons' id='edit-profile'><img src='images/acc-3.png' alt='User\'s Account' width='34' height='30'><p style='margin:5px 0 0 8px;'>Edit Profile</p></a>";
                        echo "<a href='php/customer/check_history.php' class='basic-buttons' id='order-history'><img src='images/newspaper-regular-1.svg' alt='Order History' width='34' height='25' style='margin-top:5px;'><p style='margin:5px 0 0 7px;'>Order History</p></a>";
                        echo "<a href='php/logout.php' class='basic-buttons' id='log-out' onclick='return confirm(\"Are you sure you want to log out?\")'><img src='images/vector.svg' alt='Order History' width='34' height='20' style='margin-top:5px;'><p style='margin:4px 0 0 7px;'>Logout</p></a>";
                    }
                ?>
            </div> 
        </div>
    </div>
    <div class="order-nav">
        <div id="dine-in">
            <img src="images/van-1.png" alt="van" height="50%">
            <p style="margin:0;padding-top: 4px;padding-left: 40px;font-weight: 700;">DINE IN</p>
        </div>
        <div id="delivery" onclick="window.location.href='php/order.php'">
            <img src="images/van-2.png" alt="van" height="50%">
            <p style="margin:0;padding-top: 4px;padding-left: 40px;font-weight: 700;">DELIVERY NOW</p>   
        </div>
    </div>
    <div class="banner">
        <!-- <img class="vector" src="images/vector-left.png"> -->
        <img class="banner-image" onclick="window.location.href ='php/order.php'" data-backgroundimageurl="images"
            src="images/banner.jpg" data-loaded="true">
        <!-- <img class="vector" src="images/vector-left.png" style="transform: rotate(180deg);"> -->
    </div>

    <div class="des">
        <h2>Domini's Pizza - The Best Pizza In Singapore</h2>
        <p>It is our commitment to be the best pizza joint in Singapore and offer great value for all pizza lovers from
            around the world. Domino's Pizza has won the hearts of many with a variety of great tasting signature pizzas
            delivered quickly to your door step while maintaining the freshness and its state of piping hotness, for a
            hearty meal with family and friends. Domino's Pizza has been established in Singapore since 2009. In a span of
            14 years, we have opened over 40 outlets across the island. From the first store at Bukit Timah in 2009,
            Domino's Pizza Singapore has grown strength to strength in providing great value through delicious pizzas
            delivered fast. Throughout time, Domino's has also been upgrading its menu and food choices by offering limited
            time flavors and variety such as Mentaiko Pizza, Chocolate Lava Cake, and Cheeseburger Pizzas.
        </p>
    </div>
    <script type="text/javascript" src="js/script.js"></script>
</body>

</html>