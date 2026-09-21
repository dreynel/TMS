<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case CUSTODIAN = 'custodian';
    case BORROWER = 'borrower';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'System Administrator',
            self::CUSTODIAN => 'Tool Custodian',
            self::BORROWER => 'Borrower (Student/Faculty)',
        };
    }
}
