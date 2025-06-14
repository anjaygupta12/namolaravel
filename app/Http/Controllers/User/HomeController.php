<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    public function index()
    {
        return View::make('user.index');
    }

    public function trades()
    {
        return View::make('user.trades');
    }

    public function portfolio()
    {
        return View::make('user.portfolio');
    }

    public function watchlist()
    {
        return View::make('user.watchlist');
    }

    public function myAccount()
    {
        return View::make('user.my_account');
    }

    public function depositWithdraw()
    {
        return View::make('user.deposit_withdraw');
    }

    public function depositRequestForm()
    {
        return View::make('user.deposit_request_form');
    }

    public function withdrawalRequestsForm()
    {
        return View::make('user.withdrawal_requests_form');
    }

    public function login()
    {
        return View::make('user.login');
    }
}
