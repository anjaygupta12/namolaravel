<?php
/**
 * MS SQL to MySQL Database Migration Script - Export Mode
 * 
 * This script connects to an MS SQL Server database, extracts all tables structure
 * and data, and generates SQL files that can be imported into MySQL.
 */

// MS SQL Server connection parameters (from web.config)
$mssql_server = '65.108.2.70';
$mssql_port = '1433';
$mssql_database = 'TradeView';
$mssql_user = 'TradeView';
$mssql_password = 'yw^k188C3@Iii90g';

// Output directory for SQL files
$output_dir = __DIR__ . '/sql_export';
if (!file_exists($output_dir)) {
    mkdir($output_dir, 0777, true);
}

echo "Starting MS SQL to MySQL migration (export mode)...\n";

// Connect to MS SQL Server using ODBC
try {
    // Create ODBC connection string
    $conn_str = "Driver={SQL Server};Server=$mssql_server,$mssql_port;Database=$mssql_database;Uid=$mssql_user;Pwd=$mssql_password;";
    
    // Connect using ODBC
    $conn = odbc_connect($conn_str, $mssql_user, $mssql_password);
    
    if (!$conn) {
        throw new Exception("ODBC Connection failed: " . odbc_errormsg());
    }
    
    echo "Connected to MS SQL Server successfully.\n";
} catch (Exception $e) {
    die("MS SQL Server Connection failed: " . $e->getMessage() . "\n");
}

// Create schema file
$schema_file = fopen($output_dir . '/schema.sql', 'w');
fwrite($schema_file, "-- MySQL Schema generated from MS SQL Server\n");
fwrite($schema_file, "-- Generated on: " . date('Y-m-d H:i:s') . "\n\n");
fwrite($schema_file, "SET FOREIGN_KEY_CHECKS=0;\n");
fwrite($schema_file, "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n");
fwrite($schema_file, "SET time_zone = \"+00:00\";\n\n");
fwrite($schema_file, "CREATE DATABASE IF NOT EXISTS `tradeview` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\n");
fwrite($schema_file, "USE `tradeview`;\n\n");

// Get all tables
$tables_query = odbc_exec($conn, "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_CATALOG='$mssql_database'");
$tables = array();
while ($row = odbc_fetch_array($tables_query)) {
    $tables[] = $row['TABLE_NAME'];
}

echo "Found " . count($tables) . " tables.\n";

// Data type mapping from MS SQL to MySQL
function mapDataType($mssql_type, $max_length = null, $precision = null, $scale = null) {
    $type = strtolower($mssql_type);
    
    switch ($type) {
        case 'bigint':
            return 'BIGINT';
        case 'binary':
            return 'BINARY';
        case 'bit':
            return 'TINYINT(1)';
        case 'char':
            return 'CHAR(' . ($max_length != -1 ? $max_length : 255) . ')';
        case 'date':
            return 'DATE';
        case 'datetime':
        case 'datetime2':
        case 'smalldatetime':
            return 'DATETIME';
        case 'datetimeoffset':
            return 'DATETIME';
        case 'decimal':
        case 'numeric':
            return "DECIMAL($precision, $scale)";
        case 'float':
            return 'FLOAT';
        case 'image':
            return 'LONGBLOB';
        case 'int':
            return 'INT';
        case 'money':
            return 'DECIMAL(19,4)';
        case 'nchar':
            return 'CHAR(' . ($max_length != -1 ? $max_length : 255) . ')';
        case 'ntext':
            return 'LONGTEXT';
        case 'nvarchar':
            return $max_length == -1 ? 'LONGTEXT' : "VARCHAR($max_length)";
        case 'real':
            return 'FLOAT';
        case 'smallint':
            return 'SMALLINT';
        case 'smallmoney':
            return 'DECIMAL(10,4)';
        case 'text':
            return 'LONGTEXT';
        case 'time':
            return 'TIME';
        case 'timestamp':
            return 'BINARY(8)';
        case 'tinyint':
            return 'TINYINT';
        case 'uniqueidentifier':
            return 'VARCHAR(36)';
        case 'varbinary':
            return $max_length == -1 ? 'LONGBLOB' : "VARBINARY($max_length)";
        case 'varchar':
            return $max_length == -1 ? 'LONGTEXT' : "VARCHAR($max_length)";
        case 'xml':
            return 'LONGTEXT';
        default:
            return 'VARCHAR(255)';
    }
}

// Process each table
foreach ($tables as $table) {
    echo "\nProcessing table: $table\n";
    
    // Get table columns
    $columns_query = odbc_exec($conn, "
        SELECT 
            c.COLUMN_NAME, 
            c.DATA_TYPE,
            c.CHARACTER_MAXIMUM_LENGTH,
            c.NUMERIC_PRECISION,
            c.NUMERIC_SCALE,
            c.IS_NULLABLE,
            c.COLUMN_DEFAULT,
            CASE WHEN pk.COLUMN_NAME IS NOT NULL THEN 'PRI' ELSE '' END AS COLUMN_KEY
        FROM 
            INFORMATION_SCHEMA.COLUMNS c
        LEFT JOIN (
            SELECT ku.TABLE_CATALOG, ku.TABLE_SCHEMA, ku.TABLE_NAME, ku.COLUMN_NAME
            FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS AS tc
            JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE AS ku
                ON tc.CONSTRAINT_TYPE = 'PRIMARY KEY' 
                AND tc.CONSTRAINT_NAME = ku.CONSTRAINT_NAME
        ) pk ON 
            c.TABLE_CATALOG = pk.TABLE_CATALOG
            AND c.TABLE_SCHEMA = pk.TABLE_SCHEMA
            AND c.TABLE_NAME = pk.TABLE_NAME
            AND c.COLUMN_NAME = pk.COLUMN_NAME
        WHERE 
            c.TABLE_NAME = '$table'
        ORDER BY 
            c.ORDINAL_POSITION
    ");
    
    $columns = array();
    while ($row = odbc_fetch_array($columns_query)) {
        $columns[] = $row;
    }
    
    // Generate CREATE TABLE statement
    $create_table = "DROP TABLE IF EXISTS `$table`;\n";
    $create_table .= "CREATE TABLE `$table` (\n";
    
    $column_defs = [];
    $primary_keys = [];
    
    foreach ($columns as $column) {
        $column_name = $column['COLUMN_NAME'];
        $data_type = mapDataType(
            $column['DATA_TYPE'],
            $column['CHARACTER_MAXIMUM_LENGTH'],
            $column['NUMERIC_PRECISION'],
            $column['NUMERIC_SCALE']
        );
        $nullable = $column['IS_NULLABLE'] === 'YES' ? 'NULL' : 'NOT NULL';
        $default = $column['COLUMN_DEFAULT'];
        
        $column_def = "  `$column_name` $data_type $nullable";
        
        // Add default if exists
        if ($default !== null) {
            // Clean up default value
            $default = trim($default, '()');
            if ($default === 'NULL') {
                $column_def .= " DEFAULT NULL";
            } elseif (is_numeric($default)) {
                $column_def .= " DEFAULT $default";
            } else {
                $column_def .= " DEFAULT '$default'";
            }
        }
        
        $column_defs[] = $column_def;
        
        // Track primary keys
        if ($column['COLUMN_KEY'] === 'PRI') {
            $primary_keys[] = "`$column_name`";
        }
    }
    
    // Add primary key constraint
    if (!empty($primary_keys)) {
        $column_defs[] = "  PRIMARY KEY (" . implode(", ", $primary_keys) . ")";
    }
    
    $create_table .= implode(",\n", $column_defs);
    $create_table .= "\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";
    
    // Write CREATE TABLE to schema file
    fwrite($schema_file, $create_table);
    
    // Create data file for this table
    $data_file = fopen($output_dir . "/$table.sql", 'w');
    fwrite($data_file, "-- Data for table `$table`\n");
    fwrite($data_file, "-- Generated on: " . date('Y-m-d H:i:s') . "\n\n");
    fwrite($data_file, "USE `tradeview`;\n\n");
    
    // Get data from table
    $data_query = odbc_exec($conn, "SELECT * FROM [$table]");
    $row_count = 0;
    $batch_size = 100;
    $batch = [];
    $column_names = array_column($columns, 'COLUMN_NAME');
    
    while ($row = odbc_fetch_array($data_query)) {
        $row_count++;
        
        // Prepare values
        $values = [];
        foreach ($column_names as $col) {
            if ($row[$col] === null) {
                $values[] = "NULL";
            } elseif (is_numeric($row[$col])) {
                $values[] = $row[$col];
            } else {
                // Escape string values for MySQL
                $values[] = "'" . addslashes($row[$col]) . "'";
            }
        }
        
        $batch[] = "(" . implode(", ", $values) . ")";
        
        // Write batch when it reaches the limit
        if (count($batch) >= $batch_size) {
            $insert_sql = "INSERT INTO `$table` (`" . implode("`, `", $column_names) . "`) VALUES\n";
            $insert_sql .= implode(",\n", $batch) . ";\n\n";
            fwrite($data_file, $insert_sql);
            $batch = [];
        }
    }
    
    // Write remaining rows
    if (!empty($batch)) {
        $insert_sql = "INSERT INTO `$table` (`" . implode("`, `", $column_names) . "`) VALUES\n";
        $insert_sql .= implode(",\n", $batch) . ";\n\n";
        fwrite($data_file, $insert_sql);
    }
    
    fclose($data_file);
    echo "Exported $row_count rows from table $table.\n";
}

// Finalize schema file
fwrite($schema_file, "SET FOREIGN_KEY_CHECKS=1;\n");
fclose($schema_file);

// Create import script
$import_script = fopen($output_dir . '/import_all.bat', 'w');
fwrite($import_script, "@echo off\n");
fwrite($import_script, "echo Importing schema...\n");
fwrite($import_script, "mysql -u root --password=\"\" < schema.sql\n");
fwrite($import_script, "echo.\n\n");

foreach ($tables as $table) {
    fwrite($import_script, "echo Importing data for $table...\n");
    fwrite($import_script, "mysql -u root --password=\"\" < $table.sql\n");
    fwrite($import_script, "echo.\n\n");
}

fwrite($import_script, "echo Migration completed!\n");
fwrite($import_script, "pause\n");
fclose($import_script);

echo "\nExport completed! SQL files have been generated in: $output_dir\n";
echo "To import the data into MySQL, run the import_all.bat script in that directory.\n";
?>
