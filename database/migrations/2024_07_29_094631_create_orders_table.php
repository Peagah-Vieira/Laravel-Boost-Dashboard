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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('rush_id');
            $table->text('description');
            $table->integer('progress');
            $table->float('rush_value');
            $table->foreignId('booster1_id')->references('id')->on('boosters')->onDelete('cascade');
            $table->foreignId('booster2_id')->references('id')->on('boosters')->onDelete('cascade');
            $table->foreignId('booster3_id')->references('id')->on('boosters')->onDelete('cascade');
            $table->foreignId('booster4_id')->references('id')->on('boosters')->onDelete('cascade');
            $table->boolean('paid')->default(0);
            $table->date('payment_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
