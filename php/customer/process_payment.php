<?php
session_start();

$cartItemsJSON = $_POST['cart'];
$selectedVendor = $_POST['vendor'];
$deliveryInstruction = $_POST['delivery-instruction'];

$cartItems = json_decode($cartItemsJSON, true);
$customerUsername = $_SESSION["username"];

$userServerName = "localhost";
$userDbUserName = "root";
$userDbPassword ="";
$userDbName = "database";

$userConn = new mysqli($userServerName,$userDbUserName,$userDbPassword,$userDbName);
if($userConn->connect_error)
{
    die("Database connection failed: ". $userConn->connect_error);
}
$addressQuery = "SELECT address FROM users WHERE username = ?";
$addressStmt = $userConn->prepare($addressQuery);
$addressStmt->bind_param("s", $customerUsername);
$addressStmt->execute();
$addressResult = $addressStmt->get_result();

if ($addressResult->num_rows > 0) {
    $addressRow = $addressResult->fetch_assoc();
    $address = $addressRow['address'];
} else {
    // Handle the case where the address is not found (you can set a default address or display an error)
    $address = "Address Not Found";
}


$emailQuery = "SELECT email FROM users WHERE username = ?";
$emailStmt = $userConn->prepare($emailQuery);
$emailStmt->bind_param("s", $customerUsername);
$emailStmt->execute();
$customerEmail = $emailStmt->get_result()->fetch_assoc()['email'];

$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = $selectedVendor;

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


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
        $insertQuery = "INSERT INTO orders (time, image_url, username, product_id, product_name, description, quantity, total_price, address, order_status,delivery_instruction) 
        VALUES (CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?,?, 'Order Confirmed',?)";

        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ssisssdss", $imageURL, $customerUsername, $productID, $productName, $description, $quantity, $totalPrice,$address,$deliveryInstruction);
        $stmt->execute();
        $stmt->close();
    }
    
}
// Send an email to the customer
// Create an HTML email message
$to = $customerEmail;
$subject = "Domini's Pizza House- Order Receipt";
$message = "<html><body>";
$message .= "<h1>Your Pizza Order Confirmation</h1>";
$message .= "<p>Thank you for placing your order with us. Here are the details of your order:</p>";
$message .= "<table border='1'>";
$message .= "<tr><th>Product Name</th><th>Quantity</th><th>Total Price</th></tr>";
$totalPrice=0;
// Loop through cart items to add them to the table
foreach ($cartItems as $productName => $quantity) {
    if (isset($products[$productName])) {
        $product = $products[$productName];
        $price = $product['price'];
        $totalPrice += $price * $quantity;

        $message .= "<tr>";
        $message .= "<td>$productName</td>";
        $message .= "<td>$quantity</td>";
        $message .= "<td>$$price</td>";
        $message .= "</tr>";
    }
}
$totalCost = $totalPrice+5;
date_default_timezone_set('Asia/Singapore');   
$time = date("Y-m-d H:i:s");
$message .= "</table>";
$message .= "<p>Delivery Address: $address</p>";
$message .= "<p>Delivery Instruction: $deliveryInstruction</p>";
$message .= "<p>Order Time: $time</p>"; 
$message .= "<p>Total Cost (including delivery fee $5.00): $$totalCost</p>";
$message .= "</body></html>";

// Set the email content type to HTML
$headers = "From: vendora@localhost\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

// Send the HTML email
mail($to, $subject, $message, $headers);
$addressStmt->close();
$emailStmt->close();
$userConn->close();
$conn->close();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout Confirmation</title>
    <link rel="stylesheet" type="text/css" href="../../styles.css">
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
        <p>Please check your email for the receipt.</p>
        <a href="customer_dashboard.php">Back to Menu</a>
    </div>
</body>
</html>