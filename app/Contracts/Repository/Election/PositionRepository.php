<?php

namespace App\Contracts\Repository\Election;

use App\Models\Election\Position;

interface PositionRepository
{
    /**
     * Get all Position.
     * 
     * @return Illuminate\Support\Collection
     */
    public function all();

    /**
     * Get Position.
     * 
     * @param string $Uid
     * @return Position
     */
    public function get($Uid);

    /**
     * Get by condition Position.
     * 
     * @param array $condition
     * @return Position
     */
    public function getBy($condition);

    /**
     * Create Position.
     * 
     * @param array $data
     * @return Position
     */
    public function create($data);

    /**
     * Update Position.
     * 
     * @param Positon $position
     * @return Position
     */
    public function update(Position $position);

    /**
     * Delete Position.
     * 
     * @param Position $position
     * @return bool
     */
    public function delete(Position $position);
}