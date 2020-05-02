<?php

namespace App\Repository\Election;

use Illuminate\Support\Facades\Validator;

use RuntimeException;
use App\Exceptions\FormatNotMatchException;
use App\Exceptions\RelatedObjectNotFoundException;

use App\Models\Election\Election;
use App\Models\Election\Position;
use App\Models\Election\ElectionPosition;
use App\Contracts\Repository\Election\ElectionPositionRepository as ElectionPositionRepositoryContract;

class ElectionPositionRepository implements ElectionPositionRepositoryContract
{
    /**
     * Get all Election Position.
     * 
     * @return Illuminate\Support\Collection
     */
    public function all()
    {
        return ElectionPosition::all();
    }

    /**
     * Get Election Position.
     * 
     * @param integer $id
     * @return ElectionPosition
     */
    public function get($id)
    {
        return ElectionPosition::find($id);
    }

    /**
     * Get by condition Election Position.
     * 
     * @param array $condition
     * @return ElectionPosition
     */
    public function getBy($condition)
    {
        $entities = ElectionPosition::whereNull('deleted_at');
        foreach($condition as $column => $data)
            $entities->where($column, $data);

        return $entities->get();
    }

    /**
     * Create Election Position.
     * 
     * @param array $data
     * @return ElectionPosition
     */
    public function create($data)
    {
        //Check data valid or not.
        $validator = Validator::make($data, [
            'Name' => 'required|string|max:32',
            'Election' => 'required|integer',
            'Position' => 'required|integer'
        ]);

        if($validator->fails())
            throw new RuntimeException('資料格式問題');

        //Check Position and Election is exist or not.
        if(Election::find($data['Election']) == NULL || Position::find($data['Position']) == NULL)
            throw new RuntimeException('職位找尋不道');

        $data['UID'] = hash('sha256', strval(time()).$data['Name'].'Position');
        $data['ElectionType'] = 1;

        return ElectionPosition::create($data);
    }

    /**
     * Update Election Position.
     * 
     * @param array $data
     * @return ElectionPosition
     */
    public function update($data)
    {
        //Check data valid or not.
        $validator = Validator::make($data, [
            'Name' => 'string|max:32',
            'Election' => 'integer',
            'Position' => 'integer'
        ]);

        if($validator->fails())
            throw new RuntimeException('資料格式問題');

        if(!isset($data['UID']) && !isset($data['id']))
            throw new RuntimeException('職位未設定好');

        // Get Entity and update
        if(isset($data['UID']))
            $entity = ElectionPosition::where('UID', $data['UID'])->first();

        if(isset($data['id']))
            $entity = ElectionPosition::find($data['id']);

        if(!$entity->update($data))
            throw new RuntimeException('更新發生問題');

        return $entity;
    }

    /**
     * Delete Election Position.
     * 
     * @param ElectionPosition $position
     * @return bool
     */
    public function delete(ElectionPosition $position)
    {
        return $position->delete();
    }
}