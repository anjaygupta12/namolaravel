<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TestController;
use App\Http\Controllers\Admin\DashboardTestController;

// Simple test route
Route::get('/test', function () {
    return 'Test route is working!';
});

// Test middleware directly
Route::get('/test-middleware', function () {
    return 'If you can see this, the middleware is not blocking access';
})->middleware('admin.auth');

// Test middleware with class
Route::get('/test-middleware-class', function () {
    return 'If you can see this, the middleware class is not blocking access';
})->middleware(\App\Http\Middleware\AdminAuth::class);

// Direct dashboard access without middleware
Route::get('/dashboard-test', [DashboardTestController::class, 'index']);

// Admin test route
Route::get('/admin-test', [TestController::class, 'index']);
