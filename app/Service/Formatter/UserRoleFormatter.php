<?php

namespace App\Service\Formatter;

use App\Contracts\Utility\UserRole;

class UserRoleFormatter {

    public static function cast($role) {
        switch($role) {
            case UserRole::admin:
                return '管理員';
            case UserRole::maintainer:
                return '維護者';
            case UserRole::auth_table:
                return '驗票台';
            case UserRole::vote_table:
                return '投票台';
        }
    }

    public static function role_list() {
        $role_list = [];

        foreach(UserRole::list as $key => $role)
            $role_list[$key] = self::cast($key);

        return $role_list;
    }
}