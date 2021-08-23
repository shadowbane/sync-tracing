<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class Synchronize extends Command
{
    protected string $selectedOption;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'command:name {--server=}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Synchronize data to Tracing API';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $server = $this->option('server');

        if (blank($server)) {
            $this->showBaseMenu();
        }
    }

    /**
     * Show default menu.
     */
    private function showBaseMenu()
    {
        $option = $this->menu('Select server to synchronize', [
            'all' => 'All Server',
            'hrms' => 'HRMS Yayasan Vitka',
            'siakad_btp2' => 'SIAKAD BTP',
            'siakad_iteba' => 'SIAKAD ITEBA',
        ])->open();

        if (blank($option)) {
            return;
        }

        $this->selectedOption = $option;
        $this->readDbConfig($this->selectedOption);
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
