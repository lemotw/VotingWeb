<?php

namespace App\Repository\Vote;

use Illuminate\Support\Facades\Validator;

use RuntimeException;
use App\Exceptions\FormatNotMatchException;
use App\Exceptions\RelatedObjectNotFoundException;

use App\Contracts\Utility\VoteType;
use App\Models\Vote\VoteRecord;
use App\Models\Vote\VoteResult;
use App\Models\Election\Candidate;
use App\Models\Election\ElectionPosition;

use App\Contracts\Repository\Vote\VoteResultRepository as VoteResultRepositoryContract;

class VoteResultRepository implements VoteResultRepositoryContract
{
    /**
     * Create Vote Result.
     * 
     * @param array $data
     * @return VoteResult
     * @throws \App\Exceptions\FormatNotMatchException
     */
    public function create($data)
    {
        $validator = Validator::make($data, [
            'ElectionPosition' => ['required', 'string', 'max:64'],
            'Candidate' => ['required', 'string', 'max:64'],
            'VoteCount' => ['integer'],
            'Yes' => ['integer'],
            'No' => ['integer'],
            'disable' => ['integer']
        ]);

        if($validator->fails())
            throw new FormatNotMatchException('VoteRecord create param format not match.');

        if(!ElectionPosition::isExist(['UID' => $data['ElectionPosition']], true))
            throw new RelatedObjectNotFoundException('ElectionPosition object not found!');

        if(Candidate::isExist(['Candidate' => $data['Candidate']], true))
        {
            // Check Candidate ElectionPosition is the same as data
            $CandidateEntity = Candidate::find($data['Candidate']);
            $data['ElectionPosition'] = $CandidateEntity->ElectionPositionEntity->UID;
        } else if($data['Candidate'] != 'broken')
            throw new RelatedObjectNotFoundException('Candidate object not found!');


        return VoteResult::create($data);
    }

    /**
     * Update Vote Result.
     * 
     * @param array $data
     * @return VoteResult
     */
    public function update($data)
    {
        // Get Vote Result Entity
        $voteResult = VoteResult::where('ElectionPosition', $data['ElectionPosition'])
                                ->where('Candidate', $data['Candidate'])->first();
        if($voteResult != NULL)
            throw new RuntimeException('VoteResult not found!');

        // Check ElectionPosition Exist
        if(!ElectionPosition::isExist(['UID' => $data['ElectionPosition']], true))
            throw new RelatedObjectNotFoundException('ElectionPosition object not found!');

        // Check Candidate Exist
        if(Candidate::isExist(['Candidate' => $data['Candidate']], true))
        {
            $CandidateEntity = Candidate::find($data['Candidate']);
            $data['ElectionPosition'] = $CandidateEntity->ElectionPositionEntity->UID;
        } else if($data['Candidate'] != 'broken')
            throw new RelatedObjectNotFoundException('Candidate object not found!');

        // Update VoteResult
        if(!$voteResult->update($data))
            throw new RuntimeException('VoteResult update problem!');

        return $voteResult;
    }

    /**
     * Save VoteResult, if not exist create new one.
     * 
     * @param array $data
     * @return VoteRecord
     */
    public function save($data)
    {
        $validator = Validator::make($data, [
            'ElectionPosition' => ['required', 'string', 'max:64'],
            'Candidate' => ['required', 'string', 'max:64'],
            'VoteCount' => ['integer'],
            'Yes' => ['integer'],
            'No' => ['integer'],
            'disable' => ['integer']
        ]);

        if($validator->fails())
            throw new FormatNotMatchException('VoteRecord create param format not match.');

        // if not exist create new one
        if(!$this->isExist([
            'ElectionPosition' => $data['ElectionPosition'],
            'Candidate' => $data['Candidate']
        ]))
            return $this->create($data);

        // Check Relationship
        if(!ElectionPosition::isExist(['UID' => $data['ElectionPosition']], true))
            throw new RelatedObjectNotFoundException('ElectionPosition object not found!');

        if(Candidate::isExist(['Candidate' => $data['Candidate']], true))
        {
            $CandidateEntity = Candidate::find($data['Candidate']);
            $data['ElectionPosition'] = $CandidateEntity->ElectionPositionEntity->UID;
        } else if($data['Candidate'] != 'broken')
            throw new RelatedObjectNotFoundException('Candidate ' . $data['Candidate'] . ' object not found!');

        // Get Entity and update
        $result = VoteResult::where('ElectionPosition', $data['ElectionPosition'])
                            ->where('Candidate', $data['Candidate'])->first();

        $result->VoteCount = isset($data['VoteCount'])? $data['VoteCount']:0;
        $result->Yes = isset($data['Yes'])? $data['Yes']:0;
        $result->No = isset($data['No'])? $data['No']:0;
        $result->disable = isset($data['disable'])? $data['disable']:0;

        return $result->update();
    }

    /**
     * Get Vote Result.
     * 
     * @param int $id
     * @return VoteResult
     */
    public function get($id)
    {
        return VoteResult::find($id);
    }

    /**
     * Check entity exist.
     * 
     * @param array $data
     * @return bool
     */
    public function isExist($data)
    {
        $entities = VoteResult::whereNull('deleted_at');

        foreach($data as $key => $value)
            $entities->where($key, $value);

        return !$entities->get()->isEmpty();
    }

    /**
     * Get by Election Position
     * 
     * @param string $electionPosition
     * @return Illuminate\Support\Collection
     */
    public function getByElectionPosition($electionPosition)
    {
        return VoteResult::where('ElectionPosition', $electionPosition)->get();
    }
}