<?php

namespace App\Repository\Vote;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Exceptions\FormatNotMatchException;

use App\Models\Vote\AuthToken;
use App\Contracts\Repository\Vote\AuthTokenRepository as AuthTokenRepositoryContract;

class AuthTokenRepository implements AuthTokenRepositoryContract
{
    /**
     * Create Token Record.
     * 
     * @param integer $election
     * @param string $token
     * @param string $sid
     * @return AuthToken
     */
    public function create($election, $token, $sid)
    {
        //Check data valid or not.
        $validator = Validator::make([
            'election' => $election,
            'token' => $token,
            'sid' => $sid
        ], [
            'election' => ['required', 'integer'],
            'token' => ['required', 'string', 'max:128'],
            'sid' => ['required', 'string', 'max:64']
        ]);

        if($validator->fails())
            throw new FormatNotMatchException('AuthToken create param format not match!');

        return AuthToken::create([
            'Election' => $election,
            'Token' => $token,
            'sid' => $sid,
            'Voted' => false
        ]);
    }

    /**
     * Get Token.
     * 
     * @param string $HashedToken
     * @return AuthToken
     */
    public function get($HashedToken)
    {
        return AuthToken::where('Token', $HashedToken)->first();
    }

    /**
     * Set Vote flag.
     * 
     * @param AuthToken $authtoken
     * @return AuthToken
     */
    public function setVoted(AuthToken $authtoken)
    {
        $authtoken->Voted = true;
        $authtoken->save();
        return $authtoken;
    }

}