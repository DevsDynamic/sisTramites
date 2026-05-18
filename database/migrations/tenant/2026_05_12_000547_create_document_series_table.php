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
        Schema::create('document_series', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_type_id');
            $table->unsignedBigInteger('area_id')->nullable();
            $table->string('prefix');
            $table->integer('current_number')->default(0);
            $table->integer('padding')->default(6);
            $table->boolean('reset_yearly')->default(true);
            $table->unsignedBigInteger('tenant_id');

            $table->timestamps();
            $table->unique(['document_type_id', 'area_id', 'tenant_id'], 'doc_series_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_series');
    }
};
