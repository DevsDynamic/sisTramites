<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DocumentFlow;
use App\Services\DocumentActionService;
use Illuminate\Http\Request;

class DocumentFlowController extends Controller
{
    public function __construct(
        protected DocumentActionService $actionService
    ) {}

    public function receive(DocumentFlow $flow, Request $request)
    {
        $this->actionService->receive($flow, $request->user());

        return back()->with('success', 'Documento recibido');
    }

    public function approve(DocumentFlow $flow, Request $request)
    {
        $this->actionService->approve($flow, $request->user());

        return back()->with('success', 'Documento aprobado');
    }

    public function reject(DocumentFlow $flow, Request $request)
    {
        $this->actionService->reject(
            $flow,
            $request->user(),
            $request->reason
        );

        return back()->with('success', 'Documento rechazado');
    }

    public function observe(DocumentFlow $flow, Request $request)
    {
        $this->actionService->observe(
            $flow,
            $request->user(),
            $request->comment
        );

        return back()->with('success', 'Observación registrada');
    }

    public function reassign(DocumentFlow $flow, Request $request)
    {
        $this->actionService->reassign(
            $flow,
            $request->to_area_id,
            $request->user(),
            $request->comment
        );

        return back()->with('success', 'Documento derivado');
    }

    public function flows()
    {
        return view('signature.index');
    }
}
