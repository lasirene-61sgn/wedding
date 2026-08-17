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
        Schema::table('ceramonies', function (Blueprint $table) {
            $table->string('sub_category')->nullable()->after('category_id');
            $table->string('selected_html_template')->nullable()->after('ceramony_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ceramonies', function (Blueprint $table) {
            $table->dropColumn(['sub_category', 'selected_html_template']);
        });
    }
};
