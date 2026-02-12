<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestChatController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\TodoController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn () => Inertia::render('Dashboard'))->name('dashboard');

    Route::middleware('role:customer,staff,admin')->group(function () {
        Route::resource('requests', ServiceRequestController::class)->only(['index', 'show']);
        Route::post('/requests/{serviceRequest}/chat', [RequestChatController::class, 'store'])->name('requests.chat.store');
        Route::post('/requests/{serviceRequest}/chat/seen', [RequestChatController::class, 'markSeen'])->name('requests.chat.seen');
        Route::get('/support', [SupportController::class, 'index'])->name('support.index');
    });

    Route::middleware('role:customer')->group(function () {
        Route::post('/requests', [ServiceRequestController::class, 'store'])->name('requests.store');
        Route::post('/support', [SupportController::class, 'store'])->name('support.store');
    });

    Route::middleware('role:staff')->group(function () {
        Route::patch('/requests/{serviceRequest}/chat/toggle', [RequestChatController::class, 'toggle'])->name('requests.chat.toggle');
        Route::post('/todos', [TodoController::class, 'store'])->name('todos.store');
    });

    Route::middleware('role:admin')->group(function () {
        Route::post('/requests/{serviceRequest}/transition', [ServiceRequestController::class, 'transition'])->name('requests.transition');
        Route::post('/requests/{serviceRequest}/invoice', [InvoiceController::class, 'store'])->name('requests.invoice.store');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
