<?php
session_start();

$cartItemsJSON = $_POST['cart'];
$selectedVendor = $_POST['vendor'];

$cartItems = json_decode($cartItemsJSON, true);

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
    $products[$row['product_name']] = $row;
}
$stmt->close();

foreach ($cartItems as $productName => $quantity) {
    if (isset($products[$productName])) {
        $product = $products[$productName];
        $productID = $product['product_id'];
        $imageURL = $product['image_url'];
        $description = $product['description'];
        $price = $product['price'];
        $totalPrice = $price * $quantity;

        // Insert order details into the orders table
        $insertQuery = "INSERT INTO orders (time, image_url, username, product_id, product_name, description, quantity, total_price, order_status) 
                        VALUES (CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?, 'Order Confirmed')";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ssisssd", $imageURL, $customerUsername, $productID, $productName, $description, $quantity, $totalPrice);
        $stmt->execute();
        $stmt->close();
    }
}

$conn->close();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout Confirmation</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        /* Add your confirmation animation styles here */
        @keyframes successAnimation {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .confirmation-container {
            text-align: center;
            animation: successAnimation 0.5s ease-in-out;
        }
    </style>
</head>
<body>
    <div class="confirmation-container">
        <h2>Payment Successful!</h2>
        <p>Your order has been confirmed.</p>
        <a href="customer_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>