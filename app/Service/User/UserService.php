<?php

namespace App\Service\User;

use Mail;
use App\User;

use RuntimeException;
use App\Exceptions\FormatNotMatchException;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserService
{
    /**
     * Change Certain user password.
     */
    public function UserPassword_Change(User $user, $password) {
        $password = Hash::make($password);
        $validator = Validator::make(['password' => $password], [
            'password' => 'required|string|max:255'
        ]);

        if($validator->fails())
            throw new RuntimeException('Hash後的值過大');

        $user->password = $password;
        return $user->save();
    }

    /**
     * Change other parameter for certain user.
     */
    public function User_Change(User $user, $data) {
        $validator = Validator::make($data, [
            'name' => 'string|max:255',
        ]);

        if($validator->fails())
            throw new RuntimeException('名字不合規則');

        return $user->update($data);
    }

    /**
     * user create.
     */
    public function Admin_User_Create($data) {
        $plain_password = $data['password'];

        if(isset($data['password']))
            $data['password'] = Hash::make($data['password']);

        $validator = Validator::make($data, [
            'name' => 'required',
            'email' => 'required|string|max:255|email',
            'role' => 'required|integer',
            'password' => 'required|string|max:255'
        ]);

        if($validator->fails())
            throw new RuntimeException('資料格式出錯!');
        
        $user = User::create($data);

        if($user != NULL) {
            Mail::send('mail.user.PasswordSend', ['name' => $user->name, 'password' => $plain_password], function($message) use($user) {
                $message->to($user->email)->subject('Account Password');
            });
        }
    }

    /**
     * random generate password and mail to user email.
     */
    public function Admin_UserPassword_Change(User $user) {
        $password = $this->password_generate();
        $this->UserPassword_Change($user, $password);

        //Send Mail
        Mail::send('mail.user.NewPassword', ['name' => $user->name, 'password' => $password], function($message) use($user) {
            $message->to($user->email)->subject('new Password');
        });
    }

    /**
     * Admin Change Certain User role.
     */
    public function Admin_UserRole_Change(User $user, $role) {
        if(!($role != NULL || $role <= 4 || $role >= 0))
            throw new RuntimeException('Role out of range!');

        $user->role = $role;
        return $user->save();
    }

    /**
     * Admin Delete User.
     */
    public function Admin_User_Delete(User $user) {
        if($user == NULL)
            return;

        $user->delete();
    }

    /**
     * Generate random temporary password.
     */
    protected function password_generate() {
        return substr(sha1(time()), 0, 10);
    }
}