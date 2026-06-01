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
        Schema::create('workflow_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_type_id');
            $table->foreignId('area_id');
            $table->json('allowed_actions'); // approve / reject / observe / reassign

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_permissions');
    }
};
