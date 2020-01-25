<?php

namespace App\Contracts\Repository\Election;

use App\Models\Election\CandidateRegister;

interface PositionRepository
{
    /**
     * Get all CandidateRegister.
     * 
     * @return Illuminate\Support\Collection
     */
    public function all();

    /**
     * Get CandidateRegister.
     * 
     * @param string $Uid
     * @return CandidateRegister
     */
    public function get($Uid);

    /**
     * Get by condition CandidateRegister.
     * 
     * @param array $condition
     * @return CandidateRegister
     */
    public function getBy($condition);

    /**
     * Create CandidateRegister.
     * 
     * @param array $data
     * @return CandidateRegister
     */
    public function create($data);

    /**
     * Update CandidateRegister.
     * 
     * @param CandidateRegister$position
     * @return CandidateRegister
     */
    public function update(CandidateRegister $position);

    /**
     * Delete CandidateRegister.
     * 
     * @param CandidateRegister $position
     * @return bool
     */
    public function delete(CandidateRegister $position);
}