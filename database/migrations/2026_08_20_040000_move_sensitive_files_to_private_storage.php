<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('document_attachments', function (Blueprint $table) {
            $table->string('storage_disk', 30)->default('local')->after('file_path');
        });

        Schema::table('signatures', function (Blueprint $table) {
            $table->string('pfx_disk', 30)->nullable()->after('pfx_path');
            $table->string('signature_image_disk', 30)->nullable()->after('signature_image');
        });

        DB::table('document_attachments')->orderBy('id')->each(function ($attachment) {
            $this->moveToPrivate($attachment->file_path);
        });

        DB::table('signatures')->orderBy('id')->each(function ($signature) {
            $this->moveToPrivate($signature->pfx_path);
            $this->moveToPrivate($signature->signature_image);
        });

        DB::table('signatures')->update([
            'pfx_disk' => 'local',
            'signature_image_disk' => 'local',
        ]);
    }

    public function down(): void
    {
        Schema::table('signatures', function (Blueprint $table) {
            $table->dropColumn(['pfx_disk', 'signature_image_disk']);
        });

        Schema::table('document_attachments', function (Blueprint $table) {
            $table->dropColumn('storage_disk');
        });
    }

    private function moveToPrivate(?string $path): void
    {
        if (! $path || ! Storage::disk('public')->exists($path)) {
            return;
        }

        Storage::disk('local')->put($path, Storage::disk('public')->get($path));
        Storage::disk('public')->delete($path);
    }
};
