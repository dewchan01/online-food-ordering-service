-- Insert sample data for Vendor A products
INSERT INTO products (product_name, description, price, image_url, status)
VALUES
    ('Product A1', 'Description for Product A1', 10.99, 'https://static.phdvasia.com/sg1/menu/single/desktop_thumbnail_5ee2b262-0e37-4ff8-9a7f-9132dc93fa0d.jpg', 'available'),
    ('Product A2', 'Description for Product A2', 15.99, 'https://static.phdvasia.com/sg1/menu/single/desktop_thumbnail_316f4d76-2fcb-4078-9487-6989e6b59ee7.jpg', 'available'),
    ('Product A3', 'Description for Product A3', 8.49, 'https://static.phdvasia.com/sg1/menu/single/desktop_thumbnail_45b65706-0d5d-4b16-9c53-4307d3b44884.jpg', 'unavailable');

INSERT INTO orders (time, image_url, username, product_id, product_name, description, quantity, total_price, order_status) 
VALUES
(CURRENT_TIMESTAMP,'https://static.phdvasia.com/sg1/menu/single/desktop_thumbnail_5ee2b262-0e37-4ff8-9a7f-9132dc93fa0d.jpg', 'admin','Description for Product A1', 1, 'Product A1', 2, 21.98, 'Order Confirmed')