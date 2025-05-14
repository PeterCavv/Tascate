<?php

namespace App\Enums;

enum Role: string
{
    const ADMIN = 'admin';
    const CUSTOMER = 'customer';
    const TASCA = 'tasca';
    const EMPLOYEE = 'employee';
    const MANAGER = 'manager';
    const OWNER = 'owner';

}
