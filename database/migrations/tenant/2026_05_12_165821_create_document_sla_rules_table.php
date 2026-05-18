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
        Schema::create('document_sla_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id');
            $table->foreignId('document_type_id');
            $table->integer('hours_limit')->default(24); // ⏱ HORAS MÁXIMAS
            $table->integer('warning_before_hours')->default(2); // 🔥 ALERTA PREVIA
            $table->boolean('allow_escalation')->default(true); // 🚨 ESCALAMIENTO

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_sla_rules');
    }
};
