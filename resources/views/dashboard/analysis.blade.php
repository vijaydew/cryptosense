@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-4 sm:py-6">
    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 lg:mb-8">
        <div class="mb-4 lg:mb-0">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold bg-gradient-to-r from-green-400 to-blue-600 bg-clip-text text-transparent">
                📈 Advanced Analysis
            </h1>
            <p class="text-gray-400 mt-1 sm:mt-2 text-sm sm:text-base">Deep insights and historical sentiment analysis</p>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                Data Range: Last 30 days | 
                <span class="text-green-400">{{ $sentimentHistory->count() }} data points</span>
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <!-- Timeframe Selector -->
            <div class="flex space-x-1 bg-gray-800 p-1 rounded-xl">
                <button class="timeframe-btn px-3 py-2 rounded-lg text-xs font-semibold bg-gradient-to-r from-green-500 to-blue-600 text-white">24H</button>
                <button class="timeframe-btn px-3 py-2 rounded-lg text-xs font-semibold text-gray-400 hover:text-white">7D</button>
                <button class="timeframe-btn px-3 py-2 rounded-lg text-xs font-semibold text-gray-400 hover:text-white">30D</button>
                <button class="timeframe-btn px-3 py-2 rounded-lg text-xs font-semibold text-gray-400 hover:text-white">ALL</button>
            </div>
        </div>
    </div>

    <!-- Statistical Overview Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6 mb-6 lg:mb-8">
        <!-- Correlation Coefficient -->
        <div class="crypto-card p-4 sm:p-6 relative overflow-hidden group transform hover:scale-105 transition-all duration-300">
            <div class="absolute top-0 right-0 w-16 h-16 sm:w-20 sm:h-20 bg-blue-500/10 rounded-full -mr-4 -mt-4 sm:-mr-6 sm:-mt-6"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs sm:text-sm font-medium">Correlation</p>
                    <p class="text-2xl sm:text-3xl font-bold text-white mt-1 sm:mt-2">0.78</p>
                    <p class="text-blue-400 text-xs sm:text-sm mt-1 sm:mt-2 font-semibold">
                        Strong Positive
                    </p>
                </div>
                <div class="text-2xl sm:text-4xl bg-blue-500/20 p-2 sm:p-3 rounded-xl">📊</div>
            </div>
        </div>

        <!-- Predictive Accuracy -->
        <div class="crypto-card p-4 sm:p-6 relative overflow-hidden group transform hover:scale-105 transition-all duration-300">
            <div class="absolute top-0 right-0 w-16 h-16 sm:w-20 sm:h-20 bg-green-500/10 rounded-full -mr-4 -mt-4 sm:-mr-6 sm:-mt-6"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs sm:text-sm font-medium">Accuracy</p>
                    <p class="text-2xl sm:text-3xl font-bold text-white mt-1 sm:mt-2">72%</p>
                    <p class="text-green-400 text-xs sm:text-sm mt-1 sm:mt-2 font-semibold">
                        High Confidence
                    </p>
                </div>
                <div class="text-2xl sm:text-4xl bg-green-500/20 p-2 sm:p-3 rounded-xl">🎯</div>
            </div>
        </div>

        <!-- Volatility Index -->
        <div class="crypto-card p-4 sm:p-6 relative overflow-hidden group transform hover:scale-105 transition-all duration-300">
            <div class="absolute top-0 right-0 w-16 h-16 sm:w-20 sm:h-20 bg-orange-500/10 rounded-full -mr-4 -mt-4 sm:-mr-6 sm:-mt-6"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs sm:text-sm font-medium">Volatility</p>
                    <p class="text-2xl sm:text-3xl font-bold text-white mt-1 sm:mt-2">0.34</p>
                    <p class="text-orange-400 text-xs sm:text-sm mt-1 sm:mt-2 font-semibold">
                        Moderate Risk
                    </p>
                </div>
                <div class="text-2xl sm:text-4xl bg-orange-500/20 p-2 sm:p-3 rounded-xl">🌊</div>
            </div>
        </div>

        <!-- Trend Direction -->
        <div class="crypto-card p-4 sm:p-6 relative overflow-hidden group transform hover:scale-105 transition-all duration-300">
            <div class="absolute top-0 right-0 w-16 h-16 sm:w-20 sm:h-20 bg-purple-500/10 rounded-full -mr-4 -mt-4 sm:-mr-6 sm:-mt-6"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs sm:text-sm font-medium">Trend</p>
                    <p class="text-2xl sm:text-3xl font-bold text-white mt-1 sm:mt-2">↗️ Up</p>
                    <p class="text-purple-400 text-xs sm:text-sm mt-1 sm:mt-2 font-semibold">
                        Bullish Momentum
                    </p>
                </div>
                <div class="text-2xl sm:text-4xl bg-purple-500/20 p-2 sm:p-3 rounded-xl">📈</div>
            </div>
        </div>
    </div>

    <!-- Main Analysis Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Correlation Heatmap -->
        <div class="crypto-card p-4 sm:p-6 lg:col-span-2">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6">
                <h3 class="text-lg sm:text-xl font-bold text-white mb-2 sm:mb-0">🔥 Sentiment-Price Correlation</h3>
                <div class="flex items-center space-x-2">
                    <span class="px-2 sm:px-3 py-1 bg-red-500/20 text-red-400 rounded-lg text-xs sm:text-sm">Heatmap</span>
                    <span class="text-gray-400 text-xs sm:text-sm">Pearson Correlation</span>
                </div>
            </div>
            <div class="h-80 sm:h-96">
                <canvas id="correlationChart"></canvas>
            </div>
        </div>

        <!-- Crypto Comparison -->
        <div class="crypto-card p-4 sm:p-6">
            <h3 class="text-lg sm:text-xl font-bold text-white mb-4 sm:mb-6">🔄 Crypto Sentiment Comparison</h3>
            <div class="h-64 sm:h-72 lg:h-80">
                <canvas id="comparisonChart"></canvas>
            </div>
        </div>

        <!-- Trend Analysis -->
        <div class="crypto-card p-4 sm:p-6">
            <h3 class="text-lg sm:text-xl font-bold text-white mb-4 sm:mb-6">📊 Trend Analysis</h3>
            <div class="h-64 sm:h-72 lg:h-80">
                <canvas id="trendAnalysisChart"></canvas>
            </div>
        </div>

        <!-- Volume vs Sentiment -->
        <div class="crypto-card p-4 sm:p-6">
            <h3 class="text-lg sm:text-xl font-bold text-white mb-4 sm:mb-6">📈 Volume vs Sentiment</h3>
            <div class="h-64">
                <canvas id="volumeSentimentChart"></canvas>
            </div>
        </div>

        <!-- Predictive Forecast -->
        <div class="crypto-card p-4 sm:p-6">
            <h3 class="text-lg sm:text-xl font-bold text-white mb-4 sm:mb-6">🔮 Sentiment Forecast</h3>
            <div class="h-64">
                <canvas id="forecastChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Statistical Breakdown -->
    <div class="crypto-card p-4 sm:p-6 mb-8">
        <h3 class="text-lg sm:text-xl font-bold text-white mb-4 sm:mb-6">📋 Statistical Summary</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <div class="text-center">
                <p class="text-gray-400 text-sm">Mean Sentiment</p>
                <p class="text-white font-bold text-xl">{{ number_format($summary->average_sentiment ?? 0, 4) }}</p>
            </div>
            <div class="text-center">
                <p class="text-gray-400 text-sm">Standard Deviation</p>
                <p class="text-white font-bold text-xl">0.1245</p>
            </div>
            <div class="text-center">
                <p class="text-gray-400 text-sm">Peak Sentiment</p>
                <p class="text-white font-bold text-xl">0.8567</p>
            </div>
            <div class="text-center">
                <p class="text-gray-400 text-sm">Confidence Interval</p>
                <p class="text-white font-bold text-xl">95%</p>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('📈 Analysis page loaded');
    
    // Initialize Analysis Charts
    initializeCorrelationChart();
    initializeComparisonChart();
    initializeTrendAnalysisChart();
    initializeVolumeSentimentChart();
    initializeForecastChart();

    function initializeCorrelationChart() {
        const ctx = document.getElementById('correlationChart');
        if (!ctx) return;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['BTC', 'ETH', 'SOL', 'ADA', 'DOGE', 'XRP'],
                datasets: [{
                    label: 'Correlation Coefficient',
                    data: [0.78, 0.65, 0.72, 0.58, 0.81, 0.63],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(139, 92, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(99, 102, 241, 0.8)'
                    ],
                    borderColor: [
                        '#3B82F6',
                        '#8B5CF6',
                        '#10B981',
                        '#F59E0B',
                        '#EF4444',
                        '#6366F1'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.9)',
                        titleColor: '#9CA3AF',
                        bodyColor: '#FFFFFF'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 1,
                        grid: {
                            color: 'rgba(55, 65, 81, 0.3)'
                        },
                        ticks: {
                            color: '#9CA3AF'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#9CA3AF',
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        });
    }

    function initializeComparisonChart() {
        const ctx = document.getElementById('comparisonChart');
        if (!ctx) return;

        new Chart(ctx, {
            type: 'radar',
            data: {
                labels: ['Sentiment', 'Volume', 'Volatility', 'Momentum', 'Accuracy', 'Impact'],
                datasets: [
                    {
                        label: 'Bitcoin',
                        data: [85, 92, 45, 78, 82, 88],
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        borderColor: '#3B82F6',
                        borderWidth: 2,
                        pointBackgroundColor: '#3B82F6'
                    },
                    {
                        label: 'Ethereum',
                        data: [78, 85, 52, 72, 76, 80],
                        backgroundColor: 'rgba(139, 92, 246, 0.2)',
                        borderColor: '#8B5CF6',
                        borderWidth: 2,
                        pointBackgroundColor: '#8B5CF6'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        angleLines: {
                            color: 'rgba(55, 65, 81, 0.3)'
                        },
                        grid: {
                            color: 'rgba(55, 65, 81, 0.3)'
                        },
                        pointLabels: {
                            color: '#9CA3AF'
                        },
                        ticks: {
                            color: '#9CA3AF',
                            backdropColor: 'transparent'
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

    function initializeTrendAnalysisChart() {
        const ctx = document.getElementById('trendAnalysisChart');
        if (!ctx) return;

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: 'Price Trend',
                        data: [65, 59, 80, 81, 56, 55, 70, 75, 82, 78, 85, 90],
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Sentiment Trend',
                        data: [55, 65, 75, 70, 60, 65, 72, 68, 78, 75, 80, 85],
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
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
                plugins: {
                    legend: {
                        labels: {
                            color: '#9CA3AF'
                        }
                    }
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

    function initializeVolumeSentimentChart() {
        const ctx = document.getElementById('volumeSentimentChart');
        if (!ctx) return;

        new Chart(ctx, {
            type: 'scatter',
            data: {
                datasets: [{
                    label: 'BTC',
                    data: [
                        {x: 0.8, y: 85}, {x: 0.6, y: 72}, {x: -0.2, y: 45},
                        {x: 0.9, y: 92}, {x: -0.5, y: 28}, {x: 0.3, y: 65}
                    ],
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: '#3B82F6',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Sentiment: ${context.parsed.x}, Volume: ${context.parsed.y}%`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Sentiment Score',
                            color: '#9CA3AF'
                        },
                        min: -1,
                        max: 1,
                        grid: {
                            color: 'rgba(55, 65, 81, 0.3)'
                        },
                        ticks: {
                            color: '#9CA3AF'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Volume (%)',
                            color: '#9CA3AF'
                        },
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

    function initializeForecastChart() {
        const ctx = document.getElementById('forecastChart');
        if (!ctx) return;

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['-3d', '-2d', '-1d', 'Now', '+1d', '+2d', '+3d'],
                datasets: [{
                    label: 'Historical',
                    data: [0.4, 0.5, 0.6, 0.7, null, null, null],
                    borderColor: '#6B7280',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    pointBackgroundColor: '#6B7280'
                }, {
                    label: 'Forecast',
                    data: [null, null, null, 0.7, 0.75, 0.8, 0.78],
                    borderColor: '#F59E0B',
                    borderWidth: 3,
                    pointBackgroundColor: '#F59E0B',
                    fill: {
                        target: 'origin',
                        above: 'rgba(245, 158, 11, 0.1)'
                    }
                }, {
                    label: 'Confidence Interval',
                    data: [null, null, null, 0.7, 0.72, 0.77, 0.74],
                    borderColor: 'transparent',
                    backgroundColor: 'rgba(245, 158, 11, 0.05)',
                    fill: '-1'
                }, {
                    label: 'Confidence Interval',
                    data: [null, null, null, 0.7, 0.78, 0.83, 0.82],
                    borderColor: 'transparent',
                    backgroundColor: 'rgba(245, 158, 11, 0.05)',
                    fill: '-2'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: '#9CA3AF'
                        }
                    }
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

    // Timeframe selector
    document.querySelectorAll('.timeframe-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.timeframe-btn').forEach(b => {
                b.classList.remove('bg-gradient-to-r', 'from-green-500', 'to-blue-600', 'text-white');
                b.classList.add('text-gray-400');
            });
            this.classList.add('bg-gradient-to-r', 'from-green-500', 'to-blue-600', 'text-white');
            this.classList.remove('text-gray-400');
        });
    });
});
</script>
@endsection