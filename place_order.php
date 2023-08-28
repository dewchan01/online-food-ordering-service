<?php
session_start();

$cartItemsJSON = $_GET['cart']; // Retrieve the cart items JSON string from URL parameters
$selectedVendor = $_GET['vendor']; // Retrieve the selected vendor from URL parameters

$cartItems = json_decode($cartItemsJSON, true); // Decode the JSON to an associative array

// You can process and display the cart items and retrieve prices from the database here
// For demonstration purposes, let's assume you have a database connection and query

$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = $selectedVendor;

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$customerUsername = $_SESSION["username"];

$products = [];
// Process the cart items and insert them into the orders table
$sql = "SELECT product_id, product_name, price, image_url, description FROM products";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[$row['product_name']] = $row; // Store product details in an array with product_name as key
}
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h2>Checkout</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total Price</th>
        </tr>
        <?php
        $totalCost = 0;
        foreach ($cartItems as $productName => $quantity) {
            if (isset($products[$productName])) {
                $product = $products[$productName];
                $productID = $product['product_id'];
                $price = $product['price'];
                $totalPrice = $price * $quantity;
                $totalCost += $totalPrice;
                echo "<tr>";
                echo "<td>$productID</td>";
                echo "<td>$productName</td>";
                echo "<td>$price</td>";
                echo "<td>$quantity</td>";
                echo "<td>$totalPrice</td>";
                echo "</tr>";
            }
        }
        ?>
    </table>
    <p>Total Cost: <?php echo $totalCost; ?></p>
    
    <!-- back to order page to retry order -->
    <input type="button" name="back_to_order" id="back-to-order-btn" onclick="backToOrder()"value="Back to Order"></input>
    <!-- Payment  -->
    <form method="POST" action="process_payment.php">
        <input type="hidden" name="cart" value='<?php echo json_encode($cartItems); ?>'>
        <input type="hidden" name="vendor" value='<?php echo $selectedVendor; ?>'>
        <button type="submit" name="confirm_payment" id="confirm-payment-btn" value="Confirm Payment" onclick="confirmPayment()">Confirm Payment</button>
    </form>
    <script src="payment.js"></script>
</body>
</html>
