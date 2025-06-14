<?php

// MySQL Database Creation Script to replicate MS SQL Server schema
// This script creates a MySQL database with tables matching the MS SQL Server schema

// Database connection parameters
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'tradeview';

// Connect to MySQL server
echo "Starting MySQL database creation...\n";
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'BankMaster' => "
        CREATE TABLE `BankMaster` (
            `PK_ID` BIGINT AUTO_INCREMENT NOT NULL,
            `AdminID` BIGINT NULL,
            `AccountHolder` VARCHAR(100) NULL,
            `AccountNumber` VARCHAR(100) NULL,
            `BankName` VARCHAR(100) NULL,
            `IFSC` VARCHAR(100) NULL,
            `PhonePe` VARCHAR(100) NULL,
            `GooglePay` VARCHAR(100) NULL,
            `Paytm` VARCHAR(100) NULL,
            `UPIID` VARCHAR(100) NULL,
            `QRCode` VARCHAR(100) NULL,
            `Timestamp` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
            `LastModify` DATETIME NULL,
            `Isactive` TINYINT(1) NULL DEFAULT 1,
            PRIMARY KEY (`PK_ID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'Brokers' => "
        CREATE TABLE `Brokers` (
            `BrokerId` INT AUTO_INCREMENT NOT NULL,
            `FirstName` VARCHAR(100) NULL,
            `LastName` VARCHAR(100) NULL,
            `Username` VARCHAR(50) NULL,
            `Password` VARCHAR(100) NULL,
            `TransactionPassword` VARCHAR(100) NULL,
            `RefCode` VARCHAR(50) NULL,
            `UserType` VARCHAR(20) NULL,
            `AccountStatus` TINYINT(1) NULL DEFAULT 1,
            `AutoSquareOffPercentage` DECIMAL(5, 2) NULL DEFAULT 90.00,
            `NotifyPercentage` DECIMAL(5, 2) NULL DEFAULT 70.00,
            `ProfitShare` DECIMAL(5, 2) NULL DEFAULT 0.00,
            `BrokerageShare` DECIMAL(5, 2) NULL DEFAULT 50.00,
            `ClientsLimit` INT NULL DEFAULT 10,
            `SubBrokersLimit` INT NULL DEFAULT 1,
            `SubBrokerTasksAllowed` TINYINT(1) NULL DEFAULT 0,
            `PayinAllowed` TINYINT(1) NULL DEFAULT 0,
            `PayoutAllowed` TINYINT(1) NULL DEFAULT 0,
            `CreateClientAllowed` TINYINT(1) NULL DEFAULT 0,
            `ClientTasksAllowed` TINYINT(1) NULL DEFAULT 0,
            `TradeActivityAllowed` TINYINT(1) NULL DEFAULT 0,
            `NotificationsAllowed` TINYINT(1) NULL DEFAULT 0,
            `MCXEnabled` TINYINT(1) NULL DEFAULT 1,
            `MCXBrokerageType` VARCHAR(20) NULL DEFAULT 'per_crore',
            `MCXBrokerage` DECIMAL(10, 2) NULL DEFAULT 800.00,
            `MCXExposureType` VARCHAR(20) NULL DEFAULT 'per_turnover',
            `MCXIntradayMargin` DECIMAL(10, 2) NULL DEFAULT 500.00,
            `MCXHoldingMargin` DECIMAL(10, 2) NULL DEFAULT 100.00,
            `NSEEnabled` TINYINT(1) NULL DEFAULT 1,
            `NSEBrokerage` DECIMAL(10, 2) NULL DEFAULT 800.00,
            `NSEIntradayMargin` DECIMAL(10, 2) NULL DEFAULT 500.00,
            `NSEHoldingMargin` DECIMAL(10, 2) NULL DEFAULT 100.00,
            `CreatedDate` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
            `CreatedBy` INT NULL,
            PRIMARY KEY (`BrokerId`),
            UNIQUE KEY `Username` (`Username`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'ForexOptions' => "
        CREATE TABLE `ForexOptions` (
            `Id` BIGINT NOT NULL,
            `Symbol` VARCHAR(50) NULL,
            `Description` VARCHAR(100) NULL,
            `Exchange` INT NULL,
            `Segment` INT NULL,
            `TickSize` DECIMAL(10, 6) NULL,
            `TradingSession` VARCHAR(100) NULL,
            `ExpiryDate` DATE NULL,
            `InstrumentToken` BIGINT NULL,
            `ExchangeInstrument` VARCHAR(100) NULL,
            `LotSize` INT NULL,
            `InstrumentType` INT NULL,
            `FyToken` INT NULL,
            `Underlying` VARCHAR(20) NULL,
            `Multiplier` INT NULL,
            `StrikePrice` DECIMAL(10, 2) NULL,
            `OptionType` CHAR(2) NULL,
            `FyTokenUnderlying` BIGINT NULL,
            `LastUpdateDate` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
            `Isactive` TINYINT(1) NULL DEFAULT 1,
            `ExName` VARCHAR(50) NULL,
            `SymbolShortName` VARCHAR(50) NULL,
            `DataDate` DATE NULL,
            `instrument` VARCHAR(50) NULL,
            PRIMARY KEY (`Id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'LoginMaster' => "
        CREATE TABLE `LoginMaster` (
            `PK_ID` BIGINT AUTO_INCREMENT NOT NULL,
            `FullName` VARCHAR(100) NULL,
            `Mobile` VARCHAR(15) NULL,
            `UserId` VARCHAR(50) NULL,
            `Password` VARCHAR(100) NULL,
            `RefferalCode` VARCHAR(15) NULL,
            `Timestamp` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
            `LasmtModify` DATETIME NULL,
            `Isactive` TINYINT(1) NULL DEFAULT 1,
            `TransPass` VARCHAR(50) NULL,
            PRIMARY KEY (`PK_ID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'MarketMaster' => "
        CREATE TABLE `MarketMaster` (
            `ScriptId` BIGINT AUTO_INCREMENT NOT NULL,
            `ScriptName` VARCHAR(50) NULL,
            `MarketType` VARCHAR(50) NULL,
            `LotSize` DECIMAL(18, 4) NULL,
            `TickSize` DECIMAL(18, 4) NULL,
            `Timestamp` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
            `CreatedBy` VARCHAR(50) NULL,
            `CreatedAt` VARCHAR(50) NULL,
            `UpdatedBy` VARCHAR(50) NULL,
            `UpdatedAt` VARCHAR(50) NULL,
            `LastModify` DATETIME NULL,
            `Isactive` TINYINT(1) NULL DEFAULT 1,
            PRIMARY KEY (`ScriptId`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'SocialLink' => "
        CREATE TABLE `SocialLink` (
            `Pk_Id` BIGINT AUTO_INCREMENT NOT NULL,
            `Telegram` VARCHAR(100) NULL,
            `Facebook` VARCHAR(100) NULL,
            `Whatsapp` VARCHAR(100) NULL,
            `Youtube` VARCHAR(100) NULL,
            `Instragram` VARCHAR(100) NULL,
            `Timestamp` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
            `Lastmodify` DATETIME NULL,
            `Isactive` TINYINT(1) NULL DEFAULT 1,
            PRIMARY KEY (`Pk_Id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'TradeUser' => "
        CREATE TABLE `TradeUser` (
            `UserId` INT AUTO_INCREMENT NOT NULL,
            `FullName` VARCHAR(100) NOT NULL,
            `Username` VARCHAR(50) NOT NULL,
            `Password` VARCHAR(100) NOT NULL,
            `Email` VARCHAR(100) NOT NULL,
            `Mobile` VARCHAR(15) NOT NULL,
            `Address` VARCHAR(500) NULL,
            `City` VARCHAR(100) NULL,
            `State` VARCHAR(100) NULL,
            `PinCode` VARCHAR(10) NULL,
            `PAN` VARCHAR(10) NULL,
            `Aadhar` VARCHAR(12) NULL,
            `BankName` VARCHAR(100) NULL,
            `AccountNumber` VARCHAR(50) NULL,
            `IFSCCode` VARCHAR(20) NULL,
            `AccountHolderName` VARCHAR(100) NULL,
            `IsActive` TINYINT(1) NULL DEFAULT 1,
            `IsDemo` TINYINT(1) NULL DEFAULT 0,
            `AllowOrdersBeyondHighLow` TINYINT(1) NULL DEFAULT 1,
            `AllowOrdersBetweenHighLow` TINYINT(1) NULL DEFAULT 1,
            `TradeEquityAsUnits` TINYINT(1) NULL DEFAULT 1,
            `AutoSquareOff` TINYINT(1) NULL DEFAULT 1,
            `AutoSquareOffPercentage` DECIMAL(5, 2) NULL DEFAULT 90.00,
            `NotifyPercentage` DECIMAL(5, 2) NULL DEFAULT 80.00,
            `TransPass` VARCHAR(50) NULL,
            `RefferalCode` VARCHAR(50) NULL,
            `CreatedDate` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`UserId`),
            UNIQUE KEY `Username` (`Username`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'WithdrawlMaster' => "
        CREATE TABLE `WithdrawlMaster` (
            `PK_Id` BIGINT AUTO_INCREMENT NOT NULL,
            `UserId` BIGINT NULL,
            `PaymentMethod` VARCHAR(50) NULL,
            `Amount` DECIMAL(18, 2) NULL,
            `Mobile` VARCHAR(15) NULL,
            `AccountHolder` VARCHAR(50) NULL,
            `AccountNo` VARCHAR(50) NULL,
            `IFSC` VARCHAR(25) NULL,
            `Status` VARCHAR(50) NULL DEFAULT 'PENDING',
            `Timestamp` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
            `LastModify` DATETIME NULL,
            `Isactive` TINYINT(1) NULL DEFAULT 1,
            PRIMARY KEY (`PK_Id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'DepositeMaster' => "
        CREATE TABLE `DepositeMaster` (
            `PK_Id` BIGINT AUTO_INCREMENT NOT NULL,
            `UserId` BIGINT NULL,
            `Amount` DECIMAL(18, 2) NULL,
            `ScreenShot` VARCHAR(100) NULL,
            `Approve_Status` VARCHAR(25) NULL,
            `Approve_date` DATETIME NULL,
            `Timestamp` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
            `LastModify` DATETIME NULL,
            `Isactive` TINYINT(1) NULL DEFAULT 1,
            PRIMARY KEY (`PK_Id`)
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
    // Sample admin user
    $conn->exec("INSERT INTO `AdminLogin` (`UserId`, `Password`, `Isactive`, `TransPass`) 
                VALUES ('admin', '" . password_hash('admin123', PASSWORD_DEFAULT) . "', 1, '" . password_hash('123456', PASSWORD_DEFAULT) . "')");
    
    // Sample social links
    $conn->exec("INSERT INTO `SocialLink` (`Telegram`, `Facebook`, `Whatsapp`, `Youtube`, `Instragram`) 
                VALUES ('https://t.me/namotraders', 'https://facebook.com/namotraders', '+919876543210', 'https://youtube.com/namotraders', 'https://instagram.com/namotraders')");
    
    // Sample market data
    $conn->exec("INSERT INTO `MarketMaster` (`ScriptName`, `MarketType`, `LotSize`, `TickSize`) 
                VALUES ('NIFTY', 'INDEX', 50, 0.05)");
    $conn->exec("INSERT INTO `MarketMaster` (`ScriptName`, `MarketType`, `LotSize`, `TickSize`) 
                VALUES ('BANKNIFTY', 'INDEX', 25, 0.05)");
    $conn->exec("INSERT INTO `MarketMaster` (`ScriptName`, `MarketType`, `LotSize`, `TickSize`) 
                VALUES ('RELIANCE', 'EQUITY', 1, 0.05)");
                
    // Sample forex options
    $conn->exec("INSERT INTO `ForexOptions` (`Id`, `Symbol`, `Description`, `Exchange`, `LotSize`, `StrikePrice`, `OptionType`, `Isactive`) 
                VALUES (1, 'EURUSD', 'Euro vs US Dollar', 1, 1000, 1.08, 'CE', 1)");
    $conn->exec("INSERT INTO `ForexOptions` (`Id`, `Symbol`, `Description`, `Exchange`, `LotSize`, `StrikePrice`, `OptionType`, `Isactive`) 
                VALUES (2, 'GBPUSD', 'British Pound vs US Dollar', 1, 1000, 1.25, 'CE', 1)");
    
    // Sample user
    $conn->exec("INSERT INTO `TradeUser` (`FullName`, `Username`, `Password`, `Email`, `Mobile`, `IsActive`, `IsDemo`) 
                VALUES ('Demo User', 'demo', '" . password_hash('demo123', PASSWORD_DEFAULT) . "', 'demo@example.com', '9876543210', 1, 1)");
    
    echo "Inserted sample data.\n";
} catch(PDOException $e) {
    echo "Error inserting sample data: " . $e->getMessage() . "\n";
}

echo "\nDatabase creation completed!\n";
echo "You can now connect to the MySQL database with the following details:\n";
echo "Host: localhost\n";
echo "Database: $database\n";
echo "Username: $username\n";
echo "Password: $password\n";
?>
