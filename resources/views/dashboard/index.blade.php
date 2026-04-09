@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-4 sm:py-6">
    <!-- Enhanced Header Section -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 lg:mb-8">
        <div class="mb-4 lg:mb-0">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold bg-gradient-to-r from-blue-400 to-purple-600 bg-clip-text text-transparent">
                🚀 Crypto Sentiment Dashboard
            </h1>
            <p class="text-gray-400 mt-1 sm:mt-2 text-sm sm:text-base">Real-time cryptocurrency market sentiment from social media</p>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                Data Source: 
                <span class="{{ $summary['last_updated'] ? 'text-green-400' : 'text-red-400' }}">
                    {{ $summary['last_updated'] ? 'Database (Stored)' : 'No Data Available' }}
                </span>
                @if($summary['last_updated'])
                    | Updated: {{ \Carbon\Carbon::parse($summary['last_updated'])->diffForHumans() }}
                @endif
            </p>
        </div>
        <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
            <div class="text-left sm:text-right w-full sm:w-auto">
                <p class="text-gray-400 text-xs sm:text-sm">Last Fetched</p>
                <p class="text-white font-semibold text-sm sm:text-base">
                    {{ $summary['last_updated'] ? \Carbon\Carbon::parse($summary['last_updated'])->diffForHumans() : 'Never' }}
                </p>
            </div>
            <div class="flex flex-col xs:flex-row space-y-2 xs:space-y-0 xs:space-x-2 w-full sm:w-auto">
                <form action="{{ route('fetch.live.data') }}" method="POST" class="w-full xs:w-auto">
                    @csrf
                    <button type="submit" class="fetch-live-btn bg-gradient-to-r from-green-500 to-teal-500 hover:from-green-600 hover:to-teal-600 text-white px-3 sm:px-4 py-2 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg flex items-center justify-center text-xs sm:text-sm w-full">
                        <span class="btn-text">⚡ Fetch Live</span>
                        <div class="btn-loader hidden">
                            <div class="crypto-loader"></div>
                            <span class="ml-2">Fetching...</span>
                        </div>
                    </button>
                </form>
                <form action="{{ route('collect.data') }}" method="POST" class="w-full xs:w-auto">
                    @csrf
                    <button type="submit" class="collect-btn bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 text-white px-3 sm:px-4 py-2 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg flex items-center justify-center text-xs sm:text-sm w-full">
                        <span class="btn-text">🔄 Collect Tweets</span>
                        <div class="btn-loader hidden">
                            <div class="crypto-loader"></div>
                            <span class="ml-2">Collecting...</span>
                        </div>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Auto Update Status -->
    <div id="autoUpdateStatus" class="bg-blue-500/20 border border-blue-500 text-blue-400 p-3 rounded-xl mb-6 flex items-center justify-between">
        <div class="flex items-center">
            <span class="text-lg mr-2">🔄</span>
            <div>
                <p class="font-semibold text-sm">Auto-Update Active</p>
                <p class="text-xs">Data refreshes every 60 seconds</p>
            </div>
        </div>
        <div id="countdown" class="text-blue-400 font-bold text-sm">60s</div>
    </div>

    @if(session('success'))
        <div class="bg-green-500/20 border border-green-500 text-green-400 p-3 sm:p-4 rounded-xl mb-6 lg:mb-8 flex items-center">
            <span class="text-xl sm:text-2xl mr-3">✅</span>
            <div>
                <p class="font-semibold text-sm sm:text-base">Success!</p>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500/20 border border-red-500 text-red-400 p-3 sm:p-4 rounded-xl mb-6 lg:mb-8 flex items-center">
            <span class="text-xl sm:text-2xl mr-3">❌</span>
            <div>
                <p class="font-semibold text-sm sm:text-base">Error!</p>
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Enhanced KPI Cards Section -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6 mb-6 lg:mb-8">
        <!-- Total Tweets -->
        <div class="crypto-card p-4 sm:p-6 relative overflow-hidden group transform hover:scale-105 transition-all duration-300">
            <div class="absolute top-0 right-0 w-16 h-16 sm:w-20 sm:h-20 bg-blue-500/10 rounded-full -mr-4 -mt-4 sm:-mr-6 sm:-mt-6"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs sm:text-sm font-medium">Total Tweets</p>
                    <p class="text-2xl sm:text-3xl font-bold text-white mt-1 sm:mt-2">{{ $summary['total_tweets'] }}</p>
                    <p class="text-blue-400 text-xs sm:text-sm mt-1 sm:mt-2 font-semibold">
                        Analyzed & Stored
                    </p>
                </div>
                <div class="text-2xl sm:text-4xl bg-blue-500/20 p-2 sm:p-3 rounded-xl">📈</div>
            </div>
        </div>

        <!-- Average Sentiment -->
        <div class="crypto-card p-4 sm:p-6 relative overflow-hidden group transform hover:scale-105 transition-all duration-300">
            <div class="absolute top-0 right-0 w-16 h-16 sm:w-20 sm:h-20 bg-purple-500/10 rounded-full -mr-4 -mt-4 sm:-mr-6 sm:-mt-6"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs sm:text-sm font-medium">Avg Sentiment</p>
                    <p class="text-2xl sm:text-3xl font-bold text-white mt-1 sm:mt-2">{{ number_format($summary['average_sentiment'], 4) }}</p>
                    <div class="flex items-center mt-2 sm:mt-3">
                        <div class="w-16 sm:w-20 bg-gray-700 rounded-full h-2 mr-2 sm:mr-3">
                            <div class="bg-gradient-to-r from-red-400 via-yellow-400 to-green-400 h-2 rounded-full" 
                                 style="width: {{ (($summary['average_sentiment'] + 1) / 2) * 100 }}%"></div>
                        </div>
                        <span class="text-xs sm:text-sm {{ $summary['average_sentiment'] > 0.1 ? 'text-green-400' : ($summary['average_sentiment'] < -0.1 ? 'text-red-400' : 'text-yellow-400') }}">
                            {{ $summary['average_sentiment'] > 0.1 ? '😊 Bullish' : ($summary['average_sentiment'] < -0.1 ? '😠 Bearish' : '😐 Neutral') }}
                        </span>
                    </div>
                </div>
                <div class="text-2xl sm:text-4xl bg-purple-500/20 p-2 sm:p-3 rounded-xl">📊</div>
            </div>
        </div>

        <!-- Positive Tweets -->
        <div class="crypto-card p-4 sm:p-6 relative overflow-hidden group transform hover:scale-105 transition-all duration-300">
            <div class="absolute top-0 right-0 w-16 h-16 sm:w-20 sm:h-20 bg-green-500/10 rounded-full -mr-4 -mt-4 sm:-mr-6 sm:-mt-6"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs sm:text-sm font-medium">Positive</p>
                    <p class="text-2xl sm:text-3xl font-bold text-green-400 mt-1 sm:mt-2">{{ $summary['positive_count'] }}</p>
                    <p class="text-green-400 text-xs sm:text-sm mt-1 sm:mt-2 font-semibold">
                        {{ $summary['total_tweets'] > 0 ? number_format(($summary['positive_count'] / $summary['total_tweets']) * 100, 1) : 0 }}%
                    </p>
                </div>
                <div class="text-2xl sm:text-4xl bg-green-500/20 p-2 sm:p-3 rounded-xl">😊</div>
            </div>
        </div>

        <!-- Negative Tweets -->
        <div class="crypto-card p-4 sm:p-6 relative overflow-hidden group transform hover:scale-105 transition-all duration-300">
            <div class="absolute top-0 right-0 w-16 h-16 sm:w-20 sm:h-20 bg-red-500/10 rounded-full -mr-4 -mt-4 sm:-mr-6 sm:-mt-6"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs sm:text-sm font-medium">Negative</p>
                    <p class="text-2xl sm:text-3xl font-bold text-red-400 mt-1 sm:mt-2">{{ $summary['negative_count'] }}</p>
                    <p class="text-red-400 text-xs sm:text-sm mt-1 sm:mt-2 font-semibold">
                        {{ $summary['total_tweets'] > 0 ? number_format(($summary['negative_count'] / $summary['total_tweets']) * 100, 1) : 0 }}%
                    </p>
                </div>
                <div class="text-2xl sm:text-4xl bg-red-500/20 p-2 sm:p-3 rounded-xl">😠</div>
            </div>
        </div>
    </div>

    <!-- Charts and Data Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 lg:mb-8">
        <!-- Sentiment Chart -->
        <div class="crypto-card p-4 sm:p-6 lg:col-span-2">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6">
                <h3 class="text-lg sm:text-xl font-bold text-white mb-2 sm:mb-0">📈 Sentiment Trend</h3>
                <div class="flex items-center space-x-2">
                    <span class="px-2 sm:px-3 py-1 bg-blue-500/20 text-blue-400 rounded-lg text-xs sm:text-sm">Historical</span>
                    <span class="text-gray-400 text-xs sm:text-sm">{{ $sentimentHistory->count() }} data points</span>
                </div>
            </div>
            <div class="h-64 sm:h-72 lg:h-80">
                <canvas id="sentimentChart"></canvas>
            </div>
        </div>

        <!-- Crypto Prices & Distribution -->
        <div class="space-y-4 sm:space-y-6">
            <!-- Live Crypto Prices -->
            <div class="crypto-card p-4 sm:p-6">
                <h3 class="text-lg sm:text-xl font-bold text-white mb-3 sm:mb-4">💰 Live Prices</h3>
                <div class="space-y-2 sm:space-y-3">
                    @forelse($prices as $crypto => $price)
                    <div class="flex justify-between items-center p-3 sm:p-4 bg-gray-800/50 rounded-xl hover:bg-gray-800/70 transition-colors">
                        <div class="flex items-center">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-purple-500 to-blue-600 rounded-full flex items-center justify-center mr-2 sm:mr-3 shadow-lg">
                                <span class="text-white font-bold text-xs sm:text-sm">
                                    {{ strtoupper(substr($crypto, 0, 2)) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-white font-semibold text-sm sm:text-base capitalize">{{ $crypto }}</p>
                                <p class="text-gray-400 text-xs sm:text-sm">${{ number_format($price['price'], $price['price'] < 1 ? 4 : 2) }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="{{ $price['change_24h'] >= 0 ? 'text-green-400' : 'text-red-400' }} font-bold text-sm sm:text-lg">
                                {{ $price['change_24h'] >= 0 ? '+' : '' }}{{ number_format($price['change_24h'], 2) }}%
                            </p>
                            <p class="text-gray-400 text-xs">24h change</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-gray-400 text-sm">
                        No price data available
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Sentiment Distribution -->
            <div class="crypto-card p-4 sm:p-6">
                <h3 class="text-lg sm:text-xl font-bold text-white mb-3 sm:mb-4">📊 Distribution</h3>
                <div class="h-40 sm:h-48">
                    <canvas id="distributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Tweets Section with Tabs -->
    <div class="crypto-card p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6 space-y-3 sm:space-y-0">
            <h3 class="text-lg sm:text-xl font-bold text-white">🐦 Recent Tweets</h3>
            <div class="flex flex-col xs:flex-row xs:items-center space-y-2 xs:space-y-0 xs:space-x-4">
                <div class="text-sm text-gray-400 text-center xs:text-left">
                    Showing: <span class="text-white font-semibold">{{ $recentTweets->count() }}</span> tweets
                </div>
                <a href="{{ route('tweets.all') }}" class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold transition-all text-center">
                    View All Tweets →
                </a>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="flex space-x-1 bg-gray-800 p-1 rounded-xl mb-4 sm:mb-6 overflow-x-auto">
            <button onclick="showTab('all')" id="tab-all" class="tab-button active flex-shrink-0 py-2 sm:py-3 px-3 sm:px-4 rounded-lg text-center font-semibold transition-all text-xs sm:text-sm min-w-[80px] sm:min-w-[100px]">
                🌟 All
            </button>
            <button onclick="showTab('positive')" id="tab-positive" class="tab-button flex-shrink-0 py-2 sm:py-3 px-3 sm:px-4 rounded-lg text-center font-semibold transition-all text-xs sm:text-sm min-w-[80px] sm:min-w-[100px]">
                😊 Positive
            </button>
            <button onclick="showTab('negative')" id="tab-negative" class="tab-button flex-shrink-0 py-2 sm:py-3 px-3 sm:px-4 rounded-lg text-center font-semibold transition-all text-xs sm:text-sm min-w-[80px] sm:min-w-[100px]">
                😠 Negative
            </button>
            <button onclick="showTab('neutral')" id="tab-neutral" class="tab-button flex-shrink-0 py-2 sm:py-3 px-3 sm:px-4 rounded-lg text-center font-semibold transition-all text-xs sm:text-sm min-w-[80px] sm:min-w-[100px]">
                😐 Neutral
            </button>
        </div>

        <!-- Tabs Content -->
        <div class="space-y-3 sm:space-y-4 max-h-[400px] sm:max-h-[500px] overflow-y-auto pr-1 sm:pr-2">
            <!-- All Tweets -->
            <div id="content-all" class="tab-content active">
                @forelse($recentTweets as $tweet)
                    @include('partials.tweet-card', ['tweet' => $tweet])
                @empty
                    <div class="text-center py-6 sm:py-8 text-gray-400">
                        <div class="text-3xl sm:text-4xl mb-3 sm:mb-4">📭</div>
                        <p class="text-sm sm:text-base">No tweets available</p>
                        <p class="text-xs sm:text-sm mt-1 sm:mt-2">Click "Fetch Live" to get new data</p>
                    </div>
                @endforelse
            </div>

            <!-- Positive Tweets -->
            <div id="content-positive" class="tab-content">
                @php $positiveTweets = $recentTweets->where('sentiment_label', 'positive'); @endphp
                @forelse($positiveTweets as $tweet)
                    @include('partials.tweet-card', ['tweet' => $tweet])
                @empty
                    <div class="text-center py-6 sm:py-8 text-gray-400">
                        <div class="text-3xl sm:text-4xl mb-3 sm:mb-4">😊</div>
                        <p class="text-sm sm:text-base">No positive tweets found</p>
                    </div>
                @endforelse
            </div>

            <!-- Negative Tweets -->
            <div id="content-negative" class="tab-content">
                @php $negativeTweets = $recentTweets->where('sentiment_label', 'negative'); @endphp
                @forelse($negativeTweets as $tweet)
                    @include('partials.tweet-card', ['tweet' => $tweet])
                @empty
                    <div class="text-center py-6 sm:py-8 text-gray-400">
                        <div class="text-3xl sm:text-4xl mb-3 sm:mb-4">😠</div>
                        <p class="text-sm sm:text-base">No negative tweets found</p>
                    </div>
                @endforelse
            </div>

            <!-- Neutral Tweets -->
            <div id="content-neutral" class="tab-content">
                @php $neutralTweets = $recentTweets->where('sentiment_label', 'neutral'); @endphp
                @forelse($neutralTweets as $tweet)
                    @include('partials.tweet-card', ['tweet' => $tweet])
                @empty
                    <div class="text-center py-6 sm:py-8 text-gray-400">
                        <div class="text-3xl sm:text-4xl mb-3 sm:mb-4">😐</div>
                        <p class="text-sm sm:text-base">No neutral tweets found</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Global Loader Overlay -->
<div id="globalLoader" class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-gray-800 rounded-2xl p-6 sm:p-8 max-w-sm w-full mx-4 text-center">
        <div class="crypto-loader-large mx-auto mb-4"></div>
        <h3 class="text-white font-bold text-lg sm:text-xl mb-2">Loading Data</h3>
        <p class="text-gray-400 text-sm sm:text-base">Fetching latest cryptocurrency sentiment...</p>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Dashboard JavaScript loaded');
    
    // Initialize Charts
    @if(isset($chartData) && $sentimentHistory->count() > 0)
    initializeSentimentChart();
    @endif

    @if(isset($chartData) && $summary['total_tweets'] > 0)
    initializeDistributionChart();
    @endif

    function initializeSentimentChart() {
        const sentimentCtx = document.getElementById('sentimentChart');
        if (!sentimentCtx) {
            console.error('Sentiment chart canvas not found');
            return;
        }

        console.log('📈 Initializing sentiment chart with data:', {!! json_encode($chartData['sentiment']) !!});

        new Chart(sentimentCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['sentiment']['labels']) !!},
                datasets: [{
                    label: 'Sentiment Score',
                    data: {!! json_encode($chartData['sentiment']['scores']) !!},
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3B82F6',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            color: '#9CA3AF',
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.8)',
                        titleColor: '#9CA3AF',
                        bodyColor: '#FFFFFF',
                        borderColor: '#374151',
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(55, 65, 81, 0.3)',
                            borderColor: '#374151'
                        },
                        ticks: {
                            color: '#9CA3AF',
                            maxRotation: 45
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(55, 65, 81, 0.3)',
                            borderColor: '#374151'
                        },
                        ticks: {
                            color: '#9CA3AF'
                        },
                        min: -1,
                        max: 1
                    }
                }
            }
        });
    }

    function initializeDistributionChart() {
        const distributionCtx = document.getElementById('distributionChart');
        if (!distributionCtx) {
            console.error('Distribution chart canvas not found');
            return;
        }

        console.log('📊 Initializing distribution chart with data:', {!! json_encode($chartData['distribution']) !!});

        new Chart(distributionCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($chartData['distribution']['labels']) !!},
                datasets: [{
                    data: {!! json_encode($chartData['distribution']['data']) !!},
                    backgroundColor: {!! json_encode($chartData['distribution']['colors']) !!},
                    borderColor: '#1F2937',
                    borderWidth: 2,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#9CA3AF',
                            padding: 20,
                            font: {
                                size: 11
                            },
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.8)',
                        titleColor: '#9CA3AF',
                        bodyColor: '#FFFFFF',
                        borderColor: '#374151',
                        borderWidth: 1
                    }
                }
            }
        });
    }

    // Tab functionality
    function showTab(tabName) {
        console.log('Changing to tab:', tabName);
        
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Remove active class from all buttons
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active', 'text-white', 'bg-gradient-to-r', 'from-blue-500', 'to-purple-600');
            button.classList.add('text-gray-400', 'hover:text-white');
        });
        
        // Show selected tab content
        const activeContent = document.getElementById('content-' + tabName);
        const activeButton = document.getElementById('tab-' + tabName);
        
        if (activeContent) activeContent.classList.add('active');
        if (activeButton) {
            activeButton.classList.add('active', 'text-white', 'bg-gradient-to-r', 'from-blue-500', 'to-purple-600');
            activeButton.classList.remove('text-gray-400', 'hover:text-white');
        }
    }

    // Initialize first tab as active
    showTab('all');
    
    // Button loading states
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            console.log('Form submitted:', this.action);
            
            const button = this.querySelector('button[type="submit"]');
            if (button) {
                const btnText = button.querySelector('.btn-text');
                const btnLoader = button.querySelector('.btn-loader');
                
                if (btnText && btnLoader) {
                    console.log('Showing loading state for button');
                    btnText.classList.add('hidden');
                    btnLoader.classList.remove('hidden');
                    button.disabled = true;
                    
                    // Show global loader for better UX
                    const globalLoader = document.getElementById('globalLoader');
                    if (globalLoader) {
                        globalLoader.classList.remove('hidden');
                    }
                }
            }
        });
    });

    // Auto-update countdown
    let countdown = 100;
    const countdownElement = document.getElementById('countdown');
    const autoUpdateStatus = document.getElementById('autoUpdateStatus');
    
    function updateCountdown() {
        if (countdownElement && autoUpdateStatus) {
            countdown--;
            countdownElement.textContent = countdown + 's';
            
            if (countdown <= 0) {
                countdown = 60;
                // Show loading state
                const globalLoader = document.getElementById('globalLoader');
                if (globalLoader) {
                    globalLoader.classList.remove('hidden');
                }
                // Reload the page
                console.log('Auto-refreshing page...');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
        }
    }
    
    // Start countdown only if element exists
    if (countdownElement) {
        setInterval(updateCountdown, 1000);
    }
});

// Make showTab function globally available
window.showTab = function(tabName) {
    const activeContent = document.getElementById('content-' + tabName);
    const activeButton = document.getElementById('tab-' + tabName);
    
    if (activeContent) {
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });
        activeContent.classList.add('active');
    }
    
    if (activeButton) {
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active', 'text-white', 'bg-gradient-to-r', 'from-blue-500', 'to-purple-600');
            button.classList.add('text-gray-400', 'hover:text-white');
        });
        activeButton.classList.add('active', 'text-white', 'bg-gradient-to-r', 'from-blue-500', 'to-purple-600');
        activeButton.classList.remove('text-gray-400', 'hover:text-white');
    }
};
</script>
@endsection

<style>
    .crypto-card {
        background: linear-gradient(135deg, #252836 0%, #1E2533 100%);
        border-radius: 12px;
        border: 1px solid #2D3748;
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .tab-button.active {
        background: linear-gradient(135deg, #3B82F6 0%, #8B5CF6 100%);
        color: white;
    }

    /* Cryptocurrency-themed loader */
    .crypto-loader {
        width: 16px;
        height: 16px;
        border: 2px solid transparent;
        border-top: 2px solid #10B981;
        border-right: 2px solid #3B82F6;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        display: inline-block;
    }

    .crypto-loader-large {
        width: 48px;
        height: 48px;
        border: 3px solid transparent;
        border-top: 3px solid #10B981;
        border-right: 3px solid #3B82F6;
        border-bottom: 3px solid #F59E0B;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Custom scrollbar */
    .overflow-y-auto::-webkit-scrollbar {
        width: 4px;
    }

    .overflow-y-auto::-webkit-scrollbar-track {
        background: #2D3748;
        border-radius: 2px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #4A5568;
        border-radius: 2px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #718096;
    }

    /* Responsive breakpoints */
    @media (max-width: 475px) {
        .xs\:flex-row {
            flex-direction: row !important;
        }
        .xs\:space-y-0 {
            margin-top: 0 !important;
        }
        .xs\:space-x-2 > :not([hidden]) ~ :not([hidden]) {
            --tw-space-x-reverse: 0;
            margin-right: calc(0.5rem * var(--tw-space-x-reverse));
            margin-left: calc(0.5rem * calc(1 - var(--tw-space-x-reverse)));
        }
        .xs\:w-auto {
            width: auto !important;
        }
        .xs\:text-left {
            text-align: left !important;
        }
    }

    /* Smooth transitions */
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 300ms;
    }
</style>
@endsection