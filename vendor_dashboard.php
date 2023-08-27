<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: user_login.php");
    exit();
}

// Replace these with your actual database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = $_SESSION["username"];

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$vendorUsername = $_SESSION["username"];

// Fetch orders for the logged-in vendor
$sql = "SELECT * FROM orders";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Vendor Dashboard</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Welcome, <?php echo $vendorUsername; ?>!</h1>
    
    <div id="vendor-dashboard">
        <h2>Manage Products and Items</h2>
        <!-- Provide forms for adding/editing products and items -->

        <h3>Your Orders</h3>
        <table border="1">
            <tr>
                <th>Order ID</th>
                <th>Time</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Order Status</th>
                <!-- Add more columns as needed -->
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["order_id"] . "</td>";
                    echo "<td>" . $row["time"] . "</td>";
                    echo "<td>" . $row["product_name"] . "</td>";
                    echo "<td>" . $row["quantity"] . "</td>";
                    echo "<td>" . $row["total_price"] . "</td>";
                    echo "<td>" . $row["order_status"] . "</td>";
                    // Add more cells for additional columns
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No orders found.</td></tr>";
            }
            ?>
        </table>
    </div>

    <a href="logout.php">Logout</a>
    
    <script src="script.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
