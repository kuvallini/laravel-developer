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
}
