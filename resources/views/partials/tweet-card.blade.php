@props(['tweet'])

@php
    // Handle both array and object formats
    if (is_object($tweet)) {
        $tweetData = [
            'id' => $tweet->id,
            'author' => $tweet->author,
            'created_at' => $tweet->created_at,
            'sentiment_label' => $tweet->sentiment_label,
            'sentiment_score' => $tweet->sentiment_score,
            'cryptocurrency' => $tweet->cryptocurrency,
            'content' => $tweet->content,
            'likes' => $tweet->likes,
            'retweets' => $tweet->retweets
        ];
    } else {
        $tweetData = $tweet;
    }
@endphp

<div class="p-3 sm:p-4 rounded-xl border-l-4 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg
    {{ $tweetData['sentiment_label'] == 'positive' ? 'sentiment-positive bg-green-900/10 border-green-500' : '' }}
    {{ $tweetData['sentiment_label'] == 'negative' ? 'sentiment-negative bg-red-900/10 border-red-500' : '' }}
    {{ $tweetData['sentiment_label'] == 'neutral' ? 'sentiment-neutral bg-yellow-900/10 border-yellow-500' : '' }}"
    data-sentiment="{{ $tweetData['sentiment_label'] }}"
    data-crypto="{{ $tweetData['cryptocurrency'] }}">
    
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-3">
        <div class="flex items-center mb-2 sm:mb-0">
            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3 shadow-lg flex-shrink-0">
                <span class="text-white text-xs sm:text-sm">🐦</span>
            </div>
            <div class="min-w-0 flex-1">
                <!-- FIXED: Remove the @ symbol and Blade syntax issues -->
                <p class="text-white font-bold text-sm sm:text-base truncate">{{ $tweetData['author'] }}</p>
                <p class="text-gray-400 text-xs sm:text-sm">{{ \Carbon\Carbon::parse($tweetData['created_at'])->diffForHumans() }}</p>
            </div>
        </div>
        <div class="flex flex-wrap gap-1 sm:gap-2 mt-2 sm:mt-0">
            <span class="px-2 py-1 rounded-full text-xs font-bold flex-shrink-0
                {{ $tweetData['sentiment_label'] == 'positive' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : '' }}
                {{ $tweetData['sentiment_label'] == 'negative' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : '' }}
                {{ $tweetData['sentiment_label'] == 'neutral' ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30' : '' }}">
                {{ ucfirst($tweetData['sentiment_label']) }} ({{ number_format($tweetData['sentiment_score'], 3) }})
            </span>
            <span class="px-2 py-1 bg-blue-500/20 text-blue-400 border border-blue-500/30 rounded-full text-xs font-bold capitalize flex-shrink-0">
                {{ $tweetData['cryptocurrency'] }}
            </span>
        </div>
    </div>
    
    <p class="text-gray-200 leading-relaxed mb-3 text-sm sm:text-base break-words">{{ $tweetData['content'] }}</p>
    
    <div class="flex justify-between items-center">
        <div class="flex space-x-3 sm:space-x-4 text-gray-400 text-xs sm:text-sm">
            <span class="flex items-center">
                <span class="mr-1">👍</span> {{ $tweetData['likes'] }}
            </span>
            <span class="flex items-center">
                <span class="mr-1">🔁</span> {{ $tweetData['retweets'] }}
            </span>
        </div>
        <div class="text-xs text-gray-500 flex-shrink-0">
            {{ \Carbon\Carbon::parse($tweetData['created_at'])->format('M j, H:i') }}
        </div>
    </div>
</div>