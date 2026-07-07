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
            $table->string('canva_public_link', 1000)->nullable()->after('canva_design_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ceramonies', function (Blueprint $table) {
            $table->dropColumn('canva_public_link');
        });
    }
};
