CREATE TABLE users
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL
);

CREATE TABLE orders 
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    vendor_id INT,
    order_details TEXT,
    order_status VARCHAR(50)
);
