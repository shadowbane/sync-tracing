<?php

namespace App\Providers;

use App\Services\Configs\ConfigReaderService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @throws \Throwable
     *
     * @return void
     */
    public function boot()
    {
        $configReader = new ConfigReaderService();

        try {
            config([
                // load HRMS DB
                'database.connections.hrms' => $configReader->readConfig('hrms'),
                // load SIAKAD BTP DB
                'database.connections.btp' => $configReader->readConfig('btp'),
                // load HRMS DB
                'database.connections.iteba' => $configReader->readConfig('iteba'),
                // load Tracing DB
                'database.connections.tracing' => $configReader->readConfig('tracing'),
            ]);
        } catch (\Exception $exception) {
            if (app()->runningInConsole()) {
                var_dump($exception->getMessage());
            }
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
