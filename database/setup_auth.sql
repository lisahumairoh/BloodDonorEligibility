-- Create Users Table for Backoffice Authentication

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL, -- Store hashed password
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

-- Insert Default Admin User
-- Password: password123 (MD5 for simplicity in this demo, but should use PASSWORD_DEFAULT in production)
-- For this PHP implementation, we will use simple MD5 or direct string comparison first to ensure it works easily for the user, 
-- then upgrade to password_hash() if requested. 
-- Let's use INSERT IGNORE to prevent error on duplicate.

INSERT IGNORE INTO `users` (`username`, `password`) VALUES ('admin', MD5('password123'));
