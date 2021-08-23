<?php

namespace App\Commands;

use App\Models\AbsenApi\Identifier;
use App\Services\Synchronize\Synchronize as SyncService;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

/**
 * Class Synchronize.
 *
 * @package App\Commands
 */
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
        $this->selectedOption = strtolower($this->option('server'));

        if (blank($this->selectedOption)) {
            $this->showBaseMenu();
        }

        try {
            $this->syncService = app()->make(SyncService::class);
            $this->loadData();
            $this->synchronize();
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
            exit(1);
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
            'btp' => 'SIAKAD BTP',
            'iteba' => 'SIAKAD ITEBA',
        ])->open();

        if (blank($option)) {
            exit(0);
        }

        $this->selectedOption = $option;
    }

    /**
     * Load the data from server.
     */
    private function loadData()
    {
        if ($this->selectedOption == 'all' || $this->selectedOption == 'a') {
            $this->syncService->getAllData();
        } else {
            $this->syncService->getData($this->selectedOption);
        }
    }

    /**
     * Run the synchronization.
     */
    private function synchronize()
    {
        foreach ($this->syncService->result as $item) {
            $this->task("Updating: {$item->unit} - {$item->identifier} {$item->name}", function () use ($item) {
                $data = Identifier::updateOrCreate([
                    'identifier' => $item->identifier,
                    'unit' => $item->unit,
                ], [
                    'identifier' => $item->identifier,
                    'unit' => $item->unit,
                    'name' => $item->name,
                    'vaccine_count' => $item->vaccine_count,
                ]);

                if ($data) {
                    return true;
                }

                return false;
            });
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
