<?php
namespace App\Models\Vote;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuthToken extends Model
{
    use SoftDeletes;

    public $incrementing = true;
    protected $primaryKey = 'id';
    protected $table = 'AuthToken';
    protected $dates = [ 'created_at', 'updated_at', 'deleted_at' ];
    protected $fillable = [ 'Token', 'sid', 'Voted', 'Election' ];

    public function ElectionEntity()
    {
        return $this->belongsTo('App\Models\Election\Election', 'Election', 'id');
    }
}