
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

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    // $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash the password
    $password = $_POST["password"];
    $email = $_POST["email"];
    $phone_number = $_POST["phone_number"];
    $role = "customer";
    $address=$_POST["address"];

    $checkUsernameQuery = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($checkUsernameQuery);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $errors[] = "Username already exists. Please choose a different username.";
    }
    $stmt->close();

    // Check for duplicate email
    $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $errors[] = "Email address already registered. Please use a different email address.";
    }
    $stmt->close();

    if (empty($errors)) {

    $insertQuery = "INSERT INTO users (username, password, email, phone_number, role,address) VALUES (?, ?, ?, ?, ?,?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ssssss", $username, $password, $email, $phone_number, $role,$address);
    $stmt->execute();
    $stmt->close();
    echo "<script>
    window.location.href='../login.html';</script>";
    }else{
    echo "<script>
    alert('".$errors[0]."');window.location.href='../sign_up.html';</script>";
    }
    // Redirect back to the login page
}

$conn->close();
?>