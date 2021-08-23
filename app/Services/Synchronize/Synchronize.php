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

    /**
     * Construct Synchronizer.
     */
    public function __construct()
    {
        $this->hrms = new Hrms();
        $this->btp = new Btp();
        $this->iteba = new Iteba();
    }

    public function getData(): Collection
    {
        $result = collect();

        $this->hrms->setQuery()->get()->each(fn ($val, $key) => $result->push($val));

        $this->btp->setQuery()->get()->each(fn ($val, $key) => $result->push($val));

        $this->iteba->setQuery()->get()->each(fn ($val, $key) => $result->push($val));

        return $result;
    }

}
