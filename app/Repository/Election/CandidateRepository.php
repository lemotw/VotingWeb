<?php

namespace App\Repository\Election;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Str;
use RuntimeException;
use App\Exceptions\FormatNotMatchException;
use App\Exceptions\RelatedObjectNotFoundException;

use App\Models\Election\Candidate;
use App\Models\Election\ElectionPosition;
use App\Contracts\Repository\Election\CandidateRepository as CandidateRepositoryContract;

class CandidateRepository implements CandidateRepositoryContract
{
    /**
     * Get all Candidate.
     * 
     * @return Illuminate\Support\Collection
     */
    public function all()
    {
        return Candidate::all();
    }

    /**
     * Get Candidate.
     * 
     * @param string $Uid
     * @return Candidate
     */
    public function get($Uid)
    {
        return Candidate::find($Uid);
    }

    /**
     * Get by condition Candidate.
     * 
     * @param array $condition
     * @return Candidate
     */
    public function getBy($condition)
    {
        $entities = Candidate::whereNull('deleted_at');
        foreach($condition as $column => $data)
            $entities->where($column, $data);

        return $entities->get();
    }

    /**
     * Create Candidate.
     * 
     * @param array $data
     * @return Candidate
     */
    public function create($data)
    {
        //Check data valid or not.
        $validator = Validator::make($data, [
            'Name' => 'required|string|max:32',
            'account' => 'required|email|max:128',
            'password' => 'required|string|max:256'
        ]);

        if($validator->fails())
            throw new FormatNotMatchException('Candidate create param format not match.');

        $data['Candidate'] = hash('sha256', strval(time()).$data['Name'].'Candidate');

        return Candidate::create($data);
    }

    /**
     * Update Candidate.
     * 
     * @param array $data
     * @return Candidate
     */
    public function update($data)
    {
        //Check data valid or not.
        $validator = Validator::make($data, [
            'Candidate' => 'required|string|max:64',
            'Name' => 'string|max:32',
            'account' => 'email|max:128',
            'password' => 'string|max:256'
        ]);

        if($validator->fails())
            throw new FormatNotMatchException('Candidate update param format not match.');

        // Get Entity and update
        $entity = Candidate::find($data['Candidate']);
        if(!$entity->update($data))
            throw new RuntimeException('Candidate Eloquent update problem!');

        return $entity;
    }

    /**
     * Delete Candidate.
     * 
     * @param Candidate $candidate
     * @return bool
     */
    public function delete(Candidate $candidate)
    {
        return $candidate->delete();
    }

    /**
     * Fetch Entity by credential.
     * 
     * @param $array $credentials
     * @return Candidate
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
        $query = new Candidate;

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
     * @param Candidate $candidate
     * @param array $credentials
     * @return bool
     */
    public function validCredential(Candidate $candidate, $credentials)
    {
        return Hash::check($credentials['password'], $candidate->password);
    }
}