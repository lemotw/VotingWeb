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
    protected $dates = ['StartTime', 'EndTime', 'RegisterStart', 'RegisterEnd', 'VoteStart', 'VoteEnd', 'created_at', 'updated_at', 'deleted_at' ];
    protected $fillable = [ 'Name' ];

    public function ElectionPosition()
    {
        return $this->hasMany('App\Models\Election\ElectionPosition', 'Election', 'id');
    }
}