
<?php
// Replace these with your actual database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    // $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash the password
    $password = $_POST["password"];
    $email = $_POST["email"];
    $phone_number = $_POST["phone_number"];
    $role = "customer";
    $address=$_POST["address"];

    $insertQuery = "INSERT INTO users (username, password, email, phone_number, role,address) VALUES (?, ?, ?, ?, ?,?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ssssss", $username, $password, $email, $phone_number, $role,$address);
    $stmt->execute();
    $stmt->close();
    echo "<script>
    window.location.href='../index.html';</script>";
    // Redirect back to the login page
}