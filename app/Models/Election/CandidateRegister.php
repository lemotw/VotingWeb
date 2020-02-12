<?php

namespace App\Models\Election;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CandidateRegister extends Model
{
    use SoftDeletes;

    public $incrementing = true;
    protected $primaryKey = 'id';
    protected $table = 'CandidateRegister';
    protected $dates = [ 'created_at', 'updated_at', 'deleted_at' ];
    protected $fillable = [ 'Name', 'account', 'password', 'ElectionPosition', 'token' ];
    protected $hidden = ['password', 'token'];

    public function ElectionPosition()
    {
        return $this->hasOne('App\Models\Election\ElectionPosition', 'Position', 'id');
    }
}