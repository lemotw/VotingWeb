<?php

namespace App\Repository\Election;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use RuntimeException;
use App\Exceptions\FormatNotMatchException;
use App\Exceptions\RelatedObjectNotFoundException;

use App\Models\Election\Candidate;
use App\Models\Election\ElectionPosition;
use App\Models\Election\CandidateElectionPosition;
use App\Contracts\Repository\Election\CandidateElectionPositionRepository as CandidateElectionPositionRepositoryContract;

class CandidateElectionPositionRepository implements CandidateElectionPositionRepositoryContract
{
    /**
     * Get all CandidateElectionPosition.
     * 
     * @return Illuminate\Support\Collection
     */
    public function all()
    {
        return CandidateElectionPosition::all();
    }

    /**
     * Get CandidateElectionPosition.
     * 
     * @param integer $id
     * @return CandidateElectionPosition
     */
    public function get($id)
    {
        return CandidateElectionPosition::find($id);
    }

    /**
     * Get by condition CandidateElectionPosition.
     * 
     * @param array $condition
     * @return CandidateElectionPosition
     */
    public function getBy($condition)
    {
        $entities = CandidateElectionPosition::whereNull('deleted_at');
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
            'ElectionPosition' => ['required', 'integer'],
            'Candidate' => ['required', 'string', 'max:64'],
            'path' => ['string', 'max:128']
        ]);

        if($validator->fails())
            throw new FormatNotMatchException('Candidate create param format not match.');

        // Check if repeat create.
        $entity = CandidateElectionPosition::where('ElectionPosition', $data['ElectionPosition'])
                                            ->where('Candidate', $data['Candidate'])->first();
        if($entity)
            throw new RuntimeException('CandidateElectionPostion Exist!!');


        // Check Relationship
        if(!ElectionPosition::find($data['ElectionPosition']))
            throw new RelatedObjectNotFoundException('ElectionPosition not exist.');

        if(!Candidate::find($data['Candidate']))
            throw new RelatedObjectNotFoundException('Candidate not exist.');

        return CandidateElectionPosition::create($data);
    }

    /**
     * Update CandidateElectionPosition.
     * 
     * @param array $data
     * @return CandidateElectionPosition
     */
    public function update($data)
    {
        //Check data valid or not.
        $validator = Validator::make($data, [
            'id' => 'required|integer',
            'ElectionPosition' => 'integer',
            'Candidate' => 'integer',
            'path' => 'string|max:128'
        ]);

        if($validator->fails())
            throw new FormatNotMatchException('CandidateElectionPosition update param format not match.');

        if(!ElectionPosition::find($data['ElectionPosition']))
            throw new RelatedObjectNotFoundException('ElectionPosition not exist.');

        if(!Candidate::find($data['Candidate']))
            throw new RelatedObjectNotFoundException('Candidate not exist.');

        // Get Entity and update
        $entity = CandidateElectionPosition::find($data['id']);
        if(!$entity->update($data))
            throw new RuntimeException('CandidateElectionPosition Eloquent update problem!');

        return $entity;
    }

    /**
     * Delete CandidateElectionPosition.
     * 
     * @param CandidateElectionPosition $CEP
     * @return bool
     */
    public function delete(CandidateElectionPosition $CEP)
    {
        return $CEP->delete();
    }
}