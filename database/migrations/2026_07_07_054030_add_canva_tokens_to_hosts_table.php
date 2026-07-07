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
            $table->text('canva_access_token')->nullable();
            $table->text('canva_refresh_token')->nullable();
            $table->timestamp('canva_token_expires_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('host', function (Blueprint $table) {
            $table->dropColumn(['canva_access_token', 'canva_refresh_token', 'canva_token_expires_at']);
        });
    }
};
