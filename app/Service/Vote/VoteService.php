<?php

namespace App\Service\Vote;

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
        $token = $this->authtokenRepository->get($hashedtoken);
        if($token == NULL)
            return NULL;

        $election = $token->ElectionEntity;
        if($election == NULL)
            return NULL;

        $votes = [];

        // iterate all ElectionPosition valid sid qualify
        $all_position = $election->ElectionPosition;
        foreach($all_position as $position)
        {
            // Check Position isn't NULL
            $pos = $position->PositionEntity;
            if($pos == NULL)
                continue;

            if($pos->validSid($token->sid))
                array_push($votes, $position);
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
            'ElectionPosition':1,
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
    public function CalculateVoteResult($electionPositionUID)
    {
        $electionPosition = $this->electionPositionRepository->getBy(['UID' => $electionPositionUID])->first();

        switch($electionPosition->ElectionType)
        {
            case VoteType::YN_CHOICE:
                $voteResult = $this->CalculateYNResult($electionPosition);
                $this->voteResultRepository->save([
                    'ElectionPosition' => $electionPosition->UID,
                    'Candidate' => $voteResult['candidate'],
                    'Yes' => $voteResult['Yes'],
                    'No' => $voteResult['No'],
                    'disable' => $voteResult['broken']
                ]);
                break;
            case VoteType::MULTIPLE_CHOICE:
                $voteResult = $this->CalculateMultipleResult($electionPosition);
                foreach($voteResult as $candidate => $result)
                {
                    $this->voteResultRepository->save([
                        'ElectionPosition' => $electionPosition->UID,
                        'Candidate' => $candidate,
                        'VoteCount' => $result
                    ]);
                }
                break;
            default:
                //LOG invalid Election Type
        }

        return $this->voteResultRepository->getByElectionPosition($electionPositionUID);
    }

    /**
     * Calculate Multiple choice election result.
     * 
     * @param ElectionPosition $electionPosition
     * @return Illuminate\Support\Collection
     */
    public function CalculateMultipleResult(ElectionPosition $electionPosition)
    {
        $candidates = $this->candidateRepository->getBy([
            'ElectionPosition' => $electionPosition->id
        ]);

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
        $candidates = $this->candidateRepository->getBy([
            'ElectionPosition' => $electionPosition->id
        ]);

        if($candidates->count() != 1)
            throw new RuntimeException('YN election candidate count error!!');

        // Update candidate set to Candidate UID
        $candidates = $candidates[0]->Candidate;

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