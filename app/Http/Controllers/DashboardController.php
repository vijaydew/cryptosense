<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FastApiService;
use App\Models\SentimentData;
use App\Models\CryptoPrice;
use App\Models\SentimentSummary;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $apiService;

    public function __construct(FastApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function index()
    {
        // Get latest data from database
        $latestSummary = SentimentSummary::latest()->first();
        $recentTweets = SentimentData::with('cryptoPrice')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        $currentPrices = CryptoPrice::whereIn('cryptocurrency', ['bitcoin', 'ethereum', 'solana', 'cardano', 'dogecoin'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('cryptocurrency')
            ->map(function ($prices) {
                return $prices->first();
            });

        // Get sentiment history for charts - format properly
        $sentimentHistory = SentimentSummary::orderBy('created_at', 'asc')
            ->limit(20)
            ->get();

        // Format data for view
        $summary = $latestSummary ? [
            'total_tweets' => $latestSummary->total_tweets,
            'positive_count' => $latestSummary->positive_count,
            'negative_count' => $latestSummary->negative_count,
            'neutral_count' => $latestSummary->neutral_count,
            'average_sentiment' => $latestSummary->average_sentiment,
            'last_updated' => $latestSummary->created_at
        ] : [
            'total_tweets' => 0,
            'positive_count' => 0,
            'negative_count' => 0,
            'neutral_count' => 0,
            'average_sentiment' => 0,
            'last_updated' => null
        ];

        // Prepare chart data
        $chartData = $this->prepareChartData($sentimentHistory, $summary);

        return view('dashboard.index', [
            'summary' => $summary,
            'recentTweets' => $recentTweets,
            'prices' => $currentPrices,
            'sentimentHistory' => $sentimentHistory,
            'chartData' => $chartData // Pass formatted chart data
        ]);
    }

    /**
     * Prepare data for charts
     */
    private function prepareChartData($sentimentHistory, $summary)
    {
        // Sentiment Trend Chart Data
        $sentimentLabels = [];
        $sentimentScores = [];

        foreach ($sentimentHistory as $data) {
            $sentimentLabels[] = $data->created_at->format('M j H:i');
            $sentimentScores[] = $data->average_sentiment;
        }

        // Distribution Chart Data
        $distributionData = [
            $summary['positive_count'],
            $summary['negative_count'],
            $summary['neutral_count']
        ];

        $distributionLabels = ['Positive', 'Negative', 'Neutral'];
        $distributionColors = ['#10B981', '#EF4444', '#6B7280'];

        return [
            'sentiment' => [
                'labels' => $sentimentLabels,
                'scores' => $sentimentScores
            ],
            'distribution' => [
                'data' => $distributionData,
                'labels' => $distributionLabels,
                'colors' => $distributionColors
            ]
        ];
    }

    public function fetchLiveData()
    {

        try {
            Log::info('🔄 fetchLiveData method called');
            // Fetch fresh data from FastAPI
            $liveData = $this->apiService->getDashboardData();
            // Debug: Log the data structure
            Log::info('📊 Live Data Received', [
                'has_summary' => isset($liveData['summary']),
                'has_tweets' => isset($liveData['recent_tweets']),
                'tweet_count' => isset($liveData['recent_tweets']) ? count($liveData['recent_tweets']) : 0,
                'has_prices' => isset($liveData['prices']),
                'price_count' => isset($liveData['prices']) ? count($liveData['prices']) : 0
            ]);

            // Debug: Check the actual structure
            Log::info('Live Data Structure:', $liveData);

            // Store in database
            DB::transaction(function () use ($liveData) {
                // Store sentiment summary
                $summary = SentimentSummary::create([
                    'total_tweets' => $liveData['summary']['total_tweets'] ?? 0,
                    'positive_count' => $liveData['summary']['positive_count'] ?? 0,
                    'negative_count' => $liveData['summary']['negative_count'] ?? 0,
                    'neutral_count' => $liveData['summary']['neutral_count'] ?? 0,
                    'average_sentiment' => $liveData['summary']['average_sentiment'] ?? 0,
                ]);

                // Store tweets/sentiment data
                if (isset($liveData['recent_tweets']) && is_array($liveData['recent_tweets'])) {
                    foreach ($liveData['recent_tweets'] as $tweet) {
                        // Check if tweet already exists to avoid duplicates
                        $existingTweet = SentimentData::where('tweet_id', $tweet['id'] ?? null)->first();

                        if (!$existingTweet) {
                            SentimentData::create([
                                'tweet_id' => $tweet['id'] ?? uniqid(),
                                'content' => $tweet['text'] ?? $tweet['content'] ?? '',
                                'sentiment_score' => $tweet['score'] ?? 0,
                                'sentiment_label' => $tweet['sentiment'] ?? 'neutral',
                                'cryptocurrency' => $tweet['crypto'] ?? 'general',
                                'author' => $tweet['author'] ?? 'unknown',
                                'likes' => $tweet['likes'] ?? 0,
                                'retweets' => $tweet['retweets'] ?? 0,
                                'platform' => 'twitter',
                                'created_at' => isset($tweet['timestamp']) ? \Carbon\Carbon::parse($tweet['timestamp']) : now(),
                            ]);
                        }
                    }
                }

                // Store crypto prices
                if (isset($liveData['prices']) && is_array($liveData['prices'])) {
                    foreach ($liveData['prices'] as $price) {
                        CryptoPrice::create([
                            'cryptocurrency' => $price['cryptocurrency'] ?? 'unknown',
                            'price' => $price['price'] ?? 0,
                            'change_24h' => $price['change_24h'] ?? 0,
                        ]);
                    }
                }
            });

            Log::info('Live data stored successfully');
            return back()->with('success', 'Live data fetched and stored successfully!');
        } catch (\Exception $e) {
            Log::error('Error fetching live data: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Failed to fetch live data: ' . $e->getMessage());
        }
    }

    public function collectData()
    {
        $result = $this->apiService->collectTwitterData();
        return back()->with('success', $result['message'] ?? 'Data collection started');
    }

    public function analysis()
    {
        $latestSummary = SentimentSummary::latest()->first();
        $sentimentHistory = SentimentSummary::orderBy('created_at', 'desc')->limit(20)->get();
        $recentTweets = SentimentData::with('cryptoPrice')
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();

        return view('dashboard.analysis', [
            'summary' => $latestSummary,
            'sentimentHistory' => $sentimentHistory,
            'recentTweets' => $recentTweets
        ]);
    }

    public function live()
    {
        $recentTweets = SentimentData::with('cryptoPrice')
            ->orderBy('created_at', 'desc')
            ->limit(30)
            ->get();
        $currentPrices = CryptoPrice::whereIn('cryptocurrency', ['bitcoin', 'ethereum', 'solana', 'cardano', 'dogecoin'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->groupBy('cryptocurrency')
            ->map(function ($prices) {
                return $prices->first();
            });
        $latestSummary = SentimentSummary::latest()->first();

        return view('dashboard.live', [
            'recentTweets' => $recentTweets,
            'prices' => $currentPrices,
            'summary' => $latestSummary
        ]);
    }
}
