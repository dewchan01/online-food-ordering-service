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

if (isset($_POST['insert_product'])) {
    $newProductName = $_POST['new_product_name'];
    $newProductDescription = $_POST['new_product_description'];
    $newProductPrice = $_POST['new_product_price'];
    $newImageURL = $_POST['new_image_url'];
    $newProductStatus = $_POST['new_product_status'];

    $insertQuery = "INSERT INTO products (product_name, description, price, image_url, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ssdss", $newProductName, $newProductDescription, $newProductPrice, $newImageURL, $newProductStatus);
    $stmt->execute();
    $stmt->close();

    header("Location: vendor_dashboard.php"); // Redirect back to the dashboard
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Insert New Product</title>
    <link rel="stylesheet" type="text/css" href="../../styles.css">
</head>
<body>
    <h1>Insert New Product</h1>
    
    <form method="POST" action="insert_product.php">
        <label for="new_product_name">Product Name:</label>
        <input type="text" id="new_product_name" name="new_product_name" required><br>
        
        <label for="new_product_description">Product Description:</label>
        <textarea id="new_product_description" name="new_product_description" required></textarea><br>
        
        <label for="new_product_price">Product Price:</label>
        <input type="number" id="new_product_price" name="new_product_price" step="0.01" required><br>
        
        <label for="new_image_url">Image URL:</label>
        <input type="text" id="new_image_url" name="new_image_url"><br>
        
        <label for="new_product_status">Product Status:</label>
        <select id="new_product_status" name="new_product_status" required>
            <option value="available">Available</option>
            <option value="unavailable">Unavailable</option>
        </select><br>
        
        <button type="submit" name="insert_product">Insert Product</button>
    </form>
    
    <a href="vendor_dashboard.php">Back to Dashboard</a>
</body>
</html>
