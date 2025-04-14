<?php

namespace App\Enums;

enum FriendshipStatus
{
    const PENDING = 'pending';
    const ACCEPTED = 'accepted';
    const BLOCKED = 'blocked';

    public static function getValues(): array
    {
        return [
            self::PENDING,
            self::ACCEPTED,
            self::BLOCKED,
        ];
    }
}
