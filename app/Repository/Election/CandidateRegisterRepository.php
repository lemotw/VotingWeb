<?php

namespace App\Repository\Election;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
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
     * @param CandidateRegister $candidate
     * @return CandidateRegister
     */
    public function update($data, CandidateRegister $candidate = NULL)
    {
        //Check data valid or not.
        $validator = Validator::make($data, [
            'id' => 'integer',
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
        if(!$candidate && isset($data['id']))
            $entity = CandidateRegister::find($data['id']);
        else if($candidate)
            $entity = $candidate;

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

    /**
     * Fetch Entity by credential.
     * 
     * @param $array $credentials
     * @return CandidateRegister
     */
    public function fetchByCredential($credentials)
    {
        if (empty($credentials) ||
           (count($credentials) === 1 &&
            array_key_exists('password', $credentials))) {
            return;
        }
        // First we will add each credential element to the query as a where clause.
        // Then we can execute the query and, if we found a user, return it in a
        // Eloquent User "model" that will be utilized by the Guard instances.
        $query = new CandidateRegister;

        foreach ($credentials as $key => $value) {
            if (Str::contains($key, 'password')) {
                continue;
            }

            if (is_array($value)) {
                $query = $query->whereIn($key, $value);
            } else {
                $query = $query->where($key, $value);
            }
        }
        return $query->first();
    }

    /**
     * Valid credential.
     * 
     * @param CandidateRegister $candidate
     * @param array $credentials
     * @return bool
     */
    public function validCredential(CandidateRegister $candidate, $credentials)
    {
        return Hash::check($credentials['password'], $candidate->password);
    }
}