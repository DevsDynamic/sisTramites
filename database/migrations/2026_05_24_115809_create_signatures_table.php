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
        Schema::create('signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            /*TIPO
            | official = certificado digital (.pfx)
            | visual = imagen/png simple */
            $table->enum('type', [
                'official',
                'visual'
            ]);
            /* ARCHIVOS */
            $table->string('pfx_path')->nullable();
            $table->string('signature_image')->nullable();
            /* PASSWORD CERTIFICADO */
            $table->text('pfx_password')->nullable();
            /* CERT INFO */
            $table->json('certificate_data')->nullable();
            /* FLAGS */
            $table->boolean('is_default')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signatures');
    }
};
