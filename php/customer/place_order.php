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

$customerUsername = $_SESSION["username"];
// Replace this with actual database queries to fetch customer data
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the customer's address from the database
$address = "";
$sql = "SELECT address FROM users WHERE username = '$customerUsername'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $address = $row["address"];
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" type="text/css" href="../../styles.css">
</head>
<body>
    <div class="nav" style="justify-content: flex-start;">
        <div class="header">
            <a href="../../index.html" class="headerLogo"><img src="../../images/logo.png" alt="Domini's Logo" height="21" width="23">Domini's</a>
        </div>
    </div>
    <div id="checkout-page">
        <div id="checkout-details-container">
            <div id="address">
                <div id="van2-container" style="margin-top:20px;">    
                    <img src="../../images/van-2.png" height="60%" width="60%"/>
                </div>
                <div >
                    <h3 style="margin-bottom: 0;">Delivery Now</h3>
                    <p style="margin-top: 0;"><?php echo $address; ?></p>
                </div>
            </div>
            <h2>Order Details</h2>

            <?php
            $totalCost = 0;
            $totalQuantity = 0;
            foreach ($cartItems as $productName => $quantity) {
                if (isset($products[$productName])) {
                    $product = $products[$productName];
                    $productID = $product['product_id'];
                    $price = $product['price'];
                    $totalPrice = $price * $quantity;
                    $deliveryFee = 5;
                    $totalCost += $totalPrice;
                    $totalQuantity += $quantity;
                    echo "<div class='cart-item'>";
                    echo "<div class='cart-item-container'>$quantity x $productName";
                    echo "<p style='font-weight:700;margin:0;'>$$price</p> </div>";
                    echo "</div>";
                }
            }
            ?>
            <div>
        
            <!-- <div id="cart-dropdown" class="cart-dropdown">
                <p>Delivery Service Fee: $5.00 <br> Total Price: $31.98
                Total Price:$ <?php echo $totalCost+$deliveryFee; ?></p>
            </div> -->
            <!-- Cart items will be dynamically populated here -->
            </div>
            <div class="cart-item-container"  style='font-weight:700;'>
                <p>Delivery Fee:</p>
                <p>$<?php echo $deliveryFee; ?></p>
            </div>
            <div class="cart-item-container" style='font-weight:700;'>    
                <p>Total Cost: </p>
                <p>$<?php echo $totalCost+$deliveryFee; ?></p>
            </div>
            <!-- back to order page to retry order -->
            <!-- Payment  -->
            <form method="POST" action="process_payment.php">
            <input type="hidden" name="cart" value='<?php echo json_encode($cartItems); ?>'>
            <input type="hidden" name="vendor" value='<?php echo $selectedVendor; ?>'>
            <div id="delivery-instruction">
            <h3>Delivery Instruction</h3>
            <textarea name="delivery-instruction" id="delivery-instruction-text" cols="30" rows="10" style="resize:none;width:400px;"  placeholder="Fill in special requests here. We will accommodate as best as we could. Timed orders and edits have to be made during the ordering process."></textarea>
            </div>
            <button type="submit" name="confirm_payment" class="confirm-btn" id="confirm-payment-btn" value="Confirm Payment" style="margin-top:10px;width:400px;" onclick="confirmPayment()"><?php echo $totalQuantity ?> items | Total Price: $<?php echo $totalCost+$deliveryFee; ?> | Confirm Payment</button>
            </form>
        </div>
        <input type="button" name="back_to_order" id="back-to-order-btn" onclick="backToOrder()"value="Menu">
    </div>
    <script src="../../js/payment.js"></script>

</body>
</html>
