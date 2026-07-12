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
        Schema::table('fintech_apps', function (Blueprint $table) {
            $table->string('twitter_handle')->nullable()->after('appstore_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fintech_apps', function (Blueprint $table) {
            $table->dropColumn('twitter_handle');
        });
    }
};
