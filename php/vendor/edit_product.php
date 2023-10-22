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

    // Fetch the product details from the database
    $fetchProductQuery = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($fetchProductQuery);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
}

if (isset($_POST['edit_product_submit'])) {
    // Get the edited product details from the form
    $editedProductName = $_POST['edited_product_name'];
    $editedProductDescription = $_POST['edited_product_description'];
    $editedProductPrice = $_POST['edited_product_price'];
    $editedProductStatus = $_POST['edited_product_status'];

    $target_dir = '../../images/';
    $target_file = $target_dir . basename($_FILES["edited_image_url"]["name"]);
    $editedImageURL =  $product['image_url'];

    if ($target_file!== '../../images/'){
    $editedImageURL = '../images/'.basename($_FILES["edited_image_url"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
      $check = getimagesize($_FILES["edited_image_url"]["tmp_name"]);
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
        if (move_uploaded_file($_FILES["edited_image_url"]["tmp_name"], $target_file)) {
          echo "The file ". htmlspecialchars( basename( $_FILES["edited_image_url"]["name"])). " has been uploaded.";
        } else {
          echo "Sorry, there was an error uploading your file.";
        }
        $filename = '../' . $product['image_url'];
        if (file_exists($filename)) {
            unlink($filename);
            echo 'File '.$filename.' has been deleted';
          } else {
            echo 'Could not delete '.$filename.', file does not exist';
          }
      }
    }

    // Perform edit for the selected product
    $editQuery = "UPDATE products SET product_name = ?, description = ?, price = ?, image_url = ?, status = ? WHERE product_id = ?";
    $stmt = $conn->prepare($editQuery);
    $stmt->bind_param("ssdssi", $editedProductName, $editedProductDescription, $editedProductPrice, $editedImageURL, $editedProductStatus, $productId);
    $stmt->execute();
    $stmt->close();

    header("Location: vendor_dashboard.php"); // Redirect back to the dashboard
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
    <h1>Edit Product</h1>
    <?php if (isset($product)): ?>
    <form method="POST" action="edit_product.php?product_id=<?php echo $productId; ?>" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
        <label for="edited_product_name">Product Name:</label>
        <input type="text" id="edited_product_name" name="edited_product_name" value="<?php echo $product['product_name']; ?>" required><br>
        
        <label for="edited_product_description">Product Description:</label>
        <textarea id="edited_product_description" name="edited_product_description" required><?php echo $product['description']; ?></textarea><br>
        
        <label for="edited_product_price">Product Price:</label>
        <input type="number" id="edited_product_price" name="edited_product_price" step="0.01" value="<?php echo $product['price']; ?>" required><br>

        <label for="edited_image_url">Image:</label>
        <input type="file" id="edited_image_url" name="edited_image_url"><br>
        
        <label for="edited_product_status">Product Status:</label>
        <select id="edited_product_status" name="edited_product_status" required>
            <option value="available" <?php if ($product['status'] === 'available') echo 'selected'; ?>>Available</option>
            <option value="unavailable" <?php if ($product['status'] === 'unavailable') echo 'selected'; ?>>Unavailable</option>
        </select><br>
        
        <input type="submit" name="edit_product_submit" value="Save Changes" onclick="alert('Are you sure you want to make changes?')">
    </form>
    <?php else: ?>
        <p>Product not found.</p>
    <?php endif; ?>
    
    <a href="vendor_dashboard.php">Back to Dashboard</a>
</body>
</html>
