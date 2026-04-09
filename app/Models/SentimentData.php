<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentimentData extends Model
{
    use HasFactory;

    protected $fillable = [
        'tweet_id',
        'content',
        'sentiment_score',
        'sentiment_label',
        'cryptocurrency',
        'author',
        'likes',
        'retweets',
        'platform',
        'created_at'
    ];

    protected $casts = [
        'sentiment_score' => 'float',
        'likes' => 'integer',
        'retweets' => 'integer',
        'created_at' => 'datetime'
    ];

    // Add relationship to crypto prices if needed
    public function cryptoPrice()
    {
        return $this->belongsTo(CryptoPrice::class, 'cryptocurrency', 'cryptocurrency');
    }
}