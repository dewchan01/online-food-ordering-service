<?php
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

// Prepare a statement to retrieve product details and prices from the database
$sql = "SELECT product_id, product_name, price FROM products";
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

    <!-- Payment  -->
    <button id="confirm-payment-btn">Confirm Payment</button>
    <div id="loading-overlay">
        <div class="loading-spinner"></div>
    </div>

    <script src="payment.js"></script>

</body>
</html>
