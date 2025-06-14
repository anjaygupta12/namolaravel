<?php

// Script to add additional tables from MS SQL Server schema to the MySQL database

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

// Define additional tables based on the MS SQL Server schema
$tables = [
    'broker_deletehistory' => "
        CREATE TABLE IF NOT EXISTS `broker_deletehistory` (
            `DeleteId` INT AUTO_INCREMENT NOT NULL,
            `BrokerId` INT NULL,
            `Username` VARCHAR(100) NULL,
            `FirstName` VARCHAR(100) NULL,
            `LastName` VARCHAR(100) NULL,
            `RefCode` VARCHAR(100) NULL,
            `UserType` VARCHAR(50) NULL,
            `DeletedBy` INT NULL,
            `DeletedAt` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`DeleteId`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'ClientSubscription' => "
        CREATE TABLE IF NOT EXISTS `ClientSubscription` (
            `PK_ID` BIGINT AUTO_INCREMENT NOT NULL,
            `Symbol` VARCHAR(50) NULL,
            `UserId` BIGINT NULL,
            `Timestamp` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
            `LastModify` DATETIME NULL,
            `Isactive` TINYINT(1) NULL DEFAULT 1,
            PRIMARY KEY (`PK_ID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'CloseMarketPlaceMaster' => "
        CREATE TABLE IF NOT EXISTS `CloseMarketPlaceMaster` (
            `Pk_id` BIGINT AUTO_INCREMENT NOT NULL,
            `Fk_Id` BIGINT NULL,
            `TransactionId` VARCHAR(50) NULL,
            `Mode` VARCHAR(50) NULL,
            `ToAmount` DECIMAL(18, 2) NULL,
            `TransactionMode` VARCHAR(10) NULL,
            `Bid` DECIMAL(18, 2) NULL,
            `Ask` DECIMAL(18, 2) NULL,
            `High` DECIMAL(18, 2) NULL,
            `Low` DECIMAL(18, 2) NULL,
            `TradeLast` DECIMAL(18, 2) NULL,
            `Change` DECIMAL(18, 2) NULL,
            `TradeOpen` DECIMAL(18, 2) NULL,
            `Volume` DECIMAL(18, 2) NULL,
            `LastTradeQty` DECIMAL(18, 2) NULL,
            `Atp` DECIMAL(18, 2) NULL,
            `LotSize` DECIMAL(18, 2) NULL,
            `OpenInterest` DECIMAL(18, 2) NULL,
            `BidQty` DECIMAL(18, 2) NULL,
            `AskQty` DECIMAL(18, 2) NULL,
            `PrevClose` DECIMAL(18, 2) NULL,
            `UpperCircuit` DECIMAL(18, 2) NULL,
            `LowerCircuit` DECIMAL(18, 2) NULL,
            `Timestamp` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
            `Lastmodify` DATETIME NULL,
            `Isactive` TINYINT(1) NULL DEFAULT 1,
            `UserId` VARCHAR(100) NULL,
            `Symbol` VARCHAR(50) NULL,
            `IsMin` CHAR(1) NULL,
            `IsMega` CHAR(1) NULL,
            `Lots` DECIMAL(18, 2) NULL,
            `Price` DECIMAL(18, 2) NULL,
            `Status_Exec` VARCHAR(50) NULL DEFAULT 'Pending',
            `Exitrate` DECIMAL(18, 2) NULL DEFAULT 0,
            `BUYPRICE` DECIMAL(18, 2) NULL,
            `SELLPRICE` DECIMAL(18, 2) NULL,
            PRIMARY KEY (`Pk_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'LedgerHistory' => "
        CREATE TABLE IF NOT EXISTS `LedgerHistory` (
            `PK_ID` BIGINT AUTO_INCREMENT NOT NULL,
            `MESSAGETYPE` VARCHAR(500) NULL,
            `TIMESTAMP` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
            `LASTMODIFY` DATETIME NULL,
            `IS_ADMIN` CHAR(1) NULL,
            `ISACTIVE` TINYINT(1) NULL DEFAULT 1,
            PRIMARY KEY (`PK_ID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'MarketBidMaster' => "
        CREATE TABLE IF NOT EXISTS `MarketBidMaster` (
            `Pk_id` BIGINT AUTO_INCREMENT NOT NULL,
            `TransactionId` VARCHAR(50) NULL,
            `Mode` VARCHAR(50) NULL,
            `ToAmount` DECIMAL(18, 2) NULL,
            `TransactionMode` VARCHAR(10) NULL,
            `SalePrice` DECIMAL(18, 2) NULL,
            `BuyPrice` DECIMAL(18, 2) NULL,
            `Bid` DECIMAL(18, 2) NULL,
            `Ask` DECIMAL(18, 2) NULL,
            `High` DECIMAL(18, 2) NULL,
            `Low` DECIMAL(18, 2) NULL,
            `TradeLast` DECIMAL(18, 2) NULL,
            `Change` DECIMAL(18, 2) NULL,
            `TradeOpen` DECIMAL(18, 2) NULL,
            `Volume` DECIMAL(18, 2) NULL,
            `LastTradeQty` DECIMAL(18, 2) NULL,
            `Atp` DECIMAL(18, 2) NULL,
            `LotSize` DECIMAL(18, 2) NULL,
            `OpenInterest` DECIMAL(18, 2) NULL,
            `BidQty` DECIMAL(18, 2) NULL,
            `AskQty` DECIMAL(18, 2) NULL,
            `PrevClose` DECIMAL(18, 2) NULL,
            `UpperCircuit` DECIMAL(18, 2) NULL,
            `LowerCircuit` DECIMAL(18, 2) NULL,
            `Timestamp` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
            `Lastmodify` DATETIME NULL,
            `Isactive` TINYINT(1) NULL DEFAULT 1,
            `UserId` VARCHAR(100) NULL,
            `Symbol` VARCHAR(50) NULL,
            `IsMin` CHAR(1) NULL,
            `IsMega` CHAR(1) NULL,
            `Lots` DECIMAL(18, 2) NULL,
            `Price` DECIMAL(18, 2) NULL,
            `IpAddress` VARCHAR(100) NULL,
            PRIMARY KEY (`Pk_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'MarketMappingUser' => "
        CREATE TABLE IF NOT EXISTS `MarketMappingUser` (
            `PK_ID` BIGINT AUTO_INCREMENT NOT NULL,
            `UserId` BIGINT NULL,
            `ScriptId` BIGINT NULL,
            `MarketType` VARCHAR(25) NULL,
            `Timestamp` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
            `LastModify` DATETIME NULL,
            `Isactive` TINYINT(1) NULL DEFAULT 1,
            PRIMARY KEY (`PK_ID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'MarketPlaceMaster' => "
        CREATE TABLE IF NOT EXISTS `MarketPlaceMaster` (
            `Pk_id` BIGINT AUTO_INCREMENT NOT NULL,
            `TransactionId` VARCHAR(50) NULL,
            `Mode` VARCHAR(50) NULL,
            `ToAmount` DECIMAL(18, 2) NULL,
            `TransactionMode` VARCHAR(10) NULL,
            `Bid` DECIMAL(18, 2) NULL,
            `Ask` DECIMAL(18, 2) NULL,
            `High` DECIMAL(18, 2) NULL,
            `Low` DECIMAL(18, 2) NULL,
            `TradeLast` DECIMAL(18, 2) NULL,
            `Change` DECIMAL(18, 2) NULL,
            `TradeOpen` DECIMAL(18, 2) NULL,
            `Volume` DECIMAL(18, 2) NULL,
            `LastTradeQty` DECIMAL(18, 2) NULL,
            `Atp` DECIMAL(18, 2) NULL,
            `LotSize` DECIMAL(18, 2) NULL,
            `OpenInterest` DECIMAL(18, 2) NULL,
            `BidQty` DECIMAL(18, 2) NULL,
            `AskQty` DECIMAL(18, 2) NULL,
            `PrevClose` DECIMAL(18, 2) NULL,
            `UpperCircuit` DECIMAL(18, 2) NULL,
            `LowerCircuit` DECIMAL(18, 2) NULL,
            `Timestamp` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
            `Lastmodify` DATETIME NULL,
            `Isactive` TINYINT(1) NULL DEFAULT 1,
            `UserId` VARCHAR(100) NULL,
            `Symbol` VARCHAR(50) NULL,
            `IsMin` CHAR(1) NULL,
            `IsMega` CHAR(1) NULL,
            `Lots` DECIMAL(18, 2) NULL,
            `Price` DECIMAL(18, 2) NULL,
            `Status_Exec` VARCHAR(50) NULL DEFAULT 'Pending',
            `Exitrate` DECIMAL(18, 2) NULL DEFAULT 0,
            `BUYPRICE` DECIMAL(18, 2) NULL,
            `SELLPRICE` DECIMAL(18, 2) NULL,
            `IpAddress` VARCHAR(100) NULL,
            PRIMARY KEY (`Pk_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'TradeAccountDetail' => "
        CREATE TABLE IF NOT EXISTS `TradeAccountDetail` (
            `PK_Id` BIGINT AUTO_INCREMENT NOT NULL,
            `UserId` BIGINT NULL,
            `FullName` VARCHAR(100) NULL,
            `Mobile` VARCHAR(15) NULL,
            `OptionalMobile` VARCHAR(15) NULL,
            `Username` VARCHAR(100) NULL,
            `Password` VARCHAR(100) NULL,
            `OptionalPassword` VARCHAR(100) NULL,
            `City` VARCHAR(100) NULL,
            `OptionalCity` VARCHAR(100) NULL,
            `ConfigRemark` VARCHAR(250) NULL,
            `ConfigdemoAccount` TINYINT(1) NULL,
            `ConfigAllowFreshEntryOrder` TINYINT(1) NULL,
            `ConfigAllowOrdersbetweenHighLow` TINYINT(1) NULL,
            `ConfigTradeequityasunits` TINYINT(1) NULL,
            `ConfigAccountStatus` TINYINT(1) NULL,
            `ConfigAutoCloseTrades` TINYINT(1) NULL,
            `ConfigautoCloseall` DECIMAL(10, 2) NULL,
            `ConfigNotifyclient` DECIMAL(10, 2) NULL,
            `ConfigMinTimeprofit` DECIMAL(10, 2) NULL,
            `MCXTrading` TINYINT(1) NULL,
            `MCXMinimumlotsize` DECIMAL(10, 2) NULL,
            `MCXMaximumlotsize` DECIMAL(10, 2) NULL,
            `MCXMaximumlotsizeactively` DECIMAL(10, 2) NULL,
            `MCXMaxSizeAllCommodity` DECIMAL(10, 2) NULL,
            `MCXBrokerageType` VARCHAR(100) NULL,
            `MCXbrokerage` DECIMAL(10, 2) NULL,
            `MCXExposure` VARCHAR(100) NULL,
            `MCXIntradayExposure` DECIMAL(10, 2) NULL,
            `MCXHoldingExposure` DECIMAL(10, 2) NULL,
            `Timestamp` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
            `LastModify` DATETIME NULL,
            `Isactive` TINYINT(1) NULL DEFAULT 1,
            PRIMARY KEY (`PK_Id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'Transdetail' => "
        CREATE TABLE IF NOT EXISTS `Transdetail` (
            `Pk_id` BIGINT AUTO_INCREMENT NOT NULL,
            `MemberId` BIGINT NULL,
            `TransType` VARCHAR(50) NULL,
            `TransPage` VARCHAR(50) NULL,
            `Type` CHAR(1) NULL,
            `TransDate` DATETIME NULL,
            `Amount` DECIMAL(18, 2) NULL,
            `AmountS` DECIMAL(18, 2) NULL,
            `Remark` VARCHAR(100) NULL,
            `LoginId` VARCHAR(50) NULL,
            `Pass` VARCHAR(50) NULL,
            `Expass` VARCHAR(50) NULL,
            `BitIsActive` TINYINT(1) NULL DEFAULT 1,
            `CounterId` BIGINT NULL,
            `eWalletBit` TINYINT(1) NULL,
            `PayoutId` BIGINT NULL DEFAULT 0,
            `tmpStr` VARCHAR(55) NULL,
            `productcode` BIGINT NULL,
            `RefTransID` BIGINT NULL,
            `AddRemark` VARCHAR(150) NULL,
            `ProductClaim` VARCHAR(50) NULL,
            `PayMode` VARCHAR(20) NULL,
            `PayRemark` VARCHAR(50) NULL,
            `TransId` BIGINT NULL,
            `TransHash` VARCHAR(250) NULL,
            `VoucherNO` VARCHAR(20) NULL,
            `TopUpTranId` VARCHAR(20) NULL,
            `AdminStatus` VARCHAR(50) NULL DEFAULT 'Pending',
            PRIMARY KEY (`Pk_id`)
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

echo "\nAdditional tables creation completed!\n";
echo "Total tables added: " . count($tables) . "\n";
?>
