<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('crypto_prices', function (Blueprint $table) {
            $table->id();
            $table->string('cryptocurrency');
            $table->float('price');
            $table->float('change_24h')->default(0);
            $table->timestamps();

            $table->index(['cryptocurrency', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('crypto_prices');
    }
};