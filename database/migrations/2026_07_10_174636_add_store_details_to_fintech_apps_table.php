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
            $table->string('playstore_id')->nullable()->after('package_name');
            $table->string('appstore_id')->nullable()->after('playstore_id');
            $table->string('platform')->default('android')->after('appstore_id');
            $table->bigInteger('downloads')->default(0)->after('is_active');
            $table->decimal('average_rating', 3, 2)->default(0)->after('downloads');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fintech_apps', function (Blueprint $table) {
            $table->dropColumn(['playstore_id', 'appstore_id', 'platform', 'downloads', 'average_rating']);
        });
    }
};
