<?php

namespace App\Services\Synchronize;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class AbstractConnector.
 *
 * @package App\Services\Synchronize
 */
abstract class AbstractConnector
{
    protected string $connectionName;
    public ConnectionInterface $db;
    public Builder|array $query;

    /**
     * ReaderTrait Constructor.
     * Please add `protected string $connectionName = 'some-name';` to your class.
     */
    public function __construct()
    {
        $this->db = DB::connection($this->connectionName);
    }

    /**
     * Get Query Result.
     *
     * @return \Illuminate\Support\Collection
     */
    public function get(): Collection
    {
        if ($this->query instanceof Builder) {
            return $this->query->get();
        }

        return collect($this->query);
    }

    /**
     * Set the Query.
     *
     * @return $this
     */
    abstract public function setQuery(): self;
}
