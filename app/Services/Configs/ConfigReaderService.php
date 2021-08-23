<?php

namespace App\Services\Configs;

use Exception;

/**
 * Class ConfigReaderService.
 *
 * @package App\Services\Config
 */
class ConfigReaderService
{

    /**
     * Read config file.
     *
     * @param string $file
     *
     * @throws \Throwable
     * @return mixed
     */
    public function readConfig(string $file)
    {
        throw_unless(file_exists($file), new Exception("File {$file} Not Found."));

        return json_decode(file_get_contents($file), true);
    }
}
