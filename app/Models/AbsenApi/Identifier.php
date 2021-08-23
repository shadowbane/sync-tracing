<?php

namespace App\Models\AbsenApi;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Identifier.
 *
 * @package App\Models\AbsenApi
 */
class Identifier extends Model
{
    protected $table = 'internal_identifiers';
    protected $fillable = ['identifier', 'name', 'unit', 'vaccine_count'];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
