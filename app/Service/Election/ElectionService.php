<?php

namespace App\Service\Election;

use Illuminate\Support\Facades\Validator;

use RuntimeException;
use App\Exceptions\RelatedObjectNotFoundException;

use App\Models\Election\Election;
use App\Models\Election\Position;
use App\Models\Election\ElectionPosition;
use App\Repository\Election\PositionRepository;
use App\Repository\Election\ElectionRepository;
use App\Repository\Election\ElectionPositionRepository;
use App\Repository\Election\CandidateElectionPositionRepository;

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
     * Access Candidate Election Position.
     * @var \App\Repository\Election\CandidateElectionPositionRepository
     */
    protected $candidateElectionPositionRepository;

    /**
     * Access Position.
     * @var \App\Repository\Election\PositionRepository
     */
    protected $positionRepository;

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
        $this->candidateElectionPositionRepository = new CandidateElectionPositionRepository();
        $this->positionRepository = new PositionRepository();
    }

    /**
     * Get election by id.
     * 
     * @param integer $id
     * @return Election
     */
    public function ElectionGet($id)
    {
        return $this->electionRepository->get($id);
    }

    /**
     * Get election position by id.
     * 
     * @param integer $id
     * @return ElectionPosition
     */
    public function ElectionPositionGet($id)
    {
        return $this->electionPositionRepository->get($id);
    }

    /**
     * Get position by id.
     * 
     * @param integer $id
     * @return Position
     */
    public function PositionGet($id)
    {
        return $this->positionRepository->get($id);
    }

    /**
     * Get All Position.
     * 
     * @return ICollection
     */
    public function Position()
    {
        return Position::all();
    }

    /**
     * Search Election.
     * 
     * @param array $condition
     * @return Illuminate\Support\Collection
     */
    public function ElectionSearch($condition = null)
    {
        /**
         * $condition = [
         *      'key' => $value,
         *      'key2' => $value2
         * ]
         */
        if(!$condition)
            return $this->electionRepository->all();

        return $this->electionRepository->getBy($condition);
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
    public function ElectionPositionAdd($data)
    {
        return $this->electionPositionRepository->create($data);
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

    /**
     * Add Position.
     * 
     * @param array $data
     * @return ElectionPosition
     * 
     * @throws App\Exceptions\FormatNotMatchException
     * @throws App\Exceptions\RelatedObjectNotFoundException
     */
    public function PositionAdd($data)
    {
        $this->positionRepository->create($data);
    }

    /**
     * Modify Position infomation.
     * 
     * @param array $data
     * @return ElectionPosition
     */
    public function PositionModify($data)
    {
        return $this->positionRepository->update($data);
    }

    /**
     * Delete Position.
     * 
     * @param int $id
     * @return bool
     */
    public function PositionDelete($id)
    {
        return $this->positionRepository->delete($this->PositionGet($id));
    }

    /**
     * Get Candidate Election Position to check.
     * 
     * @param int $id
     * @return bool
     */
    public function CandidateElectionPositionGet($id)
    {
        return $this->candidateElectionPositionRepository->get($id);
    }
}