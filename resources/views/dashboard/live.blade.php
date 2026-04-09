@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-4 sm:py-6">
    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 lg:mb-8">
        <div class="mb-4 lg:mb-0">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold bg-gradient-to-r from-purple-400 to-pink-600 bg-clip-text text-transparent">
                ⚡ Live Monitor
            </h1>
            <p class="text-gray-400 mt-1 sm:mt-2 text-sm sm:text-base">Real-time cryptocurrency sentiment monitoring</p>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                Last Updated: <span class="text-green-400" id="lastUpdated">{{ now()->diffForHumans() }}</span> |
                <span class="text-green-400">●</span> <span id="connectionStatus">Connected</span>
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                <span class="text-green-400 text-sm font-semibold">LIVE</span>
            </div>
            <button id="refreshData" class="bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white px-4 py-2 rounded-xl font-semibold transition-all text-sm">
                🔄 Refresh
            </button>
        </div>
    </div>

    <!-- Real-time Metrics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Live Sentiment Gauge -->
        <div class="crypto-card p-4 sm:p-6">
            <h3 class="text-lg sm:text-xl font-bold text-white mb-4">🎯 Live Sentiment</h3>
            <div class="h-48 flex items-center justify-center">
                <div class="relative w-32 h-32">
                    <canvas id="sentimentGauge"></canvas>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <div id="currentSentiment" class="text-2xl font-bold text-white">{{ number_format($summary->average_sentiment ?? 0, 3) }}</div>
                            <div id="sentimentLabel" class="text-sm {{ ($summary->average_sentiment ?? 0) > 0.1 ? 'text-green-400' : (($summary->average_sentiment ?? 0) < -0.1 ? 'text-red-400' : 'text-yellow-400') }}">
                                {{ ($summary->average_sentiment ?? 0) > 0.1 ? 'BULLISH' : (($summary->average_sentiment ?? 0) < -0.1 ? 'BEARISH' : 'NEUTRAL') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Panel -->
        <div class="crypto-card p-4 sm:p-6">
            <h3 class="text-lg sm:text-xl font-bold text-white mb-4">🚨 Active Alerts</h3>
            <div class="space-y-3 max-h-48 overflow-y-auto" id="alertPanel">
                <div class="alert-item bg-red-500/20 border border-red-500 p-3 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="text-red-400">🔴</span>
                            <span class="text-white text-sm font-semibold">High Negative Spike</span>
                        </div>
                        <span class="text-red-400 text-xs">2 min ago</span>
                    </div>
                    <p class="text-gray-400 text-xs mt-1">BTC sentiment dropped by 35%</p>
                </div>
                <div class="alert-item bg-yellow-500/20 border border-yellow-500 p-3 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="text-yellow-400">🟡</span>
                            <span class="text-white text-sm font-semibold">Volume Surge</span>
                        </div>
                        <span class="text-yellow-400 text-xs">5 min ago</span>
                    </div>
                    <p class="text-gray-400 text-xs mt-1">ETH mentions increased by 150%</p>
                </div>
                <div class="alert-item bg-green-500/20 border border-green-500 p-3 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="text-green-400">🟢</span>
                            <span class="text-white text-sm font-semibold">Positive Trend</span>
                        </div>
                        <span class="text-green-400 text-xs">8 min ago</span>
                    </div>
                    <p class="text-gray-400 text-xs mt-1">SOL sentiment rising steadily</p>
                </div>
            </div>
        </div>

        <!-- System Health -->
        <div class="crypto-card p-4 sm:p-6">
            <h3 class="text-lg sm:text-xl font-bold text-white mb-4">🖥️ System Status</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-sm">API Status:</span>
                    <span class="text-green-400 font-bold text-sm">● Operational</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-sm">Data Latency:</span>
                    <span class="text-green-400 font-bold text-sm" id="dataLatency">1.2s</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-sm">Tweet Rate:</span>
                    <span class="text-blue-400 font-bold text-sm" id="tweetRate">45/min</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-sm">Processing:</span>
                    <span class="text-green-400 font-bold text-sm">98.5%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Real-time Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Live Sentiment Stream -->
        <div class="crypto-card p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6">
                <h3 class="text-lg sm:text-xl font-bold text-white mb-2 sm:mb-0">📊 Live Sentiment Stream</h3>
                <div class="flex items-center space-x-2">
                    <span class="px-2 sm:px-3 py-1 bg-purple-500/20 text-purple-400 rounded-lg text-xs sm:text-sm">Real-time</span>
                    <span class="text-gray-400 text-xs sm:text-sm" id="streamCount">0 tweets</span>
                </div>
            </div>
            <div class="h-64 sm:h-72">
                <canvas id="liveSentimentChart"></canvas>
            </div>
        </div>

        <!-- Price vs Sentiment -->
        <div class="crypto-card p-4 sm:p-6">
            <h3 class="text-lg sm:text-xl font-bold text-white mb-4 sm:mb-6">💹 Price vs Sentiment</h3>
            <div class="h-64 sm:h-72">
                <canvas id="priceSentimentChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Live Tweet Stream -->
    <div class="crypto-card p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6">
            <h3 class="text-lg sm:text-xl font-bold text-white">🐦 Live Tweet Stream</h3>
            <div class="flex items-center space-x-3 mt-2 sm:mt-0">
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                    <span class="text-green-400 text-sm" id="tweetStreamStatus">Streaming Active</span>
                </div>
                <button id="clearTweets" class="bg-gray-700 hover:bg-gray-600 text-white px-3 py-1 rounded-lg text-xs transition-all">
                    Clear
                </button>
            </div>
        </div>
        
        <div id="tweetStream" class="space-y-3 max-h-96 overflow-y-auto">
            <!-- Dynamic tweet stream will appear here -->
            @foreach($recentTweets as $tweet)
            <div class="tweet-item bg-gray-800/50 p-3 rounded-lg border border-gray-700">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex items-center space-x-2">
                        <span class="text-blue-400 text-sm font-semibold">{{ $tweet->author }}</span>
                        <span class="text-gray-500 text-xs">•</span>
                        <span class="text-gray-400 text-xs">{{ $tweet->created_at->diffForHumans() }}</span>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full {{ $tweet->sentiment_label == 'positive' ? 'bg-green-500/20 text-green-400' : ($tweet->sentiment_label == 'negative' ? 'bg-red-500/20 text-red-400' : 'bg-yellow-500/20 text-yellow-400') }}">
                        {{ ucfirst($tweet->sentiment_label) }}
                    </span>
                </div>
                <p class="text-gray-300 text-sm mb-2">{{ $tweet->content }}</p>
                <div class="flex justify-between items-center text-xs text-gray-500">
                    <span>Score: {{ number_format($tweet->sentiment_score, 3) }}</span>
                    <div class="flex space-x-3">
                        <span>❤️ {{ $tweet->likes }}</span>
                        <span>🔄 {{ $tweet->retweets }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('⚡ Live Monitor page loaded');
    
    // Initialize Live Charts
    initializeSentimentGauge();
    initializeLiveSentimentChart();
    initializePriceSentimentChart();

    // Simulate real-time updates
    startRealTimeUpdates();

    function initializeSentimentGauge() {
        const ctx = document.getElementById('sentimentGauge');
        if (!ctx) return;

        const currentSentiment = {{ $summary->average_sentiment ?? 0 }};
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [Math.max(0, currentSentiment + 1) * 50, Math.max(0, 1 - currentSentiment) * 50, 0.1],
                    backgroundColor: [
                        currentSentiment > 0.1 ? '#10B981' : currentSentiment < -0.1 ? '#EF4444' : '#F59E0B',
                        '#374151',
                        'transparent'
                    ],
                    borderWidth: 0,
                    circumference: 270,
                    rotation: 225
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: false
                    }
                }
            }
        });
    }

    function initializeLiveSentimentChart() {
        const ctx = document.getElementById('liveSentimentChart');
        if (!ctx) return;

        // Generate sample real-time data
        const baseTime = new Date();
        const labels = [];
        const data = [];
        
        for (let i = 9; i >= 0; i--) {
            const time = new Date(baseTime.getTime() - i * 60000);
            labels.push(time.getMinutes() + ':' + time.getSeconds().toString().padStart(2, '0'));
            data.push(0.5 + Math.random() * 0.5 - 0.25); // Random data between 0.25 and 0.75
        }

        window.liveSentimentChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Sentiment Score',
                    data: data,
                    borderColor: '#8B5CF6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#8B5CF6',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 0
                },
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(55, 65, 81, 0.3)'
                        },
                        ticks: {
                            color: '#9CA3AF',
                            maxRotation: 0
                        }
                    },
                    y: {
                        min: -1,
                        max: 1,
                        grid: {
                            color: 'rgba(55, 65, 81, 0.3)'
                        },
                        ticks: {
                            color: '#9CA3AF'
                        }
                    }
                }
            }
        });
    }

    function initializePriceSentimentChart() {
        const ctx = document.getElementById('priceSentimentChart');
        if (!ctx) return;

        window.priceSentimentChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['-5m', '-4m', '-3m', '-2m', '-1m', 'Now'],
                datasets: [
                    {
                        label: 'Price',
                        data: [45000, 45200, 45150, 45300, 45250, 45350],
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        yAxisID: 'y',
                        fill: true
                    },
                    {
                        label: 'Sentiment',
                        data: [0.6, 0.65, 0.62, 0.68, 0.66, 0.7],
                        borderColor: '#8B5CF6',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        yAxisID: 'y1',
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(55, 65, 81, 0.3)'
                        },
                        ticks: {
                            color: '#9CA3AF'
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        grid: {
                            color: 'rgba(55, 65, 81, 0.3)'
                        },
                        ticks: {
                            color: '#10B981'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        min: -1,
                        max: 1,
                        grid: {
                            drawOnChartArea: false
                        },
                        ticks: {
                            color: '#8B5CF6'
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: '#9CA3AF'
                        }
                    }
                }
            }
        });
    }

    function startRealTimeUpdates() {
        // Update timestamp every second
        setInterval(() => {
            document.getElementById('lastUpdated').textContent = 'just now';
        }, 1000);

        // Simulate real-time data updates
        setInterval(() => {
            updateLiveCharts();
            simulateNewTweet();
            updateMetrics();
        }, 3000);
    }

    function updateLiveCharts() {
        if (window.liveSentimentChart) {
            const chart = window.liveSentimentChart;
            const now = new Date();
            const newLabel = now.getMinutes() + ':' + now.getSeconds().toString().padStart(2, '0');
            const newData = 0.5 + Math.random() * 0.5 - 0.25;

            // Remove first data point and add new one
            chart.data.labels.push(newLabel);
            chart.data.datasets[0].data.push(newData);
            
            if (chart.data.labels.length > 10) {
                chart.data.labels.shift();
                chart.data.datasets[0].data.shift();
            }

            chart.update('quiet');
        }

        if (window.priceSentimentChart) {
            const chart = window.priceSentimentChart;
            // Simulate price and sentiment movement
            const lastPrice = chart.data.datasets[0].data[5];
            const lastSentiment = chart.data.datasets[1].data[5];
            
            chart.data.datasets[0].data = chart.data.datasets[0].data.map((_, i, arr) => 
                i < 5 ? arr[i + 1] : lastPrice + (Math.random() - 0.5) * 100
            );
            chart.data.datasets[1].data = chart.data.datasets[1].data.map((_, i, arr) => 
                i < 5 ? arr[i + 1] : Math.max(-1, Math.min(1, lastSentiment + (Math.random() - 0.5) * 0.1))
            );

            chart.update('quiet');
        }
    }

    function simulateNewTweet() {
        const sentiments = ['positive', 'negative', 'neutral'];
        const authors = ['@crypto_whale', '@btc_max', '@eth_trader', '@defi_guru', '@web3_builder'];
        const tweets = [
            "Bitcoin showing strong bullish signals! 🚀 #BTC",
            "Market sentiment seems uncertain today 📉",
            "Ethereum upgrade is generating positive buzz! #ETH",
            "Regulatory concerns affecting overall sentiment 😟",
            "Solana ecosystem continues to grow rapidly! 🌟"
        ];

        const randomSentiment = sentiments[Math.floor(Math.random() * sentiments.length)];
        const tweetHTML = `
            <div class="tweet-item bg-gray-800/50 p-3 rounded-lg border border-gray-700">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex items-center space-x-2">
                        <span class="text-blue-400 text-sm font-semibold">${authors[Math.floor(Math.random() * authors.length)]}</span>
                        <span class="text-gray-500 text-xs">•</span>
                        <span class="text-gray-400 text-xs">just now</span>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full ${randomSentiment === 'positive' ? 'bg-green-500/20 text-green-400' : (randomSentiment === 'negative' ? 'bg-red-500/20 text-red-400' : 'bg-yellow-500/20 text-yellow-400')}">
                        ${randomSentiment.charAt(0).toUpperCase() + randomSentiment.slice(1)}
                    </span>
                </div>
                <p class="text-gray-300 text-sm mb-2">${tweets[Math.floor(Math.random() * tweets.length)]}</p>
                <div class="flex justify-between items-center text-xs text-gray-500">
                    <span>Score: ${(Math.random() * 2 - 1).toFixed(3)}</span>
                    <div class="flex space-x-3">
                        <span>❤️ ${Math.floor(Math.random() * 50)}</span>
                        <span>🔄 ${Math.floor(Math.random() * 20)}</span>
                    </div>
                </div>
            </div>
        `;

        const stream = document.getElementById('tweetStream');
        stream.insertAdjacentHTML('afterbegin', tweetHTML);
        
        // Update stream count
        const count = stream.children.length;
        document.getElementById('streamCount').textContent = `${count} tweets`;
        
        // Limit to 50 tweets
        if (count > 50) {
            stream.removeChild(stream.lastChild);
        }
    }

    function updateMetrics() {
        // Update random metrics
        document.getElementById('dataLatency').textContent = (0.5 + Math.random() * 2).toFixed(1) + 's';
        document.getElementById('tweetRate').textContent = Math.floor(30 + Math.random() * 40) + '/min';
    }

    // Refresh button
    document.getElementById('refreshData').addEventListener('click', function() {
        this.disabled = true;
        this.innerHTML = '🔄 Refreshing...';
        
        setTimeout(() => {
            this.disabled = false;
            this.innerHTML = '🔄 Refresh';
            document.getElementById('lastUpdated').textContent = 'just now';
        }, 2000);
    });

    // Clear tweets button
    document.getElementById('clearTweets').addEventListener('click', function() {
        document.getElementById('tweetStream').innerHTML = '';
        document.getElementById('streamCount').textContent = '0 tweets';
    });
});
</script>

<style>
.tweet-item {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.alert-item {
    animation: pulseAlert 2s infinite;
}

@keyframes pulseAlert {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}

#tweetStream::-webkit-scrollbar {
    width: 6px;
}

#tweetStream::-webkit-scrollbar-track {
    background: #1F2937;
    border-radius: 3px;
}

#tweetStream::-webkit-scrollbar-thumb {
    background: #4B5563;
    border-radius: 3px;
}

#tweetStream::-webkit-scrollbar-thumb:hover {
    background: #6B7280;
}
</style>
@endsection