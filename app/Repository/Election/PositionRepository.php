<?php

namespace App\Repository\Election;

use Illuminate\Support\Facades\Validator;

use RuntimeException;
use App\Exceptions\FormatNotMatchException;

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
     * @param integer $id
     * @return Position
     */
    public function get($id)
    {
        return Position::find($id);
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
            'QualifyRegex' => 'required|string|max:128',
            'RequireDocument' => 'nullable|string'
        ]);

        if($validator->fails())
            throw new RuntimeException('資料格式問題');

        return Position::create($data);
    }

    /**
     * Update Position.
     * 
     * @param array $data
     * @return Position
     */
    public function update($data)
    {
        //Check data valid or not.
        $validator = Validator::make($data, [
            'id' => 'required|integer',
            'Name' => 'string|max:32',
            'Unit' => 'nullable|string|max:32',
            'QualifyRegex' => 'required|string|max:128',
            'RequireDocument' => 'nullable|string'
        ]);

        if($validator->fails())
            throw new RuntimeException('資料格式問題');

        // Get Entity and update
        $entity = Position::find($data['id']);
        if(!$entity->update($data))
            throw new RuntimeException('更新發生問題');

        return $entity;
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