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
        // Adding the foreign key column
        $table->foreignId('category_id')
              ->after('ceramony_id') // Places it logically in the table
              ->nullable()
              ->constrained('guest_categories')
              ->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('guest_lists', function (Blueprint $table) {
        $table->dropForeign(['category_id']);
        $table->dropColumn('category_id');
    });
}
};
