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
            $table->text('cleaned_content')->nullable()->after('content');
            $table->string('detected_language', 10)->nullable()->after('cleaned_content');
            $table->integer('word_count')->nullable()->after('detected_language');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['cleaned_content', 'detected_language', 'word_count']);
        });
    }
};
