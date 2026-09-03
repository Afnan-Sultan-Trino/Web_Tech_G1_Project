-- =========================================================
-- CampusNest database schema
-- Import this once in phpMyAdmin / MySQL Workbench / CLI:
--   mysql -u root -p < schema.sql
-- =========================================================

CREATE DATABASE IF NOT EXISTS campusnest
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE campusnest;

-- ---------------------------------------------------------
-- Users (seekers, listers, admins)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    role ENUM('seeker', 'lister', 'admin') NOT NULL DEFAULT 'seeker',
    status ENUM('active', 'suspended') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------------------
-- Listings (rooms / flats posted by listers)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS listings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lister_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    location VARCHAR(100) NOT NULL,
    room_type VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    contact VARCHAR(20) NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    status ENUM('pending', 'available', 'occupied', 'removed') NOT NULL DEFAULT 'available',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lister_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ---------------------------------------------------------
-- Interest requests (a seeker asking about a listing)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS interest_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    listing_id INT NOT NULL,
    seeker_id INT NOT NULL,
    message TEXT NOT NULL,
    phone VARCHAR(20) NOT NULL,
    status ENUM('pending', 'approved', 'declined') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE,
    FOREIGN KEY (seeker_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ---------------------------------------------------------
-- Reports (a user flagging another user / listing to admin)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reporter_id INT NOT NULL,
    reported_user_id INT DEFAULT NULL,
    listing_id INT DEFAULT NULL,
    reason VARCHAR(150) NOT NULL,
    details TEXT DEFAULT NULL,
    status ENUM('open', 'resolved', 'removed') NOT NULL DEFAULT 'open',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reporter_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (reported_user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE SET NULL
);

-- ---------------------------------------------------------
-- Sample data so the site isn't empty the first time you run it.
-- Password for every sample account below is:  password123
-- (it is stored hashed with PHP's password_hash)
-- ---------------------------------------------------------
INSERT INTO users (name, email, password, phone, role) VALUES
('Admin User',    'admin@campusnest.com',   '$2y$10$92IX0NpkD86wG5RwHNvUZOEEqXwvsF3zGe9RtL0hUxWL0/UtNKtRC', '01700000000', 'admin'),
('Rakibul Hasan', 'rakibul@example.com',    '$2y$10$92IX0NpkD86wG5RwHNvUZOEEqXwvsF3zGe9RtL0hUxWL0/UtNKtRC', '01711111111', 'lister'),
('Momi Rahman',   'momi@example.com',       '$2y$10$92IX0NpkD86wG5RwHNvUZOEEqXwvsF3zGe9RtL0hUxWL0/UtNKtRC', '01722222222', 'seeker');

INSERT INTO listings (lister_id, title, location, room_type, description, price, contact, image, status) VALUES
(2, 'Sunny Single Room', 'Kuratoli', 'single', 'A bright single room close to AIUB, fully furnished.', 8000, '01711111111', 'chuttersnap-ftG8WcHwg7o-unsplash.jpg', 'available'),
(2, '2 Bedroom Flat', 'Bashundhara', 'master', 'Spacious two bedroom flat, good for families.', 16000, '01711111111', 'small-juvenile-bedroom-arrangement.jpg', 'pending');
