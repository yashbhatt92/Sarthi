<?php

namespace App\Domain\Services;

use App\Domain\Enums\UserRole;
use App\Models\SupportMessage;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class SupportQueryService
{
    public function listForUser(User $user, int $perPage = 10): LengthAwarePaginator
    {
        $query = SupportMessage::query();

        if ($user->isRole(UserRole::Customer)) {
            $query->where('customer_id', $user->id);
        }

        return $query->latest()->paginate($perPage);
    }
}
