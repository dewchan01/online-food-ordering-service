<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: ../user_login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = $_SESSION["username"];

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$vendorUsername = $_SESSION["username"];

$sqlOrders = "SELECT * FROM orders";
$resultOrders = $conn->query($sqlOrders);

$sqlProducts = "SELECT * FROM products";
$resultProducts = $conn->query($sqlProducts);

if (isset($_POST['delete_product'])) {
    $productId = $_POST['delete_product'];
    $deleteQuery = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $stmt->close();

    header("Location: vendor_dashboard.php");
}
if (isset($_POST['toggle_order_status'])) {
    $orderId = $_POST['toggle_order_status'];

    $fetchStatusQuery = "SELECT time,total_price,product_name,quantity,order_status,username FROM orders WHERE order_id = ?";
    $stmt = $conn->prepare($fetchStatusQuery);
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $stmt->bind_result($time, $price, $productName, $quantity, $currentStatus, $username);
    $stmt->fetch();
    $stmt->close();

    $newStatus = ($currentStatus === "Order Confirmed") ? "Delivered" : "Order Confirmed";
    $newStatus = strtoupper($newStatus);
    $updateStatusQuery = "UPDATE orders SET order_status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($updateStatusQuery);
    $stmt->bind_param("si", $newStatus, $orderId);
    $stmt->execute();
    $stmt->close();

    $usersServername = "localhost";
    $usersUsername = "root";
    $usersPassword = "";
    $usersDbname = "database";
    $usersConn = new mysqli($usersServername, $usersUsername, $usersPassword, $usersDbname);

    if ($usersConn->connect_error) {
        die("Users Database Connection failed: " . $usersConn->connect_error);
    }

    $fetchCustomerEmailQuery = "SELECT email FROM users WHERE username = ?";

    $stmt = $usersConn->prepare($fetchCustomerEmailQuery);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $customerEmailResult = $stmt->get_result();
    if ($customerEmailResult && $customerEmailResult->num_rows > 0) {
        $customerEmail = $customerEmailResult->fetch_assoc()['email'];

        $to = $customerEmail;
        $subject = "Domini's Pizza House - Order Status Update";
        $message = '<html><body>';
        $message .= '<h2>Pizza Order Status Update</h2>';
        $message .= '<p>Dear Customer,</p>';
        $message .= '<p>We would like to inform you that your order placed on ' . $time . ', has been <strong>' . $newStatus . '.</strong></p>';
        $message .= '<h3>Order Details:</h3>';
        $message .= "<table border='1'>";
        $message .= "<tr><th>Product Name</th><th>Quantity</th><th>Total Price</th></tr>";
        $message .= "<tr>";
        $message .= "<td>$productName</td>";
        $message .= "<td>$quantity</td>";
        $message .= "<td>$$price</td>";
        $message .= "</tr>";
        $message .= "</table>";
        $message .= '<p>Thank you for choosing our services!</p>';
        $message .= '<p>Sincerely,<br>Domini\'s Pizza House</p>';
        $message .= '</body></html>';

        $headers = "From: vendora@localhost\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";


        mail($to, $subject, $message, $headers);
    }
    $stmt->close();

    $usersConn->close();

    header("Location: vendor_dashboard.php");
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Vendor Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../../styles.css">
</head>

<body>
    <div class="nav">
        <div class="header">
            <a href="vendor_dashboard.php" class="headerLogo"><img src="../../images/logo.png" alt="Domini's Logo" height="21" width="23">Domini's</a>
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
            <div class="basic-buttons-container" id="my-account-nav">
                <a href="../edit_profile.php" class="basic-buttons" id="edit-profile"><img src="../../images/acc-3.png" alt="User's Account" width="34" height="30">
                    <p style="margin:5px 0 0 8px;">Edit Profile</p>
                </a>
                <a href="../logout.php" class="basic-buttons" id="log-out" onclick="return confirm('Are you sure you want to log out?')"><img src="../../images/vector.svg" alt="Log Out" width="34" height="20" style="margin-top:5px;">
                    <p style="margin:4px 0 0 7px;">Logout</p>
                </a>
            </div>
        </div>
    </div>

    <div id="vendor-dashboard">
        <h2>Manage Orders and Products</h2>
        <h3>Your Orders</h3>
        <div id="underline"></div>
        <table border="1" class="order-history" style="margin:auto;border-spacing: 0;">
            <tr>
                <th>Order ID</th>
                <th>Time</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Delivery Address</th>
                <th>Delivery Instruction</th>
                <th>Order Status</th>
            </tr>
            <?php
            if ($resultOrders->num_rows > 0) {
                while ($row = $resultOrders->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["order_id"] . "</td>";
                    echo "<td>" . $row["time"] . "</td>";
                    echo "<td>" . $row["product_name"] . "</td>";
                    echo "<td>" . $row["quantity"] . "</td>";
                    echo "<td>" . $row["total_price"] . "</td>";
                    echo "<td>" . $row["address"] . "</td>";
                    echo "<td>" . $row["delivery_instruction"] . "</td>";

                    echo "<td>";
                    $orderStatusText = ($row["order_status"] === "Order Confirmed") ? "Delivered" : "Delete";
                    $buttonStatus = ($row["order_status"] === "Order Confirmed") ? "" : "disabled";
                    echo "<form method='POST'>";
                    echo "<button type='submit' class='delivered-btn' name='toggle_order_status' value='" . $row['order_id'] . "' . $buttonStatus . >" . $orderStatusText . "</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No orders found.</td></tr>";
            }
            ?>
        </table>
        <h3>Your Products</h3>
        <div id="underline"></div>
        <form action="insert_product.php" method="POST">
            <button type="submit" class="insert-order-btn">Insert New Product</button>
        </form>
        <table border="1" class="order-history" style="margin:auto 100px auto 100px;border-spacing: 0;">
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Image URL</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php
            if ($resultProducts->num_rows > 0) {
                while ($row = $resultProducts->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["product_id"] . "</td>";
                    echo "<td>" . $row["product_name"] . "</td>";
                    echo "<td>" . $row["description"] . "</td>";
                    echo "<td>" . $row["price"] . "</td>";
                    echo "<td style ='width:250px'><img width=100% height=100% src='../../images/" . $row["image_url"] . "' alt='Product Image'></td>";
                    echo "<td>" . $row["status"] . "</td>";
                    echo "<td>";
                    echo "<form action='edit_product.php?product_id=" . $row['product_id'] . "' method='POST'>";
                    echo "<button type='submit' class='action-btn'>Edit</button>";
                    echo "</form>";
                    echo "<form method='POST'>";
                    echo "<button type='submit' name='delete_product' value = " . $row['product_id'] . " class='action-btn' style='color:red'>Delete</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No products found.</td></tr>";
            }
            ?>
        </table>

    </div>

    <script src="../../js/script.js"></script>
</body>
</html>

<?php
$conn->close();
?>