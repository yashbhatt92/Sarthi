<?php

namespace App\Http\Controllers;

use App\Domain\Services\TodoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function __construct(private readonly TodoService $todoService)
    {
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(['title' => ['required', 'string', 'max:255']]);

        $this->todoService->create($request->user(), $validated['title']);

        return back();
    }
}
