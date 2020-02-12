<?php

namespace App\Contracts\Service\Election;

use App\Models\Election\CandidateRegister;

interface CandidateRegisterGuard
{
    /**
     * Attemp to CandidateRegister using given credential.
     * 
     * @param array $credentials
     * @return bool
     */
    public function attempt($credentials);

    /**
     * Update Session and set current CandidateRegister.
     * 
     * @param CandidateRegister $candidate
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
     * Get current CandidateRegister
     * 
     * @return CandidateRegister
     */
    public function candidate();
}