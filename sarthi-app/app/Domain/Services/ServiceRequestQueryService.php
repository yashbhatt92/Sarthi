<?php

namespace App\Domain\Services;

use App\Domain\Enums\UserRole;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class ServiceRequestQueryService
{
    public function listForUser(User $user, int $perPage = 10): LengthAwarePaginator
    {
        $query = ServiceRequest::query()->with(['service', 'customer', 'assignedStaff']);

        if ($user->isRole(UserRole::Customer)) {
            $query->where('customer_id', $user->id);
        } elseif ($user->isRole(UserRole::Staff)) {
            $query->where('assigned_staff_id', $user->id);
        }

        return $query->latest()->paginate($perPage);
    }
}
