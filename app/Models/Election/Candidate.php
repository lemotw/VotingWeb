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

    protected $fillable = [ 'Candidate', 'Name', 'ElectionPosition', 'CandidateRegister' ];

    public function ElectionPositionEntity()
    {
        return $this->belongsTo('App\Models\Election\ElectionPosition', 'ElectionPosition', 'id');
    }

    public function CandidateRegisterEntity()
    {
        return $this->belongsTo('App\Models\Elction\CandidateRegister', 'CandidateRegister', 'id');
    }
}