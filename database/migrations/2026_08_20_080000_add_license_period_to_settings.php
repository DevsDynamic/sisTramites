<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->date('license_starts_at')->nullable()->after('plan_id');
            $table->string('license_cycle', 20)->default('annual')->after('license_starts_at');
            $table->unsignedInteger('license_custom_days')->nullable()->after('license_cycle');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['license_starts_at', 'license_cycle', 'license_custom_days']);
        });
    }
};
