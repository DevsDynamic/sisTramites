<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_signature_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->cascadeOnDelete();
            $table->foreignId('signer_user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('requested_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('signature_id')->nullable()->constrained('signatures')->nullOnDelete();
            $table->foreignId('signed_attachment_id')->nullable()->constrained('document_attachments')->nullOnDelete();
            $table->unsignedInteger('sequence')->default(1);
            $table->string('status', 20)->default('pending');
            $table->timestamp('signed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index(['document_id', 'status']);
            $table->index(['signer_user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_signature_requests');
    }
};
