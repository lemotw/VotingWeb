<?php

namespace App\Contracts\Service\Election;

use Illuminate\Http\UploadedFile;
use App\Models\Election\Candidate;

interface CandidateService
{
    /**
     * Open to anyone to Register.
     * 
     * @param array $data
     * @return Candidate
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
     * Get current Candidate
     * 
     * @return Candidate
     */
    public function Candidate();

    /**
     * Upload file.
     * 
     * @param UploadedFile $file
     * @param Candidate $candidate
     * @param integer $positionId
     * @return UploadedFile
     */
    public function CandidateFileUpload(UploadedFile $file, $candidate_ep);

    /**
     * Modify Candidate infomation.
     * 
     * @param array $data
     * @param Candidate $candidate
     * @return Candidate
     */
    public function CandidateModify($data, Candidate $candidate);
    

    /**
     * Move Candidate to Candidate.
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