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
        Schema::table('invitations', function (Blueprint $table) {
            $table->string('bride_display_name')->nullable()->after('bride_email');
            $table->string('groom_display_name')->nullable()->after('groom_email');
            
            // Make father/mother names nullable
            $table->string('bride_father_name')->nullable()->change();
            $table->string('bride_mother_name')->nullable()->change();
            $table->string('groom_father_name')->nullable()->change();
            $table->string('groom_mother_name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->dropColumn(['bride_display_name', 'groom_display_name']);
            
            $table->string('bride_father_name')->nullable(false)->change();
            $table->string('bride_mother_name')->nullable(false)->change();
            $table->string('groom_father_name')->nullable(false)->change();
            $table->string('groom_mother_name')->nullable(false)->change();
        });
    }
};
