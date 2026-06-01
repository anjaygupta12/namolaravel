<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\AdminLogin;
use App\Models\RolePermission;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\ForexOption;
use App\Models\DepositeMaster;

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
    Log::info('heating from helper 1');
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
    Log::info('heating from helper 2');
}

function canAccess($permission)
{
    // Check if admin is logged in
    if (!Session::has('admin_id')) {
        return false;
    }

    // Get user with role relationship
    $user = AdminLogin::with('role')->find(Session::get('admin_id'));

    // Check if user exists and has a role
    if (!$user || !$user->role) {
        return false;
    }

    // If user is Admin, grant all permissions
    if ($user->role->name == 'Admin') {
        return true;
    }

    // Get permissions for the user's actual role
    $permissions = RolePermission::join('permissions', 'permissions.id', 'role_permissions.permission_id')
        ->where('role_permissions.role_id', $user->role->id) // Use user's role_id, not hard-coded
        ->pluck('permissions.name')
        ->toArray();

    return in_array($permission, $permissions);
}


// function generateAppHashID($clientID, $secretKey)
// {
//     $raw = $clientID . ':' . $secretKey;
//     return hash('sha256', $raw);
// }
 function updateFyersTokens()
{
  
    try {
        // 1. Get Fyers credentials from DB
        $fyers = DB::table('fyers')->first();

        if (!$fyers) {
            return "Fyers record not found.";
        }

        $clientID  = $fyers->FYERS_CLIENT_ID;
        $secretKey = $fyers->FYERS_SECRET_KEY;
        $authCode  = $fyers->FYERS_AUTH_CODE;
        // 2. Generate appIdHash
        $raw       = $clientID . ':' . $secretKey;
        $appIdHash = hash('sha256', $raw);
        
      
        // 3. Call FYERS API
        $payload = [
            "grant_type"  => "authorization_code",
            "appIdHash"   => $appIdHash,
            "code"        => $authCode
        ];
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post('https://api-t1.fyers.in/api/v3/validate-authcode', $payload);

        $result = $response->json();
        
        // Check success response
        if (!isset($result['s']) || $result['s'] != 'ok') {
           dd($result);
        }

        // 4. Extract tokens
        $accessToken  = $result['access_token'];
        $refreshToken = $result['refresh_token'];
        // dd('hello',$accessToken,$refreshToken);
        // 5. Update DB
        DB::table('fyers')->where('id',1)->update([
            "FYERS_ACCESS_TOKEN"  => $accessToken,
            "FYERS_REFRESH_TOKEN" => $refreshToken
        ]);

        return "FYERS tokens updated successfully.";

    } catch (\Exception $e) {
        // return $e->getMessage();
        dd($e->getMessage());
    }
}

function updateForexOptions()
{
    
    $today = Carbon::today();
    $oneMonthLater = Carbon::today()->addMonth();
    
    ForexOption::whereDate('ExpiryDate', '<', $today)->update(['Isactive' => 0]);

    return DB::table('forexoptions')
        ->where('ExpiryDate', '>', $today)
        ->where('ExpiryDate', '<=', $oneMonthLater)
        ->where('symbol', 'like', '%fut%')
        ->update([
            'Isactive'   => 1,
            'updated_at'=> now()
        ]);
}

 function getNetBalance($userId, $pl = 0)
{
    $startOfWeek = Carbon::now()->startOfWeek(); // Monday
    $endOfWeek   = Carbon::now()->endOfWeek();   // Sunday

    // Opening Balance
    $openingBalance = DepositeMaster::where('UserId', $userId)
        ->where('notes', 'Opening Balance.')
        ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
        ->sum('Amount');

    // Deposit Amount
    $depositAmount = DepositeMaster::where('UserId', $userId)
        ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
        ->where('type', 1)
        ->where(function ($q) {
            $q->where('notes', '!=', 'Opening Balance.')
              ->orWhereNull('notes');
        })
        ->sum('Amount');

    // Withdraw Amount
    $withdrawAmount = DepositeMaster::where('UserId', $userId)
        ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
        ->where('type', 0)
        ->where(function ($q) {
            $q->where('notes', '!=', 'Opening Balance.')
              ->orWhereNull('notes');
        })
        ->sum('Amount');

    // Base Net Balance
    $netBalance = $openingBalance + $depositAmount - $withdrawAmount;

    // Apply P/L
    if ($pl < 0) {
        $netBalance = $netBalance - abs($pl);
    } else {
        $netBalance = $netBalance + $pl;
    }

    return [
        'opening_balance' => $openingBalance,
        'deposit_amount'  => $depositAmount,
        'withdraw_amount' => $withdrawAmount,
        'pl'              => $pl,
        'net_balance'     => number_format($netBalance, 2, '.', ''),
    ];
}





