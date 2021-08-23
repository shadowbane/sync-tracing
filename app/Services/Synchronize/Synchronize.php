<?php

namespace App\Services\Synchronize;

use Illuminate\Support\Collection;

/**
 * Class Synchronize.
 *
 * @package App\Services\Synchronize
 */
class Synchronize
{
    public Hrms $hrms;
    public Btp $btp;
    public Iteba $iteba;
    public Collection $result;

    /**
     * Construct Synchronizer.
     */
    public function __construct()
    {
        $this->hrms = new Hrms();
        $this->btp = new Btp();
        $this->iteba = new Iteba();
        $this->result = collect();
    }

    public function getAllData(): Collection
    {
        $this->result = collect();

        $this->hrms->setQuery()->get()->each(fn ($val, $key) => $this->result->push($val));

        $this->btp->setQuery()->get()->each(fn ($val, $key) => $this->result->push($val));

        $this->iteba->setQuery()->get()->each(fn ($val, $key) => $this->result->push($val));

        return $this->result;
    }

    public function getData(string $type): Collection
    {
        $this->result = collect();

        $this->{$type}->setQuery()->get()->each(fn ($val, $key) => $this->result->push($val));

        return $this->result;
    }

}
