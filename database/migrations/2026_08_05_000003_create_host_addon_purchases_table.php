<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('host_addon_purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('host_id');
            $table->unsignedBigInteger('addon_id');
            $table->string('razorpay_order_id')->nullable();
            $table->string('razorpay_payment_id')->nullable();
            $table->unsignedInteger('amount_paid'); // in INR
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->timestamps();

            $table->foreign('host_id')->references('id')->on('host')->onDelete('cascade');
            $table->foreign('addon_id')->references('id')->on('channel_addons')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('host_addon_purchases');
    }
};
