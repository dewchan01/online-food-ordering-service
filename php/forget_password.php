<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "database";

$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $checkUsername = $_POST["username"];
    $checkEmail = $_POST["email"];
    $checkPhoneNumber = $_POST["phone_number"];
    $newPassword = $_POST["password"];

    // Fetch the user details from the database
    $fetchUserQuery = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($fetchUserQuery);
    $stmt->bind_param("s",$checkUsername);
    $stmt->execute();
    $result=$stmt->get_result();
    if($result->num_rows > 0)
    {
        $user=$result->fetch_assoc();
    }
    else{
        echo "<script>alert('Incorrect username! Please try again.');
        window.location.href='forget_password.php';</script>";
    }
    $stmt->close();

    // Checking if the details are correct
    if( $user["email"]!=$checkEmail || $user["phone_number"]!=$checkPhoneNumber)
    {
        echo "<script>alert('Incorrect details! Please try again.');
        window.location.href='forget_password.php';</script>";
    }
    // Reset user's password
    $editQuery = "UPDATE users SET password = ? WHERE username = ? ";
    $stmt = $conn->prepare($editQuery);
    $stmt->bind_param("ss",$newPassword,$checkUsername);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('The password reset was successful!');
    window.location.href='../index.html';</script>";
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>
    <h1>Reset Password</h1>
    <h2>Details are required to be filled correctly to reset</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="email" name="email" placeholder="Email" requried><br>
        <input type="tel" name="phone_number" placeholder="Phone Number" required><br>
        <input type="password" name="password" placeholder="New Password" required><br>
        <button type="submit">Submit</button>

    </form>
    <a href="../index.html">Back to Login</a>
</body>
</html>