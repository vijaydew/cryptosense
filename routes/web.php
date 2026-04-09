<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TweetController;



Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/collect-data', [DashboardController::class, 'collectData'])->name('collect.data');
Route::post('/fetch-live-data', [DashboardController::class, 'fetchLiveData'])->name('fetch.live.data');
Route::get('/analysis', [DashboardController::class, 'analysis'])->name('analysis');
Route::get('/live', [DashboardController::class, 'live'])->name('live');
Route::get('/tweets', [TweetController::class, 'index'])->name('tweets.all');
