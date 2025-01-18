<?php

namespace Shubhcredit\Ore\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class OreProvider extends ServiceProvider
{

    public function register()
    {
        // Load the helper file
        if (file_exists(__DIR__ . '/../Helpers/OreHelper.php')) {
            require_once __DIR__ . '/../Helpers/OreHelper.php';
        }
        if (file_exists(__DIR__ . '/../Helpers/OreExceptionHandler.php')) {
            require_once __DIR__ . '/../Helpers/OreExceptionHandler.php';
        }

        Route::middleware('api')
            ->prefix('api')
            ->group(__DIR__ . '/../routes/api.php');
    }


    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
    }
}