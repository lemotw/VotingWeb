<?php

namespace App\Service\Vote;

use Carbon\Carbon;
use RuntimeException;
use Illuminate\Support\Facades\Validator;

use App\Contracts\Utility\VoteType;
use App\Contracts\Utility\YesNoVote;
use App\Service\Encryption\Encrypter;
use App\Service\Vote\VotePayloadException;
use App\Exceptions\RelatedObjectNotFoundException;

use App\Models\Vote\AuthToken;
use App\Models\Election\Election;
use App\Models\Election\Candidate;
use App\Models\Election\ElectionPosition;
use App\Repository\Vote\AuthTokenRepository;
use App\Repository\Vote\VoteRecordRepository;
use App\Repository\Vote\VoteResultRepository;
use App\Repository\Election\ElectionRepository;
use App\Repository\Election\CandidateRepository;
use App\Repository\Election\CandidateElectionPositionRepository;
use App\Repository\Election\ElectionPositionRepository;

use App\Contracts\Service\Vote\VoteService as VoteServiceContract;

class VoteService implements VoteServiceContract
{
    /**
     * Access AuthToken.
     * @var App\Repository\Vote\AuthTokenRepository
     */
    protected $authtokenRepository;
    
    /**
     * Access Election.
     * @var \App\Repository\Election\ElectionRepository
     */
    protected $electionRepository;

    /**
     * Access VoteRecord.
     * @var \App\Repository\Vote\VoteRecordRepository
     */
    protected $voteRecordRepository;

     /**
     * Access VoteResult.
     * @var \App\Repository\Vote\VoteResultRepository
     */
    protected $voteResultRepository;
   
    /**
     * Access ElectionPosition.
     * @var \App\Repository\Election\ElectionPositionRepository
     */
    protected $electionPositionRepository;

    /**
     * Access Candidates.
     * @var \App\Repository\Election\CandidateRepository
     */
    protected $candidateRepository;

    /**
     * Access CandidatesElectionPosition.
     * @var \App\Repository\Election\CandidateElectionPositionRepository
     */
    protected $candidateElectionPositionRepository;

    /**
     * Provide Encrypter to Service.
     * @var App\Service\Encrypter
     */
    protected $encrypter;

    /**
     * Create a new encrypter instance.
     *
     * @return void
     *
     * @throws \RuntimeException
     */
    public function __construct()
    {
        $this->electionRepository = new ElectionRepository();
        $this->authtokenRepository = new AuthTokenRepository();
        $this->candidateRepository = new CandidateRepository();
        $this->candidateElectionPositionRepository = new CandidateElectionPositionRepository();
        $this->voteRecordRepository = new VoteRecordRepository();
        $this->voteResultRepository = new VoteResultRepository();
        $this->electionPositionRepository = new ElectionPositionRepository();
        $this->encrypter = new Encrypter('r0C64xMXvoL15/dxlAu/asD+3O8roI0Y2tkaQ4F94e0=');
    }

    /**
     * Check vote qualify.
     * 
     * @param string $hashedtoken
     * @return Illuminate\Support\Collection
     */
    public function GetVotes($hashedtoken)
    {
        // Get token entity to get election.
        if(!$token = $this->authtokenRepository->get($hashedtoken))
            throw new RuntimeException('找不道卡號，請確認是否至驗票台');

        if(!$election = $token->ElectionEntity)
            throw new RuntimeException('找不道選舉，請向選舉人員反應');

        if($token->Voted)
            throw new RuntimeException('您已投過票了，請離開投票亭');

        $now = Carbon::now();
        if($election->VoteStart > $now || $election->VoteEnd < $now)
            throw new RuntimeException('已超過投票時間，請向選舉人緣確認');
        
        $votes = collect();

        // iterate all ElectionPosition valid sid qualify
        $all_position = $election->ElectionPositionEntity;
        foreach($all_position as $position)
        {
            // Check Position isn't NULL
            if(!$pos = $position->PositionEntity)
                continue;

            if($pos->validSid($token->sid))
            {
                $push_array = $position->toArray();

                // Get Candidates by ElectionPosition id and CandidateSet flag
                $candidates = $this->candidateElectionPositionRepository->getBy([
                    'ElectionPosition' => $position->id,
                    'CandidateSet' => true
                ]);

                // Set election type
                if($candidates->count() > 1)
                {
                    $push_array['_type'] = 'multiple';
                    $push_array['_candidate'] = $candidates;
                } else {
                    $push_array['_type'] = 'single';
                    $push_array['_candidate'] = $candidates[0];
                }

                $votes->push($push_array);
            }
        }

        return $votes;
    }

    /**
     * Vote to Candidate.
     * 
     * @param string $encryptStr
     * @param string $key
     * @return bool
     * @throws RuntimeException
     */
    public function vote($encryptStr, $key)
    {
        // Decrypt string by table key
        $this->encrypter->setKey($key);
        $json_str = $this->encrypter->decrypt($encryptStr);

        $payload = json_decode($json_str, true);
        /*
        Json Vote Format
        {
            'token':'testtoken123456',
            'ElectionPosition': 'ElectionPositionUIDHere',
            'Candidate':'CandidateUIDHere',

            if YN vote
            'vote':1
        }
        */

        // Get ElectionPosition to get Election type.
        if(!isset($payload['ElectionPosition']))
            throw new VotePayloadException('Payload qualify Problem');

        $electionPosition = $this->electionPositionRepository->getBy(['UID' => $payload['ElectionPosition']])->first();
        if($electionPosition == NULL)
            throw new RelatedObjectNotFoundException('ElectionPosition object not found!');

        // Set vote to 0 at MULTIPLE_CHOICE case
        if($electionPosition->ElectionType == VoteType::MULTIPLE_CHOICE)
            $payload['vote'] = 0;

        // Validate payload.
        $validatorStr = [
            'token' => ['required', 'string', 'max:128'],
            'ElectionPosition' => ['required', 'string', 'max:64'],
            'Candidate' => ['required', 'string', 'max:64'],
            'vote' => ['required', 'integer']
        ];
        $validator = Validator::make($payload, $validatorStr);

        if($validator->fails())
            throw new VotePayloadException('Payload qualify Problem');

        if(!$token = $this->authtokenRepository->get($payload['token']))
            return NULL;

        $this->authtokenRepository->setVoted($token);

        // Except 'broken' special case, Candidate need exist.
        if(!Candidate::isExist(['Candidate' => $payload['Candidate']]))
            if($payload['Candidate'] != 'broken')
                throw new RelatedObjectNotFoundException('Candidate object not found!');

        // Create Vote Record
        return $this->voteRecordRepository->create($payload['ElectionPosition'], $payload['Candidate'], $payload['vote']);
    }

    /**
     * Vote Result Calculate.
     * 
     * @param string $electionPositionUID
     * @return VoteResult
     */
    public function CalculateVoteResult($electionPositionID)
    {
        $electionPosition = $this->electionPositionRepository->getBy(['id' => $electionPositionID])->first();

        if($electionPosition->CandidateElectionPosition->count() > 1)
        {
            $voteResult = $this->CalculateMultipleResult($electionPosition);
            foreach($voteResult as $candidate => $result)
            {
                $this->voteResultRepository->save([
                    'ElectionPosition' => $electionPosition->UID,
                    'Candidate' => $candidate,
                    'VoteCount' => $result
                ]);
            }
        } else {
            $voteResult = $this->CalculateYNResult($electionPosition);
            $this->voteResultRepository->save([
                'ElectionPosition' => $electionPosition->UID,
                'Candidate' => $voteResult['candidate'],
                'Yes' => $voteResult['Yes'],
                'No' => $voteResult['No'],
                'disable' => $voteResult['broken']
            ]);
        }

        return $this->voteResultRepository->getByElectionPosition($electionPosition->UID);
    }

    /**
     * Calculate Multiple choice election result.
     * 
     * @param ElectionPosition $electionPosition
     * @return Illuminate\Support\Collection
     */
    public function CalculateMultipleResult(ElectionPosition $electionPosition)
    {
        $candidates = $electionPosition->CandidateElectionPosition;
        // $this->candidateRepository->getBy([
        //     'ElectionPosition' => $electionPosition->id
        // ]);

        // Initial VoteCount array set
        $VoteCount = ['broken' => 0];
        foreach($candidates as $candidate)
            $VoteCount[$candidate->Candidate] = 0;

        // Get VoteRecord list and calculate
        $VoteRecordSet = $this->voteRecordRepository->getByElectionPosition($electionPosition->UID);
        foreach($VoteRecordSet as $voterecord)
        {
            if($voterecord->broken || $voterecord->Candidate == 'broken' || !isset($VoteCount[$voterecord->Candidate]))
                $VoteCount['broken']++;
            else
                $VoteCount[$voterecord->Candidate]++;
        }

        return $VoteCount;
    }

    /**
     * Calculate YN choice election result.
     * 
     * @param ElectionPosition $electionPosition
     * @return VoteResult
     */
    public function CalculateYNResult(ElectionPosition $electionPosition)
    {
        // Check Candidate count
        $candidates = $electionPosition->CandidateElectionPosition;
        // $candidates = $this->candidateRepository->getBy([
        //     'ElectionPosition' => $electionPosition->id
        // ]);

        if($candidates->count() != 1)
            throw new RuntimeException('YN election candidate count error!!');

        // Update candidate set to Candidate UID
        $candidates = $candidates->fist()->Candidate;

        // Initial vote result set
        $voteresult = [
            'Yes' => 0,
            'No' => 0,
            'broken' => 0,
            'candidate' => $candidates
        ];

        // Get VoteRecord list and calculate
        $VoteRecordSet = $this->voteRecordRepository->getByElectionPosition($electionPosition->UID);
        foreach($VoteRecordSet as $voterecord)
        {
            if($voterecord->Candidate != $candidates)
            {
                $voteresult['broken']++;
                continue;
            }

            // Switch Yes No or broken index
            $index = '';
            switch($voterecord->YN_Vote)
            {
                case YesNoVote::no:
                    $index = 'No';
                    break;
                case YesNoVote::yes:
                    $index = 'Yes';
                    break;
                default:
                    $index = 'broken';
            }

            $voteresult[$index]++;
        }

        return $voteresult;
    }
}