<?php

namespace App\Repository\Vote;

use Illuminate\Support\Facades\Validator;

use App\Exceptions\FormatNotMatchException;
use App\Exceptions\RelatedObjectNotFoundException;

use App\Contracts\Utility\VoteType;
use App\Models\Vote\VoteRecord;
use App\Models\Election\Candidate;
use App\Models\Election\ElectionPosition;

use App\Contracts\Repository\Vote\VoteRecordRepository as VoteRecordRepositoryContract;

class VoteRecordRepository implements VoteRecordRepositoryContract
{
    /**
     * Create Vote Record.
     * 
     * @param string $electionPosition
     * @param string $candidate
     * @param int $vote
     * 
     * @return VoteRecord
     * @throws \App\Exceptions\FormatNotMatchException
     */
    public function create($electionPosition, $candidate, $vote)
    {
        // Validate the param
        $datasets = [
            'ElectionPosition' => $electionPosition,
            'Candidate' => $candidate,
            'YN_Vote' => $vote,
            'broken' => false
        ];

        $validator = Validator::make($datasets, [
            'ElectionPosition' => ['required', 'string', 'max:64'],
            'Candidate' => ['required', 'string', 'max:64'],
            'YN_Vote' => ['required', 'integer'],
        ]);

        if($validator->fails())
            throw new FormatNotMatchException('VoteRecord create param format not match.');

        // Check election type
        $ep = ElectionPosition::where('UID', $electionPosition)->first();

        // Check Election Position is not exist
        if($ep == NULL)
            throw new RelatedObjectNotFoundException('Election Position object not found!');

        if($ep->ElectionType == VoteType::MULTIPLE_CHOICE)
            $datasets['YN_Vote'] = 0;

        if($ep->ElectionType == VoteType::YN_CHOICE)
        {
            // Check if vote is out of Y or N
            if( !($datasets['YN_Vote'] == 1 || $datasets['YN_Vote'] == 2) )
                $datasets['broken'] = true;
        }

        // Check Candidate is not exist
        if(!Candidate::isExist(['Candidate' => $candidate], true))
            throw new RelatedObjectNotFoundException('Candidate object not found!');

        return VoteRecord::create($datasets);
    }

    /**
     * Get Vote Record.
     * 
     * @param int $id
     * @return VoteRecord
     */
    public function get($id)
    {
        return VoteRecord::find($id);
    }

    /**
     * Get by Election Position
     * 
     * @param string $electionPosition
     * @return Illuminate\Support\Collection
     */
    public function getByElectionPosition($electionPosition)
    {
        return VoteRecord::where('ElectionPosition', $electionPosition)->get();
    }
}