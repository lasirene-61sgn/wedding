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
        Schema::create('guest_family_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_list_id')->constrained('guest_lists')->onDelete('cascade');
            $table->string('name');
            $table->string('mobile')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('email')->nullable();
            $table->string('relation')->nullable();
            $table->string('age')->nullable();
            $table->enum('gender', [ 'male', 'female', 'other'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_family_members');
    }
};
