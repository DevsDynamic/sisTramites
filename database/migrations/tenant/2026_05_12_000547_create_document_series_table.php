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
            $table->unsignedBigInteger('document_type_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('area_id')->nullable()->constrained()->nullOnDelete();
            $table->string('prefix', 20);
            $table->unsignedBigInteger('current_number')->default(0);
            $table->unsignedInteger('padding')->default(6);
            $table->boolean('reset_yearly')->default(true);
            $table->boolean('active')->default(true);

            $table->timestamps();
            $table->unique(['document_type_id', 'area_id'], 'doc_series_unique');
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
