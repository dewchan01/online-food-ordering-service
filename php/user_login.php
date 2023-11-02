<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = hash('sha256',$_POST["password"]);

    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "database";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "SELECT role FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $stmt->bind_result($role);
    $stmt->fetch();
    $stmt->close();

    if ($role) {
        session_start();
        $_SESSION["username"] = $username;


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

    $conn->close();
}
