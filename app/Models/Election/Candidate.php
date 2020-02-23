<?php

namespace App\Models\Election;

use App\Models\Traits\CheckExist;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Candidate extends Model
{
    use CheckExist;
    use SoftDeletes;

    public $incrementing = false;
    protected $primaryKey = 'Candidate';
    protected $table = 'Candidate';
    protected $dates = [ 'created_at', 'updated_at', 'deleted_at' ];

    protected $fillable = [ 'Candidate', 'Name', 'account', 'password' ];

    public function ElectionPositions()
    {
        return $this->belongsToMany(
            'App\Models\Election\ElectionPosition',
            'App\Models\Election\CandidateElectionPosition',
            'Candidate',
            'ElectionPosition'
        );
    }
}