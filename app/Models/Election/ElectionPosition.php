<?php

namespace App\Models\Election;

use App\Models\Traits\CheckExist;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ElectionPosition extends Model
{
    use CheckExist;
    use SoftDeletes;

    public $incrementing = true;
    protected $primaryKey = 'id';
    protected $table = 'ElectionPosition';
    protected $dates = [ 'created_at', 'updated_at', 'deleted_at' ];
    protected $fillable = ['UID', 'Name', 'Election', 'Position', 'ElectionType' ];
    protected $visible = [ 'UID', 'Name' ];

    public function ElectionEntity()
    {
        return $this->belongsTo('App\Models\Election\Election', 'Election', 'id');
    }

    public function PositionEntity()
    {
        return $this->belongsTo('App\Models\Election\Position', 'Position', 'id');
    }

    public function CandidateElectionPosition()
    {
        return $this->hasMany('App\Models\Election\CandidateElectionPosition', 'ElectionPosition', 'id');
    }
}