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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('package_name'); //plans
            $table->decimal('price', 8,2);
            $table->integer('guest_limit'); //guest count
            $table->date('validity');
            $table->string('invitaion');
            $table->string('rsvp');
            $table->string('ceramonies');
            $table->string('reports');
            $table->string('gallery');
            $table->text('package_description'); // Message Service
            $table->string('wishboard')->nullable();
            $table->string('dcgqrcode')->nullable();
            $table->text('vaf');
            $table->integer('invite_limit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
