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
        Schema::create('time_tables', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('doctor_id');
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
            $table->time('fri_start')->nullable();
            $table->time('fri_end')->nullable();
            $table->time('sat_start')->nullable();
            $table->time('sat_end')->nullable();
            $table->time('sun_start')->nullable();
            $table->time('sun_end')->nullable();
            $table->time('mon_start')->nullable();
            $table->time('mon_end')->nullable();
            $table->time('tue_start')->nullable();
            $table->time('tue_end')->nullable();
            $table->time('wed_start')->nullable();
            $table->time('wed_end')->nullable();
            $table->time('thurs_start')->nullable();
            $table->time('thurs_end')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_tables');
    }
};
