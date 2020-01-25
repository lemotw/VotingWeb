<?php

namespace App\Contracts\Repository\Vote;

use App\Models\Vote\AuthToken;

interface AuthTokenRepository
{
    /**
     * Create Token Record.
     * 
     * @param integer $election
     * @param string $token
     * @param string $sid
     * @return \App\Models\Vote\AuthToken
     */
    public function create($election, $token, $sid);

    /**
     * Get Token.
     * 
     * @param string $HashedToken
     * @return \App\Models\Vote\AuthToken
     */
    public function get($HashedToken);

    /**
     * Set Vote flag.
     * 
     * @param \App\Models\Vote\AuthToken $authtoken
     * @return \App\Models\Vote\AuthToken
     */
    public function setVoted(AuthToken $authtoken);

}