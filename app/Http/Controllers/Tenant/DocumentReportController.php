<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Document;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentReportController extends Controller
{
    public function document(Document $document)
    {
        $pdf = Pdf::loadView(
            'tenant.reports.document',
            compact('document')
        );

        return $pdf->stream(
            'documento-' . $document->code . '.pdf'
        );
    }
}