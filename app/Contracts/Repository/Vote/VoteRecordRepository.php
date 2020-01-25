<?php

namespace App\Contracts\Repository\Vote;

use App\Models\Vote\VoteRecord;

interface VoteRecordRepository
{
    /**
     * Create Vote Record.
     * 
     * @param string $electionPosition
     * @param string $candidate
     * @param int $vote
     * 
     * @return VoteRecord
     * @throws \App\Exceptions\FormatNotMatchException
     */
    public function create($electionPosition, $candidate, $vote);

    /**
     * Get Vote Record.
     * 
     * @param int $id
     * @return VoteRecord
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