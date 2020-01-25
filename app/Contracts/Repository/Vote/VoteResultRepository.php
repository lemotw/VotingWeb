<?php

namespace App\Contracts\Repository\Vote;

use App\Models\Vote\VoteResult;

interface VoteResultRepository
{
    /**
     * Create Vote Result.
     * 
     * @param array $data
     * @return VoteResult
     * @throws \App\Exceptions\FormatNotMatchException
     */
    public function create($data);

    /**
     * Update Vote Result.
     * 
     * @param VoteResult $voteResult
     * @return VoteResult
     */
    public function update($voteResult);
    
    /**
     * Get Vote Result.
     * 
     * @param int $id
     * @return VoteResult
     */
    public function get($id);

    /**
     * Get by Election Position
     * 
     * @param string $electionPosition
     * @return Illuminate\Support\Collection
     */
    public function getByElectionPosition($electionPosition);
}