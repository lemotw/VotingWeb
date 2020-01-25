<?php
namespace App\Models\Vote;

use App\Models\Traits\CheckExist;
use Illuminate\Database\Eloquent\Model;

class VoteResult extends Model
{
    use CheckExist;

    public $incrementing = true;
    protected $primaryKey = 'id';
    protected $table = 'VoteResult';
    protected $dates = [ 'created_at', 'updated_at', 'deleted_at' ];
    protected $fillable = [ 'ElectionPosition', 'Candidate', 'VoteCount', 'Yes', 'No', 'disable' ];

    public function ElectionPositionEntity()
    {
        return $this->belongsTo('App\Models\Election\ElectionPosition', 'ElectionPosition', 'UID');
    }

    public function Candidate()
    {
        return $this->belongsTo('App\Models\Election\Candidate', 'Candidate', 'Candidate');
    }
}