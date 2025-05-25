<?php

namespace App\Enums;

enum ManageStatus: string
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';

    // This status can be only used to block a user
    case BLOCKED = 'blocked';

    // This status can be only used to reject a tasca proposal
    case REJECTED = 'rejected';

    public static function getValues(): array
    {
        return [
            self::PENDING,
            self::ACCEPTED,
            self::BLOCKED,
            self::REJECTED,
        ];
    }
}
