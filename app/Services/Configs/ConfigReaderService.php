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
    use TypeTrait;

    /**
     * Read config file.
     *
     * @param string $type
     *
     * @throws \Throwable
     *
     * @return array
     */
    public function readConfig(string $type): array
    {
        $file = $this->getFileFromType($type);
        throw_unless(file_exists($file), new Exception("File {$file} Not Found."));

        return json_decode(file_get_contents($file), true);
    }
}
