<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vendorID = $_POST["vendor_id"];
    $orderDetails = $_POST["order_details"];
    $customerID = $_SESSION["user_id"]; // Assuming you store user ID in session

    // Database connection parameters
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "database";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert the order into the database
    $insertQuery = "INSERT INTO orders (customer_id, order_details, order_status) VALUES (?, ?, ?, 'Pending')";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("iis", $customerID, $vendorID, $orderDetails);
    $stmt->execute();
    $stmt->close();

    $conn->close();
    
    header("Location: customer_dashboard.php"); // Redirect back to the dashboard
    exit();
}
?>
