<?php
/**
 * List MS SQL Server Tables
 * 
 * This script attempts to connect to MS SQL Server and list all tables
 * using various connection methods.
 */

// MS SQL Server connection parameters
$mssql_server = '65.108.2.70';
$mssql_port = '1433';
$mssql_database = 'TradeView';
$mssql_user = 'TradeView';
$mssql_password = 'yw^k188C3@Iii90g';

echo "Attempting to connect to MS SQL Server and list tables...\n";

// Try different connection methods
$methods = ['sqlsrv', 'odbc', 'pdo_sqlsrv', 'pdo_odbc', 'dblib'];
$connected = false;

foreach ($methods as $method) {
    echo "\nTrying connection method: $method\n";
    
    try {
        switch ($method) {
            case 'sqlsrv':
                if (!function_exists('sqlsrv_connect')) {
                    echo "  Function sqlsrv_connect not available. Skipping...\n";
                    continue 2;
                }
                
                $conn = sqlsrv_connect("$mssql_server,$mssql_port", [
                    'Database' => $mssql_database,
                    'UID' => $mssql_user,
                    'PWD' => $mssql_password
                ]);
                
                if ($conn === false) {
                    echo "  Connection failed: " . print_r(sqlsrv_errors(), true) . "\n";
                    continue 2;
                }
                
                $query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_CATALOG='$mssql_database'";
                $result = sqlsrv_query($conn, $query);
                
                if ($result === false) {
                    echo "  Query failed: " . print_r(sqlsrv_errors(), true) . "\n";
                    continue 2;
                }
                
                $tables = [];
                while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                    $tables[] = $row['TABLE_NAME'];
                }
                
                sqlsrv_free_stmt($result);
                sqlsrv_close($conn);
                break;
                
            case 'odbc':
                if (!function_exists('odbc_connect')) {
                    echo "  Function odbc_connect not available. Skipping...\n";
                    continue 2;
                }
                
                $conn_str = "Driver={SQL Server};Server=$mssql_server,$mssql_port;Database=$mssql_database;";
                $conn = odbc_connect($conn_str, $mssql_user, $mssql_password);
                
                if (!$conn) {
                    echo "  Connection failed: " . odbc_errormsg() . "\n";
                    continue 2;
                }
                
                $query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_CATALOG='$mssql_database'";
                $result = odbc_exec($conn, $query);
                
                if (!$result) {
                    echo "  Query failed: " . odbc_errormsg() . "\n";
                    continue 2;
                }
                
                $tables = [];
                while ($row = odbc_fetch_array($result)) {
                    $tables[] = $row['TABLE_NAME'];
                }
                
                odbc_free_result($result);
                odbc_close($conn);
                break;
                
            case 'pdo_sqlsrv':
                if (!in_array('sqlsrv', PDO::getAvailableDrivers())) {
                    echo "  PDO sqlsrv driver not available. Skipping...\n";
                    continue 2;
                }
                
                $dsn = "sqlsrv:Server=$mssql_server,$mssql_port;Database=$mssql_database";
                $pdo = new PDO($dsn, $mssql_user, $mssql_password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_CATALOG='$mssql_database'";
                $stmt = $pdo->query($query);
                
                $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
                break;
                
            case 'pdo_odbc':
                if (!in_array('odbc', PDO::getAvailableDrivers())) {
                    echo "  PDO ODBC driver not available. Skipping...\n";
                    continue 2;
                }
                
                $dsn = "odbc:Driver={SQL Server};Server=$mssql_server,$mssql_port;Database=$mssql_database";
                $pdo = new PDO($dsn, $mssql_user, $mssql_password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_CATALOG='$mssql_database'";
                $stmt = $pdo->query($query);
                
                $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
                break;
                
            case 'dblib':
                if (!in_array('dblib', PDO::getAvailableDrivers())) {
                    echo "  PDO dblib driver not available. Skipping...\n";
                    continue 2;
                }
                
                $dsn = "dblib:host=$mssql_server:$mssql_port;dbname=$mssql_database";
                $pdo = new PDO($dsn, $mssql_user, $mssql_password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_CATALOG='$mssql_database'";
                $stmt = $pdo->query($query);
                
                $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
                break;
        }
        
        $connected = true;
        break;
    } catch (Exception $e) {
        echo "  Error: " . $e->getMessage() . "\n";
        continue;
    }
}

if (!$connected) {
    echo "\nFailed to connect to MS SQL Server using any available method.\n";
    echo "Please make sure you have the necessary extensions installed.\n";
    echo "You may need to install one of the following PHP extensions:\n";
    echo "- sqlsrv and pdo_sqlsrv (Microsoft SQL Server Driver for PHP)\n";
    echo "- odbc and pdo_odbc (ODBC Driver)\n";
    echo "- dblib (FreeTDS)\n";
    exit(1);
}

// Output the table list
echo "\nSuccessfully connected using method: $method\n";
echo "Found " . count($tables) . " tables in database '$mssql_database':\n";

foreach ($tables as $index => $table) {
    echo ($index + 1) . ". $table\n";
}

// Save table list to a file
$output_file = __DIR__ . '/mssql_tables.txt';
file_put_contents($output_file, implode("\n", $tables));
echo "\nTable list saved to: $output_file\n";

// Generate MySQL creation script
$mysql_script = __DIR__ . '/create_mysql_tables.sql';
$sql = "-- MySQL table creation script generated from MS SQL Server database '$mssql_database'\n";
$sql .= "-- Generated on: " . date('Y-m-d H:i:s') . "\n\n";
$sql .= "CREATE DATABASE IF NOT EXISTS `tradeview` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\n";
$sql .= "USE `tradeview`;\n\n";

foreach ($tables as $table) {
    $sql .= "-- Table structure for table `$table`\n";
    $sql .= "DROP TABLE IF EXISTS `$table`;\n";
    $sql .= "CREATE TABLE `$table` (\n";
    $sql .= "  `id` int(11) NOT NULL AUTO_INCREMENT,\n";
    $sql .= "  -- Add your columns here based on the original table structure\n";
    $sql .= "  PRIMARY KEY (`id`)\n";
    $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";
}

file_put_contents($mysql_script, $sql);
echo "MySQL creation script template saved to: $mysql_script\n";
echo "You'll need to manually add the column definitions based on your MS SQL Server schema.\n";
?>
