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
            $table->string('reminder_image')->nullable();
            $table->boolean('reminders_active')->default(false);
            $table->integer('reminders_sent_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('host', function (Blueprint $table) {
            $table->dropColumn(['reminder_image', 'reminders_active', 'reminders_sent_count']);
        });
    }
};
