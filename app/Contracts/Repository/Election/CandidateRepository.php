<?php

namespace App\Contracts\Repository\Election;

use App\Models\Election\Candidate;

interface CandidateRepository
{
    /**
     * Get all Candidate.
     * 
     * @return Illuminate\Support\Collection
     */
    public function all();

    /**
     * Get Candidate.
     * 
     * @param string $Uid
     * @return Candidate
     */
    public function get($Uid);

    /**
     * Get by condition Candidate.
     * 
     * @param array $condition
     * @return Candidate
     */
    public function getBy($condition);

    /**
     * Create Candidate.
     * 
     * @param array $data
     * @return Candidate
     */
    public function create($data);

    /**
     * Update Candidate.
     * 
     * @param Candidate $position
     * @return Candidate
     */
    public function update(Candidate $position);

    /**
     * Delete Candidate.
     * 
     * @param Candidate $position
     * @return bool
     */
    public function delete(Candidate $position);
}