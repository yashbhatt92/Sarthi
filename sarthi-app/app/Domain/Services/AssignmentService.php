<?php

namespace App\Domain\Services;

use App\Domain\Enums\ServiceRequestStatus;
use App\Domain\Enums\UserRole;
use App\Domain\Exceptions\DomainException;
use App\Models\ServiceRequest;
use App\Models\User;

class AssignmentService
{
    public function assign(ServiceRequest $request, User $staff, User $admin): ServiceRequest
    {
        if (!$admin->isRole(UserRole::Admin)) {
            throw new DomainException('Only admins can assign staff.');
        }

        if (!$staff->isRole(UserRole::Staff)) {
            throw new DomainException('Assignee must be a staff user.');
        }

        $request->update([
            'assigned_staff_id' => $staff->id,
            'status' => ServiceRequestStatus::Assigned,
            'chat_enabled' => true,
        ]);

        return $request->refresh();
    }
}
