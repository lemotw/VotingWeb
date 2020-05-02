<?php

namespace App\Contracts\Utility;

abstract class UserRole
{
    const admin = 0;
    const maintainer = 1;
    const auth_table = 2;
    const vote_table = 3;

    const list = [
        self::admin => 'admin',
        self::maintainer => 'maintainer',
        self::auth_table => 'auth_table',
        self::vote_table => 'vote_table'
    ];
}