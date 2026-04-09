<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sentiment_data', function (Blueprint $table) {
            $table->id();
            $table->string('tweet_id')->nullable();
            $table->text('content');
            $table->float('sentiment_score');
            $table->string('sentiment_label'); // positive, negative, neutral
            $table->string('cryptocurrency');
            $table->string('author')->nullable();
            $table->integer('likes')->default(0);
            $table->integer('retweets')->default(0);
            $table->string('platform')->default('twitter');
            
            $table->timestamps();
            
            $table->index(['cryptocurrency', 'sentiment_label']);
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sentiment_data');
    }
};