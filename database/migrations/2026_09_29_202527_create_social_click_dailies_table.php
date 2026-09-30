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
        Schema::create('social_click_dailies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->string('utm_source', 100)->nullable();
            $table->string('utm_campaign', 100)->nullable();
            $table->string('device_type', 20)->nullable();
            $table->unsignedInteger('clicks')->default(0);
            $table->timestamps();

            $table->unique(
                ['social_id', 'date', 'utm_source', 'utm_campaign', 'device_type'],
                'social_click_dailies_unique'
            );
            $table->index(['social_id', 'date'], 'social_click_dailies_social_date_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_click_dailies');
    }
};
