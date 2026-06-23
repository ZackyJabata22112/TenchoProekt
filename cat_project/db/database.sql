DROP DATABASE IF EXISTS cat_db;
CREATE DATABASE cat_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cat_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    profile_pic VARCHAR(255) NOT NULL,
    is_admin TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, email, password, profile_pic, is_admin) 
VALUES (
    'admin', 
    'admin@catportal.com', 
    '$2y$10$J9RVb/PQbdNdTy72uuheSOkRlqKaUs4xE985Bj5IEWtPfMDcnfHMi', 
    '../images/profiles/admin.jpg', 
    1
);

INSERT INTO products (name, description, price, image) VALUES 
(
    'Cozy Marshmallow Cloud Bed', 
    'An ultra-soft, self-warming circular bed designed to give your feline friend ultimate comfort and deep sleep.', 
    34.99, 
    '../images/products/sample_bed.jpg'
),
(
    'Interactive Organic Catnip Mouse', 
    'Filled with 100% premium organic North American catnip. Perfect for batting, chasing, and pouncing.', 
    6.50, 
    '../images/products/sample_mouse.jpg'
),
(
    'Premium Salmon & Cranberry Bites', 
    'Grain-free, all-natural crunchy treats packed with real salmon oils to promote a shiny coat and healthy skin.', 
    8.99, 
    '../images/products/sample_treats.jpg'
),
(
    'Multi-Level Natural Sisal Scratching Post', 
    'Durable wooden tower wrapped in heavy-duty natural sisal rope, complete with a hanging feather toy.', 
    49.95, 
    '../images/products/sample_post.jpg'
);