<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sentiment_summaries', function (Blueprint $table) {
            $table->id();
            $table->integer('total_tweets')->default(0);
            $table->integer('positive_count')->default(0);
            $table->integer('negative_count')->default(0);
            $table->integer('neutral_count')->default(0);
            $table->float('average_sentiment')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sentiment_summaries');
    }
};