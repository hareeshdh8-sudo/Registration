-- Create the database
CREATE DATABASE IF NOT EXISTS user_registration;
USE user_registration;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    birth_date DATE NOT NULL,
    birth_time TIME NOT NULL,
    birth_month VARCHAR(20) NOT NULL,
    birth_week INT NOT NULL,
    website VARCHAR(255) DEFAULT NULL,
    gender ENUM('male','female','other') NOT NULL,
    color VARCHAR(7) NOT NULL DEFAULT '#563d7c',
    salary DECIMAL(10,2) NOT NULL,
    bio TEXT,
    profile_image VARCHAR(255) DEFAULT NULL,
    address TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    country VARCHAR(100) NOT NULL,
    qualification VARCHAR(100) NOT NULL,
    newsletter BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample user (password is 'password123' hashed with password_hash())
INSERT INTO users (
    first_name, 
    last_name, 
    email, 
    password, 
    birth_date, 
    birth_time, 
    birth_month, 
    birth_week, 
    website, 
    gender, 
    color, 
    salary, 
    bio, 
    address, 
    city, 
    state, 
    country, 
    qualification, 
    newsletter
) VALUES (
    'John', 
    'Doe', 
    'john.doe@example.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    '1990-05-15', 
    '14:30:00', 
    'May', 
    20, 
    'https://johndoe.example.com', 
    'male', 
    '#3498db', 
    75000.00, 
    'A passionate web developer with 5+ years of experience in building modern web applications.', 
    '123 Main St', 
    'New York', 
    'NY', 
    'United States', 
    'Master\'s Degree', 
    1
);

-- Insert another sample user
INSERT INTO users (
    first_name, 
    last_name, 
    email, 
    password, 
    birth_date, 
    birth_time, 
    birth_month, 
    birth_week, 
    website, 
    gender, 
    color, 
    salary, 
    bio, 
    address, 
    city, 
    state, 
    country, 
    qualification, 
    newsletter
) VALUES (
    'Jane', 
    'Smith', 
    'jane.smith@example.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    '1992-08-22', 
    '09:15:00', 
    'August', 
    34, 
    'https://janesmith.example.com', 
    'female', 
    '#e74c3c', 
    85000.00, 
    'UX/UI Designer with a passion for creating beautiful and functional user experiences.', 
    '456 Oak Avenue', 
    'San Francisco', 
    'CA', 
    'United States', 
    'Bachelor\'s Degree', 
    0
);

-- Create a view for basic user information
CREATE OR REPLACE VIEW vw_user_profiles AS
SELECT 
    id,
    CONCAT(first_name, ' ', last_name) AS full_name,
    email,
    CONCAT(address, ', ', city, ', ', state, ', ', country) AS full_address,
    qualification,
    created_at
FROM users;

-- Create a stored procedure to get users by country
DELIMITER //
CREATE PROCEDURE sp_get_users_by_country(IN country_name VARCHAR(100))
BEGIN
    SELECT 
        id,
        CONCAT(first_name, ' ', last_name) AS name,
        email,
        city,
        state,
        country
    FROM users
    WHERE country = country_name
    ORDER BY last_name, first_name;
END //
DELIMITER ;

-- Create a trigger to log user updates
CREATE TABLE IF NOT EXISTS user_update_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    updated_field VARCHAR(50) NOT NULL,
    old_value TEXT,
    new_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELIMITER //
CREATE TRIGGER after_user_update
AFTER UPDATE ON users
FOR EACH ROW
BEGIN
    IF OLD.first_name != NEW.first_name THEN
        INSERT INTO user_update_logs (user_id, updated_field, old_value, new_value)
        VALUES (NEW.id, 'first_name', OLD.first_name, NEW.first_name);
    END IF;
    
    IF OLD.last_name != NEW.last_name THEN
        INSERT INTO user_update_logs (user_id, updated_field, old_value, new_value)
        VALUES (NEW.id, 'last_name', OLD.last_name, NEW.last_name);
    END IF;
    
    IF OLD.email != NEW.email THEN
        INSERT INTO user_update_logs (user_id, updated_field, old_value, new_value)
        VALUES (NEW.id, 'email', OLD.email, NEW.email);
    END IF;
    
    -- Add more fields as needed
END //
DELIMITER ;
