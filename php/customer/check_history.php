<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: ../user_login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vendora";//selected vendor

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$loggedInUsername = $_SESSION['username'];

// Fetch orders for the logged-in customer
$fetchOrdersQuery = "SELECT * FROM orders WHERE username = ?";
$stmt = $conn->prepare($fetchOrdersQuery);
$stmt->bind_param("s", $loggedInUsername);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Orders</title>
    <link rel="stylesheet" type="text/css" href="../../styles.css">
</head>
<body>
    <div class="nav">
        <div class="header">
            <a href="../../index.html" class="headerLogo"><img src="../../images/logo.png" alt="Domini's Logo" height="21" width="23">Domini's</a>
        </div>
        <div class="menu">
            <a href="customer_dashboard.php">MENU</a>
        </div>
        <div class="my-account">
            <div id="my-account-container" style="cursor: pointer;">
                <a><img src="../../images/acc-1.png" alt="User's Account" height="37" width="34" style="padding-top: 5px;"></a>
                <a>MY ACCOUNT</a>
            </div>
            <div class="basic-buttons-container" id="my-account-nav" style="display:none;position:relative;z-index:10;background-color:white;">
                <a href="../edit_profile.php" class="basic-buttons" id="edit-profile"><img src="../../images/acc-3.png" alt="User's Account" width= "34"
height= "30"><p style="margin:5px 0 0 8px;">Edit Profile</p></a>
                <a href="check_history.php" class="basic-buttons" id="order-history"><img src="../../images/newspaper-regular-1.svg" alt="Order History" width= "34"
height= "25" style="margin-top:5px;"><p style="margin:5px 0 0 7px;">Order History</p></a>
                <a href="../logout.php" class="basic-buttons" id="log-out" onclick="return confirm('Are you sure you want to log out?')"><img src="../../images/vector.svg" alt="Order History" width= "34"
height= "20"style="margin-top:5px;" ><p style="margin:4px 0 0 7px;">Logout</p></a>
            </div> 
        </div>
    </div>
    <h1 style="text-align:center;">Your Orders</h1>
    <?php if (!empty($orders)): ?>
    <table border="1"  style="margin:auto;">
        <tr>
            <th>Time of Order</th>
            <th>Product Name</th>
            <th>Product Image</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Order Status</th>
            <th>Delivery Instruction</th>

        </tr>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?php echo $order['time']; ?></td>
                <td><?php echo $order['product_name']; ?></td>
                <td style="text-align:center;"><img src="<?php echo $order['image_url']; ?>" alt="Product Image" width=50% height=50%></td>
                <td><?php echo $order['quantity']; ?></td>
                <td><?php echo $order['total_price']; ?></td>
                <td><?php echo $order['order_status']; ?></td>
                <td><?php echo $order['delivery_instruction']; ?></td>

            </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>
    
    <a href="customer_dashboard.php" id="back-to-order-btn" style="margin-left:200px;">Back</a>
    <script type="text/javascript" src="../../js/script.js"></script>

</body>
</html>
