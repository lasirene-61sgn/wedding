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
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('host_id')->constrained('host')->onDelete('cascade');
            $table->foreignId('venue_id')->nullable()->constrained('venue_names')->onDelete('cascade');
            $table->string('selected_background_id')->nullable()->constrained('ceramony_backgrounds')->nullOnDelete();
            $table->enum('invite',['brideparents', 'groomparents', 'bride', 'groom', 'weddingcouple'])->nullable();
            $table->string('bride_name');
            $table->string('bride_number');
            $table->string('bride_email')->nullable();
            $table->string('bride_father_name');
            $table->string('bride_mother_name');
            $table->string('groom_name');
            $table->string('groom_number');
            $table->string('groom_email')->nullable();
            $table->string('groom_father_name');
            $table->string('groom_mother_name');
            $table->date('wedding_date')->nullable();
            $table->time('wedding_time')->nullable();
            // $table->string('wedding_location')->nullable();
            // $table->string('pincode', 10)->nullable();
            // $table->string('area_name')->nullable();
            // $table->string('district')->nullable();
            // $table->string('state')->nullable();
            // $table->string('circle')->nullable();
            // $table->string('country')->nullable();
            $table->string('wedding_image')->nullable();
            $table->string('theme')->default('classic')->nullable();
            $table->boolean('is_main')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
