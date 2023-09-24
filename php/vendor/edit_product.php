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

    // Perform edit for the selected product
    $editQuery = "UPDATE products SET product_name = ?, description = ?, price = ?, status = ? WHERE product_id = ?";
    $stmt = $conn->prepare($editQuery);
    $stmt->bind_param("ssdsi", $editedProductName, $editedProductDescription, $editedProductPrice, $editedProductStatus, $productId);
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
    <form method="POST" action="edit_product.php?product_id=<?php echo $productId; ?>">
        <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
        <label for="edited_product_name">Product Name:</label>
        <input type="text" id="edited_product_name" name="edited_product_name" value="<?php echo $product['product_name']; ?>" required><br>
        
        <label for="edited_product_description">Product Description:</label>
        <textarea id="edited_product_description" name="edited_product_description" required><?php echo $product['description']; ?></textarea><br>
        
        <label for="edited_product_price">Product Price:</label>
        <input type="number" id="edited_product_price" name="edited_product_price" step="0.01" value="<?php echo $product['price']; ?>" required><br>
        
        <label for="edited_product_status">Product Status:</label>
        <select id="edited_product_status" name="edited_product_status" required>
            <option value="available" <?php if ($product['status'] === 'available') echo 'selected'; ?>>Available</option>
            <option value="unavailable" <?php if ($product['status'] === 'unavailable') echo 'selected'; ?>>Unavailable</option>
        </select><br>
        
        <button type="submit" name="edit_product_submit">Save Changes</button>
    </form>
    <?php else: ?>
        <p>Product not found.</p>
    <?php endif; ?>
    
    <a href="vendor_dashboard.php">Back to Dashboard</a>
</body>
</html>
