<?php

namespace App\Services\Synchronize;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\DB;

/**
 * Trait ReaderTrait.
 *
 * @package App\Services\Synchronize
 */
trait ReaderTrait
{
    public ConnectionInterface $db;

    /**
     * ReaderTrait Constructor.
     * Please add `protected string $connectionName = 'some-name';` to your class.
     */
    public function __construct()
    {
        $this->db = DB::connection($this->connectionName);
    }
}
