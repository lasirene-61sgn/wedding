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
        Schema::table('host', function (Blueprint $table) {
            $table->string('alternate_number')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('complex_name')->nullable();
            $table->string('floor')->nullable();
            $table->string('door_no')->nullable();
            $table->string('street_name')->nullable();
            $table->string('area')->nullable();
            $table->string('district')->nullable();
            $table->string('pincode')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('location_map')->nullable();
            $table->json('permissions')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('host', function (Blueprint $table) {
            //
        });
    }
};
