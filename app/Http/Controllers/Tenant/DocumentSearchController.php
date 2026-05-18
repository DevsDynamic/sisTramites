<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Area;
use App\Models\Tenant\DocumentType;
use App\Services\DocumentSearchService;
use Illuminate\Http\Request;

class DocumentSearchController extends Controller
{
    public function __construct(
        protected DocumentSearchService $searchService
    ) {}

    public function index(Request $request)
    {
        $documents = $this->searchService->search(
            $request->all()
        );

        $documentTypes = DocumentType::all();

        $areas = Area::all();

        return view(
            'tenant.documents.search',
            compact(
                'documents',
                'documentTypes',
                'areas'
            )
        );
    }
}