-- Insert sample user data with roles
INSERT INTO users (username, password, email, phone_number, role) VALUES
    ('customer1', 'password123', 'customer1@example.com', '123456789', 'customer'),
    ('customer2', 'p123', 'customer2@example.com', '987654321', 'customer'),
    ('vendora', 'vendorpass', 'vendor@example.com', '555555555', 'vendor'),
    ('admin', '123', 'admin@example.com', '111111111', 'customer'),
    ('vadmin', '123', 'vadmin@example.com', '999999999', 'vendor');
