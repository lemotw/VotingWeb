<?php

namespace App\Models\Election;

use Illuminate\Support\Facades\Validator;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use SoftDeletes;

    public $incrementing = true;
    protected $primaryKey = 'id';
    protected $table = 'Position';
    protected $dates = [ 'created_at', 'updated_at', 'deleted_at' ];

    protected $fillable = [ 'Name', 'Unit', 'QualifyRegex', 'RequireDocument' ];

    /**
     * Valid student qualify.
     * 
     * @param string $sid
     * @return bool
     */
    public function validSid($sid)
    {
        $validator = Validator::make(['sid' => $sid], [
            'sid' => ['required', 'regex:'.$this->QualifyRegex]
        ]);

        // Catch Regex exception and return false
        try
        {
            if($validator->fails())
                return false;
        }
        catch(ErrorException $exception)
        {
            return false;
        }

        return true;
    }
}