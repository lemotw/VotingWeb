<?php

namespace App\Repository\Election;

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
            'Candidate' => 'required|string|max:64',
            'Name' => 'required|string|max:32',
            'ElectionPosition' => 'required|integer',
            'File' => 'required|string|max:64'
        ]);

        if($validator->fails())
            return NULL;

        //Check ElectionPosition is exist or not.
        if(ElectionPosition::find($data['ElectionPosition']) == NULL)
            return NULL;

        $data['Candidate'] = hash('sha256', strval(time()).$data['Name'].'Candidate');

        return Candidate::create($data);
    }

    /**
     * Update Candidate.
     * 
     * @param Candidate $candidate
     * @return Candidate
     */
    public function update(Candidate $candidate)
    {
        //Check data valid or not.
        $validator = Validator::make($candidate->toArray(), [
            'Candidate' => 'required|string|max:64',
            'Name' => 'required|string|max:32',
            'ElectionPosition' => 'required|integer',
            'File' => 'required|string|max:64'
        ]);

        if($validator->fails())
            return NULL;

        if(!$candidate->update())
            return NULL;

        return $candidate;
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
}