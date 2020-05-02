<?php

namespace App\Service\Token;

use Mail;
use Illuminate\Support\Facades\Validator;

use App\User;
use App\Service\Encryption\Encrypter;

use App\Repository\Vote\AuthTokenRepository;
use App\Repository\Election\ElectionRepository;
use App\Contracts\Service\Token\TokenService as TokenServiceContract;

class TokenService implements TokenServiceContract
{
    /**
     * Provide Encrypter to Service.
     * @var App\Service\Encrypter
     */
    protected $encrypter;

    /**
     * Access AuthToken.
     * @var App\Repository\Vote\AuthTokenRepository
     */
    protected $authtokenRepository;
    
    /**
     * Access Election.
     * @var \App\Repository\Election\ElectionRepository
     */
    protected $electionRepository;

    /**
     * Create a new encrypter instance.
     *
     * @return void
     *
     * @throws \RuntimeException
     */
    public function __construct()
    {
        $this->encrypter = new Encrypter('r0C64xMXvoL15/dxlAu/asD+3O8roI0Y2tkaQ4F94e0=');
        $this->authtokenRepository = new AuthTokenRepository();
        $this->electionRepository = new ElectionRepository();
    }

    /**
     * Get encrypted table key.
     * 
     * @param \App\User $user
     * @return string $encrypted_key
     */
    public function getTableKey(User $user)
    {
        //User is NULL
        if($user == NULL)
            return NULL;

        // Check Table key exist. if not generate new ont.
        $key = ($user->table_key == NULL)? $this->refreshTableKey($user):$user->table_key;

        // Generate verification code and send to mail
        $verifyCode = $this->generateVerifyCode(32);

        // Send Verify Code to user email
        Mail::send('mail.auth.VerifyCode', ['name' => $user->name, 'verify_code' => $verifyCode], function($message) use($user) {
            $message->to($user->email)->subject('Verify Code');
        });

        // Encrypt by verification code
        $json = json_encode(compact('key'));
        if (json_last_error() !== JSON_ERROR_NONE) {
            //json encode exception
        }

        $this->encrypter->setKey($verifyCode);
        $encrypted_key = $this->encrypter->encrypt($json);

        return $encrypted_key;
    }

    /**
     * Refresh table key
     * 
     * @param \App\User $user
     * @return string $key
     */
    public function refreshTableKey(User $user)
    {
        // Refresh Table Key
        $key = base64_encode(random_bytes(32));
        $user->table_key = $key;
        $user->save();

        return $key;
    }

    /**
     * Auth voter token.
     * 
     * @param string $encrypted_str
     * @param string $key
     * @return void
     */
    public function authVoter($encrypted_str, $key)
    {
        // Decrypt string by table key
        $this->encrypter->setKey($key);
        $json_str = $this->encrypter->decrypt($encrypted_str);

        $payload = json_decode($json_str, true);

        $validator = Validator::make($payload, [
            'election' => ['required', 'integer'],
            'sid' => ['required', 'string'],
            'token' => ['required', 'string']
        ]);

        if($validator->fails())
            return NULL;

        // Election not exist at database.
        if($this->electionRepository->get($payload['election']) == NULL)
            return NULL;

        // Create Token Record
        return $this->authtokenRepository->create($payload['election'], $payload['token'], $payload['sid']);
    }

    /**
     * Generate random verification code encode by base64.
     * 
     * @param int $len
     * @return string
     */
    protected function generateVerifyCode($len)
    {
        $code = '';
        $charSets = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        for($i=0; $i<$len ;++$i)
            $code .= $charSets[rand(0, strlen($charSets)-1)];

        return base64_encode($code);
    }
}