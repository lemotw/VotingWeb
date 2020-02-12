<?php

namespace App\Service\Election;

use Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Election\CandidateRegister;
use App\Repository\Election\CandidateRegisterRepository;

use App\Contracts\Service\Election\CandidateRegisterGuard as CandidateRegisterGuardContract;

class CandidateRegisterGuard implements CandidateRegisterGuardContract
{

    /**
     * Temp CandidateRegister by the guard.
     * 
     * @var App\Models\Election\CandidateRegister
     */
    protected $candidate;

    /**
     * The Repository used by the guard.
     * 
     * @var App\Contracts\Repository\Election\CandidateRegisterRepository
     */
    protected $CandidateRegisterRepository;


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
     * Create a new CandidateRegister Guard.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->candidate = NULL;
        $this->loggedOut = true;
        $this->CandidateRegisterRepository = new CandidateRegisterRepository();
    }

    /**
     * Attemp to CandidateRegister using given credential.
     * 
     * @param array $credentials
     * @return bool
     */
    public function attempt($credentials)
    {
        $validator = $this->CredentialValidator($credentials);
        if($validator->fails())
            return false;

        // Fetch CandidateRegister to attempt.
        $candidate = $this->CandidateRegisterRepository->fetchByCredential($credentials);

        if($this->CandidateRegisterRepository->validCredential($candidate, $credentials))
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
     * Update Session and set current CandidateRegister.
     * 
     * @param CandidateRegister $candidate
     * @return void
     */
    public function login($candidate)
    {
        $this->candidate = $candidate;
        $this->updateSession($candidate->id);
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
     * Update Session with CandidateRegister identify.
     * 
     * @param int $id
     * @return void
     */
    protected function updateSession($id)
    {
        Session::put($this->getName() ,$id);
        Session::migrate(true);
    }

    /**
     * Logout.
     * 
     * @return void
     */
    public function logout()
    {
        $this->clearCandidateRegisterData();
        $this->candidate = NULL;
        $this->loggedOut = true;
    }

    /**
     * Clear CandidateRegister data from session.
     * 
     * @return void
     */
    protected function clearCandidateRegisterData()
    {
        Session::remove($this->getName());
    }

    /**
     * Get current CandidateRegister
     * 
     * @return CandidateRegister
     */
    public function candidate()
    {
        if ($this->loggedOut) {
            return NULL;
        }

        // If we've already retrieved the candidate for the current request we can just
        // return it back immediately. We do not want to fetch the candidate data on
        // every call to this method because that would be tremendously slow.
        if (! is_null($this->candidate)) {
            return $this->candidate;
        }

        $id = Session::get($this->getName());

        // First we will try to load the user using the identifier in the session if
        // one exists. Otherwise we will check for a "remember me" cookie in this
        // request, and if one exists, attempt to retrieve the user using that.
        if (! is_null($id) && $this->candidate = $this->CandidateRegisterRepository->get($id))
            return $this->candidate;

        return $this->candidate;
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