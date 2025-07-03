<?php 

use Illuminate\Support\Facades\Http;


function fetchFyersHistoricalData($symbol, $fromDate, $toDate, $resolution = 1)
{
    $data = \DB::table('fyers')->first();
    $url = "https://api-t1.fyers.in/data/history";

    $queryParams = [
        'symbol' => $symbol, 
        'resolution' => $resolution,
        'date_format' => 1,
        'range_from' => $fromDate,
        'range_to' => $toDate,  
        'cont_flag' => 1,
    ];
    
       $token = $data->FYERS_CLIENT_ID.':'.$data->FYERS_ACCESS_TOKEN;
       
    try {
        $response = Http::withHeaders([
            'Authorization' => $token
        ])->get($url, $queryParams);

        if ($response->successful()) {
            return $response->json();
        } else {
            return [
                'error' => true,
                'status' => $response->status(),
                'message' => $response->body()
            ];
        }
    } catch (\Exception $e) {
        return [
            'error' => true,
            'message' => $e->getMessage()
        ];
    }
}



function fetchFyersQuotes(array $symbols)
{
    $url = "https://api-t1.fyers.in/data/quotes";
     $data = \DB::table('fyers')->first();

    $queryParams = [
        'symbols' => implode(',', $symbols),
    ];

 $token = $data->FYERS_CLIENT_ID.':'.$data->FYERS_ACCESS_TOKEN;
    // dd($token);
    try {
        $response = Http::withHeaders([
            'Authorization' => $token
        ])->get($url, $queryParams);

        if ($response->successful()) {
            return $response->json();
        } else {
            return [
                'error' => true,
                'status' => $response->status(),
                'message' => $response->body()
            ];
        }
    } catch (\Exception $e) {
        return [
            'error' => true,
            'message' => $e->getMessage()
        ];
    }
}


