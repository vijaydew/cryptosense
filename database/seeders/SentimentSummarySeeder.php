<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SentimentSummary;
use Carbon\Carbon;

class SentimentSummarySeeder extends Seeder
{
    public function run(): void
    {
        // Create multiple summary entries to show historical trend
        $summaries = [
            [
                'total_tweets' => 10,
                'positive_count' => 6,
                'negative_count' => 2,
                'neutral_count' => 2,
                'average_sentiment' => 0.3340,
                'created_at' => Carbon::now()
            ],
            [
                'total_tweets' => 8,
                'positive_count' => 4,
                'negative_count' => 2,
                'neutral_count' => 2,
                'average_sentiment' => 0.2567,
                'created_at' => Carbon::now()->subHours(1)
            ],
            [
                'total_tweets' => 12,
                'positive_count' => 7,
                'negative_count' => 3,
                'neutral_count' => 2,
                'average_sentiment' => 0.4123,
                'created_at' => Carbon::now()->subHours(2)
            ],
            [
                'total_tweets' => 9,
                'positive_count' => 5,
                'negative_count' => 2,
                'neutral_count' => 2,
                'average_sentiment' => 0.2987,
                'created_at' => Carbon::now()->subHours(3)
            ],
            [
                'total_tweets' => 11,
                'positive_count' => 6,
                'negative_count' => 3,
                'neutral_count' => 2,
                'average_sentiment' => 0.3567,
                'created_at' => Carbon::now()->subHours(4)
            ]
        ];

        foreach ($summaries as $summary) {
            SentimentSummary::create($summary);
        }

        $this->command->info('✅ Sample sentiment summaries seeded successfully!');
    }
}