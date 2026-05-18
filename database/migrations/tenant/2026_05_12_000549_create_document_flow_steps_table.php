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
        Schema::create('document_flow_steps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_type_id');
            $table->unsignedBigInteger('from_area_id');
            $table->unsignedBigInteger('to_area_id');
            $table->integer('order')->default(1);
            $table->boolean('is_required')->default(true);
            $table->unsignedBigInteger('tenant_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_flow_steps');
    }
};
