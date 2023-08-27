-- Create products table for Vendor A
CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255), 
    status ENUM('available', 'unavailable') NOT NULL
);

CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    image_url VARCHAR(255),
    username VARCHAR(255),
    product_id INT,
    product_name VARCHAR(255),
    description TEXT,
    quantity INT,
    total_price DECIMAL(10, 2),
    order_status VARCHAR(50)
);
