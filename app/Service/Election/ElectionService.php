<?php

namespace App\Service\Election;

use Illuminate\Support\Facades\Validator;

use RuntimeException;
use App\Exceptions\RelatedObjectNotFoundException;

use App\Models\Election\Election;
use App\Models\Election\ElectionPosition;
use App\Repository\Election\ElectionRepository;
use App\Repository\Election\ElectionPositionRepository;

use App\Contracts\Service\Election\ElectionService as ElectionServiceContract;

class ElectionService implements ElectionServiceContract
{

    /**
     * Access Election.
     * @var \App\Repository\Election\ElectionRepository
     */
    protected $electionRepository;

    /**
     * Access Election Position.
     * @var \App\Repository\Election\ElectionPositionRepository
     */
    protected $electionPositionRepository;

    /**
     * Create repository.
     *
     * @return void
     *
     * @throws \RuntimeException
     */
    public function __construct()
    {
        $this->electionRepository = new ElectionRepository();
        $this->electionPositionRepository = new ElectionPositionRepository();
    }

    /**
     * 
     * Add a Election
     * 
     * @param array $data
     * @return Election
     * 
     * @throws App\Exceptions\FormatNotMatchException
     * @throws App\Exceptions\RelatedObjectNotFoundException
     */
    public function ElectionAdd($data)
    {
        // Create election
        $election = $this->electionRepository->create($data);

        // Create all ElectionPosition Set
        if(isset($data['ElectionPosition']))
        {
            foreach($data['ElectionPosition'] as $electionPosition)
                $this->ElectionPositionAdd($election->id, $electionPosition);
        }

        return $election;
    }

    /**
     * Modify Election infomation.
     * 
     * @param array $data
     * @return Election
     * 
     * @throws RuntimeException
     * @throws App\Exceptions\FormatNotMatchException
     */
    public function ElectionModify($data)
    {
        return $this->electionRepository->update($data);
    }

    /**
     * Delete Election.
     * 
     * @param int $id
     * @return bool
     */
    public function ElectionDelete($id)
    {
        $election = $this->electionRepository->get($id);
        return $this->electionRepository->delete($election);
    }


    /**
     * Add ElectionPosition to Election.
     * 
     * @param int $ElectionId
     * @param array $data
     * @return ElectionPosition
     * 
     * @throws App\Exceptions\FormatNotMatchException
     * @throws App\Exceptions\RelatedObjectNotFoundException
     */
    public function ElectionPositionAdd($ElectionId, $data)
    {
        $data['Election'] = $ElectionId;
        $this->electionPositionRepository->create($data);
    }

    /**
     * Modify ElectionPosition infomation.
     * 
     * @param array $data
     * @return ElectionPosition
     */
    public function ElectionPositionModify($data)
    {
        return $this->electionPositionRepository->update($data);
    }

    /**
     * Delete ElectionPosition.
     * 
     * @param int $id
     * @return bool
     */
    public function ElectionPositionDelete($id)
    {
        return $this->electionPositionRepository->delete(ElectionPosition::find($id));
    }
}