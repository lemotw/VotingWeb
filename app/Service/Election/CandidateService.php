<?php

namespace App\Service\Election;

use Session;
use Storage;
use Carbon\Carbon;

use RuntimeException;
use App\Exceptions\RelatedObjectNotFoundException;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

use App\Models\Election\Candidate;
use App\Models\Election\ElectionPosition;
use App\Repository\Election\CandidateRepository;
use App\Repository\Election\CandidateElectionPositionRepository;
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
     * Repository provide CandidateElectionPosition.
     * 
     * @var CandidateElectionPositionRepository
     */
    protected $candidateElectionPositionRepository;

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
        $this->guards = [];
        $this->candidateRepository = new CandidateRepository;
        $this->candidateElectionPositionRepository = new CandidateElectionPositionRepository;
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
     * Get CandidateElectionPosition
     * 
     * @param integer $id
     * @return CandidateElectionPosition
     */
    public function CandidateElectionPositionGet($id)
    {
        return $this->candidateElectionPositionRepository->get($id);
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
     * SignUp ElectionPosition for Candidate.
     * 
     * @param array $data
     * @return CandidateElectionPosition
     */
    public function CandidateElectionPositionSignUp($data)
    {
        return $this->candidateElectionPositionRepository->create($data);
    }

    /**
     * Add CandidateElectionPosition.
     * 
     * @param array $data
     * @return CandidateElectionPosition
     */
    public function CandidateElectionPositionAdd($data)
    {
        $data['Candidate'] = $this->guard()->candidate()->Candidate;

        $positions = $this->guard()->candidate()->CandidateElectionPositions;
        $election_position = ElectionPosition::find($data['ElectionPosition']);

        foreach($positions as $p) {
            if($p->ElectionPositionEntity->Election == $election_position->Election)
                throw new RuntimeException('同個選舉重複登記');
        }

        if(is_array($data['exp'])) {
            $exp_str = '';
            foreach ($data['exp'] as $exp) {
                if($exp == '')
                    continue;
                else
                    $exp_str .= $exp.'<br/>';
            }
        } else {
            $exp_str = '';
        }
        $data['exp'] = $exp_str;

        $candidateElectionPosition = $this->candidateElectionPositionRepository->create($data);

        // Reload Candidate's CandidateElectionPositions
        $this->guard()->candidate()->load('CandidateElectionPositions');

        return $candidateElectionPosition;
    }

    /**
     * Modify CandidateElectionPosition.
     * 
     * @param array $data
     * @return CandidateElectionPosition
     */
    public function CandidateElectionPositionModify($data)
    {
        $guard = $this->guard();
        $data['Candidate'] = $guard->candidate()->Candidate;

        if(is_array($data['exp'])) {
            $exp_str = '';
            foreach ($data['exp'] as $exp) {
                if($exp == '')
                    continue;
                else
                    $exp_str .= $exp.'<br/>';
            }
        } else {
            $exp_str = '';
        }
        $data['exp'] = $exp_str;

        $candidateElectionPosition = $this->candidateElectionPositionRepository->update($data);
        // Reload Candidate's CandidateElectionPositions
        $guard->candidate()->load('CandidateElectionPositions');

        return $candidateElectionPosition;
    }

    /**
     * Delete CandidateElectionPosition.
     * 
     * @param integer $id
     * @return bool
     */
    public function CandidateElectionPositionDelete($id)
    {
        $CEP = $this->candidateElectionPositionRepository->get($id);
        return $this->candidateElectionPositionRepository->delete($CEP);
    }

    /**
     * Candidate File Download.
     * 
     * @param integer $id
     * @return StreamedResponse
     */
    public function CandidateElectionPositionFileDownload($candidateElectionPosition)
    {
        if(!$candidateElectionPosition)
            throw new RuntimeException('找不道登記資訊');

        if($candidateElectionPosition->path == NULL)
            throw new RuntimeException('檔案未上傳');

        $downloadName = $candidateElectionPosition->Name. '.' . pathinfo($candidateElectionPosition->FilePath)['extension'];

        return Storage::download($candidateElectionPosition->FilePath, $downloadName);
    }

    /**
     * For admin to Set Candidate.
     * 
     * @param integer $id
     * @return Candidate
     */
    public function CandidateSet($id)
    {
        $CandidateEP = $this->candidateElectionPositionRepository->get($id);
        $CandidateEP->CandidateSet = true;
        $CandidateEP = $CandidateEP->save();

        $guard = $this->guard();
        $guard->candidate()->load('CandidateElectionPositions');

        return $CandidateEP;
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
     * Logout.
     * 
     * @return void 
     */
    public function CandidateLogout()
    {
        if(!$guard = $this->guard())
            return;

        $guard->logout();
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
    public function CandidateFileUpload(UploadedFile $file, $candidate_ep)
    {
        if($candidate_ep == NULL)
            throw new RuntimeException('尚未登入');

        $Path_To_Store = 'Candidate/'.$candidate_ep->CandidateEntity->Candidate.'/'.strval($candidate_ep->ElectionPosition);
        $FileName = sha1(time()).'.'.$file->clientExtension();
        $file->storeAs($Path_To_Store, $FileName);
        
        //not Repo!!
        $candidate_ep->file_updated = Carbon::now();
        $candidate_ep->path = $FileName;
        $candidate_ep->save();

        return $FileName;
    }

    /**
     * Upload Candidate image.
     * 
     * @param UploadedFile $file
     * @param Candidate $candidate
     * @return UploadedFile
     */
    public function CandidateImageUpload(UploadedFile $file=NULL)
    {
        $candidate = $this->guard()->candidate();
        if($file == NULL)
            throw new RuntimeException('未上傳檔案');
        if(!in_array($file->extension(), ['jpeg', 'png', 'bmp']))
            throw new RuntimeException('檔案格式有問題');

        if(!$candidate)
            throw new RuntimeException('尚未登入');

        $Path_To_Store = 'image/Candidate/'.$candidate->Candidate;
        $filename = sha1(time()).'.'.$file->extension();
        $candidate->image = $filename;
        $candidate->save();
        $this->guard()->refreshCandidate();

        $file->storeAs('public/'.$Path_To_Store, $filename);
        return $this->guard();
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

        // Update Candidate
        $candidate = $this->candidateRepository->update($data);
        $this->guard()->refreshCandidate();

        return $candidate;
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
            throw new RuntimeException('沒有這未候選人');

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