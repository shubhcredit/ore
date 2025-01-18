<?php

use Illuminate\Support\Facades\Route;
use Shubhcredit\Ore\Controllers\Api\OreController;

Route::middleware('web')->group(function () {
    Route::get('ore/up', function () {
        return response()->json(['Status' => true, 'message' => 'Ore web route is ready to serve | Make development easy...']);
    });
});