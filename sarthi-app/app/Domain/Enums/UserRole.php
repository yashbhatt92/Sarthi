<?php

namespace App\Domain\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Staff = 'staff';
    case Affiliate = 'affiliate';
    case Customer = 'customer';
}
