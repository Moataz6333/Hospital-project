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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('patient_id')->unsigned();
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->bigInteger('appointment_id')->unsigned();
            $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
            $table->string('InvoiceId', 50);
            $table->string('InvoiceReference', 50);
            $table->double('InvoiceValue', 15, 8);
            $table->string('Currency', 5);
            $table->string('CustomerName', 70);
            $table->string('CustomerMobile', 20);
            $table->string('PaymentGateway', 30);
            $table->string('PaymentId', 100);
            $table->string('CardNumber', 100);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
