<?php

namespace App\Http\Controllers;

use App\Domain\Services\InvoiceService;
use App\Models\ServiceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function __construct(private readonly InvoiceService $invoiceService)
    {
    }

    public function store(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $validated = $request->validate(['amount' => ['required', 'numeric', 'min:0.01']]);

        $this->invoiceService->generate($serviceRequest, (float) $validated['amount'], $request->user());

        return back();
    }
}
