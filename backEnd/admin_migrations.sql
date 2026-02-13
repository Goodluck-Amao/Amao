-- Admin Panel Database Updates
-- Execute these queries to set up the admin panel

-- Add 'role' column to users table if it doesn't exist
ALTER TABLE `users` ADD COLUMN `role` VARCHAR(20) DEFAULT 'user' AFTER `created_at`;

-- Create a test admin user (password: admin123)
-- Note: Replace the password_hash with bcrypt hash in production
INSERT INTO `users` (`first_name`, `last_name`, `email`, `phone`, `password_hash`, `role`) 
VALUES ('Admin', 'User', 'admin@canteen.com', '+2341234567890', 'admin123', 'admin')
ON DUPLICATE KEY UPDATE role='admin';

-- Update the orders table to ensure it has all required columns
ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `status` VARCHAR(50) DEFAULT 'pending';

-- Create admin logs table (optional - for tracking admin actions)
CREATE TABLE IF NOT EXISTS `admin_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `admin_id` INT,
  `action` VARCHAR(255),
  `description` TEXT,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create backup table for orders (optional - for audit trail)
CREATE TABLE IF NOT EXISTS `order_backups` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `original_order_id` INT,
  `user_id` INT,
  `items` TEXT,
  `total` DECIMAL(10,2),
  `old_status` VARCHAR(50),
  `new_status` VARCHAR(50),
  `changed_by` INT,
  `changed_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (changed_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add indexes for better query performance
ALTER TABLE `orders` ADD INDEX idx_user_id (user_id);
ALTER TABLE `orders` ADD INDEX idx_status (status);
ALTER TABLE `orders` ADD INDEX idx_created_at (created_at);
ALTER TABLE `users` ADD INDEX idx_email (email);
ALTER TABLE `users` ADD INDEX idx_role (role);
ALTER TABLE `menu_items` ADD INDEX idx_category (category);
