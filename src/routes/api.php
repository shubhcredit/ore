<?php

use Illuminate\Support\Facades\Route;

Route::get('ore/up', function () {
    return response()->json(['Status' => true, 'message' => 'Ore api route is ready to serve | Make development easy...']);
});