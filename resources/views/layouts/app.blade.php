<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crypto Sentiment Dashboard</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom Crypto Theme -->
    <style>
        :root {
            --crypto-blue: #1E53E5;
            --bitcoin-orange: #F7931A;
            --ethereum-purple: #8A2BE2;
            --dark-bg: #1A1D29;
            --card-bg: #252836;
        }
        
        body {
            background-color: var(--dark-bg);
            color: white;
            font-family: 'Inter', sans-serif;
        }
        
        .crypto-card {
            background: linear-gradient(135deg, #252836 0%, #1E2533 100%);
            border-radius: 16px;
            border: 1px solid #2D3748;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        
        .nav-active {
            background: linear-gradient(135deg, #3B82F6 0%, #8B5CF6 100%);
            color: white;
        }
    </style>
</head>
<body class="bg-gray-900 min-h-screen">
    <!-- Enhanced Navigation -->
    <nav class="bg-gray-800 border-b border-gray-700 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-white bg-gradient-to-r from-blue-400 to-purple-600 bg-clip-text text-transparent">
                        🚀 Crypto Sentiment
                    </h1>
                </div>
                <div class="flex space-x-1 bg-gray-900 p-1 rounded-xl">
                    <a href="{{ route('dashboard') }}" class="nav-item px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ request()->routeIs('dashboard') ? 'nav-active' : 'text-gray-400 hover:text-white' }}">
                        📊 Dashboard
                    </a>
                    <a href="{{ route('tweets.all') }}" class="nav-item px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ request()->routeIs('tweets.all') ? 'nav-active' : 'text-gray-400 hover:text-white' }}">
                        🐦 All Tweets
                    </a>
                    <a href="{{ route('analysis') }}" class="nav-item px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ request()->routeIs('analysis') ? 'nav-active' : 'text-gray-400 hover:text-white' }}">
                        📈 Analysis
                    </a>
                    <a href="{{ route('live') }}" class="nav-item px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ request()->routeIs('live') ? 'nav-active' : 'text-gray-400 hover:text-white' }}">
                        ⚡ Live Monitor
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 border-t border-gray-700 mt-12">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex justify-between items-center">
                <div class="text-gray-400 text-sm">
                    Crypto Sentiment Analyzer &copy; 2024
                </div>
                <div class="text-gray-400 text-sm">
                    Real-time social media analysis
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.0/axios.min.js"></script>
    @yield('scripts')
</body>
</html>