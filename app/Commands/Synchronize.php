<?php

namespace App\Commands;

use App\Services\Synchronize\Synchronize as SyncService;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class Synchronize extends Command
{
    protected string $selectedOption;
    protected SyncService $syncService;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'sync
        {--server= : Valid Options: all, hrms, btp, iteba}
    ';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Synchronize data to Tracing API';

    /**
     * Execute the console command.
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     *
     * @return mixed
     */
    public function handle()
    {
        $this->syncService = app()->make(SyncService::class);

        $this->selectedOption = strtolower($this->option('server'));

        if (blank($this->selectedOption)) {
            $this->showBaseMenu();
        }

        $this->loadData();
    }

    /**
     * Show default menu.
     */
    private function showBaseMenu()
    {
        $option = $this->menu('Select server to synchronize', [
            'all' => 'All Server',
            'hrms' => 'HRMS Yayasan Vitka',
            'btp' => 'SIAKAD BTP',
            'iteba' => 'SIAKAD ITEBA',
        ])->open();

        if (blank($option)) {
            exit(0);
        }

        $this->selectedOption = $option;
    }

    private function loadData()
    {
        if ($this->selectedOption == 'all' || $this->selectedOption == 'a') {
            $this->syncService->getAllData();
        } else {
            $this->syncService->getData($this->selectedOption);
        }
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
         $schedule->command(static::class, ['--server=a'])->everyThirtyMinutes();
    }
}
