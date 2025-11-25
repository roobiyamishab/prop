<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyCode extends Model
{
    use HasFactory;

    protected $table = 'property_codes';

    protected $fillable = [
        'prefix',
        'number',
    ];

    /**
     * Helper to get full code like LND100.
     */
    public function getFullCodeAttribute(): string
    {
        return $this->prefix . $this->number;
    }
}
