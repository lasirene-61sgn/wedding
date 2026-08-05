<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('channel_addons', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "50 WhatsApp Credits"
            $table->enum('type', ['whatsapp', 'sms', 'email']);
            $table->unsignedInteger('count'); // credits to add
            $table->unsignedInteger('price'); // price in INR (integer)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('channel_addons');
    }
};
