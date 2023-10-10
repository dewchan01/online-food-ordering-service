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
    $checkEmail = $_POST["email"];

     // Generate a temporary password
    $newPassword = generateRandomPassword();


    // Fetch the user details from the database
    $fetchUserQuery = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($fetchUserQuery);
    $stmt->bind_param("s",$checkEmail);
    $stmt->execute();
    $result=$stmt->get_result();
    if($result->num_rows > 0)
    {
        $user=$result->fetch_assoc();
    }
    else{
        echo "<script>alert('Email does not registered! Please try again.');
        window.location.href='forget_password.php';</script>";
    }
    $stmt->close();

    $to = $checkEmail;
    $subject = "Reset New Password";
    $message = "Your temporary password is: $newPassword";
    $headers = "From: vendora@localhost";

    mail($to, $subject, $message, $headers);
    // Reset user's password
    $editQuery = "UPDATE users SET password = ? WHERE email = ? ";
    $stmt = $conn->prepare($editQuery);
    $stmt->bind_param("ss",$newPassword,$checkEmail);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('The temporary password has been sent to your email address. Please check your email.');
    window.location.href='../login.html';</script>";
}

// Function to generate a random temporary password
function generateRandomPassword($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+~`|}{[]\:;?><,.-=';
    $randomPassword = '';
    $characterCount = strlen($characters);
    
    for ($i = 0; $i < $length; $i++) {
        $randomIndex = rand(0, $characterCount - 1);
        $randomPassword .= $characters[$randomIndex];
    }
    
    return $randomPassword;
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
    <div class="nav" style="justify-content: flex-start;">
        <div class="header">
            <a href="../index.html" class="headerLogo"><img src="../images/logo.png" alt="Domini's Logo" height="21" width="23">Domini's</a>
        </div>
    </div>
    <div class="login-signup">
        <h2>Reset Password</h2>
        <div id="user-login">
            <h3>Please enter your email address and we will email you instructions to reset your password.</h3>
            <form method="post">
                <input type="email" name="email" placeholder="Email" class="user-input" requried><br>
                <button type="submit" class="submit-button">Submit</button>

            </form>
            <input type="button" value="Back" id="back" onclick="window.location.href='../index.html'">
        </div>
        
    </div>
    
</body>
</html>