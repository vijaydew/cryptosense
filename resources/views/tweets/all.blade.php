@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8">
        <div class="mb-4 lg:mb-0">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-400 to-purple-600 bg-clip-text text-transparent">
                🐦 All Analyzed Tweets
            </h1>
            <p class="text-gray-400 mt-2">Complete collection of sentiment-analyzed cryptocurrency tweets</p>
        </div>
        <div class="flex space-x-4">
            <div class="text-right">
                <p class="text-gray-400 text-sm">Total Tweets</p>
                <p class="text-white font-semibold text-xl">{{ $tweets->total() }}</p>
            </div>
            <a href="{{ route('dashboard') }}" class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300">
                ← Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="crypto-card p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
            <div class="flex space-x-4">
                <select id="sentimentFilter" onchange="filterTweets()" class="bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                    <option value="all">All Sentiments</option>
                    <option value="positive">😊 Positive</option>
                    <option value="negative">😠 Negative</option>
                    <option value="neutral">😐 Neutral</option>
                </select>
                <select id="cryptoFilter" onchange="filterTweets()" class="bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                    <option value="all">All Cryptocurrencies</option>
                    <option value="bitcoin">Bitcoin</option>
                    <option value="ethereum">Ethereum</option>
                    <option value="general">General Crypto</option>
                </select>
            </div>
            <div class="text-gray-400 text-sm">
                Showing {{ $tweets->firstItem() }} - {{ $tweets->lastItem() }} of {{ $tweets->total() }} tweets
            </div>
        </div>
    </div>

    <!-- Tweets Grid -->
    <div class="space-y-4">
        @forelse($tweets as $tweet)
            @include('partials.tweet-card', ['tweet' => $tweet])
        @empty
            <div class="crypto-card p-12 text-center">
                <div class="text-6xl mb-4">📭</div>
                <h3 class="text-2xl font-bold text-white mb-2">No Tweets Found</h3>
                <p class="text-gray-400 mb-6">No tweets have been analyzed yet.</p>
                <a href="{{ route('dashboard') }}" class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-semibold transition-all">
                    Go to Dashboard to Fetch Data
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($tweets->hasPages())
    <div class="mt-8">
        {{ $tweets->links() }}
    </div>
    @endif
</div>

@section('scripts')
<script>
    function filterTweets() {
        const sentiment = document.getElementById('sentimentFilter').value;
        const crypto = document.getElementById('cryptoFilter').value;
        
        let url = new URL(window.location.href);
        let params = new URLSearchParams(url.search);
        
        if (sentiment !== 'all') {
            params.set('sentiment', sentiment);
        } else {
            params.delete('sentiment');
        }
        
        if (crypto !== 'all') {
            params.set('crypto', crypto);
        } else {
            params.delete('crypto');
        }
        
        window.location.href = url.pathname + '?' + params.toString();
    }

    // Set current filter values from URL
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const sentiment = urlParams.get('sentiment');
        const crypto = urlParams.get('crypto');
        
        if (sentiment) {
            document.getElementById('sentimentFilter').value = sentiment;
        }
        if (crypto) {
            document.getElementById('cryptoFilter').value = crypto;
        }
    });
</script>

<style>
    .pagination {
        display: flex;
        justify-content: center;
        list-style: none;
        padding: 0;
    }
    
    .pagination li {
        margin: 0 2px;
    }
    
    .pagination a, .pagination span {
        display: block;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
    }
    
    .pagination a {
        background: #374151;
        color: white;
        transition: background 0.3s;
    }
    
    .pagination a:hover {
        background: #4B5563;
    }
    
    .pagination .active span {
        background: linear-gradient(135deg, #3B82F6 0%, #8B5CF6 100%);
        color: white;
    }
</style>
@endsection
@endsection