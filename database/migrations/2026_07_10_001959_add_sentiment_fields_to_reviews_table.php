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
            $table->enum('sentiment_status', ['pending', 'analyzed', 'error'])->default('pending')->after('ml_status');
            $table->decimal('sentiment_positive', 5, 4)->nullable()->after('is_bug');
            $table->decimal('sentiment_negative', 5, 4)->nullable()->after('sentiment_positive');
            $table->decimal('sentiment_neutral', 5, 4)->nullable()->after('sentiment_negative');
            $table->decimal('sentiment_compound', 5, 4)->nullable()->after('sentiment_neutral');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn([
                'sentiment_status',
                'sentiment_positive',
                'sentiment_negative',
                'sentiment_neutral',
                'sentiment_compound'
            ]);
        });
    }
};
