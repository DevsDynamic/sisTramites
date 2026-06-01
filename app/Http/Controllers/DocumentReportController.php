<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentReportController extends Controller
{
    public function document(Document $document)
    {
        $pdf = Pdf::loadView(
            'reports.document',
            compact('document')
        );

        return $pdf->stream(
            'documento-' . $document->code . '.pdf'
        );
    }
}