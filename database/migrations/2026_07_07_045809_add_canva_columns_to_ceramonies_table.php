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
        Schema::table('ceramonies', function (Blueprint $table) {
            $table->string('canva_template_id')->nullable();
            $table->string('canva_design_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ceramonies', function (Blueprint $table) {
            $table->dropColumn(['canva_template_id', 'canva_design_url']);
        });
    }
};
