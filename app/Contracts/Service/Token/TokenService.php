<?php

namespace App\Contracts\Service\Token;

use App\User;

interface TokenService
{
    /**
     * Get encrypted table key.
     * 
     * @param \App\User $user
     * @return string $encrypted_key
     */
    public function getTableKey(User $user);

    /**
     * Refresh table key
     * 
     * @param \App\User $user
     * @return bool
     */
    public function refreshTableKey(User $user);

    /**
     * Auth voter token.
     * 
     * @param string $encrypted_str
     * @return bool
     */
    public function authVoter($encrypted_str, $key);
}