<?php

namespace App\Domain\Services;

use App\Domain\Enums\ServiceRequestStatus;
use App\Domain\Enums\UserRole;
use App\Domain\Exceptions\DomainException;
use App\Models\ServiceRequest;
use App\Models\User;

class ServiceRequestService
{
    public function create(User $customer, array $data): ServiceRequest
    {
        if (!$customer->isRole(UserRole::Customer)) {
            throw new DomainException('Only customers can create requests.');
        }

        return ServiceRequest::query()->create([
            ...$data,
            'customer_id' => $customer->id,
            'status' => ServiceRequestStatus::Pending,
        ]);
    }

    public function transition(ServiceRequest $request, ServiceRequestStatus $status, User $actor): ServiceRequest
    {
        if ($actor->isRole(UserRole::Customer)) {
            throw new DomainException('Customers cannot change request lifecycle status directly.');
        }

        if (!$request->status->canTransitionTo($status)) {
            throw new DomainException('Invalid service request status transition.');
        }

        $request->update(['status' => $status]);

        return $request->refresh();
    }
}
