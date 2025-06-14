<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PDO;

class MigrateMsSqlToMySql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:migrate-mssql-to-mysql';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate MS SQL Server database to MySQL';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting MS SQL to MySQL migration...');

        // Add MS SQL connection configuration dynamically
        config([
            'database.connections.sqlsrv_source' => [
                'driver' => 'sqlsrv',
                'host' => '65.108.2.70',
                'port' => '1433',
                'database' => 'TradeView',
                'username' => 'TradeView',
                'password' => 'yw^k188C3@Iii90g',
                'charset' => 'utf8',
                'prefix' => '',
                'prefix_indexes' => true,
            ]
        ]);

        // Test MS SQL connection
        try {
            DB::connection('sqlsrv_source')->getPdo();
            $this->info('Connected to MS SQL Server successfully.');
        } catch (\Exception $e) {
            $this->error('MS SQL Server Connection failed: ' . $e->getMessage());
            return 1;
        }

        // Test MySQL connection
        try {
            DB::connection('mysql')->getPdo();
            $this->info('Connected to MySQL successfully.');
        } catch (\Exception $e) {
            $this->error('MySQL Connection failed: ' . $e->getMessage());
            return 1;
        }

        // Get all tables from MS SQL Server
        try {
            $tables = DB::connection('sqlsrv_source')
                ->select("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_CATALOG='TradeView'");
            
            $this->info('Found ' . count($tables) . ' tables in MS SQL Server.');
        } catch (\Exception $e) {
            $this->error('Error fetching tables: ' . $e->getMessage());
            return 1;
        }

        // Process each table
        foreach ($tables as $table) {
            $tableName = $table->TABLE_NAME;
            $this->info("\nProcessing table: $tableName");
            
            // Get table columns from MS SQL
            try {
                $columns = DB::connection('sqlsrv_source')->select("
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
                        c.TABLE_NAME = '$tableName'
                    ORDER BY 
                        c.ORDINAL_POSITION
                ");
            } catch (\Exception $e) {
                $this->error("Error fetching columns for table $tableName: " . $e->getMessage());
                continue;
            }
            
            // Create table in MySQL
            try {
                // Drop table if exists
                Schema::connection('mysql')->dropIfExists($tableName);
                
                // Create table
                Schema::connection('mysql')->create($tableName, function ($table) use ($columns) {
                    foreach ($columns as $column) {
                        $columnName = $column->COLUMN_NAME;
                        $dataType = $column->DATA_TYPE;
                        $maxLength = $column->CHARACTER_MAXIMUM_LENGTH;
                        $precision = $column->NUMERIC_PRECISION;
                        $scale = $column->NUMERIC_SCALE;
                        $isNullable = $column->IS_NULLABLE === 'YES';
                        $default = $column->COLUMN_DEFAULT;
                        $isPrimary = $column->COLUMN_KEY === 'PRI';
                        
                        // Create column based on data type
                        $tableColumn = null;
                        
                        switch (strtolower($dataType)) {
                            case 'bigint':
                                $tableColumn = $table->bigInteger($columnName);
                                break;
                            case 'binary':
                                $tableColumn = $table->binary($columnName);
                                break;
                            case 'bit':
                                $tableColumn = $table->boolean($columnName);
                                break;
                            case 'char':
                            case 'nchar':
                                $tableColumn = $table->char($columnName, $maxLength !== -1 ? $maxLength : 255);
                                break;
                            case 'date':
                                $tableColumn = $table->date($columnName);
                                break;
                            case 'datetime':
                            case 'datetime2':
                            case 'smalldatetime':
                                $tableColumn = $table->dateTime($columnName);
                                break;
                            case 'datetimeoffset':
                                $tableColumn = $table->dateTime($columnName);
                                break;
                            case 'decimal':
                            case 'numeric':
                            case 'money':
                            case 'smallmoney':
                                $tableColumn = $table->decimal($columnName, $precision, $scale);
                                break;
                            case 'float':
                            case 'real':
                                $tableColumn = $table->float($columnName);
                                break;
                            case 'image':
                                $tableColumn = $table->binary($columnName);
                                break;
                            case 'int':
                                $tableColumn = $table->integer($columnName);
                                break;
                            case 'ntext':
                            case 'text':
                            case 'xml':
                                $tableColumn = $table->longText($columnName);
                                break;
                            case 'nvarchar':
                            case 'varchar':
                                $length = ($maxLength === -1) ? 'max' : $maxLength;
                                $tableColumn = ($length === 'max') ? 
                                    $table->text($columnName) : 
                                    $table->string($columnName, $length);
                                break;
                            case 'smallint':
                                $tableColumn = $table->smallInteger($columnName);
                                break;
                            case 'time':
                                $tableColumn = $table->time($columnName);
                                break;
                            case 'timestamp':
                                // SQL Server timestamp is not the same as MySQL timestamp
                                $tableColumn = $table->binary($columnName);
                                break;
                            case 'tinyint':
                                $tableColumn = $table->tinyInteger($columnName);
                                break;
                            case 'uniqueidentifier':
                                $tableColumn = $table->uuid($columnName);
                                break;
                            case 'varbinary':
                                $tableColumn = $table->binary($columnName);
                                break;
                            default:
                                $tableColumn = $table->string($columnName, 255);
                                break;
                        }
                        
                        // Set nullable
                        if ($isNullable) {
                            $tableColumn->nullable();
                        }
                        
                        // Set default value if exists
                        if ($default !== null) {
                            $default = trim($default, '()');
                            if ($default === 'NULL') {
                                $tableColumn->default(null);
                            } elseif (is_numeric($default)) {
                                $tableColumn->default($default);
                            } else {
                                $tableColumn->default($default);
                            }
                        }
                        
                        // Set primary key
                        if ($isPrimary) {
                            $tableColumn->primary();
                        }
                    }
                    
                    // Disable timestamps
                    $table->timestamps(false);
                });
                
                $this->info("Created table $tableName in MySQL.");
            } catch (\Exception $e) {
                $this->error("Error creating table $tableName: " . $e->getMessage());
                continue;
            }
            
            // Copy data
            try {
                // Get data from MS SQL
                $data = DB::connection('sqlsrv_source')->table($tableName)->get();
                $totalRows = count($data);
                
                // Insert in batches
                $batchSize = 100;
                $batches = array_chunk($data->toArray(), $batchSize);
                
                foreach ($batches as $batch) {
                    // Convert stdClass objects to arrays
                    $batchArray = array_map(function ($item) {
                        return (array) $item;
                    }, $batch);
                    
                    DB::connection('mysql')->table($tableName)->insert($batchArray);
                }
                
                $this->info("Copied $totalRows rows to table $tableName.");
            } catch (\Exception $e) {
                $this->error("Error copying data for table $tableName: " . $e->getMessage());
            }
        }

        $this->info("\nMigration completed!");
        return 0;
    }
}
