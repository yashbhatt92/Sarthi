<?php

namespace App\Models;

use App\Domain\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'role',
        'name',
        'email',
        'password',
        'stau',
        'is_vf',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_vf' => 'boolean',
            'role' => UserRole::class,
        ];
    }

    public function isRole(UserRole $role): bool
    {
        return $this->role === $role;
    }
}
