<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SentimentData;
use Carbon\Carbon;

class SentimentDataSeeder extends Seeder
{
    public function run(): void
    {
        $sampleTweets = [
            [
                'content' => 'Bitcoin is looking super bullish today! 🚀 #BTC #crypto to the moon!',
                'sentiment_score' => 0.8562,
                'sentiment_label' => 'positive',
                'cryptocurrency' => 'bitcoin',
                'author' => 'crypto_enthusiast',
                'likes' => 24,
                'retweets' => 12,
                'platform' => 'twitter',
                'created_at' => Carbon::now()->subHours(2)
            ],
            [
                'content' => 'Why is Ethereum dropping so much? Concerned about my investments 😟',
                'sentiment_score' => -0.7234,
                'sentiment_label' => 'negative',
                'cryptocurrency' => 'ethereum',
                'author' => 'worried_investor',
                'likes' => 8,
                'retweets' => 3,
                'platform' => 'twitter',
                'created_at' => Carbon::now()->subHours(3)
            ],
            [
                'content' => 'Just bought more Bitcoin! Perfect timing for the upcoming bull run 💰',
                'sentiment_score' => 0.9123,
                'sentiment_label' => 'positive',
                'cryptocurrency' => 'bitcoin',
                'author' => 'smart_trader',
                'likes' => 45,
                'retweets' => 22,
                'platform' => 'twitter',
                'created_at' => Carbon::now()->subHours(1)
            ],
            [
                'content' => 'Crypto market is so volatile today. Not sure what to do with my portfolio...',
                'sentiment_score' => -0.2345,
                'sentiment_label' => 'negative',
                'cryptocurrency' => 'general',
                'author' => 'crypto_newbie',
                'likes' => 12,
                'retweets' => 2,
                'platform' => 'twitter',
                'created_at' => Carbon::now()->subHours(4)
            ],
            [
                'content' => 'Ethereum 2.0 is going to revolutionize everything! The future looks bright for #ETH',
                'sentiment_score' => 0.7891,
                'sentiment_label' => 'positive',
                'cryptocurrency' => 'ethereum',
                'author' => 'eth_maximalist',
                'likes' => 67,
                'retweets' => 34,
                'platform' => 'twitter',
                'created_at' => Carbon::now()->subHours(5)
            ],
            [
                'content' => 'Just checking the crypto prices. Everything seems stable today.',
                'sentiment_score' => 0.0567,
                'sentiment_label' => 'neutral',
                'cryptocurrency' => 'general',
                'author' => 'market_watcher',
                'likes' => 5,
                'retweets' => 1,
                'platform' => 'twitter',
                'created_at' => Carbon::now()->subHours(6)
            ],
            [
                'content' => 'Solana network is performing amazingly! Fast and cheap transactions 🚀',
                'sentiment_score' => 0.8345,
                'sentiment_label' => 'positive',
                'cryptocurrency' => 'solana',
                'author' => 'solana_fan',
                'likes' => 89,
                'retweets' => 45,
                'platform' => 'twitter',
                'created_at' => Carbon::now()->subHours(7)
            ],
            [
                'content' => 'Another crypto scam project rugged. When will this stop? 😠 Lost money again!',
                'sentiment_score' => -0.9123,
                'sentiment_label' => 'negative',
                'cryptocurrency' => 'general',
                'author' => 'disappointed_investor',
                'likes' => 34,
                'retweets' => 67,
                'platform' => 'twitter',
                'created_at' => Carbon::now()->subHours(8)
            ],
            [
                'content' => 'Cardano development is progressing steadily. Good fundamentals for long term.',
                'sentiment_score' => 0.4456,
                'sentiment_label' => 'positive',
                'cryptocurrency' => 'cardano',
                'author' => 'ada_holder',
                'likes' => 23,
                'retweets' => 8,
                'platform' => 'twitter',
                'created_at' => Carbon::now()->subHours(9)
            ],
            [
                'content' => 'Dogecoin community is the best! So much fun and positivity 🐕',
                'sentiment_score' => 0.6678,
                'sentiment_label' => 'positive',
                'cryptocurrency' => 'dogecoin',
                'author' => 'doge_lover',
                'likes' => 156,
                'retweets' => 89,
                'platform' => 'twitter',
                'created_at' => Carbon::now()->subHours(10)
            ]
        ];

        foreach ($sampleTweets as $tweet) {
            SentimentData::create($tweet);
        }

        $this->command->info('✅ Sample sentiment data seeded successfully!');
    }
}