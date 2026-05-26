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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); /* DOCUMENTO */
            $table->string('subject');
            $table->longText('content')->nullable();
            $table->unsignedBigInteger('document_type_id');
            $table->unsignedBigInteger('area_id');
            $table->unsignedBigInteger('created_by');
            $table->string('status')
                ->default('draft');
            $table->timestamp('sent_at')->nullable(); /* SLA / TRACKING */
            $table->timestamps();
            /* FOREIGN KEYS */
            $table->foreign('document_type_id') /* RELACIONES */
                ->references('id')
                ->on('document_types');
            $table->foreign('area_id')
                ->references('id')
                ->on('areas');
            /** ÍNDICES ENTERPRISE */
            $table->index('status'); // FILTROS
            $table->index('document_type_id');
            $table->index('area_id');
            $table->index('created_by');
            $table->index('created_at');
            $table->fullText([ // 🔎 FULLTEXT SEARCH
                'code',
                'subject',
                'content'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
