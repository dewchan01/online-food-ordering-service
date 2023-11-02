<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: ../user_login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = $_SESSION["username"];

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['insert_product'])) {
    $newProductName = $_POST['new_product_name'];
    $newProductDescription = $_POST['new_product_description'];
    $newProductPrice = $_POST['new_product_price'];
    $newProductStatus = $_POST['new_product_status'];
    
    $target_dir = '../../images/';
    $target_file = $target_dir . basename($_FILES["new_image_url"]["name"]);
    $newImageURL = '../images/'.basename($_FILES["new_image_url"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
      $check = getimagesize($_FILES["new_image_url"]["tmp_name"]);
      if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
      } else {
        echo "File is not an image.";
        $uploadOk = 0;
      }
    }
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
      // if everything is ok, try to upload file
      } else {
        if (move_uploaded_file($_FILES["new_image_url"]["tmp_name"], $target_file)) {
          echo "The file ". htmlspecialchars( basename( $_FILES["new_image_url"]["name"])). " has been uploaded.";
        } else {
          echo "Sorry, there was an error uploading your file.";
        }
      }

    $insertQuery = "INSERT INTO products (product_name, description, price, image_url, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ssdss", $newProductName, $newProductDescription, $newProductPrice, $newImageURL, $newProductStatus);
    $stmt->execute();
    $stmt->close();

    header("Location: vendor_dashboard.php"); // Redirect back to the dashboard
}

$conn->close();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Insert New Product</title>
    <link rel="stylesheet" type="text/css" href="../../styles.css">
</head>

<body>
    <div class="nav">
        <div class="header">
            <a href="vendor_dashboard.php" class="headerLogo"><img src="../../images/logo.png" alt="Domini's Logo" height="21" width="23">Domini's</a>
        </div>
        <div class="my-account">
            <div id="my-account-container" style="cursor: pointer;">
                <a><img src="../../images/acc-1.png" alt="User's Account" height="37" width="34" style="padding-top: 5px;"></a>
                <a>MY ACCOUNT</a>
            </div>
            <div class="basic-buttons-container" id="my-account-nav">
                <a href="../edit_profile.php" class="basic-buttons" id="edit-profile"><img src="../../images/acc-3.png" alt="User's Account" width= "34"
height= "30"><p style="margin:5px 0 0 8px;">Edit Profile</p></a>
                <a href="../logout.php" class="basic-buttons" id="log-out" onclick="return confirm('Are you sure you want to log out?')"><img src="../../images/vector.svg" alt="Order History" width= "34"
height= "20"style="margin-top:5px;" ><p style="margin:4px 0 0 7px;">Logout</p></a>
            </div> 
        </div>
    </div>
    <h1 style="text-align:center;">Insert New Product</h1>
    
    <div class="login-signup">
      <a href="vendor_dashboard.php" id="back">Back</a>
      <div id="user-login" style="text-align:left;">
        <form method="POST" action="insert_product.php" enctype="multipart/form-data">
            <label for="new_product_name" class="user-input">Product Name:</label>
            <input type="text" id="new_product_name" class="user-input" name="new_product_name" style="margin-bottom:25px;" required><br>

            <label for="new_product_description" class="user-input" style="margin: 10px 0 10px 0;">Product Description:</label>
            <input type="text" id="new_product_description" class="user-input" name="new_product_description" style="margin-bottom:25px;" required></textarea><br>

            <label for="new_product_price" class="user-input" style="margin: 10px 0 10px 0;">Product Price:</label>
            <input type="number" id="new_product_price" class="user-input" name="new_product_price" step="0.01" style="margin-bottom:25px;" required><br>

            <label for="new_image_url" class="user-input" style="margin-right:20px;">Image:</label>
            <input type="file" id="new_image_url" name="new_image_url" style="margin-bottom:25px;"><br>

            <label for="new_product_status" class="user-input">Product Status:</label>
            <select id="new_product_status" name="new_product_status" class="user-input" required>
                <option value="available">Available</option>
                <option value="unavailable">Unavailable</option>
            </select><br>

            <input type="submit" name="insert_product" class="insert-order-btn" value="Insert Product">
        </form>
      </div>
      
</div>
<script src="../../js/script.js"></script>
</body>

</html>