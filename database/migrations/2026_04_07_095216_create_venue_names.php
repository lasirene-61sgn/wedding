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
        Schema::create('venue_names', function (Blueprint $table) {
            $table->id();
            $table->foreignId('host_id')->constrained('host')->onDelete('cascade');
            $table->string('venue_name');
            $table->string('pincode', 10);
            $table->string('area_name');
            $table->string('district');
            $table->string('state');
            $table->string('circle');
            $table->string('country');
            $table->string('venue_address');
            $table->string('wedding_location')->nullable();
            $table->string('location_map')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venue_names');
    }
};