<?php
/**
 * MS SQL to MySQL Database Migration Script
 * 
 * This script connects to an MS SQL Server database, extracts all tables,
 * creates equivalent tables in MySQL, and migrates all data.
 */

// MS SQL Server connection parameters
$mssql_server = '65.108.2.70,1433';
$mssql_database = 'TradeView';
$mssql_user = 'TradeView';
$mssql_password = 'yw^k188C3@Iii90g';

// MySQL connection parameters
$mysql_host = 'localhost';
$mysql_database = 'tradeview'; // lowercase for MySQL
$mysql_user = 'root';
$mysql_password = '';

// Connect to MS SQL Server
try {
    $mssql = new PDO(
        "sqlsrv:Server=$mssql_server;Database=$mssql_database",
        $mssql_user,
        $mssql_password
    );
    $mssql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to MS SQL Server successfully.\n";
} catch (PDOException $e) {
    die("MS SQL Server Connection failed: " . $e->getMessage() . "\n");
}

// Connect to MySQL
try {
    $mysql = new PDO(
        "mysql:host=$mysql_host",
        $mysql_user,
        $mysql_password
    );
    $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to MySQL successfully.\n";
} catch (PDOException $e) {
    die("MySQL Connection failed: " . $e->getMessage() . "\n");
}

// Create MySQL database if it doesn't exist
try {
    $mysql->exec("CREATE DATABASE IF NOT EXISTS `$mysql_database`");
    $mysql->exec("USE `$mysql_database`");
    echo "Using database $mysql_database.\n";
} catch (PDOException $e) {
    die("Error creating MySQL database: " . $e->getMessage() . "\n");
}

// Get all tables from MS SQL Server
try {
    $tables_query = $mssql->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_CATALOG='$mssql_database'");
    $tables = $tables_query->fetchAll(PDO::FETCH_COLUMN);
    echo "Found " . count($tables) . " tables in MS SQL Server.\n";
} catch (PDOException $e) {
    die("Error fetching tables: " . $e->getMessage() . "\n");
}

// Data type mapping from MS SQL to MySQL
function mapDataType($mssql_type) {
    $type = strtolower($mssql_type);
    
    // Remove precision/scale if present
    if (strpos($type, '(') !== false) {
        $type = substr($type, 0, strpos($type, '('));
    }
    
    $map = [
        'bigint' => 'BIGINT',
        'binary' => 'BINARY',
        'bit' => 'TINYINT(1)',
        'char' => 'CHAR',
        'date' => 'DATE',
        'datetime' => 'DATETIME',
        'datetime2' => 'DATETIME',
        'datetimeoffset' => 'DATETIME',
        'decimal' => 'DECIMAL',
        'float' => 'FLOAT',
        'image' => 'LONGBLOB',
        'int' => 'INT',
        'money' => 'DECIMAL(19,4)',
        'nchar' => 'CHAR',
        'ntext' => 'LONGTEXT',
        'numeric' => 'DECIMAL',
        'nvarchar' => 'VARCHAR',
        'real' => 'FLOAT',
        'smalldatetime' => 'DATETIME',
        'smallint' => 'SMALLINT',
        'smallmoney' => 'DECIMAL(10,4)',
        'text' => 'LONGTEXT',
        'time' => 'TIME',
        'timestamp' => 'TIMESTAMP',
        'tinyint' => 'TINYINT',
        'uniqueidentifier' => 'VARCHAR(36)',
        'varbinary' => 'VARBINARY',
        'varchar' => 'VARCHAR',
        'xml' => 'LONGTEXT'
    ];
    
    return isset($map[$type]) ? $map[$type] : 'VARCHAR(255)';
}

// Process each table
foreach ($tables as $table) {
    echo "\nProcessing table: $table\n";
    
    // Get table columns from MS SQL
    try {
        $columns_query = $mssql->query("
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
        
        $columns = $columns_query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching columns for table $table: " . $e->getMessage() . "\n";
        continue;
    }
    
    // Create table in MySQL
    try {
        // Drop table if exists
        $mysql->exec("DROP TABLE IF EXISTS `$table`");
        
        // Start CREATE TABLE statement
        $create_table_sql = "CREATE TABLE `$table` (\n";
        
        // Add columns
        $column_definitions = [];
        $primary_keys = [];
        
        foreach ($columns as $column) {
            $column_name = $column['COLUMN_NAME'];
            $data_type = $column['DATA_TYPE'];
            $max_length = $column['CHARACTER_MAXIMUM_LENGTH'];
            $precision = $column['NUMERIC_PRECISION'];
            $scale = $column['NUMERIC_SCALE'];
            $is_nullable = $column['IS_NULLABLE'] === 'YES' ? 'NULL' : 'NOT NULL';
            $default = $column['COLUMN_DEFAULT'];
            $column_key = $column['COLUMN_KEY'];
            
            // Map data type
            $mysql_type = mapDataType($data_type);
            
            // Add length/precision if applicable
            if ($max_length !== null && $max_length != -1 && in_array(strtolower($data_type), ['char', 'varchar', 'nchar', 'nvarchar'])) {
                $mysql_type .= "($max_length)";
            } elseif ($precision !== null && in_array(strtolower($data_type), ['decimal', 'numeric'])) {
                $mysql_type .= "($precision, $scale)";
            }
            
            // Build column definition
            $column_def = "`$column_name` $mysql_type $is_nullable";
            
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
            
            // Add to column definitions
            $column_definitions[] = $column_def;
            
            // Track primary keys
            if ($column_key === 'PRI') {
                $primary_keys[] = "`$column_name`";
            }
        }
        
        // Add primary key constraint if exists
        if (!empty($primary_keys)) {
            $column_definitions[] = "PRIMARY KEY (" . implode(", ", $primary_keys) . ")";
        }
        
        // Complete CREATE TABLE statement
        $create_table_sql .= implode(",\n", $column_definitions);
        $create_table_sql .= "\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        
        // Execute CREATE TABLE
        $mysql->exec($create_table_sql);
        echo "Created table $table in MySQL.\n";
    } catch (PDOException $e) {
        echo "Error creating table $table: " . $e->getMessage() . "\n";
        continue;
    }
    
    // Copy data
    try {
        // Get data from MS SQL
        $data_query = $mssql->query("SELECT * FROM [$table]");
        $total_rows = 0;
        
        // Prepare column names for INSERT
        $column_names = array_column($columns, 'COLUMN_NAME');
        $column_list = "`" . implode("`, `", $column_names) . "`";
        
        // Insert in batches
        $batch_size = 100;
        $batch = [];
        
        while ($row = $data_query->fetch(PDO::FETCH_ASSOC)) {
            $total_rows++;
            
            // Prepare values
            $values = [];
            foreach ($column_names as $col) {
                if ($row[$col] === null) {
                    $values[] = "NULL";
                } elseif (is_numeric($row[$col])) {
                    $values[] = $row[$col];
                } else {
                    $values[] = "'" . $mysql->quote($row[$col]) . "'";
                }
            }
            
            $batch[] = "(" . implode(", ", $values) . ")";
            
            // Insert when batch is full
            if (count($batch) >= $batch_size) {
                $insert_sql = "INSERT INTO `$table` ($column_list) VALUES " . implode(", ", $batch);
                $mysql->exec($insert_sql);
                $batch = [];
            }
        }
        
        // Insert remaining rows
        if (!empty($batch)) {
            $insert_sql = "INSERT INTO `$table` ($column_list) VALUES " . implode(", ", $batch);
            $mysql->exec($insert_sql);
        }
        
        echo "Copied $total_rows rows to table $table.\n";
    } catch (PDOException $e) {
        echo "Error copying data for table $table: " . $e->getMessage() . "\n";
    }
}

echo "\nMigration completed!\n";
