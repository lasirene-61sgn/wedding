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
        Schema::table('guest_lists', function (Blueprint $table) {
            $table->index('host_id'); // Add simple index to support the foreign key
            $table->dropUnique('guest_lists_host_id_guest_number_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guest_lists', function (Blueprint $table) {
            $table->unique(['host_id', 'guest_number']);
            $table->dropIndex(['host_id']);
        });
    }
};
