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
            $table->string('setup_role', 50)->nullable()->comment('Bride, Groom, Other');
            $table->string('creator_relationship', 100)->nullable()->comment('Father, Mother, Uncle, Custom, etc.');
            $table->unsignedBigInteger('wedding_category_id')->nullable();
            $table->string('custom_wedding_category', 100)->nullable();
            $table->boolean('is_engagement_completed')->nullable();
            $table->boolean('is_date_finalized')->default(0);
            $table->boolean('is_venue_finalized')->default(0);
            $table->string('venue_name', 255)->nullable();
            $table->string('current_city', 100)->nullable();
            $table->string('wedding_city', 100)->nullable();
            $table->string('wedding_state', 100)->nullable();
            
            $table->foreign('wedding_category_id')->references('id')->on('category_venues')->onDelete('set null');
        });

        Schema::table('host', function (Blueprint $table) {
            $table->enum('quick_setup_status', ['pending', 'completed'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->dropForeign(['wedding_category_id']);
            $table->dropColumn([
                'setup_role', 'creator_relationship', 'wedding_category_id', 'custom_wedding_category',
                'is_engagement_completed', 'is_date_finalized', 'is_venue_finalized', 'venue_name',
                'current_city', 'wedding_city', 'wedding_state'
            ]);
        });

        Schema::table('host', function (Blueprint $table) {
            $table->dropColumn('quick_setup_status');
        });
    }
};
