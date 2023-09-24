<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: user_login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['username'])) {
    $loggedInUsername = $_SESSION['username'];

    // Fetch the user details from the database
    $fetchUserQuery = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($fetchUserQuery);
    $stmt->bind_param("s", $loggedInUsername);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}

$errors = [];

if (isset($_POST['edit_profile_submit'])) {
    
    // Get the edited user details from the form
    $editedEmail = $_POST['edited_email'];
    $editedPhoneNumber = $_POST['edited_phone_number'];
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $editedAddress = $_POST['edited_address'];

    if($user['password']!=$currentPassword)
    {
        echo "<script>alert('Incorrect password! Please try again.');
        window.location.href='edit_profile.php';</script>";
    }

    // Check for duplicate email
    $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $editedEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $errors[] = "Email address already registered. Please use a different email address.";
    }
    $stmt->close();

    if (empty($errors)) {

    // Perform edit for the user profile
    $editQuery = "UPDATE users SET email = ?, phone_number = ?, address = ?, password = ?  WHERE username = ?";
    $stmt = $conn->prepare($editQuery);
    $stmt->bind_param("sssss", $editedEmail, $editedPhoneNumber, $editedAddress,$newPassword, $loggedInUsername);
    $stmt->execute();
    $stmt->close();
    // Redirect based on role
    if ($user['role'] === 'customer') {
        echo "<script>alert('Changes saved successfully!');
        window.location.href='order.php';</script>";
    } else if ($user['role'] === 'vendor') {
        echo "<script>alert('Changes saved successfully!');
        window.location.href='vendor/vendor_dashboard.php';</script>";
    }
}else{
    echo "<script>
    alert('".$errors[0]."');window.location.href='edit_profile.php';</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
    <script type="text/javascript">
    function validateForm() {
        var username = document.getElementsByName("edited_username")[0].value;
        var password = document.getElementById("current_password").value;
        var confirmPassword = document.getElementById("new_password").value;
        var email = document.getElementsByName("edited_email")[0].value;
        var phoneNumber = document.getElementsByName("edited_phone_number")[0].value;
        var address = document.getElementById("edited_address").value;

        // Regular expressions for validation
        var usernameRegex = /^[a-zA-Z0-9]+$/;
        var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]/;
        var phoneNumberRegex = /^\d{8}$/;
        var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,12}$/;

        // Check if the username contains only letters and numbers
        if (!username.match(usernameRegex)) {
            alert("Username can only contain letters and numbers.");
            return false;
        }
        if (!password.match(passwordRegex)) {
            alert("Please enter a valid password between 8-12 characters, including at least one special character, one capital letter, and one number.");
            return false;
        }
        // Check if the password and confirm password match
        if (password !== confirmPassword) {
            alert("Passwords do not match.");
            return false;
        }

        // Check if the email address is valid
        if (!email.match(emailRegex)) {
            alert("Please enter a valid email address.");
            return false;
        }

        // Check if the phone number contains exactly 8 digits
        if (!phoneNumber.match(phoneNumberRegex)) {
            alert("Please enter a valid 8-digit phone number.");
            return false;
        }

        // Check if the address is not empty
        if (address.trim() === "") {
            alert("Address is required.");
            return false;
        }

        return true;
    }
    </script>
</head>
</head>
<body>
    <h1>Edit Profile</h1>
    <?php if ($user['role']==='customer'): ?>
    <form method="POST" onsubmit="return validateForm()" action="edit_profile.php">
        <label for="edited_username">Username:</label>
        <input type="text" id="edited_username" name="edited_username" value="<?php echo $user['username']; ?>" readonly><br>
        
        <label for="edited_email">Email:</label>
        <input type="email" id="edited_email" name="edited_email" value="<?php echo $user['email']; ?>" required><br>
        
        <label for="edited_phone_number">Phone Number:</label>
        <input type="tel" id="edited_phone_number" name="edited_phone_number" value="<?php echo $user['phone_number']; ?>" required><br>
        
        <label for="edited_address" style="vertical-align:top">New Address:</label>
        <textarea name="edited_address" id ="edited_address" required><?php echo $user['address']; ?></textarea><br>

        <label for="_password">Current Password:</label>
        <input type="password" id="current_password" name="current_password" required><br>

        <label for="_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br>
        
        <button type="submit" name="edit_profile_submit">Save Changes</button>
        <a href="order.php">Back to Order</a>
    </form>

    <?php elseif ($user['role']==='vendor'): ?>
    <form method="POST" action="edit_profile.php">
        <label for="edited_username">Username:</label>
        <input type="text" id="edited_username" name="edited_username" value="<?php echo $user['username']; ?>" readonly><br>
        
        <label for="edited_email">Email:</label>
        <input type="email" id="edited_email" name="edited_email" value="<?php echo $user['email']; ?>" required><br>
        
        <label for="edited_phone_number">Phone Number:</label>
        <input type="tel" id="edited_phone_number" name="edited_phone_number" value="<?php echo $user['phone_number']; ?>" required><br>
        
        <label for="current_password">Current Password:</label>
        <input type="password" id="current_password" name="current_password" required><br>
        <label for="_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br>
        <br>
        <button type="submit" name="edit_profile_submit">Save Changes</button>
        <a href='vendor/vendor_dashboard.php';>Back to Dashboard</a>
    </form>
    <?php else: ?>
        <p>User profile not found.</p>
    <?php endif; ?>
    

</body>
</html>
