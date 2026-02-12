<?php

namespace App\Policies;

use App\Domain\Enums\UserRole;
use App\Models\ServiceRequest;
use App\Models\User;

class ServiceRequestPolicy
{
    public function view(User $user, ServiceRequest $request): bool
    {
        return $user->isRole(UserRole::Admin)
            || ($user->isRole(UserRole::Customer) && $request->customer_id === $user->id)
            || ($user->isRole(UserRole::Staff) && $request->assigned_staff_id === $user->id);
    }

    public function chat(User $user, ServiceRequest $request): bool
    {
        return $this->view($user, $request);
    }
}
