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
        Schema::table('host_family_details', function (Blueprint $table) {
            $table->string('text_color', 10)->nullable()->default('#b02663');
            $table->string('details_color', 10)->nullable()->default('#2b4c5e');
            $table->json('text_positions')->nullable();
            $table->json('custom_canvas_texts')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('host_family_details', function (Blueprint $table) {
            $table->dropColumn(['text_color', 'details_color', 'text_positions', 'custom_canvas_texts']);
        });
    }
};
