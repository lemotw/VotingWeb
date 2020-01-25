<?php

namespace App\Contracts\Service\Vote;

use App\Models\Election\ElectionPosition;

interface VoteService
{
    /**
     * Check vote qualify.
     * 
     * @param string $hashedtoken
     * @return Illuminate\Support\Collection
     */
    public function GetVotes($hashedtoken);

    /**
     * Vote to Candidate.
     * 
     * @param string $encryptStr
     * @param string $key
     * 
     * @throws \App\Service\Vote\VotePayloadException
     * @return bool
     */
    public function vote($encryptStr, $key);

    /**
     * Vote Result Calculate.
     * 
     * @param string $electionPositionUID
     * @return VoteResult
     */
    public function CalculateVoteResult($electionPositionUID);
}