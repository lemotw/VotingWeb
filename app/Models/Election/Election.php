<?php

namespace App\Models\Election;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Election extends Model
{
    use SoftDeletes;

    public $incrementing = true;
    protected $primaryKey = 'id';
    protected $table = 'Election';
    protected $fillable = ['Name', 'StartTime', 'EndTime', 'RegisterStart', 'RegisterEnd', 'VoteStart', 'VoteEnd', 'created_at', 'updated_at', 'deleted_at' ];

    public function ElectionPositionEntity()
    {
        return $this->hasMany('App\Models\Election\ElectionPosition', 'Election', 'id');
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($e) {
            foreach ($e->ElectionPositionEntity as $ep)
                $ep->CandidateElectionPosition()->delete();

            $e->ElectionPositionEntity()->delete();
        });
    }
}