<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentimentSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_tweets',
        'positive_count',
        'negative_count',
        'neutral_count',
        'average_sentiment',
        'created_at'
    ];

    protected $casts = [
        'average_sentiment' => 'float',
        'total_tweets' => 'integer',
        'positive_count' => 'integer',
        'negative_count' => 'integer',
        'neutral_count' => 'integer',
        'created_at' => 'datetime'
    ];
}