<?php

namespace App\Repository\Election;

use Illuminate\Support\Facades\Validator;

use RuntimeException;
use App\Exceptions\FormatNotMatchException;
use App\Exceptions\RelatedObjectNotFoundException;

use App\Models\Election\ElectionPosition;
use App\Models\Election\CandidateRegister;
use App\Contracts\Repository\Election\CandidateRegisterRepository as CandidateRegisterRepositoryContract;

class CandidateRegisterRepository implements CandidateRegisterRepositoryContract
{
    /**
     * Get all Candidate Register.
     * 
     * @return Illuminate\Support\Collection
     */
    public function all()
    {
        return CandidateRegister::all();
    }

    /**
     * Get Candidate Register.
     * 
     * @param int $id
     * @return CandidateRegister
     */
    public function get($id)
    {
        return CandidateRegister::find($id);
    }

    /**
     * Get by condition Candidate Register.
     * 
     * @param array $condition
     * @return CandidateRegister
     */
    public function getBy($condition)
    {
        $entities = CandidateRegister::whereNull('deleted_at');
        foreach($condition as $column => $data)
            $entities->where($column, $data);

        return $entities->get();
    }

    /**
     * Create Candidate Register.
     * 
     * @param array $data
     * @return CandidateRegister
     */
    public function create($data)
    {
        //Check data valid or not.
        $validator = Validator::make($data, [
            'Name' => 'required|string|max:32',
            'account' => 'string|max:128',
            'password' => 'string|max:256',
            'ElectionPosition' => 'integer'
        ]);

        if($validator->fails())
            throw new FormatNotMatchException('CandidateRegister create param format not match.');

        //Check ElectionPosition is exist or not.
        if(ElectionPosition::find($data['ElectionPosition']) == NULL)
            throw new RelatedObjectNotFoundException('Election Position object not found!');

        return CandidateRegister::create($data);
    }

    /**
     * Update Candidate Register.
     * 
     * @param array $data
     * @return CandidateRegister
     */
    public function update($data)
    {
        //Check data valid or not.
        $validator = Validator::make($data, [
            'id' => 'required|integer',
            'Name' => 'required|string|max:32',
            'account' => 'required|string|max:128',
            'password' => 'required|string|max:256',
            'ElectionPosition' => 'required|integer'
        ]);

        if($validator->fails())
            throw new FormatNotMatchException('CandidateRegister update param format not match.');

        //Check ElectionPosition is exist or not.
        if(ElectionPosition::find($data['ElectionPosition']) == NULL)
            throw new RelatedObjectNotFoundException('Election Position object not found!');

        // Get Entity and update
        $entity = CandidateRegister::find($data['id']);
        if(!$entity->update($data))
            throw new RuntimeException('CandidateRegister Eloquent update problem!');

        return $entity;
    }

    /**
     * Delete Candidate Register.
     * 
     * @param CandidateRegister $candidate
     * @return bool
     */
    public function delete(CandidateRegister $candidate)
    {
        return $candidate->delete();
    }
}