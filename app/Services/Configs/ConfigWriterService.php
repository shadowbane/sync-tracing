<?php

namespace App\Services\Configs;

use Exception;

/**
 * Class ConfigWriterService.
 *
 * @package App\Services\Configs;
 */
class ConfigWriterService
{
    protected string $dbName;
    protected string $dbHost;
    protected string $dbUser;
    protected string $dbPassword;
    protected int $dbPort;

    /**
     * @param string $dbName
     * @param string $dbHost
     * @param string $dbUser
     * @param string $dbPassword
     * @param int $dbPort
     *
     * @return $this
     */
    public function setConfig(string $dbName, string $dbHost, string $dbUser, string $dbPassword, int $dbPort = 3306): static | self
    {
        $this->dbName = $dbName;
        $this->dbHost = $dbHost;
        $this->dbUser = $dbUser;
        $this->dbPassword = $dbPassword;
        $this->dbPort = $dbPort;

        return $this;
    }

    public function writeConfig()
    {
        $mysql = config('database.connections.mysql');
        $mysql['host'] = $this->dbHost;
        $mysql['port'] = $this->dbPort;
        $mysql['database'] = $this->dbName;
        $mysql['username'] = $this->dbUser;
        $mysql['password'] = $this->dbPassword;

        $jsonString = collect($mysql)->toJson();
        $this->openAndWrite("{$this->dbName}.json", $jsonString);
    }

    /**
     * @param string $file
     * @param string $textToWrite
     */
    private function openAndWrite(string $file, string $textToWrite)
    {
        try {
            $openedFile = fopen($file, 'w') or exit('Cannot open '.$file.'!');
            fwrite($openedFile, $textToWrite);
            fclose($openedFile);
        } catch (Exception $exception) {
            exit($exception->getMessage());
        }
    }
}
