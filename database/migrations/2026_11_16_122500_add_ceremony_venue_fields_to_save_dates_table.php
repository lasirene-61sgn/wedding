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
        Schema::table('save_dates', function (Blueprint $table) {
            if (!Schema::hasColumn('save_dates', 'ceremony_id')) {
                $table->foreignId('ceremony_id')->nullable()->after('invitation_id');
            }
            if (!Schema::hasColumn('save_dates', 'venue_id')) {
                $table->foreignId('venue_id')->nullable()->after('ceremony_id');
            }
            if (!Schema::hasColumn('save_dates', 'name_order')) {
                $table->string('name_order')->default('bride_groom')->nullable()->after('venue_id');
            }
            if (!Schema::hasColumn('save_dates', 'use_first_name')) {
                $table->boolean('use_first_name')->default(true)->after('name_order');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('save_dates', function (Blueprint $table) {
            if (Schema::hasColumn('save_dates', 'use_first_name')) {
                $table->dropColumn('use_first_name');
            }
            if (Schema::hasColumn('save_dates', 'name_order')) {
                $table->dropColumn('name_order');
            }
            if (Schema::hasColumn('save_dates', 'venue_id')) {
                $table->dropColumn('venue_id');
            }
            if (Schema::hasColumn('save_dates', 'ceremony_id')) {
                $table->dropColumn('ceremony_id');
            }
        });
    }
};
