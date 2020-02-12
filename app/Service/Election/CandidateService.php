<?php

namespace App\Service\Election;

use Session;
use RuntimeException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

use App\Models\Election\CandidateRegister;
use App\Repository\Election\CandidateRepository;
use App\Repository\Election\CandidateRegisterRepository;

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
     * Repository provide CandidateRegister.
     * 
     * @var CandidateRegisterRepository
     */
    protected $candidateRegisterRepository;

    /**
     * Create a new CandidateRegister Guard.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->guards = ['time' => time()];
        $this->candidateRepository = new CandidateRepository;
        $this->candidateRegisterRepository = new CandidateRegisterRepository;
    }

    /**
     * Open to anyone to Register.
     * 
     * @param array $data
     * @return CandidateRegister
     */
    public function CandidateRegister($data)
    {
        // If password set hash it.
        if(isset($data['password']))
            $data['password'] = Hash::make($data['password']);

        return $this->candidateRegisterRepository->create($data);
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
     * Get current CandidateRegister
     * 
     * @return CandidateRegister
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
     * @param CandidateRegister $candidate
     * @return UploadedFile
     */
    public function CandidateFileUpload(UploadedFile $file, CandidateRegister $candidate)
    {
        if(!$candidate)
            throw new RuntimeException('Candidate in guard is NULL.');

        $Path_To_Store= 'public/Candidate/Candidate_'.strval($candidate->id).'/';
        $FileName = 'Name_'.$candidate->Name.'.'.$file->clientExtension();

        return $file->storeAs($Path_To_Store, $FileName);
    }

    /**
     * Modify Candidate Register infomation.
     * 
     * @param array $data
     * @return CandidateRegister
     */
    public function CandidateModify($data, CandidateRegister $candidate)
    {
        if(isset($data['password']))
            $data['password'] = Hash::make($data['password']);

        return $this->candidateRegisterRepository->update($data, $candidate);
    }
    

    /**
     * Move CandidateRegister to Candidate.
     * 
     * @param integer $id
     * @return Candidate
     */
    public function CandidateSet($id)
    {
        if(!$candidateRegister = $this->candidateRegisterRepository->get($id))
            throw new RuntimeException('CandidateRegister not found.');

        $dataset = [
            'Name' => $candidateRegister->Name,
            'ElectionPosition' => $candidateRegister->ElectionPosition,
            'CandidateRegister' => $id
        ];

        return $this->candidateRepository->create($dataset);
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
     * @return CandidateRegisterGuard
     */
    protected function guard()
    {
        return Session::has($this->getGuardName())?Session::get($this->getGuardName()):NULL;
    }

    /**
     * Generate a new guard, and put in Session
     * 
     * @return CandidateRegisterGuard
     */
    protected function new_guard()
    {
        $guard = new CandidateRegisterGuard;
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