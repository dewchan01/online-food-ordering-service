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

if (isset($_POST['edit_profile_submit'])) {
    
    // Get the edited user details from the form
    $editedEmail = $_POST['edited_email'];
    $editedPhoneNumber = $_POST['edited_phone_number'];
    $currentPassword = $_POST['current_password'];
    $editedAddress = $_POST['edited_address'];
    if($user['password']!=$currentPassword)
    {
        echo "<script>alert('Incorrect password! Please try again.');
        window.location.href='edit_profile.php';</script>";
    }
    // Perform edit for the user profile
    $editQuery = "UPDATE users SET email = ?, phone_number = ?, address = ? WHERE username = ?";
    $stmt = $conn->prepare($editQuery);
    $stmt->bind_param("ssss", $editedEmail, $editedPhoneNumber, $editedAddress, $loggedInUsername);
    $stmt->execute();
    $stmt->close();
    // Redirect based on role
    if ($user['role'] === 'customer') {
        echo "<script>alert('Changes saved successfully!');
        window.location.href='customer/customer_dashboard.php';</script>";
    } elseif ($user['role'] === 'vendor') {
        echo "<script>alert('Changes saved successfully!');
        window.location.href='vendor/vendor_dashboard.php';</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>
    <h1>Edit Profile</h1>
    <?php if ($user['role']==='customer'): ?>
    <form method="POST" action="edit_profile.php">
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
        
        <button type="submit" name="edit_profile_submit">Save Changes</button>
        <a href="../order.php">Back to Order</a>
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
        <br>
        <button type="submit" name="edit_profile_submit">Save Changes</button>
        <a href='vendor/vendor_dashboard.php';>Back to Dashboard</a>
    </form>
    <?php else: ?>
        <p>User profile not found.</p>
    <?php endif; ?>
    

</body>
</html>
