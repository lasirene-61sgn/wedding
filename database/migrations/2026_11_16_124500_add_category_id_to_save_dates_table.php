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
            if (!Schema::hasColumn('save_dates', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable()->after('invitation_id');
                $table->foreign('category_id')->references('id')->on('category_venues')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('save_dates', function (Blueprint $table) {
            if (Schema::hasColumn('save_dates', 'category_id')) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            }
        });
    }
};
