<?php

namespace App\Http\Controllers;

use App\Domain\Services\SupportQueryService;
use App\Domain\Services\SupportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SupportController extends Controller
{
    public function __construct(
        private readonly SupportService $supportService,
        private readonly SupportQueryService $queryService,
    ) {
    }

    public function index(Request $request): Response
    {
        return Inertia::render('Support/Index', [
            'tickets' => $this->queryService->listForUser($request->user()),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        $this->supportService->create($request->user(), $validated['subject'], $validated['message']);

        return back();
    }
}
