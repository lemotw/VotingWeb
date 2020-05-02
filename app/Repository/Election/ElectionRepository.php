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
     * Get election continue.
     * 
     * @param Carbon $time
     * @return ICollection
     */
    public function ElectionHold($time)
    {
        return Election::where('StartTime', '<=', $time)
                       ->where('EndTime', '>=', $time)->get();
    }

    /**
     * Get Registering election.
     * 
     * @param Carbon $time
     * @retun ICollection
     */
    public function RegisterHold($time)
    {
        return Election::where('RegisterStart', '<=', $time)
                       ->where('RegisterEnd', '>=', $time)->get();
    }

    /**
     * Get Voting election.
     * 
     * @param Carbon $time
     * @return ICollection
     */
    public function VoteHold($time)
    {
        return Election::where('VoteStart', '<=', $time)
                       ->where('VoteEnd', '>=', $time)->get();
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
            throw new RuntimeException('資料格式問題');

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
            throw new RuntimeException('資料格式問題!');

        // Get entity and update
        $entity = Election::find($data['id']);
        if(!$entity->update($data))
            throw new RuntimeException('更新發生問題');

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