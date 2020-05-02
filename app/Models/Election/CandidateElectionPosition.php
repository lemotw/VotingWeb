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

    protected $visible = [ 'Candidate', 'name', 'path', 'exp', 'CandidateImage' ];
    protected $fillable = [ 'Candidate', 'ElectionPosition', 'path', 'exp', 'CandidateSet' ];
    protected $appends = [ 'name','CandidateImage' ];

    public function getFilePathAttribute()
    {
        if($this->CandidateEntity == NULL)
            return NULL;

        return 'Candidate/'.$this->CandidateEntity->Candidate.'/'.strval($this->ElectionPosition).'/'.$this->path;
    }

    public function getNameAttribute()
    {
        return $this->CandidateEntity?$this->CandidateEntity->Name:NULL;
    }

    public function getCandidateImageAttribute()
    {
        return $this->CandidateEntity?$this->CandidateEntity->imageURL:NULL;
    }

    public function ElectionPositionEntity()
    {
        return $this->belongsTo('App\Models\Election\ElectionPosition', 'ElectionPosition', 'id');
    }

    public function CandidateEntity()
    {
        return $this->belongsTo('App\Models\Election\Candidate', 'Candidate', 'Candidate');
    }
}