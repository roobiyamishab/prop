<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    // Package table name
    protected $table = 'countries';

    // If the package doesn't use timestamps, uncomment:
    // public $timestamps = false;

    protected $fillable = [
        'name', 'iso2', 'iso3', 'phone_code', 'flag',
    ];

    public function states()
    {
        return $this->hasMany(State::class);
    }
}
