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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('phone',20);
            $table->double('value', 15, 8);
            $table->string('currency',10);
            $table->string('national_id',50);
            $table->enum('registeration_method', ['reception', 'online']);
            $table->enum('payment_method', ['cash', 'online']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
