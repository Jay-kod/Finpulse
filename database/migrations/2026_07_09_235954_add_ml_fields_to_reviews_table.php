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
            $table->enum('ml_status', ['pending', 'classified', 'error'])->default('pending')->after('processed_status');
            $table->string('topic')->nullable()->after('word_count');
            $table->string('intent')->nullable()->after('topic');
            $table->boolean('is_bug')->default(false)->after('intent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['ml_status', 'topic', 'intent', 'is_bug']);
        });
    }
};
