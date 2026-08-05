<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('host', function (Blueprint $table) {
            $table->unsignedInteger('whatsapp_sent_count')->default(0)->after('messages_sent_count');
            $table->unsignedInteger('sms_sent_count')->default(0)->after('whatsapp_sent_count');
            $table->unsignedInteger('email_sent_count')->default(0)->after('sms_sent_count');
        });
    }

    public function down(): void
    {
        Schema::table('host', function (Blueprint $table) {
            $table->dropColumn(['whatsapp_sent_count', 'sms_sent_count', 'email_sent_count']);
        });
    }
};
