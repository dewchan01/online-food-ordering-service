-- Insert sample user data with roles
INSERT INTO users (username, password, role) VALUES
    ('customer1', 'password123', 'customer'),
    ('customer2', 'letmein', 'customer'),
    ('vendor', 'vendorpass', 'vendor'),
    ('admin', '123', 'customer'),
    ('vadmin', '123', 'vendor');

