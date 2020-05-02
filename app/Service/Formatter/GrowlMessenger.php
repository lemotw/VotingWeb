<?php

namespace App\Service\Formatter;

class GrowlMessenger
{
    /**
     * Get growl string of json.
     * 
     * @param string $type
     * @param string $message
     * @return string
     */
    public static function message($type, $message)
    {
        return json_encode([
            '_type' => $type,
            '_message' => $message
        ]);
    }
}