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
        Schema::create('guest_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('host_id')->constrained('host')->onDelete('cascade');
            $table->foreignId('ceramony_id')->nullable()->constrained('ceramonies')->onDelete('cascade');
            $table->string('guest_name');
            $table->string('guest_number');
            $table->string('guest_email')->nullable();
            $table->enum('relation', ['bride', 'groom'])->nullable();
            $table->enum('gender',['male', 'female', 'other'])->nullable();
            $table->string('alternate_number')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('age')->nullable();
            $table->string('complex')->nullable();
            $table->string('floor')->nullable();
            $table->string('door_no')->nullable();
            $table->string('street_name')->nullable();
            $table->string('pincode')->nullable();
            $table->string('area_name')->nullable();
            $table->string('district')->nullable();
            $table->string('state')->nullable();
            $table->string('circle')->nullable();
            $table->string('country')->nullable();
            $table->string('location_map')->nullable();
            $table->boolean('invitation_sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->string('send_via')->nullable();
            $table->text('assigned_ceremonies')->nullable();
            $table->timestamps();
            $table->unique(['host_id', 'guest_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_lists');
    }
};
