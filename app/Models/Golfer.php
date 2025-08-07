<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Golfer extends Model
{
    /** @use HasFactory<\Database\Factories\GolferFactory> */
    use HasFactory;

    protected $fillable = [
        'debitor_account',
        'name',
        'email',
        'born_at',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'debitor_account' => 'integer',
        'name' => 'string',
        'email' => 'string',
        'born_at' => 'immutable_date',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    /**
     * Added: Alex Kuvallini
     * Autoincrease the debitor account number based on the lastest number in the golfer database
     */
    public static function booted(): void
    {
        static::creating(function (self $table) {
            $table->debitor_account = $table->max('debitor_account') + 1;
        });
    }
}
