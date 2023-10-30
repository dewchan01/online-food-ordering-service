<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: ../login.html");
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
    $newPassword = hash("sha256", $_POST['new_password']);
    $editedAddress = $_POST['edited_address'];
    if ($currentPassword === '') {
        echo "<script>alert('No password is input! Please try again.');
        window.location.href='edit_profile.php';</script>";
    } else if ($user['password'] !== hash('sha256', $currentPassword)) {
        echo "<script>alert('Incorrect password! Please try again.');
        window.location.href='edit_profile.php';</script>";
    } else {

        // Check for duplicate email
        $checkEmailQuery = "SELECT * FROM users WHERE email = ? AND username <> ?";
        $stmt = $conn->prepare($checkEmailQuery);
        $stmt->bind_param("ss", $editedEmail, $loggedInUsername);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors[] = "Email address already registered. Please use a different email address.";
        }

        if (empty($errors)) {
            // Perform edit for the user profile
            if ($_POST['new_password'] !== '') {
                $editQuery = "UPDATE users SET email = ?, phone_number = ?, address = ?, password = ?  WHERE username = ?";
                $stmt = $conn->prepare($editQuery);
                $stmt->bind_param("sssss", $editedEmail, $editedPhoneNumber, $editedAddress, $newPassword, $loggedInUsername);
                $stmt->execute();
                $stmt->close();
            } else {
                $editQuery = "UPDATE users SET email = ?, phone_number = ?, address = ? WHERE username = ?";
                $stmt = $conn->prepare($editQuery);
                $stmt->bind_param("ssss", $editedEmail, $editedPhoneNumber, $editedAddress, $loggedInUsername);
                $stmt->execute();
                $stmt->close();
            }

            // Redirect based on role
            if ($user['role'] === 'customer') {
                echo "<script>alert('Changes saved successfully!');
        window.location.href='order.php';</script>";
            } else if ($user['role'] === 'vendor') {
                echo "<script>alert('Changes saved successfully!');
        window.location.href='vendor/vendor_dashboard.php';</script>";
            }
        } else {
            echo "<script>
    alert('" . $errors[0] . "');window.location.href='edit_profile.php';</script>";
        }
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
        function checkUsername(input) {
            var usernameRegex = /^[a-zA-Z0-9]+$/;
            let container = document.createElement("div");
            container.className = "checkUsername";
            let p = document.createElement("p");
            p.textContent = "Username can only contain letters and numbers.";
            container.appendChild(p);

            // Check if a previous error message exists and remove it
            let existingError = document.querySelector(".checkUsername");
            if (existingError) {
                existingError.remove();
            }

            let span = document.createElement("span");
            submitButton = document.getElementsByClassName("submit-button")[0];

            if (!input.value.match(usernameRegex) || input.value === '') {
                container.appendChild(p);
                input.after(container);
                submitButton.disabled = true;
                return false;
            } else {
                submitButton.disabled = false;
                return true;
            }
        }

        // Check if the email address is valid
        function checkEmail(input) {
            var emailRegex = /^[a-zA-Z0-9._-]+@localhost$/;

            let container = document.createElement("div");
            container.className = "checkEmail";
            let p = document.createElement("p");
            p.textContent = "Please enter a valid email address.(eg: test@localhost)";
            container.appendChild(p);

            // Check if a previous error message exists and remove it
            let existingError = document.querySelector(".checkEmail");
            if (existingError) {
                existingError.remove();
            }
            current_password = document.getElementById("current_password");
            if (current_password.value === '') {
                checkPassword();
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

        // Check if the phone number contains exactly 8 digits
        function checkPhone(input) {
            var phoneNumberRegex = /^\d{8}$/;
            let container = document.createElement("div");
            container.className = "checkPhone";
            let p = document.createElement("p");
            p.textContent = "Please enter a valid 8-digit phone number.";
            container.appendChild(p);

            // Check if a previous error message exists and remove it
            let existingError = document.querySelector(".checkPhone");
            if (existingError) {
                existingError.remove();
            }
            current_password = document.getElementById("current_password");
            if (current_password.value === '') {
                checkPassword();
            }

            let span = document.createElement("span");
            submitButton = document.getElementsByClassName("submit-button")[0];

            if (!input.value.match(phoneNumberRegex)) {
                container.appendChild(p);
                input.after(container);
                submitButton.disabled = true;
                return false;
            } else {
                submitButton.disabled = false;
                return true;
            }
        }


        // Check if the address is not empty
        function checkAddress(input) {
            let container = document.createElement("div");
            container.className = "checkAddress";
            let p = document.createElement("p");
            p.textContent = "Address is required.";
            container.appendChild(p);

            // Check if a previous error message exists and remove it
            let existingError = document.querySelector(".checkAddress");
            if (existingError) {
                existingError.remove();
            }
            current_password = document.getElementById("current_password");
            if (current_password.value === '') {
                checkPassword();
            }

            let span = document.createElement("span");
            submitButton = document.getElementsByClassName("submit-button")[0];
            if (input.value.trim() === "") {
                container.appendChild(p);
                input.after(container);
                submitButton.disabled = true;
                return false;
            } else {
                submitButton.disabled = false;
                return true;
            }
        }

        function checkPassword(input) {
            var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,12}$/;
            let container = document.createElement("div");
            container.className = "checkPassword";
            let p = document.createElement("p");
            p.textContent = "Please enter a valid password between 8-12 characters, including at least one allowed special character [@$!%*?&], one capital letter, and one number.";
            container.appendChild(p);
            let existingError = document.querySelector(".checkPassword");
            if (existingError) {
                existingError.remove();
            }
            let span = document.createElement("span");
            submitButton = document.getElementsByClassName("submit-button")[0];
            new_password = document.getElementById("new_password");
            if (new_password.value !== '') {
                matchPassword(new_password);
            }
            if (!input.value.match(passwordRegex) || input.value === '') {
                container.appendChild(p);
                input.after(container);
                submitButton.disabled = true;
                return false;
            } else {
                submitButton.disabled = false;
                return true;
            }
        }

        function matchPassword(input) {
            var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,12}$/;
            let container = document.createElement("div");
            container.className = "matchPassword";
            let p = document.createElement("p");
            p.textContent = "Please enter a valid password between 8-12 characters, including at least one special character [@$!%*?&], one capital letter, and one number.";
            container.appendChild(p);

            current_password = document.getElementById("current_password");
            if (current_password.value === '') {
                p.textContent = "Please enter your current password!";
            }
            if (current_password.value === input.value) {
                p.textContent = "Please enter new password!";
            }

            // Check if a previous error message exists and remove it
            let existingError = document.querySelector(".matchPassword");
            if (existingError) {
                existingError.remove();
            }

            let span = document.createElement("span");
            submitButton = document.getElementsByClassName("submit-button")[0];

            if (!input.value.match(passwordRegex) && input.value !== '' || current_password.value === '' || current_password.value === input.value || input.value !== '' && current_password.value === '') {
                container.appendChild(p);
                input.after(container);
                submitButton.disabled = true;
                return false;
            } else {
                submitButton.disabled = false;
                return true;
            }
        }

        function validateForm() {
            checkUsername();
            checkPassword();
            checkEmail();
            checkPhone();
            checkAddress();
        }
    </script>
</head>
</head>

<body>
    <div class="nav">
        <div class="header">
            <a href="../index.php" class="headerLogo"><img src="../images/logo.png" alt="Domini's Logo" height="21" width="23">Domini's</a>
        </div>
        <div class="menu">
            <a href="order.php">MENU</a>
        </div>
        <div class="my-account">
            <div id="my-account-container" style="cursor: pointer;">
                <a><img src="../images/acc-1.png" alt="User's Account" height="37" width="34" style="padding-top: 5px;"></a>
                <a>MY ACCOUNT</a>
            </div>
            <div class="basic-buttons-container" id="my-account-nav" style="display:none;">
                <a href="edit_profile.php" class="basic-buttons" id="edit-profile"><img src="../images/acc-3.png" alt="User's Account" width="34" height="30">
                    <p style="margin:5px 0 0 8px;">Edit Profile</p>
                </a>
                <a href="customer/check_history.php" class="basic-buttons" id="order-history"><img src="../images/newspaper-regular-1.svg" alt="Order History" width="34" height="25" style="margin-top:5px;">
                    <p style="margin:5px 0 0 7px;">Order History</p>
                </a>
                <a href="logout.php" class="basic-buttons" id="log-out" onclick="return confirm('Are you sure you want to log out?')"><img src="../images/vector.svg" alt="Order History" width="34" height="20" style="margin-top:5px;">
                    <p style="margin:4px 0 0 7px;">Logout</p>
                </a>
            </div>
        </div>
    </div>
    <?php if ($user['role'] === 'customer') : ?>


    <div class="login-signup">
        <h2>My Details</h2>
        <input type="button" value="Back to Order"  id="back-to-order-btn" style="margin-right:800px;" onclick="window.location.href='customer/customer_dashboard.php'">
        <div id="user-login">
            <form method="POST" onsubmit="return validateForm()" action="edit_profile.php">
                <p class="edit-input">Username:</p>
                <input type="text" class="user-input" id="edited_username" name="edited_username" style="background:#AFAFAF" value="<?php echo $user['username']; ?>" onchange="checkUsername(this)" readonly><br>

                <p class="edit-input">Email:</p>
                <input type="email" class="user-input" id="edited_email" name="edited_email" value="<?php echo $user['email']; ?>" onchange="checkEmail(this)" required><br>

                <p class="edit-input">Phone Number:</p>
                <input type="tel" class="user-input" id="edited_phone_number" name="edited_phone_number" value="<?php echo $user['phone_number']; ?>" onchange="checkPhone(this)" required><br>

                <p class="edit-input">New Address:</p>
                <input type="text" name="edited_address" class="user-input" id="edited_address" value="<?php echo $user['address']; ?>" required onchange="checkAddress(this)"><br>

                <p class="edit-input">*Current Password:</p>
                <input type="password" class="user-input" id="current_password" name="current_password" onchange="checkPassword(this)"><br>

                <p class="edit-input">New Password:</p>
                <input type="password" class="user-input" id="new_password" name="new_password" onchange="matchPassword(this)"><br>

                <p style="color:red;"> *Current Password is required to edit details</p>

                <input type="submit" name="edit_profile_submit" class="submit-button" onclick="return confirm('Are you sure you want to make changes?')" value="Save Changes" disabled>
            </form>
        </div>

    </div>

    <?php elseif ($user['role'] === 'vendor') : ?>
        <div class="login-signup">
        <h2>My Details</h2>
        <input type="button" value="Back" id="back" onclick="window.location.href='vendor/vendor_dashboard.php'">
        <div id="user-login">
            <form method="POST" onsubmit="return validateForm()" action="edit_profile.php">
                <p class="edit-input">Username:</p>
                <input type="text" class="user-input" id="edited_username" name="edited_username" style="background:#AFAFAF" value="<?php echo $user['username']; ?>" onchange="checkUsername(this)" readonly><br>

                <p class="edit-input">Email:</p>
                <input type="email" class="user-input" id="edited_email" name="edited_email" value="<?php echo $user['email']; ?>" onchange="checkEmail(this)" required><br>

                <p class="edit-input">Phone Number:</p>
                <input type="tel" class="user-input" id="edited_phone_number" name="edited_phone_number" value="<?php echo $user['phone_number']; ?>" onchange="checkPhone(this)" required><br>

                <p class="edit-input">New Address:</p>
                <input type="text" name="edited_address" class="user-input" id="edited_address" value="<?php echo $user['address']; ?>" required onchange="checkAddress(this)"><br>

                <p class="edit-input">*Current Password:</p>
                <input type="password" class="user-input" id="current_password" name="current_password" onchange="checkPassword(this)"><br>

                <p class="edit-input">New Password:</p>
                <input type="password" class="user-input" id="new_password" name="new_password" onchange="matchPassword(this)"><br>

                <p style="color:red;"> *Current Password is required to edit details</p>

                <input type="submit" name="edit_profile_submit" class="submit-button" onclick="return confirm('Are you sure you want to make changes?')" value="Save Changes" disabled>
            </form>
        </div>

    </div>
    <?php else : ?>
        <p>User profile not found.</p>
    <?php endif; ?>

    <script type="text/javascript" src="../js/script.js"></script>
</body>

</html>