<?php
    // Process the order data, update status, and send email
    // You'll need to connect to a database and handle the data accordingly
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$selectedVendor = $_GET['vendor']; // Retrieve the selected vendor from URL parameters

$conn = new mysqli($servername, $dbusername, $dbpassword, $selectedVendor);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user ID and username from the session or wherever it's stored
$customerUsername = $_SESSION["username"]; // Assuming you store username in session

// Retrieve and decode the cart items JSON from URL parameters
$cartItemsJSON = $_GET['cart'];
$cartItems = json_decode($cartItemsJSON, true);

// Process the cart items and insert them into the orders table
foreach ($cartItems as $productID => $quantity) {
    // Fetch product details from the products table (adjust your SQL query)
    $sql = "SELECT image_url, product_id, price FROM products WHERE product_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    
    // Insert order details into the orders table
    $productName = $product['product_name'];
    $price = $product['price'];
    $totalPrice = $price * $quantity;
    
    $insertQuery = "INSERT INTO orders (image_url, username, product_id, product_name, quantity, total_price, order_status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'Payment Received')";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ssiiiii", $customerID, $customerUsername, $vendorID, $productID, $productName, $quantity, $totalPrice);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

?>
