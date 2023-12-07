<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: ../../login.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vendora";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$loggedInUsername = $_SESSION['username'];
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
            <a href="../../index.php" class="headerLogo"><img src="../../images/logo.png" alt="Domini's Logo" height="21" width="23">Domini's</a>
        </div>
        <div class="menu">
            <a href="customer_dashboard.php">MENU</a>
        </div>
        <div class="my-account">
            <div id="my-account-container" style="cursor: pointer;">
                <a><svg width="26px" height="24px" viewBox="0 0 38 36" style="padding-top:7px;">
                        <g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g id="My-Account" fill="#202020">
                                <path d="M18.82 35.964c9.96 0 18.248-8.042 18.248-17.732S28.758.5 18.798.5C8.862.5.596 8.542.596 18.232s8.288 17.732 18.225 17.732zm0-11.821c-5.17 0-9.168 1.691-11.042 3.669a14.102 14.102 0 0 1-3.704-9.58c0-7.998 6.526-14.392 14.724-14.392 8.22 0 14.792 6.394 14.815 14.392 0 3.691-1.4 7.053-3.704 9.58-1.874-1.978-5.894-3.67-11.088-3.67zm0-2.9c3.41.043 6.098-2.791 6.098-6.505 0-3.493-2.687-6.372-6.097-6.372-3.388 0-6.098 2.879-6.075 6.372.022 3.714 2.665 6.46 6.075 6.504z"></path>
                            </g>
                        </g>
                    </svg></a>
                <a>MY ACCOUNT</a>
            </div>
            <div class="basic-buttons-container" id="my-account-nav" style="display:none;position:relative;z-index:10;background-color:white;">
                <a href="../edit_profile.php" class="basic-buttons" id="edit-profile"><img src="../../images/acc-3.png" alt="User's Account" width="34" height="30">
                    <p style="margin:5px 0 0 8px;">Edit Profile</p>
                </a>
                <a href="check_history.php" class="basic-buttons" id="order-history"><img src="../../images/newspaper-regular-1.svg" alt="Order History" width="34" height="25" style="margin-top:5px;">
                    <p style="margin:5px 0 0 7px;">Order History</p>
                </a>
                <a href="../logout.php" class="basic-buttons" id="log-out" onclick="return confirm('Are you sure you want to log out?')"><img src="../../images/vector.svg" alt="Log Out" width="34" height="20" style="margin-top:5px;">
                    <p style="margin:4px 0 0 7px;">Logout</p>
                </a>
            </div>
        </div>
    </div>
    <h1 style="text-align:center;">Your Orders</h1>
    <?php if (!empty($orders)) : ?>
        <table class="order-history" style="margin:auto;border-spacing: 0;">
            <tr>
                <th>Time of Order</th>
                <th>Product Name</th>
                <th>Product Image</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Order Status</th>
                <th>Delivery Instruction</th>

            </tr>
            <?php foreach ($orders as $order) : ?>
                <tr>
                    <td><?php echo $order['time']; ?></td>
                    <td><?php echo $order['product_name']; ?></td>
                    <td style="text-align:center;width:250px;"><img src="../<?php echo $order['image_url']; ?>" alt="Product Image" width=100% height=100%></td>
                    <td><?php echo $order['quantity']; ?></td>
                    <td><?php echo $order['total_price']; ?></td>
                    <td><?php echo $order['order_status']; ?></td>
                    <td><?php echo $order['delivery_instruction']; ?></td>

                </tr>
            <?php endforeach; ?>
        </table>
    <?php else : ?>
        <p style="text-align:center;">No orders found.</p>
    <?php endif; ?>

    <a href="../order.php" id="back-to-menu-btn">Back to Order</a>
    <script type="text/javascript" src="../../js/script.js"></script>

</body>

</html>