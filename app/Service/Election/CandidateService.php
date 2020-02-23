<?php

namespace App\Service\Election;

use Session;
use Carbon\Carbon;
use RuntimeException;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

use App\Models\Election\Candidate;
use App\Repository\Election\CandidateRepository;
use App\Repository\Election\ElectionRepository;
use App\Repository\Election\ElectionPositionRepository;

use App\Contracts\Service\Election\CandidateService as CandidateServiceContract;

class CandidateService implements CandidateServiceContract
{

    /**
     * Array of guards.
     * 
     * @var array
     */
    protected $guards;

    /**
     * Repository provide Candidate.
     * 
     * @var CandidateRepository
     */
    protected $candidateRepository;

    /**
     * Repository provide Election.
     * 
     * @var ElectionRepository
     */
    protected $electionRepository;

    /**
     * Repository provide ElectionPosition.
     * 
     * @var ElectionPositionRepository
     */
    protected $electionPositionRepository;

    /**
     * Create a new Candidate Guard.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->guards = ['time' => time()];
        $this->candidateRepository = new CandidateRepository;
        $this->electionRepository = new ElectionRepository;
        $this->electionPositionRepository = new ElectionPositionRepository;
    }

    /**
     * Search Candidate.
     * 
     * @param array $condition
     * @return ICollection
     */
    public function CandidateSearch($condition)
    {
        /**
         * $condition = [
         *      'key' => $value,
         *      'key2' => $value2
         * ]
         */
        if(!$condition)
            return $this->candidateRepository->all();

        return $this->candidateRepository->getBy($condition);
    }

    /**
     * Get ElectionPosition that Candidate can Select.
     * 
     * @return ICollection
     */ 
    public function CandidatePositionSelection()
    {
        $time = Carbon::now();
        $elections = $this->electionRepository->RegisterHold($time);
        
        $positions = collect();
        foreach($elections as $election)
            $positions = $positions->concat($election->ElectionPositionEntity);

        return $positions;
    }

    /**
     * Open to anyone to Register.
     * 
     * @param array $data
     * @return Candidate
     */
    public function CandidateRegister($data)
    {
        // If password set hash it.
        if(isset($data['password']))
            $data['password'] = Hash::make($data['password']);

        return $this->candidateRepository->create($data);
    }

    /**
     * Get Login token.
     * 
     * @return string
     */
    public function CandidateLogin($credentials)
    {
        if(!$guard = $this->guard())
            $guard = $this->new_guard();

        return $guard->attempt($credentials);
    }

    /**
     * Get current Candidate
     * 
     * @return Candidate
     */
    public function Candidate()
    {
        if(!$guard = $this->guard())
            return NULL;

        return $guard->candidate();
    }

    /**
     * Upload file.
     * 
     * @param UploadedFile $file
     * @param Candidate $candidate
     * @return UploadedFile
     */
    public function CandidateFileUpload(UploadedFile $file, Candidate $candidate)
    {
        if(!$candidate)
            throw new RuntimeException('Candidate in guard is NULL.');

        $Path_To_Store= 'public/Candidate/Candidate_'.strval($candidate->id).'/';
        $FileName = 'Name_'.$candidate->Name.'.'.$file->clientExtension();

        return $file->storeAs($Path_To_Store, $FileName);
    }

    /**
     * Modify Candidate infomation.
     * 
     * @param array $data
     * @return Candidate
     */
    public function CandidateModify($data, Candidate $candidate = null)
    {
        if(isset($data['password']))
            $data['password'] = Hash::make($data['password']);

        return $this->candidateRepository->update($data);
    }
    

    /**
     * Move Candidate to Candidate.
     * 
     * @param string $uid
     * @return Candidate
     */
    public function CandidateSet($uid)
    {
    }

    /**
     * Delete Candidate.
     * 
     * @param string $Candidate
     * @return bool
     */
    public function CandidateDelete($Candidate)
    {
        if(!$cand = $this->candidateRepository->get($Candidate))
            throw new RuntimeException('Candidate not found.');

        return $cand->delete();
    }

    /**
     * Check if guard login.
     * 
     * @return bool
     */
    protected function isLogin()
    {
        if($guard = $this->guard())
            return $guard->isLogin();

        return false;
    }

    /**
     * Get current session guard.
     * 
     * @return CandidateGuard
     */
    protected function guard()
    {
        return Session::has($this->getGuardName())?Session::get($this->getGuardName()):NULL;
    }

    /**
     * Generate a new guard, and put in Session
     * 
     * @return CandidateGuard
     */
    protected function new_guard()
    {
        $guard = new CandidateGuard;
        Session::put($this->getGuardName(), $guard);
        return $guard;
    }

    /**
     * Get guard Name.
     * 
     * @return string
     */
    protected function getGuardName()
    {
        return 'CandidateService_'.sha1(static::class).'_guard';
    }
}