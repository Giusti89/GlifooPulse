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
        Schema::table('social_clicks', function (Blueprint $table) {
            $table->string('utm_source')->nullable()->after('user_agent');
            $table->string('utm_campaign')->nullable()->after('utm_source');
            $table->string('device_type', 20)->nullable()->after('utm_campaign');

            $table->index(['social_id', 'clicked_at'], 'social_clicks_social_date_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('social_clicks', function (Blueprint $table) {
            $table->dropIndex('social_clicks_social_date_idx');
            $table->dropColumn(['utm_source', 'utm_campaign', 'device_type']);
        });
    }
};
