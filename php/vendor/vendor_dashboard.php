<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: ../user_login.php");
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
$sqlOrders = "SELECT * FROM orders";
$resultOrders = $conn->query($sqlOrders);

// Fetch products for the logged-in vendor
$sqlProducts = "SELECT * FROM products";
$resultProducts = $conn->query($sqlProducts);

if (isset($_POST['delete_product'])) {
    $productId = $_POST['delete_product']; // Example input name in your form

    // Perform delete for the selected product
    $deleteQuery = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $stmt->close();

    header("Location: vendor_dashboard.php");

}
if(isset($_POST['toggle_order_status'])){
    $orderId = $_POST['toggle_order_status']; // Assuming the button value is set to the order ID

    // Fetch the current order status
    $fetchStatusQuery = "SELECT order_status,username FROM orders WHERE order_id = ?";
    $stmt = $conn->prepare($fetchStatusQuery);
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $stmt->bind_result($currentStatus,$username);
    $stmt->fetch();
    $stmt->close();

    // Determine the new order status
    $newStatus = ($currentStatus === "Order Confirmed") ? "Delivered" : "Order Confirmed";

    // Update the order status in the database
    $updateStatusQuery = "UPDATE orders SET order_status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($updateStatusQuery);
    $stmt->bind_param("si", $newStatus, $orderId);
    $stmt->execute();
    $stmt->close();

    $usersServername = "localhost";
    $usersUsername = "root";
    $usersPassword = "";
    $usersDbname = "database"; // Replace with the actual users database name

    $usersConn = new mysqli($usersServername, $usersUsername, $usersPassword, $usersDbname);

    if ($usersConn->connect_error) {
        die("Users Database Connection failed: " . $usersConn->connect_error);
    }

    // Fetch the customer's email from the users database using the username
    $fetchCustomerEmailQuery = "SELECT email FROM users WHERE username = ?";

    $stmt = $usersConn->prepare($fetchCustomerEmailQuery);
    $stmt->bind_param("s", $username); // Use the username obtained from the orders table
    $stmt->execute();
    $customerEmailResult = $stmt->get_result();
    if ($customerEmailResult && $customerEmailResult->num_rows > 0) {
        $customerEmail = $customerEmailResult->fetch_assoc()['email'];

        // Send an email to the customer
        $to = $customerEmail;
        $subject = "Order Status Update";
        $message = "Your order's status has been updated to: $newStatus";
        $headers = "From: vendora@localhost";

        mail($to, $subject, $message, $headers);
    }
    $stmt->close();

    // Close the users database connection
    $usersConn->close();

    // Redirect to refresh the page
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
    <h1>Welcome, <?php echo $vendorUsername; ?>!</h1>
    
    <div id="vendor-dashboard">
        <h2>Manage Orders and Products</h2>
        <!-- Provide forms for adding/editing products and items -->

        <h3>Your Orders</h3>
        <table border="1">
        <tr>
            <th>Order ID</th>
            <th>Time</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Delivery Address</th>
            <th>Delivery Instruction</th>
            <th>Order Status</th>
            
            <!-- Add more columns as needed -->
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
            // Determine the text content based on the order status
            $orderStatusText = ($row["order_status"] === "Order Confirmed") ? "Delivered" : "Delete";
            $buttonStatus = ($row["order_status"] === "Order Confirmed") ? "" : "disabled";
            // $orderStatusText = (strpos($row["order_status"], "Order Confirmed") !== false) ? "Delivered" : "Delete";
            // $buttonStatus = (strpos($row["order_status"], "Order Confirmed") !== false) ? "" : "disabled";

            echo "<form method='POST'>";
            echo "<button type='submit' name='toggle_order_status' value='" . $row['order_id'] . "' . $buttonStatus . >" . $orderStatusText . "</button>";
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
        <form action="insert_product.php" method="POST">
            <button type="submit">Insert New Product</button>
        </form>
        <table border="1">
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
            echo "<td><a href='" . $row["image_url"] . "'><img src='" . $row["image_url"] . "' alt='Product Image'></a></td>";
            echo "<td>" . $row["status"] . "</td>";
            echo "<td>";
            echo "<form action='edit_product.php?product_id=" . $row['product_id'] . "' method='POST'>";
            echo "<button type='submit'>Edit</button>";
            echo "</form>";
            echo "<form method='POST'>";
            echo "<button type='submit' name='delete_product' value = ".$row['product_id'] . ">Delete</button>";
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

    <a href="../logout.php">Logout</a>
    <a href="../edit_profile.php">Edit Profile</a>
    <script src="../../js/script.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
