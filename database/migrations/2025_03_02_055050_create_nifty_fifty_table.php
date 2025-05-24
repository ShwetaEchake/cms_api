<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nifty_fifty', function (Blueprint $table) {
            $table->id();
            $table->string('symbol');
            $table->float('open');
            $table->float('high');
            $table->float('low');
            $table->float('prev_close');
            $table->float('ltp');
            $table->float('indicative_close')->nullable();
            $table->float('chng')->nullable();
            $table->float('percent_chng')->nullable();
            $table->bigInteger('volume')->nullable();
            $table->float('value')->nullable();
            $table->float('fifty_two_wk_high')->nullable();
            $table->float('fifty_two_wk_low')->nullable();
            // $table->float('30_d_chng')->nullable();
            // $table->float('365_d_chng_29_feb_2024')->nullable();
            $table->float('sale')->nullable(); // New sale column
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nifty_fifty');
    }
};
