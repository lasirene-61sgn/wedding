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
        Schema::create('host_family_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('host_id')->constrained('host')->onDelete('cascade');
            $table->foreignId('selected_background_id')->nullable()->constrained('ceramony_backgrounds')->nullOnDelete();
            $table->text('textone')->nullable();
            $table->string('topic_title_one')->nullable();
            $table->text('texttwo')->nullable();
            $table->string('topic_title_two')->nullable();
            $table->text('textthree')->nullable();
            $table->string('topic_title_three')->nullable();
            $table->text('textfour')->nullable();
            $table->string('topic_title_four')->nullable();
            $table->text('textfive')->nullable();
            $table->string('topic_title_five')->nullable();
            $table->text('textsix')->nullable();
            $table->string('topic_title_six')->nullable();
            $table->text('textseven')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('host_family_details');
    }
};
