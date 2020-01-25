<?php

namespace App\Models\Traits;

trait CheckExist
{
    /**
     * Check conditions object is exist.
     * 
     * @param array $condition
     * @param bool $deleted_at_f
     * @return bool
     */
    static public function isExist($condition, $deleted_at_f = false)
    {
        if($deleted_at_f)
            $entities = static::whereNull('deleted_at');
        else
            $entities = static::all();

        foreach($condition as $column => $data)
            $entities->where($column, $data);

        return !$entities->get()->isEmpty();
    }
}