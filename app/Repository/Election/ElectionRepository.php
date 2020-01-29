<?php

namespace App\Repository\Election;

use Illuminate\Support\Facades\Validator;

use RuntimeException;
use App\Exceptions\FormatNotMatchException;

use App\Models\Election\Election;
use App\Contracts\Repository\Election\ElectionRepository as ElectionRepositoryContract;

class ElectionRepository implements ElectionRepositoryContract
{
    /**
     * Get all Election.
     * 
     * @return Illuminate\Support\Collection
     */
    public function all()
    {
        return Election::all();
    }

    /**
     * Get Election.
     * 
     * @param int $id
     * @return Election
     */
    public function get($id)
    {
        return Election::find($id);
    }

    /**
     * Get by condition Election.
     * 
     * @param array $condition
     * @return Election
     */
    public function getBy($condition)
    {
        $entities = Election::whereNull('deleted_at');
        foreach($condition as $column => $data)
            $entities->where($column, $data);

        return $entities->get();
    }

    /**
     * Create Election.
     * 
     * @param array $data
     * @return Election
     */
    public function create($data)
    {
        //Check data valid or not.
        $validator = Validator::make($data, [
            'Name' => 'required|string|max:32',
            'StartTime' => 'required|date',
            'EndTime' => 'required|date',
            'RegisterStart' => 'required|date',
            'RegisterEnd' => 'required|date',
            'VoteStart' => 'required|date',
            'VoteEnd' => 'required|date'
        ]);

        if($validator->fails())
            throw new FormatNotMatchException('Election create param format not match!');

        return Election::create($data);
    }

    /**
     * Update Election.
     * 
     * @param array $data
     * @return Election
     */
    public function update($data)
    {
        //Check data valid or not.
        $validator = Validator::make($data, [
            'id' => 'required|integer',
            'Name' => 'required|string|max:32',
            'StartTime' => 'required|date',
            'EndTime' => 'required|date',
            'RegisterStart' => 'required|date',
            'RegisterEnd' => 'required|date',
            'VoteStart' => 'required|date',
            'VoteEnd' => 'required|date'
        ]);

        if($validator->fails())
            throw new FormatNotMatchException('Election update param format not match!');

        // Get entity and update
        $entity = Election::find($data['id']);
        if(!$entity->update($data))
            throw new RuntimeException('Election Eloquent update failed.');

        return $entity;
    }

    /**
     * Delete Election.
     * 
     * @param Election $election
     * @return bool
     */
    public function delete(Election $election)
    {
        return $election->delete();
    }
}