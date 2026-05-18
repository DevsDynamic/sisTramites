<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentFileVersion;
use Illuminate\Support\Facades\Storage;

class DocumentFileVersionService
{
    public function upload(
        Document $document,
        $file,
        $user,
        ?string $comment = null
    ) {

        $lastVersion = DocumentFileVersion::where(
            'document_id',
            $document->id
        )->max('version');

        $version = $lastVersion + 1;

        $path = $file->store(
            'documents/' . tenant_id(),
            'public'
        );

        return DocumentFileVersion::create([
            'tenant_id' => tenant_id(),
            'document_id' => $document->id,
            'uploaded_by' => $user->id,
            'version' => $version,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'comment' => $comment,
        ]);
    }
}