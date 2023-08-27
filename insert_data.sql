-- Insert sample user data with roles
INSERT INTO users (username, password, role) VALUES
    ('customer1', 'password123', 'customer'),
    ('customer2', 'letmein', 'customer'),
    ('vendor1', 'vendorpass', 'vendor'),
    ('admin', '123', 'customer');


INSERT INTO orders (customer_id, order_details, order_status) VALUES
    (1, 'Pizza and Salad', 'Pending'),
    (2, 'Burger and Fries', 'Processing'),
    (3, 'Sushi Platter', 'Delivered');
