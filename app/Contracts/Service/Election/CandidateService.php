<?php

namespace App\Contracts\Service\Election;

use Illuminate\Http\UploadedFile;
use App\Models\Election\CandidateRegister;

interface CandidateService
{
    /**
     * Open to anyone to Register.
     * 
     * @param array $data
     * @return CandidateRegister
     */
    public function CandidateRegister($data);

    /**
     * Get Login token.
     * 
     * @param array $credentials
     * @return string
     */
    public function CandidateLogin($credentials);

    /**
     * Get current CandidateRegister
     * 
     * @return CandidateRegister
     */
    public function Candidate();

    /**
     * Upload file.
     * 
     * @param UploadedFile $file
     * @param CandidateRegister $candidate
     * @return UploadedFile
     */
    public function CandidateFileUpload(UploadedFile $file, CandidateRegister $candidate);

    /**
     * Modify Candidate Register infomation.
     * 
     * @param array $data
     * @param CandidateRegister $candidate
     * @return CandidateRegister
     */
    public function CandidateModify($data, CandidateRegister $candidate);
    

    /**
     * Move CandidateRegister to Candidate.
     * 
     * @param integer $id
     * @return Candidate
     */
    public function CandidateSet($id);

    /**
     * Delete Candidate.
     * 
     * @param string $Candidate
     * @return bool
     */
    public function CandidateDelete($Candidate);
}