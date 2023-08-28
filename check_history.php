<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: user_login.php");
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
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Your Orders</h1>
    <?php if (!empty($orders)): ?>
    <table border="1">
        <tr>
            <th>Order ID</th>
            <th>Time of Order</th>
            <th>Product Name</th>
            <th>Product Image</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Order Status</th>
        </tr>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?php echo $order['order_id']; ?></td>
                <td><?php echo $order['time']; ?></td>
                <td><?php echo $order['product_name']; ?></td>
                <td><img src="<?php echo $order['image_url']; ?>" alt="Product Image"></td>
                <td><?php echo $order['quantity']; ?></td>
                <td><?php echo $order['total_price']; ?></td>
                <td><?php echo $order['order_status']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>
    
    <a href="customer_dashboard.php">Back to Dashboard</a>
</body>
</html>
