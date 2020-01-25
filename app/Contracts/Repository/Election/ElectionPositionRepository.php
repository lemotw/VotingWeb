<?php

namespace App\Contracts\Repository\Election;

use App\Models\Election\ElectionPosition;

interface ElectionPositionRepository
{
    /**
     * Get all Election Position.
     * 
     * @return Illuminate\Support\Collection
     */
    public function all();

    /**
     * Get Election Position.
     * 
     * @param int $Uid
     * @return ElectionPosition
     */
    public function get($id);

    /**
     * Get by condition Election Position.
     * 
     * @param array $condition
     * @return ElectionPosition
     */
    public function getBy($condition);

    /**
     * Create Election Position.
     * 
     * @param array $data
     * @return ElectionPosition
     */
    public function create($data);

    /**
     * Update Election Position.
     * 
     * @param ElectionPositon $position
     * @return ElectionPosition
     */
    public function update(ElectionPosition $position);

    /**
     * Delete Election Position.
     * 
     * @param ElectionPosition $position
     * @return bool
     */
    public function delete(ElectionPosition $position);
}