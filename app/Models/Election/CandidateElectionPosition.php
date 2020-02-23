<?php

namespace App\Models\Election;

use App\Models\Traits\CheckExist;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CandidateElectionPosition extends Model
{
    use CheckExist;
    use SoftDeletes;

    public $incrementing = true;
    protected $primaryKey = 'id';
    protected $table = 'CandidateElectionPosition';
    protected $dates = [ 'created_at', 'updated_at', 'deleted_at' ];

    protected $fillable = [ 'Candidate', 'ElectionPosition', 'path', 'CandidateSet' ];

    public function ElectionPositionEntity()
    {
        return $this->belongsTo('App\Models\Election\ElectionPosition', 'ElectionPosition', 'id');
    }

    public function CandidateEntity()
    {
        return $this->belongsTo('App\Models\Elction\Candidate', 'Candidate', 'Candidate');
    }
}