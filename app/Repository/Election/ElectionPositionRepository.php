<?php

namespace App\Repository\Election;

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
     * @param string $Uid
     * @return ElectionPosition
     */
    public function get($Uid)
    {
        return ElectionPosition::where('UID', $Uid)->first();
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
            return NULL;

        //Check Position and Election is exist or not.
        if(Election::find($data['Election']) == NULL || Position::find($data['Position']) == NULL)
            return NULL;

        $data['UID'] = hash('sha256', strval(time()).$data['Name'].'Position');

        return ElectionPosition::create($data);
    }

    /**
     * Update Election Position.
     * 
     * @param ElectionPositon $position
     * @return ElectionPosition
     */
    public function update(ElectionPosition $position)
    {
        //Check data valid or not.
        $validator = Validator::make($position->toArray(), [
            'Name' => 'required|string|max:32',
            'UID' => 'required|string|max:64',
            'Election' => 'required|integer',
            'Position' => 'required|integer'
        ]);

        if($validator->fails())
            return NULL;

        if(!$position->update())
            return NULL;

        return $position;
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