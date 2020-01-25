<?php

namespace App\Contracts\Repository\Election;

use App\Models\Election\Election;

interface ElectionRepository
{
    /**
     * Get all Election.
     * 
     * @return Illuminate\Support\Collection
     */
    public function all();

    /**
     * Get Position.
     * 
     * @param string $Uid
     * @return Election
     */
    public function get($Uid);

    /**
     * Get by condition Election.
     * 
     * @param array $condition
     * @return Election
     */
    public function getBy($condition);

    /**
     * Create Election.
     * 
     * @param array $data
     * @return Election
     */
    public function create($data);

    /**
     * Update Election.
     * 
     * @param Election $position
     * @return Election
     */
    public function update(Election $position);

    /**
     * Delete Election.
     * 
     * @param Election$position
     * @return bool
     */
    public function delete(Election$position);
}