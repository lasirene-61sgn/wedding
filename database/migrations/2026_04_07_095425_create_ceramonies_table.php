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
        Schema::create('ceramonies', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_main')->default(false);
            $table->foreignId('host_id')->constrained('host')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('category_venues')->onDelete('cascade');
            $table->unsignedBigInteger('venue_id')->nullable();
            $table->foreign('venue_id')
                ->references('id')
                ->on('venue_names')
                ->onDelete('set null');
            $table->string('ceramony_name');
            $table->string('ceramony_date')->nullable();
            $table->string('ceramony_time')->nullable();
            $table->string('ceramony_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ceramonies');
    }
};
