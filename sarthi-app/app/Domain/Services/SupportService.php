<?php

namespace App\Domain\Services;

use App\Domain\Enums\UserRole;
use App\Domain\Exceptions\DomainException;
use App\Models\SupportMessage;
use App\Models\User;

class SupportService
{
    public function create(User $customer, string $subject, string $message): SupportMessage
    {
        if (!$customer->isRole(UserRole::Customer)) {
            throw new DomainException('Only customers can create support tickets.');
        }

        return SupportMessage::query()->create([
            'customer_id' => $customer->id,
            'subject' => $subject,
            'message' => $message,
            'status' => 'open',
        ]);
    }
}
