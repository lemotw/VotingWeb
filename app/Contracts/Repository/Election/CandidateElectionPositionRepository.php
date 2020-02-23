<?php

namespace App\Contracts\Repository\Election;

use App\Models\Election\CandidateElectionPosition;

interface CandidateElectionPositionRepository
{
    /**
     * Get all CandidateElectionPosition.
     * 
     * @return Illuminate\Support\Collection
     */
    public function all();

    /**
     * Get CandidateElectionPosition.
     * 
     * @param integer $id
     * @return CandidateElectionPosition
     */
    public function get($id);

    /**
     * Get by condition CandidateElectionPosition.
     * 
     * @param array $condition
     * @return CandidateElectionPosition
     */
    public function getBy($condition);

    /**
     * Create CandidateElectionPosition.
     * 
     * @param array $data
     * @return CandidateElectionPosition
     */
    public function create($data);

    /**
     * Update CandidateElectionPosition.
     * 
     * @param array $data
     * @return CandidateElectionPosition
     */
    public function update($data);

    /**
     * Delete CandidateElectionPosition.
     * 
     * @param CandidateElectionPosition $CEP
     * @return bool
     */
    public function delete(CandidateElectionPosition $CEP);
}