<?php
// Assume you have a database connection established
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";

$selectedVendor = $_GET['vendor']; // Get the selected vendor from the query parameter

$dbname = $selectedVendor;
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products from the database based on the selected vendor
// $sql = "SELECT product_id, product_name, price, image_url FROM products WHERE vendor = ?";
$sql = "SELECT product_id, product_name, price, image_url FROM products";
$stmt = $conn->prepare($sql);
// $stmt->bind_param("s", $selectedVendor);
$stmt->execute();
$result = $stmt->get_result();
$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// Return products as JSON response
header('Content-Type: application/json');
echo json_encode($products);

$conn->close();

?>
