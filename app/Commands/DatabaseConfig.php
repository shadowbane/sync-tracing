<?php

namespace App\Commands;

use App\Services\Configs\ConfigReaderService;
use App\Services\Configs\ConfigWriterService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Validation\ValidationException;
use LaravelZero\Framework\Commands\Command;

/**
 * Class DatabaseConfig.
 *
 * @package App\Commands
 */
class DatabaseConfig extends Command
{
    protected array $config;
    protected array $reader;
    protected string $selectedOption;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'dbconfig';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Database Configuration';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->showBaseMenu();

        return;
    }

    /**
     * Show default menu.
     */
    private function showBaseMenu()
    {
        $option = $this->menu('Config Database', [
            'hrms' => 'HRMS Yayasan Vitka',
            'btp' => 'SIAKAD BTP',
            'iteba' => 'SIAKAD ITEBA',
            'tracing' => 'Tracing API',
        ])->open();

        if (blank($option)) {
            return;
        }

        $this->selectedOption = $option;
        $this->readDbConfig($this->selectedOption);
    }

    /**
     * @param string $db
     *
     * @throws \Throwable
     */
    private function readDbConfig(string $db)
    {
        try {
            $this->reader = (new ConfigReaderService())->readConfig($this->selectedOption);

            $this->info("Database Hostname / IP Address: {$this->reader['host']}");
            $this->info("Database Port: {$this->reader['port']}");
            $this->info("Database Name: {$this->reader['database']}");
            $this->info("Database Username: {$this->reader['username']}");
            $this->info("Database Password: {$this->reader['password']}");

            $generateMessage = 'update';

        } catch (ValidationException $validationException) {
            $this->error($validationException->errors()['type'][0]);
            exit(1);
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
            $generateMessage = 'generate';
        }

        $this->askToUpdate($generateMessage);
    }

    /**
     * Asks user whether they want to generate / update.
     *
     * @param string $generateMessage
     *
     * @throws \Throwable
     */
    private function askToUpdate(string $generateMessage)
    {
        $generate = $this->ask("Would you like to {$generateMessage} {$this->selectedOption}.json? [yes / no]", 'yes');

        if (strtolower($generate) == 'y' || strtolower($generate) == 'yes') {
            $this->updateConfig();
        } else {
            $this->showBaseMenu();
        }
    }

    /**
     * Update the config via ConfigWriterService.
     *
     * @throws \Throwable
     */
    public function updateConfig()
    {
        $dbName = $this->ask('Enter Database Name', $this->reader['database'] ?? $this->selectedOption);
        $dbHost = $this->ask('Enter Database Hostname / IP Address', $this->reader['host'] ?? 'localhost');
        $dbUser = $this->ask('Enter Database Username', $this->reader['username'] ?? 'root');
        $dbPassword = $this->ask('Enter Database Password', $this->reader['password'] ?? 'secret');
        $dbPort = $this->ask('Enter Database Port', $this->reader['port'] ?? 3306);

        try {
            $writer = new ConfigWriterService();
            $writer->setConfig(
                $this->selectedOption,
                $dbName,
                $dbHost,
                $dbUser,
                $dbPassword,
                $dbPort
            )->writeConfig();
        } catch (ValidationException $validationException) {
            $this->error($validationException->errors()['type'][0]);
            exit(1);
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }

        $this->showBaseMenu();
    }
}
