<?php
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";

$selectedVendor = $_GET['vendor']; 

$dbname = $selectedVendor;
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT product_id, product_name, price, image_url, description FROM products WHERE status = 'available' ";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

header('Content-Type: application/json');
echo json_encode($products);

$conn->close();
