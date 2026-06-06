CREATE DATABASE IF NOT EXISTS football_booking CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE football_booking;

DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS pitches;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    fullname VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    role ENUM('customer', 'admin') NOT NULL DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE pitches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type ENUM('Sân 5', 'Sân 7', 'Sân 11') NOT NULL,
    price_per_hour DECIMAL(10,2) NOT NULL,
    status ENUM('active', 'maintenance') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    pitch_id INT NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    booking_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    total_price DECIMAL(12,2) NOT NULL DEFAULT 0,
    status ENUM('PENDING', 'CONFIRMED', 'CANCELLED') NOT NULL DEFAULT 'PENDING',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_bookings_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_bookings_pitch FOREIGN KEY (pitch_id) REFERENCES pitches(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO users (username, password_hash, fullname, phone, role) VALUES
('admin', '$2y$12$K5OHoB05mGpPKmogHZhk3OwACdhSLTtYcEzkvBSi6XYYIfdPozNw2', 'Chủ sân (Admin)', '0900000000', 'admin');

INSERT INTO pitches (name, type, price_per_hour, status) VALUES
('Sân A1 (Mini 5)', 'Sân 5', 200000, 'active'),
('Sân A2 (Mini 5)', 'Sân 5', 200000, 'active'),
('Sân B1 (Đại 7)', 'Sân 7', 350000, 'active'),
('Sân C1 (Sân 11)', 'Sân 11', 600000, 'maintenance');
