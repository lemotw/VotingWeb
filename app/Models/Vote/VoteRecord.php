<?php
namespace App\Models\Vote;

use App\Models\Traits\CheckExist;
use Illuminate\Database\Eloquent\Model;

class VoteRecord extends Model
{
    use CheckExist;

    public $incrementing = true;
    protected $primaryKey = 'id';
    protected $table = 'VoteRecord';
    protected $dates = [ 'created_at', 'updated_at', 'deleted_at' ];
    protected $fillable = [ 'ElectionPosition', 'Candidate', 'YN_Vote', 'broken' ];

    public function ElectionPositionEntity()
    {
        return $this->belongsTo('App\Models\Election\ElectionPosition', 'ElectionPosition', 'UID');
    }
}