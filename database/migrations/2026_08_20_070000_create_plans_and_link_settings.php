<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description', 500)->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->unsignedInteger('duration_days')->nullable();
            $table->unsignedInteger('max_users')->nullable();
            $table->unsignedInteger('max_areas')->nullable();
            $table->unsignedInteger('max_signatures')->nullable();
            $table->unsignedInteger('max_documents')->nullable();
            $table->unsignedInteger('max_workflows')->nullable();
            $table->unsignedInteger('max_storage_mb')->nullable();
            $table->json('features')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_custom')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->foreignId('plan_id')->nullable()->after('plan_name')
                ->constrained('plans')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('plan_id');
        });

        Schema::dropIfExists('plans');
    }
};
