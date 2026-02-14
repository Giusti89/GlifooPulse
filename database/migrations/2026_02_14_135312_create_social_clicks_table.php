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
        Schema::create('social_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_id')->constrained()->onDelete('cascade');
            $table->timestamp('clicked_at')->index();
            $table->string('ip', 45)->nullable()->index();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            $table->index(['social_id', 'clicked_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_clicks');
    }
};
