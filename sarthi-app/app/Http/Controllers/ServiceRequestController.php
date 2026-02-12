<?php

namespace App\Http\Controllers;

use App\Domain\Enums\ServiceRequestStatus;
use App\Domain\Services\ServiceRequestQueryService;
use App\Domain\Services\ServiceRequestService;
use App\Models\ServiceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ServiceRequestController extends Controller
{
    public function __construct(
        private readonly ServiceRequestService $service,
        private readonly ServiceRequestQueryService $queryService,
    ) {
    }

    public function index(Request $request): Response
    {
        return Inertia::render('Requests/Index', [
            'requests' => $this->queryService->listForUser($request->user()),
        ]);
    }

    public function show(ServiceRequest $serviceRequest): Response
    {
        $this->authorize('view', $serviceRequest);

        return Inertia::render('Requests/Show', [
            'request' => $serviceRequest->load(['service', 'customer', 'assignedStaff', 'messages.sender']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        $record = $this->service->create($request->user(), $validated);

        return redirect()->route('requests.show', $record);
    }

    public function transition(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string'],
        ]);

        $this->service->transition($serviceRequest, ServiceRequestStatus::from($validated['status']), $request->user());

        return back();
    }
}
