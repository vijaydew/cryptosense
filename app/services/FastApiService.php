<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class FastApiService
{
    protected $client;
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'http://localhost:1999';
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => 20.0,
            'connect_timeout' => 10.0,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'verify' => false, // Disable SSL verification for local development
        ]);
    }

    public function getDashboardData()
    {
        try {
            Log::info("🔄 Attempting to connect to FastAPI at: {$this->baseUrl}/dashboard/data");
            
            $response = $this->client->get('/dashboard/data');
            $data = json_decode($response->getBody(), true);
            
            Log::info("✅ Successfully fetched data from FastAPI");
            return $data;
            
        } catch (RequestException $e) {
            $this->logRequestException($e, 'getDashboardData');
            return $this->getMockData();
        } catch (\Exception $e) {
            Log::error('🚨 Unexpected error in getDashboardData: ' . $e->getMessage());
            return $this->getMockData();
        }
    }

    public function collectTwitterData()
    {
        try {
            Log::info("🔄 Attempting to collect tweets from FastAPI");
            
            $response = $this->client->get('/collect/twitter');
            $data = json_decode($response->getBody(), true);
            
            Log::info("✅ Successfully triggered tweet collection");
            return $data;
            
        } catch (RequestException $e) {
            $this->logRequestException($e, 'collectTwitterData');
            return ['status' => 'error', 'message' => 'Failed to connect to data collection service'];
        } catch (\Exception $e) {
            Log::error('🚨 Unexpected error in collectTwitterData: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Unexpected error occurred'];
        }
    }

    public function getRecentTweets($limit = 20)
    {
        try {
            $response = $this->client->get("/tweets/recent?limit={$limit}");
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            $this->logRequestException($e, 'getRecentTweets');
            return ['tweets' => []];
        } catch (\Exception $e) {
            Log::error('🚨 Unexpected error in getRecentTweets: ' . $e->getMessage());
            return ['tweets' => []];
        }
    }

    private function logRequestException(RequestException $e, string $method)
    {
        if ($e->hasResponse()) {
            $statusCode = $e->getResponse()->getStatusCode();
            $responseBody = $e->getResponse()->getBody()->getContents();
            Log::error("🚨 FastAPI Error in {$method}: Status {$statusCode} - {$responseBody}");
        } else {
            Log::error("🚨 FastAPI Error in {$method}: No response - " . $e->getMessage());
        }
    }

    private function getMockData()
    {
        Log::info("🔄 Using mock data as fallback");
        
        return [
            'summary' => [
                'total_tweets' => 42,
                'positive_count' => 20,
                'negative_count' => 5,
                'neutral_count' => 17,
                'average_sentiment' => 0.334032,
                'last_updated' => now()->toISOString()
            ],
            'recent_tweets' => [
                [
                    'id' => 1,
                    'text' => 'Bitcoin is looking bullish today! 🚀 #BTC #crypto to the moon!',
                    'sentiment' => 'positive',
                    'score' => 0.8562,
                    'crypto' => 'bitcoin',
                    'author' => 'crypto_enthusiast',
                    'timestamp' => now()->subMinutes(5)->toISOString(),
                    'likes' => 24,
                    'retweets' => 12
                ],
                [
                    'id' => 2,
                    'text' => 'Ethereum 2.0 is going to revolutionize everything! The future looks bright for #ETH',
                    'sentiment' => 'positive',
                    'score' => 0.7891,
                    'crypto' => 'ethereum',
                    'author' => 'eth_maximalist',
                    'timestamp' => now()->subMinutes(10)->toISOString(),
                    'likes' => 67,
                    'retweets' => 34
                ]
            ],
            'prices' => [
                [
                    'cryptocurrency' => 'bitcoin',
                    'price' => 102770.50,
                    'change_24h' => 0.38,
                    'timestamp' => now()->toISOString()
                ],
                [
                    'cryptocurrency' => 'ethereum',
                    'price' => 3376.17,
                    'change_24h' => 0.84,
                    'timestamp' => now()->toISOString()
                ]
            ],
            'sentiment_history' => [
                [
                    'timestamp' => now()->subHours(1)->toISOString(),
                    'average_sentiment' => 0.334032,
                    'positive_count' => 24,
                    'negative_count' => 5
                ]
            ]
        ];
    }

    // Add a health check method
    public function healthCheck()
    {
        try {
            $response = $this->client->get('/', ['timeout' => 5]);
            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            Log::error('🚨 FastAPI health check failed: ' . $e->getMessage());
            return false;
        }
    }
}