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
       if (!Schema::hasColumn('host', 'package_id')) {
        Schema::table('host', function (Blueprint $table) {
            $table->unsignedBigInteger('package_id')->nullable()->after('email');
            $table->string('package_status')->default('pending')->after('package_id');
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('host', function (Blueprint $table) {
            //
        });
    }
};
