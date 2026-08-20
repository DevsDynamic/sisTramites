<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('document_attachments', function (Blueprint $table) {
            $table->string('original_name')->nullable()->after('file_name');
            $table->string('kind', 20)->default('primary')->after('document_id');
            $table->foreignId('source_attachment_id')
                ->nullable()
                ->after('signature_id')
                ->constrained('document_attachments')
                ->nullOnDelete();
        });

        DB::table('document_attachments')
            ->where('is_signed', true)
            ->update(['kind' => 'signed_copy']);
    }

    public function down(): void
    {
        Schema::table('document_attachments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('source_attachment_id');
            $table->dropColumn(['original_name', 'kind']);
        });
    }
};
