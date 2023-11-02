<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $checkEmail = $_POST["email"];
    $password = generateRandomPassword();
    $newPassword = hash('sha256', $password);


    $fetchUserQuery = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($fetchUserQuery);
    $stmt->bind_param("s", $checkEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "<script>alert('Email does not registered! Please try again.');
        window.location.href='forget_password.php';</script>";
    }
    $stmt->close();

    $to = $checkEmail;
    $subject = "Domini's Pizza House - Reset New Password";
    $message = "<html><body>";
    $message .= "<h1>Your password has been reset!</h1>";
    $message .= "<p><strong>Don't expose your password to others!</strong><br><br>Your temporary password is: <strong>$password</strong></p>";
    $message .= "</body></html>";
    $headers = "From: vendora@localhost\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    mail($to, $subject, $message, $headers);
    $editQuery = "UPDATE users SET password = ? WHERE email = ? ";
    $stmt = $conn->prepare($editQuery);
    $stmt->bind_param("ss", $newPassword, $checkEmail);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('The temporary password has been sent to your email address. Please check your email.');
    window.location.href='../login.html';</script>";
}

function generateRandomPassword()
{
    $lowercase = 'abcdefghijklmnopqrstuvwxyz';
    $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $digits = '0123456789';
    $specialChars = '@$!%*?&';

    $password = $lowercase[rand(0, strlen($lowercase) - 1)];
    $password .= $uppercase[rand(0, strlen($uppercase) - 1)];
    $password .= $digits[rand(0, strlen($digits) - 1)];
    $password .= $specialChars[rand(0, strlen($specialChars) - 1)];

    $remainingLength = rand(4, 8);

    $allCharacters = $lowercase . $uppercase . $digits . $specialChars;

    for ($i = 0; $i < $remainingLength; $i++) {
        $randomIndex = rand(0, strlen($allCharacters) - 1);
        $password .= $allCharacters[$randomIndex];
    }

    $password = str_shuffle($password);

    return $password;
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Reset Password</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
    <script type="text/javascript">
        function checkEmail(input) {
            var emailRegex = /^[a-zA-Z0-9._-]+@localhost$/;

            let container = document.createElement("div");
            container.className = "checkEmail";
            let p = document.createElement("p");
            p.textContent = "Please enter a valid email address.(eg: test@localhost)";
            container.appendChild(p);

            let existingError = document.querySelector(".checkEmail");
            if (existingError) {
                existingError.remove();
            }

            let span = document.createElement("span");
            submitButton = document.getElementsByClassName("submit-button")[0];

            if (!input.value.match(emailRegex)) {
                container.appendChild(p);
                input.after(container);
                submitButton.disabled = true;
                return false;
            } else {
                submitButton.disabled = false;
                return true;
            }
        }
    </script>
</head>

<body>
    <div class="nav" style="justify-content: flex-start;">
        <div class="header">
            <a href="../index.php" class="headerLogo"><img src="../images/logo.png" alt="Domini's Logo" height="21" width="23">Domini's</a>
        </div>
    </div>
    <div class="login-signup">
        <h2>Reset Password</h2>
        <div id="user-login">
            <h3>Please enter your email address and we will email you instructions to reset your password.</h3>
            <form method="post">
                <input type="email" name="email" placeholder="Email" class="user-input" required onchange="checkEmail(this)"><br>
                <button type="submit" class="submit-button" style="margin-top:25px;" onclick="return confirm('Are you sure is this your email address?')">Submit</button>
            </form>
            <input type="button" value="Back" id="back" onclick="window.location.href='../login.html'">
        </div>
    </div>
</body>

</html>