<?php

namespace App\Models\Traits;

use Illuminate\Support\Collection;

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

        // if deleted_at contain.
        if($entities instanceof Collection)
            return !$entities->isEmpty();

        return !$entities->get()->isEmpty();
    }
}