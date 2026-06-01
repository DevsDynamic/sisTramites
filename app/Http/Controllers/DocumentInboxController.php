<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DocumentFlow;
use Illuminate\Http\Request;

class DocumentInboxController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = DocumentFlow::query()
            ->with(['document'])
            ->where('to_area_id', $user->area_id);

        // 🔍 filtros
        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->whereHas('document', function ($q) use ($request) {
                $q->where('subject', 'like', "%{$request->search}%")
                    ->orWhere('code', 'like', "%{$request->search}%");
            });
        }

        $flows = $query->latest()->paginate(10);

        return view('inbox.index', compact('flows'));
    }

    public function outbox()
    {
        return view('inbox.outbox');
    }

    public function tracking()
    {
        return view('inbox.tracking');
    }

    public function search()
    {
        return view('inbox.search');
    }

    public function archived()
    {
        return view('inbox.archived');
    }
}
