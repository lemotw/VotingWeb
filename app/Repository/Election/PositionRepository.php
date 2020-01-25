<?php

namespace App\Repository\Election;

use Illuminate\Support\Facades\Validator;

use App\Models\Election\Position;
use App\Contracts\Repository\Election\PositionRepository as PositionRepositoryContract;

class PositionRepository implements PositionRepositoryContract
{
    /**
     * Get all Position
     * 
     * @return Illuminate\Support\Collection
     */
    public function all()
    {
        return Position::all();
    }

    /**
     * Get Position.
     * 
     * @param string $Uid
     * @return Position
     */
    public function get($Uid)
    {
        return Position::find($Uid);
    }

    /**
     * Get by condition Position.
     * 
     * @param array $condition
     * @return Position
     */
    public function getBy($condition)
    {
        $entities = Position::whereNull('deleted_at');
        foreach($condition as $column => $data)
            $entities = $entities->where($column, $data);

        return $entities->get();
    }

    /**
     * Create Position.
     * 
     * @param array $data
     * @return Position
     */
    public function create($data)
    {
        //Check data valid or not.
        $validator = Validator::make($data, [
            'Name' => 'required|string|max:32',
            'Unit' => 'nullable|string|max:32',
            'RequireDocument' => 'required|string'
        ]);

        if($validator->fails())
            return NULL;

        return Position::create($data);
    }

    /**
     * Update Position.
     * 
     * @param Positon $position
     * @return Position
     */
    public function update(Position $position)
    {
        //Check data valid or not.
        $validator = Validator::make($position->toArray(), [
            'Name' => 'required|string|max:32',
            'Unit' => 'nullable|string|max:32',
            'RequireDocument' => 'required|string'
        ]);

        if($validator->fails())
            return NULL;

        if(!$position->update())
            return NULL;

        return $position;
    }

    /**
     * Delete Position.
     * 
     * @param Position $position
     * @return bool
     */
    public function delete(Position $position)
    {
        return $position->delete();
    }
}