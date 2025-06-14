<?php
/**
 * MySQL Database Creator Script
 * 
 * This script creates a MySQL database with tables based on a predefined schema
 * extracted from the MS SQL Server database structure.
 */

// MySQL connection parameters
$mysql_host = 'localhost';
$mysql_user = 'root';
$mysql_password = '';
$mysql_database = 'tradeview';

echo "Starting MySQL database creation...\n";

// Connect to MySQL
try {
    $mysql = new PDO("mysql:host=$mysql_host", $mysql_user, $mysql_password);
    $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to MySQL successfully.\n";
} catch (PDOException $e) {
    die("MySQL Connection failed: " . $e->getMessage() . "\n");
}

// Create database if it doesn't exist
try {
    $mysql->exec("DROP DATABASE IF EXISTS `$mysql_database`");
    $mysql->exec("CREATE DATABASE `$mysql_database` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $mysql->exec("USE `$mysql_database`");
    echo "Created database $mysql_database.\n";
} catch (PDOException $e) {
    die("Error creating database: " . $e->getMessage() . "\n");
}

// Define tables based on MS SQL Server schema
$tables = [
    'users' => [
        'columns' => [
            'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
            'username' => 'VARCHAR(255) NOT NULL',
            'email' => 'VARCHAR(255) NOT NULL',
            'password' => 'VARCHAR(255) NOT NULL',
            'name' => 'VARCHAR(255)',
            'mobile' => 'VARCHAR(20)',
            'status' => 'TINYINT(1) DEFAULT 1',
            'created_at' => 'DATETIME',
            'updated_at' => 'DATETIME'
        ]
    ],
    'trades' => [
        'columns' => [
            'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
            'user_id' => 'INT NOT NULL',
            'symbol' => 'VARCHAR(20) NOT NULL',
            'trade_type' => 'VARCHAR(10) NOT NULL',
            'quantity' => 'INT NOT NULL',
            'price' => 'DECIMAL(10,2) NOT NULL',
            'status' => 'VARCHAR(20) NOT NULL',
            'created_at' => 'DATETIME',
            'updated_at' => 'DATETIME'
        ]
    ],
    'positions' => [
        'columns' => [
            'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
            'user_id' => 'INT NOT NULL',
            'trade_id' => 'INT NOT NULL',
            'symbol' => 'VARCHAR(20) NOT NULL',
            'position_type' => 'VARCHAR(10) NOT NULL',
            'quantity' => 'INT NOT NULL',
            'entry_price' => 'DECIMAL(10,2) NOT NULL',
            'current_price' => 'DECIMAL(10,2)',
            'profit_loss' => 'DECIMAL(10,2)',
            'status' => 'VARCHAR(20) NOT NULL',
            'created_at' => 'DATETIME',
            'updated_at' => 'DATETIME'
        ]
    ],
    'bank_details' => [
        'columns' => [
            'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
            'user_id' => 'INT NOT NULL',
            'bank_name' => 'VARCHAR(255) NOT NULL',
            'account_number' => 'VARCHAR(50) NOT NULL',
            'ifsc_code' => 'VARCHAR(20) NOT NULL',
            'account_holder' => 'VARCHAR(255) NOT NULL',
            'is_primary' => 'TINYINT(1) DEFAULT 0',
            'created_at' => 'DATETIME',
            'updated_at' => 'DATETIME'
        ]
    ],
    'funds' => [
        'columns' => [
            'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
            'user_id' => 'INT NOT NULL',
            'amount' => 'DECIMAL(12,2) NOT NULL',
            'transaction_type' => 'VARCHAR(20) NOT NULL',
            'reference_id' => 'VARCHAR(50)',
            'status' => 'VARCHAR(20) NOT NULL',
            'created_at' => 'DATETIME',
            'updated_at' => 'DATETIME'
        ]
    ],
    'withdrawal_requests' => [
        'columns' => [
            'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
            'user_id' => 'INT NOT NULL',
            'amount' => 'DECIMAL(12,2) NOT NULL',
            'bank_detail_id' => 'INT NOT NULL',
            'status' => 'VARCHAR(20) NOT NULL',
            'processed_at' => 'DATETIME',
            'created_at' => 'DATETIME',
            'updated_at' => 'DATETIME'
        ]
    ],
    'deposit_requests' => [
        'columns' => [
            'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
            'user_id' => 'INT NOT NULL',
            'amount' => 'DECIMAL(12,2) NOT NULL',
            'payment_method' => 'VARCHAR(50) NOT NULL',
            'reference_number' => 'VARCHAR(100)',
            'proof_image' => 'VARCHAR(255)',
            'status' => 'VARCHAR(20) NOT NULL',
            'verified_at' => 'DATETIME',
            'created_at' => 'DATETIME',
            'updated_at' => 'DATETIME'
        ]
    ],
    'notifications' => [
        'columns' => [
            'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
            'user_id' => 'INT',
            'title' => 'VARCHAR(255) NOT NULL',
            'message' => 'TEXT NOT NULL',
            'is_read' => 'TINYINT(1) DEFAULT 0',
            'created_at' => 'DATETIME',
            'updated_at' => 'DATETIME'
        ]
    ],
    'action_ledger' => [
        'columns' => [
            'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
            'user_id' => 'INT',
            'action' => 'VARCHAR(255) NOT NULL',
            'description' => 'TEXT',
            'ip_address' => 'VARCHAR(45)',
            'created_at' => 'DATETIME',
            'updated_at' => 'DATETIME'
        ]
    ],
    'scrip_data' => [
        'columns' => [
            'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
            'symbol' => 'VARCHAR(20) NOT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'exchange' => 'VARCHAR(20) NOT NULL',
            'last_price' => 'DECIMAL(10,2)',
            'change' => 'DECIMAL(10,2)',
            'change_percent' => 'DECIMAL(5,2)',
            'volume' => 'BIGINT',
            'updated_at' => 'DATETIME'
        ]
    ],
    'market_scripts' => [
        'columns' => [
            'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
            'symbol' => 'VARCHAR(20) NOT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'exchange' => 'VARCHAR(20) NOT NULL',
            'is_active' => 'TINYINT(1) DEFAULT 1',
            'created_at' => 'DATETIME',
            'updated_at' => 'DATETIME'
        ]
    ],
    'brokers' => [
        'columns' => [
            'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
            'name' => 'VARCHAR(255) NOT NULL',
            'api_key' => 'VARCHAR(255)',
            'api_secret' => 'VARCHAR(255)',
            'status' => 'TINYINT(1) DEFAULT 1',
            'created_at' => 'DATETIME',
            'updated_at' => 'DATETIME'
        ]
    ],
    'social_links' => [
        'columns' => [
            'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
            'platform' => 'VARCHAR(50) NOT NULL',
            'url' => 'VARCHAR(255) NOT NULL',
            'icon' => 'VARCHAR(50)',
            'is_active' => 'TINYINT(1) DEFAULT 1',
            'created_at' => 'DATETIME',
            'updated_at' => 'DATETIME'
        ]
    ],
    'admins' => [
        'columns' => [
            'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
            'username' => 'VARCHAR(255) NOT NULL',
            'email' => 'VARCHAR(255) NOT NULL',
            'password' => 'VARCHAR(255) NOT NULL',
            'name' => 'VARCHAR(255)',
            'role' => 'VARCHAR(50) DEFAULT "admin"',
            'status' => 'TINYINT(1) DEFAULT 1',
            'created_at' => 'DATETIME',
            'updated_at' => 'DATETIME'
        ]
    ]
];

// Create tables
foreach ($tables as $table_name => $table_def) {
    try {
        $columns = [];
        foreach ($table_def['columns'] as $column_name => $column_def) {
            $columns[] = "`$column_name` $column_def";
        }
        
        $sql = "CREATE TABLE `$table_name` (\n" . implode(",\n", $columns) . "\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
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
