<?php

namespace App\Domain\Services;

use App\Domain\Enums\UserRole;
use App\Domain\Exceptions\DomainException;
use App\Models\CabInvc;
use App\Models\ServiceRequest;
use App\Models\User;

class InvoiceService
{
    public function generate(ServiceRequest $request, float $amount, User $admin): CabInvc
    {
        if (!$admin->isRole(UserRole::Admin)) {
            throw new DomainException('Only admin can generate invoices.');
        }

        return CabInvc::query()->create([
            'service_request_id' => $request->id,
            'amount' => $amount,
            'status' => 'issued',
            'issued_at' => now(),
        ]);
    }
}
