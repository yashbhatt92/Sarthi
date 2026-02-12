<?php

use App\Domain\Enums\UserRole;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('presence-request.{requestId}', function ($user, int $requestId) {
    $request = ServiceRequest::query()->find($requestId);

    if (!$request) {
        return false;
    }

    $isCustomer = $user->role === UserRole::Customer && $request->customer_id === $user->id;
    $isAssignedStaff = $user->role === UserRole::Staff && $request->assigned_staff_id === $user->id;

    if (!$isCustomer && !$isAssignedStaff) {
        return false;
    }

    return ['id' => $user->id, 'name' => $user->name, 'role' => $user->role->value];
});
