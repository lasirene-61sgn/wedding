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
        if (Schema::hasTable('save_dates') && !Schema::hasColumn('save_dates', 'template')) {
            Schema::table('save_dates', function (Blueprint $table) {
                $table->string('template')->default('savethedate/invitation.html')->nullable()->after('message');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('save_dates') && Schema::hasColumn('save_dates', 'template')) {
            Schema::table('save_dates', function (Blueprint $table) {
                $table->dropColumn('template');
            });
        }
    }
};
