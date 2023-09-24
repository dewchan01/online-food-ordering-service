<?php
// Handle user login and authentication
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Database connection parameters
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "database";

    // Create a database connection
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    // Check for connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Perform user authentication
    $query = "SELECT role FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $stmt->bind_result($role);
    $stmt->fetch();
    $stmt->close();

    if ($role) {
        // Start a session and store user information
        session_start();
        $_SESSION["username"] = $username;


        // Redirect to appropriate user dashboard
        if ($role === "customer") {
            header("Location: order.php");
            exit();
        } elseif ($role === "vendor") {
            header("Location: vendor/vendor_dashboard.php");
            exit();
        }
    } else {
        echo "<script>alert('Invalid username or password');
        window.location.href='../login.html';</script>";
        exit();
    }

    // Close the database connection
    $conn->close();
}
?>
