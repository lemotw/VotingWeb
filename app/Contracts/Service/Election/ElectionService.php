<?php

namespace App\Contracts\Service\Election;

interface ElectionService
{
    /**
     * Add a Election
     * 
     * @param array $data
     * @return Election
     */
    public function ElectionAdd($data);

    /**
     * Modify Election infomation.
     * 
     * @param array $data
     * @return Election
     */
    public function ElectionModify($data);

    /**
     * Delete Election.
     * 
     * @param int $id
     * @return bool
     */
    public function ElectionDelete($id);


    /**
     * Add ElectionPosition to Election.
     * 
     * @param array $data
     * @return ElectionPosition
     */
    public function ElectionPositionAdd($data);

    /**
     * Modify ElectionPosition infomation.
     * 
     * @param array $data
     * @return ElectionPosition
     */
    public function ElectionPositionModify($data);

    /**
     * Delete ElectionPosition.
     * 
     * @param int $id
     * @return bool
     */
    public function ElectionPositionDelete($id);
}