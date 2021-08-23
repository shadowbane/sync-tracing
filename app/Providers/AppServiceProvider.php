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
                'database.connections.hrms' => $configReader->readConfig('hrms.json'),
                // load SIAKAD BTP DB
                'database.connections.btp' => $configReader->readConfig('siakad_btp2.json'),
                // load HRMS DB
                'database.connections.iteba' => $configReader->readConfig('siakad_iteba.json'),
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
