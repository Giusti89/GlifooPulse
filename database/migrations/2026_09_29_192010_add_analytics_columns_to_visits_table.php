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
        Schema::table('visits', function (Blueprint $table) {
            $table->string('referrer')->nullable()->after('user_agent');
            $table->string('referrer_domain')->nullable()->after('referrer');
            $table->string('utm_source')->nullable()->after('referrer_domain');
            $table->string('utm_medium')->nullable()->after('utm_source');
            $table->string('utm_campaign')->nullable()->after('utm_medium');
            $table->string('device_type', 20)->nullable()->after('utm_campaign');
            $table->string('session_id', 40)->nullable()->after('device_type');

            $table->index(['spot_id', 'visited_at'], 'visits_spot_date_idx');
            $table->index(['spot_id', 'utm_source'], 'visits_spot_source_idx');
            $table->index(['spot_id', 'utm_campaign'], 'visits_spot_campaign_idx');
            $table->index(['spot_id', 'referrer_domain'], 'visits_spot_referrer_idx');
            $table->index('session_id', 'visits_session_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->dropIndex('visits_spot_date_idx');
            $table->dropIndex('visits_spot_source_idx');
            $table->dropIndex('visits_spot_campaign_idx');
            $table->dropIndex('visits_spot_referrer_idx');
            $table->dropIndex('visits_session_idx');

            $table->dropColumn([
                'referrer',
                'referrer_domain',
                'utm_source',
                'utm_medium',
                'utm_campaign',
                'device_type',
                'session_id',
            ]);
        });
    }
};
