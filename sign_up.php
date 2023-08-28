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
    $role = $_POST["role"];

    $insertQuery = "INSERT INTO users (username, password, email, phone_number, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("sssss", $username, $password, $email, $phone_number, $role);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Registration successful!');
    window.location.href='index.html';</script>";
    // Redirect back to the login page
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Sign Up</h1>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="tel" name="phone_number" placeholder="Phone Number" required>
        <select name="role" required>
            <option value="customer">Customer</option>
            <option value="vendor">Vendor</option>
        </select>
        <button type="submit">Sign Up</button>
    </form>
    <a href="index.html">Back to Login</a>
</body>
</html>
