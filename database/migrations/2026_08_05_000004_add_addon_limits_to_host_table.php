<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('host', function (Blueprint $table) {
            $table->unsignedInteger('whatsapp_addon_limit')->default(0)->after('email_sent_count');
            $table->unsignedInteger('sms_addon_limit')->default(0)->after('whatsapp_addon_limit');
            $table->unsignedInteger('email_addon_limit')->default(0)->after('sms_addon_limit');
        });
    }

    public function down(): void
    {
        Schema::table('host', function (Blueprint $table) {
            $table->dropColumn(['whatsapp_addon_limit', 'sms_addon_limit', 'email_addon_limit']);
        });
    }
};
