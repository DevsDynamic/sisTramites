<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('plan_name')->default('Plan SaaS')->after('company_name');
            $table->date('license_expires_at')->nullable()->after('plan_name');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_path')->nullable()->after('email');
        });
    }
    public function down(): void {
        Schema::table('users', fn(Blueprint $table) => $table->dropColumn('avatar_path'));
        Schema::table('settings', fn(Blueprint $table) => $table->dropColumn(['plan_name', 'license_expires_at']));
    }
};
