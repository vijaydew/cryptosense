<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CryptoPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'cryptocurrency',
        'price',
        'change_24h',
        'created_at'
    ];

    protected $casts = [
        'price' => 'float',
        'change_24h' => 'float',
        'created_at' => 'datetime'
    ];
}