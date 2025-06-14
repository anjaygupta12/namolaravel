<?php

// Script to add final set of tables from MS SQL Server schema to the MySQL database

// Database connection parameters
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'tradeview';

// Connect to MySQL server
echo "Connecting to MySQL database...\n";
try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to MySQL successfully.\n";
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Define final set of tables based on the MS SQL Server schema
$tables = [
    'roles' => "
        CREATE TABLE IF NOT EXISTS `roles` (
            `id` INT AUTO_INCREMENT NOT NULL,
            `name` VARCHAR(50) NOT NULL,
            `description` VARCHAR(255) NULL,
            `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'permissions' => "
        CREATE TABLE IF NOT EXISTS `permissions` (
            `id` INT AUTO_INCREMENT NOT NULL,
            `name` VARCHAR(50) NOT NULL,
            `description` VARCHAR(255) NULL,
            `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'role_permissions' => "
        CREATE TABLE IF NOT EXISTS `role_permissions` (
            `id` INT AUTO_INCREMENT NOT NULL,
            `role_id` INT NOT NULL,
            `permission_id` INT NOT NULL,
            `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `role_id` (`role_id`),
            KEY `permission_id` (`permission_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'admin_roles' => "
        CREATE TABLE IF NOT EXISTS `admin_roles` (
            `id` INT AUTO_INCREMENT NOT NULL,
            `admin_id` INT NOT NULL,
            `role_id` INT NOT NULL,
            `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `admin_id` (`admin_id`),
            KEY `role_id` (`role_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'transaction_passwords' => "
        CREATE TABLE IF NOT EXISTS `transaction_passwords` (
            `id` INT AUTO_INCREMENT NOT NULL,
            `user_id` INT NOT NULL,
            `password` VARCHAR(255) NOT NULL,
            `last_changed` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            `is_active` TINYINT(1) DEFAULT 1,
            PRIMARY KEY (`id`),
            KEY `user_id` (`user_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'settings' => "
        CREATE TABLE IF NOT EXISTS `settings` (
            `id` INT AUTO_INCREMENT NOT NULL,
            `key` VARCHAR(50) NOT NULL,
            `value` TEXT NULL,
            `group` VARCHAR(50) NULL,
            `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `key` (`key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'user_logs' => "
        CREATE TABLE IF NOT EXISTS `user_logs` (
            `id` INT AUTO_INCREMENT NOT NULL,
            `user_id` INT NOT NULL,
            `action` VARCHAR(255) NOT NULL,
            `ip_address` VARCHAR(45) NULL,
            `user_agent` VARCHAR(255) NULL,
            `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `user_id` (`user_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'admin_logs' => "
        CREATE TABLE IF NOT EXISTS `admin_logs` (
            `id` INT AUTO_INCREMENT NOT NULL,
            `admin_id` INT NOT NULL,
            `action` VARCHAR(255) NOT NULL,
            `details` TEXT NULL,
            `ip_address` VARCHAR(45) NULL,
            `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `admin_id` (`admin_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'payment_methods' => "
        CREATE TABLE IF NOT EXISTS `payment_methods` (
            `id` INT AUTO_INCREMENT NOT NULL,
            `name` VARCHAR(50) NOT NULL,
            `description` VARCHAR(255) NULL,
            `is_active` TINYINT(1) DEFAULT 1,
            `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'user_kyc' => "
        CREATE TABLE IF NOT EXISTS `user_kyc` (
            `id` INT AUTO_INCREMENT NOT NULL,
            `user_id` INT NOT NULL,
            `document_type` VARCHAR(50) NOT NULL,
            `document_number` VARCHAR(50) NOT NULL,
            `document_front` VARCHAR(255) NULL,
            `document_back` VARCHAR(255) NULL,
            `status` VARCHAR(20) DEFAULT 'pending',
            `remarks` TEXT NULL,
            `verified_by` INT NULL,
            `verified_at` TIMESTAMP NULL,
            `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NULL,
            PRIMARY KEY (`id`),
            KEY `user_id` (`user_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'trade_history' => "
        CREATE TABLE IF NOT EXISTS `trade_history` (
            `id` INT AUTO_INCREMENT NOT NULL,
            `user_id` INT NOT NULL,
            `trade_id` INT NOT NULL,
            `action` VARCHAR(20) NOT NULL,
            `price` DECIMAL(10,2) NOT NULL,
            `quantity` INT NOT NULL,
            `total` DECIMAL(12,2) NOT NULL,
            `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `user_id` (`user_id`),
            KEY `trade_id` (`trade_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'positions' => "
        CREATE TABLE IF NOT EXISTS `positions` (
            `id` INT AUTO_INCREMENT NOT NULL,
            `user_id` INT NOT NULL,
            `symbol` VARCHAR(50) NOT NULL,
            `position_type` VARCHAR(10) NOT NULL,
            `quantity` INT NOT NULL,
            `entry_price` DECIMAL(10,2) NOT NULL,
            `current_price` DECIMAL(10,2) NOT NULL,
            `stop_loss` DECIMAL(10,2) NULL,
            `take_profit` DECIMAL(10,2) NULL,
            `pnl` DECIMAL(12,2) NOT NULL,
            `status` VARCHAR(20) NOT NULL,
            `opened_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            `closed_at` TIMESTAMP NULL,
            PRIMARY KEY (`id`),
            KEY `user_id` (`user_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'funds' => "
        CREATE TABLE IF NOT EXISTS `funds` (
            `id` INT AUTO_INCREMENT NOT NULL,
            `user_id` INT NOT NULL,
            `balance` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `equity` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `margin` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `free_margin` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `margin_level` DECIMAL(10,2) NULL,
            `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `user_id` (`user_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'notifications' => "
        CREATE TABLE IF NOT EXISTS `notifications` (
            `id` INT AUTO_INCREMENT NOT NULL,
            `user_id` INT NULL,
            `title` VARCHAR(255) NOT NULL,
            `message` TEXT NOT NULL,
            `type` VARCHAR(20) NULL,
            `is_read` TINYINT(1) DEFAULT 0,
            `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `user_id` (`user_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    "
];

// Create tables
foreach ($tables as $table_name => $sql) {
    try {
        $conn->exec($sql);
        echo "Created table $table_name.\n";
    } catch(PDOException $e) {
        echo "Error creating table $table_name: " . $e->getMessage() . "\n";
    }
}

// Insert sample data
try {
    // Insert roles
    $conn->exec("INSERT INTO `roles` (`name`, `description`) VALUES ('admin', 'Administrator with full access')");
    $conn->exec("INSERT INTO `roles` (`name`, `description`) VALUES ('manager', 'Manager with limited administrative access')");
    $conn->exec("INSERT INTO `roles` (`name`, `description`) VALUES ('user', 'Regular user')");
    
    // Insert permissions
    $conn->exec("INSERT INTO `permissions` (`name`, `description`) VALUES ('view_dashboard', 'Can view dashboard')");
    $conn->exec("INSERT INTO `permissions` (`name`, `description`) VALUES ('manage_users', 'Can manage users')");
    $conn->exec("INSERT INTO `permissions` (`name`, `description`) VALUES ('manage_trades', 'Can manage trades')");
    $conn->exec("INSERT INTO `permissions` (`name`, `description`) VALUES ('manage_settings', 'Can manage system settings')");
    
    // Insert role permissions
    $conn->exec("INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (1, 1), (1, 2), (1, 3), (1, 4), (2, 1), (2, 3), (3, 1)");
    
    // Insert payment methods
    $conn->exec("INSERT INTO `payment_methods` (`name`, `description`, `is_active`) VALUES ('Bank Transfer', 'Direct bank transfer', 1)");
    $conn->exec("INSERT INTO `payment_methods` (`name`, `description`, `is_active`) VALUES ('UPI', 'Unified Payment Interface', 1)");
    $conn->exec("INSERT INTO `payment_methods` (`name`, `description`, `is_active`) VALUES ('PayTM', 'PayTM Wallet', 1)");
    
    // Insert settings
    $conn->exec("INSERT INTO `settings` (`key`, `value`, `group`) VALUES ('site_name', 'Namo Traders', 'general')");
    $conn->exec("INSERT INTO `settings` (`key`, `value`, `group`) VALUES ('contact_email', 'support@namotraders.com', 'general')");
    $conn->exec("INSERT INTO `settings` (`key`, `value`, `group`) VALUES ('maintenance_mode', '0', 'system')");
    
    echo "Inserted sample data.\n";
} catch(PDOException $e) {
    echo "Error inserting sample data: " . $e->getMessage() . "\n";
}

echo "\nFinal tables creation completed!\n";
echo "Total tables added: " . count($tables) . "\n";
?>
