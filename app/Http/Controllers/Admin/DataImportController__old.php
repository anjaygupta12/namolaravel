<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\ForexOption;
use Carbon\Carbon;

class DataImportController extends Controller
{
    public function index()
    {
        // Get database connection info
        $dbInfo = [
            'connection' => config('database.default'),
            'database' => config('database.connections.' . config('database.default') . '.database'),
            'host' => config('database.connections.' . config('database.default') . '.host'),
            'forexOptionsCount' => 'Unknown (skipped count to avoid timeout)'
        ];
        
        try {
            // Just check connection without counting records
            DB::connection()->getPdo();
            $dbInfo['connectionStatus'] = 'Connected';
            
            // Check if table exists without counting
            if (DB::getSchemaBuilder()->hasTable('ForexOptions')) {
                $dbInfo['tableExists'] = 'Yes';
            } else {
                $dbInfo['tableExists'] = 'No';
            }
        } catch (\Exception $e) {
            $dbInfo['connectionStatus'] = 'Error: ' . $e->getMessage();
        }
        
        return view('admin.data-import', ['dbInfo' => $dbInfo]);
    }

    public function importData(Request $request)
    {
        // Increase PHP execution time limit for large imports
        set_time_limit(36000); // 10 hours (as per user setting)
        ini_set('memory_limit', '1G'); // Increase memory limit
        
        // Validate the request
        $request->validate([
            'url' => 'required|url'
        ]);
        
        try {
            // Check database connection
            try {
                DB::connection()->getPdo();
                $dbStatus = 'Connected to database: ' . DB::connection()->getDatabaseName();
                Log::info($dbStatus);
            } catch (\Exception $e) {
                $dbStatus = 'Could not connect to database: ' . $e->getMessage();
                Log::error($dbStatus);
                return back()->withErrors(['error' => $dbStatus]);
            }
            
            // Fetch data from URL
            Log::info('Fetching data from URL: ' . $request->url);
            try {
                $response = Http::timeout(300)->get($request->url);
                
                if (!$response->successful()) {
                    Log::error('Failed to fetch data from URL: ' . $request->url . ', Status: ' . $response->status());
                    return back()->withErrors(['error' => 'Failed to fetch data from URL. Status: ' . $response->status()]);
                }
    
                // Process CSV data
                $csvData = $response->body();
                Log::info('CSV data fetched successfully. Size: ' . strlen($csvData) . ' bytes');
                
                // Check if we actually received CSV data
                if (strlen($csvData) < 10) {
                    Log::error('Received empty or very small CSV data');
                    return back()->withErrors(['error' => 'Received empty or very small CSV data']);
                }
                
                // Log the first line to help debug format issues
                $firstLine = strtok($csvData, "\n");
                Log::info('First line of CSV: ' . $firstLine);
                Log::info('First line contains tabs: ' . (strpos($firstLine, "\t") !== false ? 'Yes' : 'No'));
                Log::info('First line contains commas: ' . (strpos($firstLine, ',') !== false ? 'Yes' : 'No'));
            } catch (\Exception $e) {
                Log::error('Exception while fetching CSV: ' . $e->getMessage());
                return back()->withErrors(['error' => 'Error fetching CSV data: ' . $e->getMessage()]);
            }
            
            // Get a sample of the CSV data for debugging
            $csvLines = explode("\n", $csvData);
            $csvSample = array_slice($csvLines, 0, 5);
            
            // Parse the sample to understand structure
            $sampleData = [];
           
            foreach ($csvSample as $line) {
                if (!empty(trim($line))) {
                    $sampleData[] = str_getcsv($line, "\t"); // Use tab delimiter for consistency
                }
            }
            
            // Process the entire CSV file in batches
            $batchSize = 100; // Process 100 records at a time
            $totalLines = count($csvLines);
            $totalProcessed = 0;
            $totalImported = 0;
            $batchNumber = 0;
            
            Log::info('Starting batched import of ' . $totalLines . ' lines with batch size ' . $batchSize);
            
            // For testing: First try to process 100 records directly without batching
            Log::info('TESTING MODE: Attempting to process first 100 records directly');
            
            // Get the first 100 lines (plus header)
            $testLines = array_slice($csvLines, 0, 101); // 100 records + header
            $testCsvData = implode("\n", $testLines);
            
            // Log the first few lines for debugging
            $debugLines = array_slice($testLines, 0, min(5, count($testLines)));
            Log::info('First few lines of test batch:\n' . implode("\n", $debugLines));
            
            // Parse using the existing parseCsvData method - use 'ALL' to process all exchanges
            $testOptions = $this->parseCsvData($testCsvData, 'ALL', 100);
            Log::info('Test parsing produced ' . count($testOptions) . ' records');
            
            // If we have options to save, log a few for debugging
            if (count($testOptions) > 0) {
                Log::info('First test record:\n' . json_encode($testOptions[0], JSON_PRETTY_PRINT));
                if (count($testOptions) > 1) {
                    Log::info('Second test record:\n' . json_encode($testOptions[1], JSON_PRETTY_PRINT));
                }
            } else {
                Log::warning('No records parsed in test batch');
            }
            
            // Save the test batch to database
            $testImported = $this->saveToDatabase($testOptions);
            Log::info('Test import complete. Processed: ' . count($testOptions) . ', Imported: ' . $testImported);
            
            // Skip regular processing if we're just testing
            if ($request->has('test_mode')) {
                return back()->with([
                    'success' => "Test import completed. Processed: " . count($testOptions) . ", Imported: " . $testImported,
                    'debug' => [
                        'csv_sample' => $sampleData,
                        'test_processed' => count($testOptions),
                        'test_imported' => $testImported
                    ]
                ]);
            }
            
            // Regular batch processing
            while ($totalProcessed < $totalLines) {
                $batchNumber++;
                Log::info('Processing batch #' . $batchNumber . ' starting at line ' . $totalProcessed);
                
                // Get the current batch of CSV data
                $batchLines = array_slice($csvLines, $totalProcessed, $batchSize + 1); // +1 for header if needed
                $batchCsvData = implode("\n", $batchLines);
                
                // Log the first few lines of this batch for debugging
                $debugLines = array_slice($batchLines, 0, min(3, count($batchLines)));
                Log::info('First few lines of batch #' . $batchNumber . ':\n' . implode("\n", $debugLines));
                
                // Parse the batch - use 'ALL' to process all exchanges
                $options = $this->parseCsvData($batchCsvData, 'ALL', $batchSize);
                $processedInBatch = count($options);
                $totalProcessed += $processedInBatch;
                
                Log::info('Batch #' . $batchNumber . ' parsed ' . $processedInBatch . ' records');
                
                // If we have options to save, log the first one for debugging
                if (count($options) > 0) {
                    Log::info('Sample record from batch #' . $batchNumber . ':\n' . json_encode($options[0], JSON_PRETTY_PRINT));
                } else {
                    Log::warning('No records parsed in batch #' . $batchNumber);
                }
                
                // Save the batch to database
                $importedInBatch = $this->saveToDatabase($options);
                $totalImported += $importedInBatch;
                
                Log::info('Batch #' . $batchNumber . ' complete. Processed: ' . $processedInBatch . 
                         ', Imported: ' . $importedInBatch . 
                         ', Total processed: ' . $totalProcessed . 
                         ', Total imported: ' . $totalImported);
                
                // If we processed less than the batch size, we've reached the end
                if ($processedInBatch < $batchSize) {
                    break;
                }
            }

            // Return with debug information
            return back()->with([
                'success' => "Import completed. Total lines: " . $totalLines . ", Total processed: " . $totalProcessed . ", Total imported: " . $totalImported,
                'debug' => [
                    'csv_sample' => $sampleData,
                    'csv_line_count' => $totalLines,
                    'total_processed' => $totalProcessed,
                    'total_imported' => $totalImported,
                    'batch_count' => $batchNumber
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Data import error: ' . $e->getMessage() . '\nStack trace: ' . $e->getTraceAsString());
            return back()->withErrors(['error' => 'Error importing data: ' . $e->getMessage()]);
        }
    }
    
    protected function tryExtractFromDescription($description, $fallbackDateStr, &$underlying, &$expiryDate, &$instrumentData)
    {
        $underlying = '';
        $expiryDate = null;
        $instrumentData = '';
        
        Log::info('Trying to extract from description: "' . $description . '"');
        
        $parts = explode(' ', $description);
        Log::info('Description parts: ' . json_encode($parts));
        
        if (count($parts) >= 4) {
            try {
                // Be more flexible with the format detection
                // Just check if we have enough parts and the second part might be a year
                if (count($parts) >= 4) {
                    
                    $underlying = trim($parts[0]);           // CRUDEOIL
                    $yearPart = trim($parts[1]);            // 25 (2-digit year)
                    $month = trim($parts[2]);               // Jul
                    $day = trim($parts[3]);                 // 17
                    
                    // Get the last part as instrument data (CE/PE/FUT)
                    $instrumentData = trim($parts[count($parts) - 1]);
                    
                    Log::info('Description parts analysis: underlying=' . $underlying . 
                        ', yearPart=' . $yearPart . 
                        ', month=' . $month . 
                        ', day=' . $day . 
                        ', instrumentData=' . $instrumentData);
                    
                    // Special handling for FUT (futures) records
                    if ($instrumentData === 'FUT' || $instrumentData === 'FU') {
                        Log::info('Detected futures contract');
                        try {
                            // For futures, the format is typically "GOLD 25 Aug 05 FUT" where 05 is the day
                            // So we need to swap day and year parts
                            if (is_numeric($yearPart) && is_numeric($day)) {
                                // Convert 2-digit year to 4-digit year
                                $year = 2000 + intval($yearPart);       // 25 → 2025
                                
                                $dateStr = $day . ' ' . $month . ' ' . $year;  // "05 Aug 2025"
                                Log::info('Futures - Parsing date string: "' . $dateStr . '"');
                                $expiryDate = Carbon::parse($dateStr);
                                Log::info('Futures date parsing succeeded');
                                return true;
                            }
                        } catch (\Exception $ex) {
                            Log::error('Futures date parse error: ' . $ex->getMessage());
                        }
                    }
                    
                    // Try multiple approaches to parse the date
                    try {
                        // First attempt: Assume parts[1] is year, parts[3] is day
                        if (is_numeric($yearPart) && is_numeric($day)) {
                            // Convert 2-digit year to 4-digit year
                            $year = 2000 + intval($yearPart);       // 25 → 2025
                            
                            $dateStr = $day . ' ' . $month . ' ' . $year;  // "17 Jul 2025"
                            Log::info('Attempt 1 - Parsing date string: "' . $dateStr . '"');
                            $expiryDate = Carbon::parse($dateStr);
                            Log::info('Attempt 1 succeeded');
                            return true;
                        } else {
                            throw new \Exception('First attempt failed - parts not numeric');
                        }
                    } catch (\Exception $e1) {
                        Log::warning('First date parse attempt failed: ' . $e1->getMessage());
                        
                        try {
                            // Second attempt: Try standard date format if available in the CSV
                            if (!empty($fallbackDateStr)) {
                                Log::info('Attempt 2 - Using fallback date: "' . $fallbackDateStr . '"');
                                $expiryDate = Carbon::parse($fallbackDateStr);
                                Log::info('Attempt 2 succeeded');
                            } else {
                                throw new \Exception('No fallback date available');
                            }
                        } catch (\Exception $e2) {
                            Log::warning('Second date parse attempt failed: ' . $e2->getMessage());
                            
                            // Third attempt: Try to extract date using regex
                            if (preg_match('/\d{1,2}\s+[A-Za-z]{3}\s+\d{4}/', $description, $matches)) {
                                try {
                                    Log::info('Attempt 3 - Using regex match: "' . $matches[0] . '"');
                                    $expiryDate = Carbon::parse($matches[0]);
                                    Log::info('Attempt 3 succeeded');
                                } catch (\Exception $e3) {
                                    Log::error('Third date parse attempt failed: ' . $e3->getMessage());
                                    throw $e3; // Re-throw to be caught by outer try-catch
                                }
                            } else {
                                throw new \Exception('No date pattern found in description');
                            }
                        }
                    }
                    
                    Log::info('Successfully extracted date components: Day=' . $day . ', Month=' . $month . ', Year=' . $year);
                    Log::info('Parsed date: ' . $expiryDate->format('Y-m-d'));
                    Log::info('Underlying: ' . $underlying . ', InstrumentData: ' . $instrumentData);
                    
                    return true;
                } else {
                    Log::warning('Description format does not match expected pattern: "' . $description . '"');
                    return false;
                }
            } catch (\Exception $ex) {
                Log::error('Date parse error for "' . $description . '": ' . $ex->getMessage());
            }
        } else {
            Log::warning('Not enough parts in description: "' . $description . '" (parts: ' . count($parts) . ')');
        }
        
        // Try fallback date
        if (!empty($fallbackDateStr)) {
            try {
                $expiryDate = Carbon::parse($fallbackDateStr);
                return true;
            } catch (\Exception $ex) {
                Log::error('Fallback date parse error: ' . $ex->getMessage());
            }
        }
        
        return false;
    }
    
    protected function parseCsvData($csvData, $exchangeName, $limit = 1000)
    {
        $options = [];
        $lines = explode("\n", $csvData);
        $count = 0;
        $headerRow = null;
        $processedLines = 0;
        $skippedLines = 0;
        $validLines = 0;
        
        Log::info('Total lines in CSV: ' . count($lines));
        Log::info('First 100 characters of CSV data: ' . substr($csvData, 0, 100));
        
        foreach ($lines as $lineIndex => $line) {
            $processedLines++;
            if (empty(trim($line))) {
                Log::info('Skipping empty line at index: ' . $lineIndex);
                continue;
            }
            
            // Try to detect the delimiter by checking the first line
            $delimiter = "\t"; // Default to tab
            
            // Check if comma might be a better delimiter
            $commaCount = substr_count($line, ',');
            $tabCount = substr_count($line, "\t");
            
            // Log the actual line content for the first few lines
            if ($lineIndex < 5) {
                Log::info("Line $lineIndex content: " . substr($line, 0, 100) . (strlen($line) > 100 ? '...' : ''));
                Log::info("Row $lineIndex delimiter counts: Tabs=$tabCount, Commas=$commaCount");
            }
            
            if ($commaCount > $tabCount && $commaCount > 5) {
                $delimiter = ',';
                Log::info("Using comma as delimiter for row $lineIndex");
            }
            
            // Parse the CSV line with the detected delimiter
            $fields = str_getcsv($line, $delimiter);
            
            // Clean up fields to handle potential encoding issues
            foreach ($fields as $key => $value) {
                $fields[$key] = trim($value);
            }
            
            // Special logging for the GOLD futures record we're looking for
            if (isset($fields[0]) && isset($fields[1]) && 
                (strpos($fields[0], '1120250805438425') !== false || 
                 strpos($fields[1], 'GOLD 25 Aug 05 FUT') !== false)) {
                Log::info('FOUND TARGET RECORD: ' . $line);
                Log::info('Parsed fields: ' . json_encode($fields));
                Log::info('Field count: ' . count($fields));
                
                // Force debug mode for this record
                $debugThisRecord = true;
            } else {
                $debugThisRecord = false;
            }
            
            // Check for MCX records to see if they're being filtered out
            if (isset($fields[2]) && $fields[2] == '30') { // 30 is the exchange code for MCX
                Log::info('Found MCX record: ' . substr($line, 0, 100));
            }
            
            // Log the parsed fields for the first few lines
            if ($lineIndex < 3) {
                Log::info("Row $lineIndex parsed fields: " . json_encode(array_slice($fields, 0, 5)) . '...');
            }
            
            // Store header row for debugging
            if ($lineIndex === 0) {
                $headerRow = $fields;
                Log::info('CSV Headers: ' . json_encode($headerRow));
                continue; // Skip the header row
            }
            
            // Debug specific record
            $isTargetRecord = (isset($fields[0]) && $fields[0] === '1120250805438425') || 
                             (isset($fields[1]) && strpos($fields[1], 'GOLD 25 Aug 05 FUT') !== false);
            if ($isTargetRecord) {
                Log::info('Processing target GOLD record at line ' . $lineIndex);
            }
            
            // Skip header row or invalid rows
            if (!isset($fields[0])) {
                Log::info('Skipping row at index: ' . $lineIndex . ' - No first field');
                $skippedLines++;
                continue;
            }
            
            // Log field count for debugging
            if ($lineIndex % 100 == 0 || $lineIndex < 10) { // Log every 100th row and the first 10 rows
                Log::info('Row ' . $lineIndex . ' has ' . count($fields) . ' fields. First field: "' . $fields[0] . '"');
            }
            
            // Accept any non-empty first field to be more flexible
            if (empty($fields[0])) {
                Log::info('Skipping row at index: ' . $lineIndex . ' - Empty first field');
                $skippedLines++;
                continue;
            }
            
            $validLines++;
            
            // Limit the number of records processed
            if ($count >= $limit) {
                Log::info("Reached limit of {$limit} records, stopping processing");
                break;
            }
            
            Log::info('Processing valid data row at index: ' . $lineIndex . ', ID: ' . $fields[0] . ', Fields count: ' . count($fields));
            
            // Based on the sample data, map fields correctly
            // We'll be more flexible with field mapping to handle different CSV formats
            
            // Map fields directly based on the .NET implementation
            $id = isset($fields[0]) && !empty($fields[0]) ? $fields[0] : '0';
            $description = isset($fields[1]) && !empty($fields[1]) ? $fields[1] : 'Unknown';
            $exchangeCode = isset($fields[2]) && !empty($fields[2]) ? $fields[2] : '0';
            $segment = isset($fields[3]) && !empty($fields[3]) ? $fields[3] : '0';
            $tickSize = isset($fields[4]) && is_numeric($fields[4]) ? $fields[4] : '0.05';
            $tradingSession = isset($fields[5]) && !empty($fields[5]) ? $fields[5] : '';
            $dataDateStr = isset($fields[7]) && !empty($fields[7]) ? $fields[7] : '';
            $instrumentToken = isset($fields[8]) && !empty($fields[8]) ? $fields[8] : '0';
            $exchangeInstrument = isset($fields[9]) && !empty($fields[9]) ? $fields[9] : $description;
            $lotSize = isset($fields[10]) && is_numeric($fields[10]) ? $fields[10] : '1';
            $instrumentType = isset($fields[11]) && !empty($fields[11]) ? $fields[11] : '0';
            $fyToken = isset($fields[12]) && !empty($fields[12]) ? $fields[12] : '0';
            $underlyingFromCsv = isset($fields[13]) && !empty($fields[13]) ? $fields[13] : '';
            $multiplier = isset($fields[14]) && is_numeric($fields[14]) ? $fields[14] : '1';
            $strikePrice = isset($fields[15]) && is_numeric($fields[15]) ? $fields[15] : '0';
            $optionType = isset($fields[16]) && !empty($fields[16]) ? $fields[16] : '';
            $fyTokenUnderlying = isset($fields[17]) && !empty($fields[17]) ? $fields[17] : '0';
            
            // Extract underlying and option details from description if available
            $underlying = 'Unknown';
            $optionType = '';
            $strikePrice = '0';
         
            // Try to extract from description
            if (!empty($description)) {
                $parts = explode(' ', $description);
                if (count($parts) > 0) {
                    $underlying = $parts[0]; // First part is usually the underlying
                }
                
                // Look for option type (CE/PE/FUT)
                if (strpos($description, 'CE') !== false) {
                    $optionType = 'CE';
                } elseif (strpos($description, 'PE') !== false) {
                    $optionType = 'PE';
                } elseif (strpos($description, 'FUT') !== false) {
                    $optionType = 'FUT'; // Now using full 'FUT' since column is char(3)
                }
                
                // Try to extract strike price - but only for options, not futures
                if ($optionType === 'CE' || $optionType === 'PE') {
                    preg_match('/\d+(\.\d+)?/', $description, $matches);
                    if (!empty($matches[0])) {
                        $strikePrice = $matches[0];
                    }
                } else {
                    // For futures, set strike price to 0
                    $strikePrice = '0';
                }
            }
            
            // Additional fields with defaults
            $fyTokenUnderlying = isset($fields[20]) && !empty($fields[20]) ? $fields[20] : '0';
            
            // Handle multiplier field - use a reasonable default for futures
            if (strpos($description, 'FUT') !== false) {
                // For futures, use a default multiplier of 1
                $multiplier = isset($fields[14]) && is_numeric($fields[14]) ? $fields[14] : '1';
                // If the multiplier is negative, make it positive
                if (is_numeric($multiplier) && $multiplier < 0) {
                    $multiplier = abs($multiplier);
                }
            } else {
                // For options, use field 17
                $multiplier = isset($fields[17]) && is_numeric($fields[17]) ? $fields[17] : '1';
            }
            
            // Ensure multiplier is within SMALLINT range (-32768 to 32767)
            if (is_numeric($multiplier)) {
                if ($multiplier > 32767) {
                    $multiplier = 1; // Use a safe default instead of max value
                } elseif ($multiplier < -32768) {
                    $multiplier = 1; // Use a safe default instead of min value
                }
            } else {
                $multiplier = 1; // Default value
            }
            
            // Parse expiry date
            $expiryDate = null;
            $underlying = 'Unknown';
            $instrumentData = '';
            
            // Use the tryExtractFromDescription method to extract date and other info
            if ($this->tryExtractFromDescription($description, $dataDateStr, $underlying, $expiryDate, $instrumentData)) {
                Log::info('Successfully extracted date from description: ' . $expiryDate->format('Y-m-d'));
            } else {
                // If extraction failed, try to parse the date from dataDateStr
                try {
                    if (!empty($dataDateStr)) {
                        $expiryDate = Carbon::parse($dataDateStr);
                        Log::info('Using fallback date from CSV: ' . $expiryDate->format('Y-m-d'));
                    } else {
                        // If all else fails, use current date
                        $expiryDate = Carbon::now();
                        Log::warning('Using current date as fallback: ' . $expiryDate->format('Y-m-d'));
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to parse any date. Using current date: ' . $e->getMessage());
                    $expiryDate = Carbon::now();
                }
            }
            
            // Generate a safe ID value - use a hash if the token is too large
            $safeId = null;
            if (is_numeric($fyToken) && strlen($fyToken) <= 9) {
                // If it's a reasonable size, use it directly
                $safeId = intval($fyToken);
            } else {
                // Otherwise generate a hash-based ID that's safe for an integer column
                $safeId = abs(crc32($fyToken . $description)) % 1000000000; // Ensure it's positive and fits in INT
            }
            
            // Check if this is our target GOLD record
            $isTargetGoldRecord = ($id === '1120250805438425' || strpos($description, 'GOLD 25 Aug 05 FUT') !== false);
            if ($isTargetGoldRecord) {
                Log::info('GOLD RECORD: About to add to options array with ID: ' . $id);
                Log::info('GOLD RECORD: Description: ' . $description);
                Log::info('GOLD RECORD: Exchange code: ' . $exchangeCode);
                Log::info('GOLD RECORD: Expiry date: ' . ($expiryDate ? $expiryDate->format('Y-m-d') : 'null'));
            }
            
            // Create option record with properly mapped fields exactly matching the .NET implementation
            $options[] = [
                'Id' => $id, // Keep ID as string to preserve large values
                'Symbol' => $exchangeInstrument, // In .NET: ExchangeInstrument
                'Description' => $description,
                'Exchange' => is_numeric($exchangeCode) ? intval($exchangeCode) : 0,
                'Segment' => is_numeric($segment) ? intval($segment) : 0,
                'TickSize' => is_numeric($tickSize) ? floatval($tickSize) : 0.05,
                'TradingSession' => $tradingSession,
                'ExpiryDate' => $expiryDate ? $expiryDate->format('Y-m-d') : now()->format('Y-m-d'),
                'InstrumentToken' => is_numeric($instrumentToken) ? intval($instrumentToken) : 0,
                'ExchangeInstrument' => $exchangeInstrument,
                'LotSize' => is_numeric($lotSize) ? intval($lotSize) : 1,
                'InstrumentType' => is_numeric($instrumentType) ? intval($instrumentType) : 0,
                'FyToken' => is_numeric($fyToken) ? intval($fyToken) : 0,
                'Underlying' => !empty($underlyingFromCsv) ? $underlyingFromCsv : $underlying,
                'Multiplier' => is_numeric($multiplier) ? intval($multiplier) : 1,
                'StrikePrice' => is_numeric($strikePrice) ? floatval($strikePrice) : 0,
                'OptionType' => !empty($optionType) ? $optionType : $instrumentData,
                'FyTokenUnderlying' => is_numeric($fyTokenUnderlying) ? intval($fyTokenUnderlying) : 0,
                'ExName' => $exchangeName,
                'SymbolShortName' => $underlying,
                'DataDate' => !empty($dataDateStr) ? Carbon::parse($dataDateStr)->format('Y-m-d') : now()->format('Y-m-d'),
                'instrument' => $instrumentData
            ];
            
            $count++;
        }
        // If no records were created, log the first few lines for debugging
        if (count($options) === 0 && count($lines) > 0) {
            for ($i = 0; $i < min(5, count($lines)); $i++) {
                Log::info('Line ' . $i . ': ' . $lines[$i]);
            }
        }
        
        return $options;
    }
    
    protected function saveToDatabase($options)
    {
        $importedCount = 0;
        
        Log::info('Starting database save with ' . count($options) . ' records');
        
        if (count($options) === 0) {
            Log::warning('No records to save to database');
            return 0;
        }
        
        // Log the first record for debugging
        if (count($options) > 0) {
            Log::info('First record to save: ' . json_encode($options[0]));
        }
       
        // First check if the GOLD record is in the options array
        $goldRecordFound = false;
        foreach ($options as $checkOption) {
            if ($checkOption['Id'] == '1120250805438425' || 
                (isset($checkOption['Description']) && strpos($checkOption['Description'], 'GOLD 25 Aug 05 FUT') !== false)) {
                $goldRecordFound = true;
                Log::info('GOLD RECORD FOUND IN OPTIONS ARRAY BEFORE SAVE: ' . json_encode($checkOption));
                break;
            }
        }
        
        if (!$goldRecordFound) {
            Log::warning('GOLD RECORD NOT FOUND IN OPTIONS ARRAY BEFORE SAVE');
        }
        
        foreach ($options as $index => $option) {
            try {
                // Special check for the GOLD futures record
                if ($option['Id'] == '1120250805438425' || 
                    (isset($option['Description']) && strpos($option['Description'], 'GOLD 25 Aug 05 FUT') !== false)) {
                    Log::info('FOUND TARGET RECORD IN SAVE PROCESS: ' . json_encode($option));
                }
                
                Log::info('Attempting to save record ' . ($index + 1) . ' with ID: ' . $option['Id']);
                
                // Log the record data for debugging
                Log::info('Record data: ' . json_encode(array_slice($option, 0, 5)) . '...');
                
                // Make sure all required fields are present
                $requiredFields = ['Id', 'Symbol', 'Description', 'Exchange'];
                foreach ($requiredFields as $field) {
                    if (!isset($option[$field]) || $option[$field] === '') {
                        Log::warning('Missing required field: ' . $field);
                        $option[$field] = $field === 'Id' ? rand(100000, 999999) : 'Unknown';
                    }
                }
                
                // Force proper data types for critical fields
                $option['Id'] = (int)$option['Id'];
                $option['Isactive'] = true;
                $option['LastUpdateDate'] = now()->format('Y-m-d H:i:s');
               
                // Try direct DB insertion to bypass model validation
                try {
                    // Handle large ID values as strings instead of integers
                    // Other numeric fields still need to be within database limits
                    $maxIntValue = 2147483647; // Max value for INT in MySQL
                    
                    // Handle FyToken specifically since that's causing the error
                    $fyTokenValue = $option['FyToken'] ?? 0;
                    if (is_numeric($fyTokenValue) && $fyTokenValue > $maxIntValue) {
                        $fyTokenValue = $maxIntValue; // Truncate to max INT value
                    }
                    
                    // Handle other potentially large numeric fields
                    $instrumentToken = $option['InstrumentToken'] ?? 0;
                    if (is_numeric($instrumentToken) && $instrumentToken > $maxIntValue) {
                        $instrumentToken = $maxIntValue;
                    }
                    
                    $fyTokenUnderlying = $option['FyTokenUnderlying'] ?? 0;
                    if (is_numeric($fyTokenUnderlying) && $fyTokenUnderlying > $maxIntValue) {
                        $fyTokenUnderlying = $maxIntValue;
                    }
                    
                    // Handle Multiplier specifically
                    $multiplier = $option['Multiplier'] ?? 0;
                    if (is_numeric($multiplier)) {
                        if ($multiplier > 32767) { // Max value for SMALLINT
                            $multiplier = 32767;
                        } elseif ($multiplier < -32768) { // Min value for SMALLINT
                            $multiplier = -32768;
                        }
                    } else {
                        $multiplier = 0;
                    }
                    
                    // Use updateOrInsert to handle duplicate IDs - use ID as string to preserve large values
                    $result = DB::table('forexoptions')->updateOrInsert(
                        ['Id' => $option['Id'] ?? '0'], // Key for checking duplicates - keep as string
                        [
                        'Id' => $option['Id'] ?? '0', // Keep ID as string to handle large values
                        'Symbol' => substr($option['Symbol'] ?? '', 0, 50), // Limit string length
                        'Description' => substr($option['Description'] ?? '', 0, 255),
                        'Exchange' => (int)($option['Exchange'] ?? 0),
                        'Segment' => (int)($option['Segment'] ?? 0),
                        'TickSize' => (float)($option['TickSize'] ?? 0),
                        'TradingSession' => substr($option['TradingSession'] ?? '', 0, 50),
                        'ExpiryDate' => $option['ExpiryDate'] ?? now()->format('Y-m-d'),
                        'InstrumentToken' => $instrumentToken,
                        'ExchangeInstrument' => substr($option['ExchangeInstrument'] ?? '', 0, 50),
                        'LotSize' => (int)($option['LotSize'] ?? 0),
                        'InstrumentType' => (int)($option['InstrumentType'] ?? 0),
                        'FyToken' => $fyTokenValue,
                        'Underlying' => substr($option['Underlying'] ?? '', 0, 50),
                        'Multiplier' => $multiplier,
                        'StrikePrice' => (float)($option['StrikePrice'] ?? 0),
                        'OptionType' => substr($option['OptionType'] ?? '', 0, 10),
                        'FyTokenUnderlying' => $fyTokenUnderlying,
                        'ExName' => substr($option['ExName'] ?? '', 0, 50),
                        'SymbolShortName' => substr($option['SymbolShortName'] ?? '', 0, 50),
                        'DataDate' => $option['DataDate'] ?? now()->format('Y-m-d'),
                        'instrument' => substr($option['instrument'] ?? '', 0, 50),
                        'Isactive' => 1,
                        'LastUpdateDate' => now()->format('Y-m-d H:i:s')
                    ]);
                } catch (\Exception $e) {
                    Log::error('DB insertion error for ID ' . $option['Id'] . ': ' . $e->getMessage());
                    Log::error('Problem record: ' . json_encode($option));
                    
                    // Try to identify specific database errors
                    if (strpos($e->getMessage(), 'Out of range value') !== false) {
                        Log::error('Numeric value out of range. Check field sizes in database schema.');
                    } elseif (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                        Log::error('Duplicate key error. Record with this ID likely already exists.');
                        // For duplicates, we'll count them as imported since they're already in the database
                        $importedCount++;
                    } elseif (strpos($e->getMessage(), 'Data too long') !== false) {
                        Log::error('Data too long for column. Check field length limits in database.');
                    }
                    
                    $result = false;
                }
                
                if ($result) {
                    Log::info('Successfully saved record ' . ($index + 1) . ' with ID: ' . $option['Id']);
                    $importedCount++;
                } else {
                    Log::warning('Failed to save record ' . ($index + 1) . ' with ID: ' . $option['Id'] . ' (no exception thrown)');
                }
            } catch (\Exception $ex) {
                // Log error but continue with next record
                Log::error('Error saving record ' . ($index + 1) . ' with ID: ' . $option['Id'] . ': ' . $ex->getMessage());
                
                // Try to identify specific database errors
                if (strpos($ex->getMessage(), 'Duplicate entry') !== false) {
                    Log::error('Duplicate key error. Record with this ID likely already exists.');
                } elseif (strpos($ex->getMessage(), 'Data too long') !== false) {
                    Log::error('Data too long for column. Check field length limits in database.');
                } elseif (strpos($ex->getMessage(), 'cannot be null') !== false) {
                    Log::error('Required field is null. Check that all required fields are provided.');
                }
                
                Log::error('Exception trace: ' . $ex->getTraceAsString());
            }
        }
        
        Log::info('Database save completed. Successfully imported ' . $importedCount . ' out of ' . count($options) . ' records');
        return $importedCount;
    }
}
