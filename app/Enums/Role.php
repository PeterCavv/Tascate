<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'admin';
    case CUSTOMER = 'customer';
    case TASCA = 'tasca';
    case EMPLOYEE = 'employee';
    case MANAGER = 'manager';
    case OWNER = 'owner';

    public static function getValues(): array
    {
        return [
            self::ADMIN,
            self::CUSTOMER,
            self::TASCA,
            self::EMPLOYEE,
            self::MANAGER,
            self::OWNER,
        ];
    }

}
