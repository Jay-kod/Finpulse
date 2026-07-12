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
        Schema::table('reviews', function (Blueprint $table) {
            $table->index('sentiment_status');
            $table->index('published_at');
            $table->index('topic');
            $table->index('intent');
            $table->index('is_bug');
            $table->index('sentiment_compound');
            $table->index(['sentiment_status', 'published_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['sentiment_status']);
            $table->dropIndex(['published_at']);
            $table->dropIndex(['topic']);
            $table->dropIndex(['intent']);
            $table->dropIndex(['is_bug']);
            $table->dropIndex(['sentiment_compound']);
            $table->dropIndex(['sentiment_status', 'published_at']);
        });
    }
};
