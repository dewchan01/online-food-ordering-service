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

if (isset($_GET['product_id'])) {
  $productId = $_GET['product_id'];

  $fetchProductQuery = "SELECT * FROM products WHERE product_id = ?";
  $stmt = $conn->prepare($fetchProductQuery);
  $stmt->bind_param("i", $productId);
  $stmt->execute();
  $result = $stmt->get_result();
  $product = $result->fetch_assoc();
  $stmt->close();
}

if (isset($_POST['edit_product_submit'])) {
  $editedProductName = $_POST['edited_product_name'];
  $editedProductDescription = $_POST['edited_product_description'];
  $editedProductPrice = $_POST['edited_product_price'];
  $editedProductStatus = $_POST['edited_product_status'];

  $target_dir = '../../images/';
  $target_file = $target_dir . basename($_FILES["edited_image_url"]["name"]);
  $editedImageURL =  $product['image_url'];

  if ($target_file !== '../../images/') {
    $editedImageURL = '../images/' . basename($_FILES["edited_image_url"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    if (isset($_POST["submit"])) {
      $check = getimagesize($_FILES["edited_image_url"]["tmp_name"]);
      if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
      } else {
        echo "File is not an image.";
        $uploadOk = 0;
      }
    }
    if ($uploadOk == 0) {
      echo "Sorry, your file was not uploaded.";
    } else {
      if (move_uploaded_file($_FILES["edited_image_url"]["tmp_name"], $target_file)) {
        echo "The file " . htmlspecialchars(basename($_FILES["edited_image_url"]["name"])) . " has been uploaded.";
      } else {
        echo "Sorry, there was an error uploading your file.";
      }
      $filename = '../' . $product['image_url'];
      if (file_exists($filename)) {
        unlink($filename);
        echo 'File ' . $filename . ' has been deleted';
      } else {
        echo 'Could not delete ' . $filename . ', file does not exist';
      }
    }
  }

  $editQuery = "UPDATE products SET product_name = ?, description = ?, price = ?, image_url = ?, status = ? WHERE product_id = ?";
  $stmt = $conn->prepare($editQuery);
  $stmt->bind_param("ssdssi", $editedProductName, $editedProductDescription, $editedProductPrice, $editedImageURL, $editedProductStatus, $productId);
  $stmt->execute();
  $stmt->close();

  header("Location: vendor_dashboard.php");
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
  <title>Edit Product</title>
  <link rel="stylesheet" type="text/css" href="../../styles.css">
</head>

<body>
  <div class="nav">
    <div class="header">
      <a href="vendor_dashboard.php" class="headerLogo"><img src="../../images/logo.png" alt="Domini's Logo" height="21" width="23">Domini's</a>
    </div>
    <div class="my-account">
      <div id="my-account-container" style="cursor: pointer;">
        <a><svg width="26px" height="24px" viewBox="0 0 38 36" style="padding-top:7px;">
            <g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
              <g id="My-Account" fill="#202020">
                <path d="M18.82 35.964c9.96 0 18.248-8.042 18.248-17.732S28.758.5 18.798.5C8.862.5.596 8.542.596 18.232s8.288 17.732 18.225 17.732zm0-11.821c-5.17 0-9.168 1.691-11.042 3.669a14.102 14.102 0 0 1-3.704-9.58c0-7.998 6.526-14.392 14.724-14.392 8.22 0 14.792 6.394 14.815 14.392 0 3.691-1.4 7.053-3.704 9.58-1.874-1.978-5.894-3.67-11.088-3.67zm0-2.9c3.41.043 6.098-2.791 6.098-6.505 0-3.493-2.687-6.372-6.097-6.372-3.388 0-6.098 2.879-6.075 6.372.022 3.714 2.665 6.46 6.075 6.504z"></path>
              </g>
            </g>
          </svg></a>
        <a>MY ACCOUNT</a>
      </div>
      <div class="basic-buttons-container" id="my-account-nav">
        <a href="../edit_profile.php" class="basic-buttons" id="edit-profile"><img src="../../images/acc-3.png" alt="User's Account" width="34" height="30">
          <p style="margin:5px 0 0 8px;">Edit Profile</p>
        </a>
        <a href="../logout.php" class="basic-buttons" id="log-out" onclick="return confirm('Are you sure you want to log out?')"><img src="../../images/vector.svg" alt="Log Out" width="34" height="20" style="margin-top:5px;">
          <p style="margin:4px 0 0 7px;">Logout</p>
        </a>
      </div>
    </div>
  </div>
  <h1 style="text-align:center;">Edit Product</h1>
  <?php if (isset($product)) : ?>
    <div class="login-signup">
      <a href="vendor_dashboard.php" id="back">Back</a>
      <div id="user-login" style="text-align:left;">
        <form method="POST" action="edit_product.php?product_id=<?php echo $productId; ?>" enctype="multipart/form-data">
          <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
          <label for="edited_product_name" class="user-input">Product Name:</label>
          <input type="text" id="edited_product_name" class="user-input" name="edited_product_name" value="<?php echo $product['product_name']; ?>" required><br>

          <label for="edited_product_description" class="user-input" style="margin:10px 0 10px 0;">Product Description:</label>
          <input type="text" id="edited_product_description" class="user-input" name="edited_product_description" required value="<?php echo $product['description']; ?>"><br>

          <label for="edited_product_price" class="user-input" style="margin:10px 0 10px 0;">Product Price:</label>
          <input type="number" id="edited_product_price" class="user-input" name="edited_product_price" step="0.01" value="<?php echo $product['price']; ?>" required><br>

          <label for="edited_image_url" class="user-input" style="margin-right:20px;">Image:</label>
          <input type="file" id="edited_image_url" style="margin-bottom:25px;" name="edited_image_url"><br>

          <label for="edited_product_status" class="user-input">Product Status:</label>
          <select id="edited_product_status" class="user-input" name="edited_product_status" required>
            <option value="available" <?php if ($product['status'] === 'available') echo 'selected'; ?>>Available</option>
            <option value="unavailable" <?php if ($product['status'] === 'unavailable') echo 'selected'; ?>>Unavailable</option>
          </select><br>

          <input type="submit" class="insert-order-btn" name="edit_product_submit" value="Save Changes" onclick="return confirm('Are you sure you want to make changes?')">
        </form>
      </div>
    </div>
  <?php else : ?>
    <p>Product not found.</p>
  <?php endif; ?>
  <script src="../../js/script.js"></script>

</html>