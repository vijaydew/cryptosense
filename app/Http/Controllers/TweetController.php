<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SentimentData;

class TweetController extends Controller
{
    public function index(Request $request)
    {
        $query = SentimentData::query()->with('cryptoPrice')->latest();
        
        // Filter by sentiment
        if ($request->has('sentiment') && $request->sentiment !== 'all') {
            $query->where('sentiment_label', $request->sentiment);
        }
        
        // Filter by cryptocurrency
        if ($request->has('crypto') && $request->crypto !== 'all') {
            $query->where('cryptocurrency', $request->crypto);
        }
        
        $tweets = $query->paginate(50);
        
        return view('tweets.all', compact('tweets'));
    }
}