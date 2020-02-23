<?php

namespace App\Contracts\Service\Election;

use App\Models\Election\Candidate;

interface CandidateGuard
{
    /**
     * Attemp to Candidate using given credential.
     * 
     * @param array $credentials
     * @return bool
     */
    public function attempt($credentials);

    /**
     * Update Session and set current Candidate.
     * 
     * @param Candidate $candidate
     * @return void
     */
    public function login($candidate);

    /**
     * Logout.
     * 
     * @return void
     */
    public function logout();

    /**
     * Get current Candidate
     * 
     * @return Candidate
     */
    public function candidate();
}