<?php

namespace App\Repository\Election;

use Illuminate\Support\Facades\Validator;

use RuntimeException;
use App\Exceptions\FormatNotMatchException;
use App\Exceptions\RelatedObjectNotFoundException;

use App\Models\Election\Candidate;
use App\Models\Election\CandidateRegister;
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
            'ElectionPosition' => 'required|integer',
            'CandidateRegister' => 'required|integer'
        ]);

        if($validator->fails())
            throw new FormatNotMatchException('Candidate create param format not match.');

        //Check ElectionPosition is exist or not.
        if(ElectionPosition::find($data['ElectionPosition']) == NULL)
            throw new RelatedObjectNotFoundException('Election Position object not found!');

        //Check CandidateRegister is exist or not
        if(CandidateRegister::find($data['CandidateRegister']) == NULL)
            throw new RelatedObjectNotFoundException('CandidateRegister object not found!');

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
            'ElectionPosition' => 'integer',
            'CandidateRegister' => 'integer'
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
}