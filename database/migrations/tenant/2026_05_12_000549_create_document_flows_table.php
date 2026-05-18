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
        Schema::create('document_flows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id');
            $table->unsignedBigInteger('from_area_id');
            $table->unsignedBigInteger('to_area_id');
            $table->unsignedBigInteger('sent_by');
            $table->unsignedBigInteger('received_by')->nullable();
            $table->text('comment')->nullable();
            $table->enum('status', [
                'pending',
                'received',
                'observed',
                'approved',
                'rejected'
            ])->default('pending');
            $table->timestamp('sent_at');
            $table->timestamp('received_at')->nullable();
            $table->unsignedBigInteger('tenant_id');
            /* SLA */
            $table->timestamp('sla_deadline')->nullable();
            $table->boolean('sla_warning_sent')->default(false);
            $table->boolean('sla_expired')->default(false);
            $table->timestamp('sla_escalated_at')->nullable();

            $table->timestamps();
            $table->index(['document_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_flows');
    }
};
