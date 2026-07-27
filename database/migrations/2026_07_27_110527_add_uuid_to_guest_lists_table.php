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
            $table->uuid('uuid')->nullable()->after('id');
        });

        // Populate UUIDs for existing records
        $guests = \App\Models\GuestList::all();
        foreach ($guests as $guest) {
            $guest->uuid = (string) \Illuminate\Support\Str::uuid();
            $guest->save();
        }

        // Make the column non-nullable and unique after populating
        Schema::table('guest_lists', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guest_lists', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
