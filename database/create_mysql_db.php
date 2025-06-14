<?php
/**
 * Create MySQL Database Script
 * 
 * This script creates a MySQL database with tables based on a predefined schema
 * extracted from the MS SQL Server database structure.
 */

// MySQL connection parameters
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'tradeview';

echo "Starting MySQL database creation...\n";

// Connect to MySQL server
try {
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to MySQL successfully.\n";
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Create database
try {
    $conn->exec("DROP DATABASE IF EXISTS $database");
    $conn->exec("CREATE DATABASE $database CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Created database $database.\n";
} catch(PDOException $e) {
    die("Database creation failed: " . $e->getMessage());
}

// Connect to the newly created database
try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Define tables based on the MS SQL Server schema
$tables = [
    'AdminLogin' => "
        CREATE TABLE `AdminLogin` (
            `PK_ID` INT AUTO_INCREMENT NOT NULL,
            `UserId` VARCHAR(50) NULL,
            `Password` VARCHAR(100) NULL,
            `Timestamp` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
            `LasmtModify` DATETIME NULL,
            `Isactive` TINYINT(1) NULL DEFAULT 1,
            `TransPass` VARCHAR(50) NULL,
            PRIMARY KEY (`PK_ID`)
            `email` VARCHAR(255) NOT NULL,
            `password` VARCHAR(255) NOT NULL,
            `name` VARCHAR(255),
            `mobile` VARCHAR(20),
            `status` TINYINT(1) DEFAULT 1,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'admins' => "
        CREATE TABLE `admins` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `username` VARCHAR(255) NOT NULL,
            `email` VARCHAR(255) NOT NULL,
            `password` VARCHAR(255) NOT NULL,
            `name` VARCHAR(255),
            `role` VARCHAR(50) DEFAULT 'admin',
            `status` TINYINT(1) DEFAULT 1,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'trades' => "
        CREATE TABLE `trades` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NOT NULL,
            `symbol` VARCHAR(20) NOT NULL,
            `trade_type` VARCHAR(10) NOT NULL,
            `quantity` INT NOT NULL,
            `price` DECIMAL(10,2) NOT NULL,
            `status` VARCHAR(20) NOT NULL,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'positions' => "
        CREATE TABLE `positions` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NOT NULL,
            `trade_id` INT NOT NULL,
            `symbol` VARCHAR(20) NOT NULL,
            `position_type` VARCHAR(10) NOT NULL,
            `quantity` INT NOT NULL,
            `entry_price` DECIMAL(10,2) NOT NULL,
            `current_price` DECIMAL(10,2),
            `profit_loss` DECIMAL(10,2),
            `status` VARCHAR(20) NOT NULL,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'bank_details' => "
        CREATE TABLE `bank_details` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NOT NULL,
            `bank_name` VARCHAR(255) NOT NULL,
            `account_number` VARCHAR(50) NOT NULL,
            `ifsc_code` VARCHAR(20) NOT NULL,
            `account_holder` VARCHAR(255) NOT NULL,
            `is_primary` TINYINT(1) DEFAULT 0,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'funds' => "
        CREATE TABLE `funds` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NOT NULL,
            `amount` DECIMAL(12,2) NOT NULL,
            `transaction_type` VARCHAR(20) NOT NULL,
            `reference_id` VARCHAR(50),
            `status` VARCHAR(20) NOT NULL,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'withdrawal_requests' => "
        CREATE TABLE `withdrawal_requests` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NOT NULL,
            `amount` DECIMAL(12,2) NOT NULL,
            `bank_detail_id` INT NOT NULL,
            `status` VARCHAR(20) NOT NULL,
            `processed_at` TIMESTAMP NULL,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'deposit_requests' => "
        CREATE TABLE `deposit_requests` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NOT NULL,
            `amount` DECIMAL(12,2) NOT NULL,
            `payment_method` VARCHAR(50) NOT NULL,
            `reference_number` VARCHAR(100),
            `proof_image` VARCHAR(255),
            `status` VARCHAR(20) NOT NULL,
            `verified_at` TIMESTAMP NULL,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'notifications' => "
        CREATE TABLE `notifications` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT,
            `title` VARCHAR(255) NOT NULL,
            `message` TEXT NOT NULL,
            `is_read` TINYINT(1) DEFAULT 0,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'action_ledger' => "
        CREATE TABLE `action_ledger` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT,
            `action` VARCHAR(255) NOT NULL,
            `description` TEXT,
            `ip_address` VARCHAR(45),
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'scrip_data' => "
        CREATE TABLE `scrip_data` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `symbol` VARCHAR(20) NOT NULL,
            `name` VARCHAR(255) NOT NULL,
            `exchange` VARCHAR(20) NOT NULL,
            `last_price` DECIMAL(10,2),
            `change` DECIMAL(10,2),
            `change_percent` DECIMAL(5,2),
            `volume` BIGINT,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'market_scripts' => "
        CREATE TABLE `market_scripts` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `symbol` VARCHAR(20) NOT NULL,
            `name` VARCHAR(255) NOT NULL,
            `exchange` VARCHAR(20) NOT NULL,
            `is_active` TINYINT(1) DEFAULT 1,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'brokers' => "
        CREATE TABLE `brokers` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(255) NOT NULL,
            `api_key` VARCHAR(255),
            `api_secret` VARCHAR(255),
            `status` TINYINT(1) DEFAULT 1,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'social_links' => "
        CREATE TABLE `social_links` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `platform` VARCHAR(50) NOT NULL,
            `url` VARCHAR(255) NOT NULL,
            `icon` VARCHAR(50),
            `is_active` TINYINT(1) DEFAULT 1,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'roles' => "
        CREATE TABLE `roles` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(50) NOT NULL,
            `description` VARCHAR(255),
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'permissions' => "
        CREATE TABLE `permissions` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(50) NOT NULL,
            `description` VARCHAR(255),
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'role_permissions' => "
        CREATE TABLE `role_permissions` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `role_id` INT NOT NULL,
            `permission_id` INT NOT NULL,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'admin_roles' => "
        CREATE TABLE `admin_roles` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `admin_id` INT NOT NULL,
            `role_id` INT NOT NULL,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'transaction_passwords' => "
        CREATE TABLE `transaction_passwords` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NOT NULL,
            `password` VARCHAR(255) NOT NULL,
            `last_changed` TIMESTAMP NULL,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'settings' => "
        CREATE TABLE `settings` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `key` VARCHAR(50) NOT NULL,
            `value` TEXT,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'user_logs' => "
        CREATE TABLE `user_logs` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NOT NULL,
            `action` VARCHAR(255) NOT NULL,
            `ip_address` VARCHAR(45),
            `user_agent` TEXT,
            `created_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'admin_logs' => "
        CREATE TABLE `admin_logs` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `admin_id` INT NOT NULL,
            `action` VARCHAR(255) NOT NULL,
            `ip_address` VARCHAR(45),
            `user_agent` TEXT,
            `created_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'payment_methods' => "
        CREATE TABLE `payment_methods` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(100) NOT NULL,
            `description` TEXT,
            `instructions` TEXT,
            `is_active` TINYINT(1) DEFAULT 1,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'user_kyc' => "
        CREATE TABLE `user_kyc` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NOT NULL,
            `id_type` VARCHAR(50) NOT NULL,
            `id_number` VARCHAR(100) NOT NULL,
            `id_front_image` VARCHAR(255),
            `id_back_image` VARCHAR(255),
            `selfie_image` VARCHAR(255),
            `status` VARCHAR(20) DEFAULT 'pending',
            `verified_at` TIMESTAMP NULL,
            `verified_by` INT,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'trade_history' => "
        CREATE TABLE `trade_history` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NOT NULL,
            `trade_id` INT NOT NULL,
            `action` VARCHAR(20) NOT NULL,
            `price` DECIMAL(10,2) NOT NULL,
            `quantity` INT NOT NULL,
            `total` DECIMAL(12,2) NOT NULL,
            `created_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'forexoption' => "
        CREATE TABLE `forexoption` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `symbol` VARCHAR(20) NOT NULL,
            `name` VARCHAR(255) NOT NULL,
            `current_price` DECIMAL(10,2) NOT NULL,
            `previous_close` DECIMAL(10,2),
            `open_price` DECIMAL(10,2),
            `day_high` DECIMAL(10,2),
            `day_low` DECIMAL(10,2),
            `volume` BIGINT,
            `market_cap` DECIMAL(20,2),
            `pe_ratio` DECIMAL(10,2),
            `dividend_yield` DECIMAL(5,2),
            `is_active` TINYINT(1) DEFAULT 1,
            `last_updated` TIMESTAMP NULL,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    "
];

// Create tables
foreach ($tables as $table_name => $sql) {
    try {
        $mysql->exec($sql);
        echo "Created table $table_name.\n";
    } catch (PDOException $e) {
        echo "Error creating table $table_name: " . $e->getMessage() . "\n";
    }
}

// Insert sample data for testing
try {
    // Insert admin user
    $mysql->exec("INSERT INTO `admins` (`username`, `email`, `password`, `name`, `role`, `status`, `created_at`, `updated_at`) 
                 VALUES ('admin', 'admin@namotraders.com', '" . password_hash('admin123', PASSWORD_BCRYPT) . "', 'Administrator', 'admin', 1, NOW(), NOW())");
    
    // Insert sample users
    $mysql->exec("INSERT INTO `users` (`username`, `email`, `password`, `name`, `mobile`, `status`, `created_at`, `updated_at`) 
                 VALUES ('user1', 'user1@example.com', '" . password_hash('password', PASSWORD_BCRYPT) . "', 'John Doe', '9876543210', 1, NOW(), NOW()),
                        ('user2', 'user2@example.com', '" . password_hash('password', PASSWORD_BCRYPT) . "', 'Jane Smith', '9876543211', 1, NOW(), NOW())");
    
    // Insert sample bank details
    $mysql->exec("INSERT INTO `bank_details` (`user_id`, `bank_name`, `account_number`, `ifsc_code`, `account_holder`, `is_primary`, `created_at`, `updated_at`) 
                 VALUES (1, 'HDFC Bank', 'XXXX1234', 'HDFC0001234', 'John Doe', 1, NOW(), NOW()),
                        (2, 'ICICI Bank', 'XXXX5678', 'ICIC0001234', 'Jane Smith', 1, NOW(), NOW())");
    
    // Insert sample scrip data
    $mysql->exec("INSERT INTO `scrip_data` (`symbol`, `name`, `exchange`, `last_price`, `change`, `change_percent`, `volume`, `updated_at`) 
                 VALUES ('RELIANCE', 'Reliance Industries Ltd', 'NSE', 2500.50, 25.30, 1.02, 5000000, NOW()),
                        ('INFY', 'Infosys Ltd', 'NSE', 1450.75, -10.25, -0.70, 3000000, NOW()),
                        ('TCS', 'Tata Consultancy Services', 'NSE', 3200.00, 15.50, 0.49, 1500000, NOW())");
    
    // Insert sample social links
    $mysql->exec("INSERT INTO `social_links` (`platform`, `url`, `icon`, `is_active`, `created_at`, `updated_at`) 
                 VALUES ('facebook', 'https://facebook.com/namotraders', 'fab fa-facebook', 1, NOW(), NOW()),
                        ('twitter', 'https://twitter.com/namotraders', 'fab fa-twitter', 1, NOW(), NOW()),
                        ('instagram', 'https://instagram.com/namotraders', 'fab fa-instagram', 1, NOW(), NOW())");
    
    echo "Inserted sample data.\n";
} catch (PDOException $e) {
    echo "Error inserting sample data: " . $e->getMessage() . "\n";
}

echo "\nDatabase creation completed!\n";
echo "You can now connect to the MySQL database with the following details:\n";
echo "Host: $mysql_host\n";
echo "Database: $mysql_database\n";
echo "Username: $mysql_user\n";
echo "Password: $mysql_password\n";
?>
