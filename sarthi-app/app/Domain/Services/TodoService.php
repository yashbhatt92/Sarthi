<?php

namespace App\Domain\Services;

use App\Domain\Enums\UserRole;
use App\Domain\Exceptions\DomainException;
use App\Models\CabTodo;
use App\Models\User;

class TodoService
{
    public function create(User $staff, string $title): CabTodo
    {
        if (!$staff->isRole(UserRole::Staff)) {
            throw new DomainException('Only staff can own todo entries.');
        }

        return CabTodo::query()->create([
            'staff_id' => $staff->id,
            'title' => $title,
        ]);
    }
}
