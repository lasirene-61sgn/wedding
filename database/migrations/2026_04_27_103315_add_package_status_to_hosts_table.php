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
        // Only add if it doesn't exist to avoid previous "Duplicate column" errors
        if (!Schema::hasColumn('host', 'package_status')) {
            $table->string('package_status')->default('pending')->after('package_id');
        }
    });
}

public function down(): void
{
    Schema::table('host', function (Blueprint $table) {
        $table->dropColumn('package_status');
    });
}
};
