<?php

namespace App\Service\Election;

use Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Election\Candidate;
use App\Repository\Election\CandidateRepository;

use App\Contracts\Service\Election\CandidateGuard as CandidateGuardContract;

class CandidateGuard implements CandidateGuardContract
{

    /**
     * Temp Candidate by the guard.
     * 
     * @var App\Models\Election\Candidate
     */
    protected $candidate;

    /**
     * The Repository used by the guard.
     * 
     * @var App\Contracts\Repository\Election\CandidateRepository
     */
    protected $CandidateRepository;


    /**
     * Flag for logout.
     * 
     * @var bool
     */
    protected $loggedOut;

    /**
     * session for guard.
     * 
     * @var Illuminate\Session\Store
     */
    protected $session;

    /**
     * Create a new Candidate Guard.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->candidate = NULL;
        $this->loggedOut = true;
        $this->CandidateRepository = new CandidateRepository();
    }

    /**
     * Attemp to Candidate using given credential.
     * 
     * @param array $credentials
     * @return bool
     */
    public function attempt($credentials)
    {
        $validator = $this->CredentialValidator($credentials);
        if($validator->fails())
            return false;

        // Fetch Candidate to attempt.
        $candidate = $this->CandidateRepository->fetchByCredential($credentials);

        if($this->CandidateRepository->validCredential($candidate, $credentials))
        {
            $this->login($candidate);
            return true;
        }

        return false;
    }

    /**
     * Validator for credential.
     * 
     * @param array $credentials
     * @return bool
     */
    public function CredentialValidator($credentials)
    {
        return Validator::make($credentials, [
            'account' => ['required', 'string', 'max:128'],
            'password' => ['required', 'string']
        ]);
    }

    /**
     * Update Session and set current Candidate.
     * 
     * @param Candidate $candidate
     * @return void
     */
    public function login($candidate)
    {
        $this->candidate = $candidate;
        $this->updateSession($candidate->Candidate);
        $this->loggedOut = false;
    }

    /**
     * Check is guard login.
     * 
     * @return bool
     */
    public function isLogin()
    {
        return !$this->loggedOut;
    }

    /**
     * Update Session with Candidate identify.
     * 
     * @param string $UID
     * @return void
     */
    protected function updateSession($UID)
    {
        Session::put($this->getName() ,$UID);
        Session::migrate(true);
    }

    /**
     * Logout.
     * 
     * @return void
     */
    public function logout()
    {
        $this->clearCandidateData();
        $this->candidate = NULL;
        $this->loggedOut = true;
    }

    /**
     * Clear Candidate data from session.
     * 
     * @return void
     */
    protected function clearCandidateData()
    {
        Session::remove($this->getName());
    }

    /**
     * Get current Candidate
     * 
     * @return Candidate
     */
    public function candidate()
    {
        if ($this->loggedOut) {
            return NULL;
        }

        $UID = Session::get($this->getName());
        // If we've already retrieved the candidate for the current request we can just
        // return it back immediately. We do not want to fetch the candidate data on
        // every call to this method because that would be tremendously slow.
        if (!is_null($this->candidate)) {
            return $this->candidate;
        }

        // First we will try to load the user using the identifier in the session if
        // one exists. Otherwise we will check for a "remember me" cookie in this
        // request, and if one exists, attempt to retrieve the user using that.
        if (!is_null($UID) && $this->candidate = $this->CandidateRepository->get($UID))
            return $this->candidate;

        return $this->candidate;
    }

    /**
     * Refresh Candidate from database.
     * 
     * @return Candidate
     */
    public function refreshCandidate()
    {
        if(!$candidate = $this->candidate())
            return NULL;

        return $candidate->refresh();
    }

    /**
     * Get guard name.
     * 
     * @return string
     */
    protected function getName()
    {
        return 'login_'.sha1(static::class);
    }
}