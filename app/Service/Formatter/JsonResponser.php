<?php

namespace App\Service\Formatter;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Arrayable;

class JsonResponser
{
    /**
     * Get response string of json.
     * 
     * @param bool $success
     * @param Arrayable $data
     * @param string $errorType
     * @param string $errorStr
     * 
     * @return string
     */
    public static function Response($success, $data, $errorType=NULL, $errorStr=NULL)
    {
        // initialize return payload
        $returnArr = [
            'success' => $success,
        ];

        if($success)
        {
            // Data not Jsonable object
            if(!($data instanceof Arrayable))
                return json_encode($returnArr);

            // if is Collection doesn't need to wrap
            if($data instanceof Collection)
                $returnArr['data'] = $data->toArray();
            else
                $returnArr['data'] = [$data->toArray()];
        }
        else
            $returnArr['error'] = [
                '_type' => $errorType,
                '_message' => $errorStr
            ];

        return json_encode($returnArr);
    }
}