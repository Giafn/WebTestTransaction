<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up() {
        Schema::create('transaction_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('payment_method_id');
            $table->foreign('transaction_id')->references('id')->on('transactions');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('transaction_payment_methods');
    }
};
