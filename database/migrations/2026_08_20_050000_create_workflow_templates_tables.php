<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('document_type_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('origin_area_id')->nullable()->constrained('areas')->nullOnDelete();
            $table->boolean('active')->default(true);
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
            $table->unique(['name', 'document_type_id', 'origin_area_id'], 'workflow_template_scope_unique');
        });

        Schema::create('workflow_template_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_template_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('step_order');
            $table->string('name');
            $table->string('action', 20); // review, approval, signature
            $table->foreignId('responsible_area_id')->nullable()->constrained('areas')->nullOnDelete();
            $table->foreignId('responsible_role_id')->nullable()->constrained('roles')->nullOnDelete();
            $table->boolean('requires_signature')->default(false);
            $table->timestamps();
            $table->unique(['workflow_template_id', 'step_order']);
        });

        Schema::create('document_workflows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('workflow_template_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status', 20)->default('active');
            $table->unsignedInteger('current_step_order')->default(1);
            $table->foreignId('started_by')->constrained('users')->restrictOnDelete();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('document_workflow_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_workflow_id')->constrained()->cascadeOnDelete();
            $table->foreignId('workflow_template_step_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('step_order');
            $table->string('name');
            $table->string('action', 20);
            $table->foreignId('responsible_area_id')->nullable()->constrained('areas')->nullOnDelete();
            $table->foreignId('responsible_role_id')->nullable()->constrained('roles')->nullOnDelete();
            $table->boolean('requires_signature')->default(false);
            $table->string('status', 20)->default('pending');
            $table->foreignId('acted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('comment')->nullable();
            $table->timestamp('acted_at')->nullable();
            $table->timestamps();
            $table->unique(['document_workflow_id', 'step_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_workflow_steps');
        Schema::dropIfExists('document_workflows');
        Schema::dropIfExists('workflow_template_steps');
        Schema::dropIfExists('workflow_templates');
    }
};
