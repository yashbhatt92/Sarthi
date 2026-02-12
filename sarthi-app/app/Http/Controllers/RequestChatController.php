<?php

namespace App\Http\Controllers;

use App\Domain\Services\RequestChatService;
use App\Models\ServiceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RequestChatController extends Controller
{
    public function __construct(private readonly RequestChatService $chatService)
    {
    }

    public function store(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $this->authorize('chat', $serviceRequest);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:3000'],
        ]);

        $this->chatService->sendMessage($serviceRequest, $request->user(), $validated['body']);

        return back();
    }

    public function markSeen(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $this->authorize('chat', $serviceRequest);

        $this->chatService->markSeen($serviceRequest, $request->user());

        return back();
    }

    public function toggle(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $validated = $request->validate(['enabled' => ['required', 'boolean']]);

        $this->chatService->setChatEnabled($serviceRequest, $validated['enabled'], $request->user());

        return back();
    }
}
