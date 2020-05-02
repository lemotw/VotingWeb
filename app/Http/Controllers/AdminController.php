<?php

namespace App\Http\Controllers;

use App\User;
use App\Service\User\UserService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AdminController extends Controller
{
    use AuthenticatesUsers;

    /**
     * For user data manipulate.
     */
    protected $userService;

    /**
     * Create a new ElectionController.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->userService = new UserService();
    }

    /**
     * Provide add User page.
     * 
     * @param Request $request
     */
    public function UserAdd_Page(Request $request) {
        $title = '新增管理者';
        $subtitle = '新增管理者';
        return view('admin.add', compact('title', 'subtitle'));
    }

    /**
     * Dealing with User add request
     * 
     * @param Request $request
     */
    public function UserAdd_Post(Request $request) {
        try {
            $data_to_create = $request->only(['email', 'name', 'role']);
            $data_to_create['password'] = substr(sha1(time()), 0, 10);

            $this->userService->Admin_User_Create($data_to_create);
        } catch(RuntimeException $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }

        return redirect()->route('admin.users.page')->with('msg', '帳號新增成功');
    }

    /**
     * Provide user info modify page
     * 
     * @param Request $request
     */
    public function UserModify_Page(Request $request) {
        $title = '個人資料維護';
        $subtitle = '資料維護';
        $user = Auth::user();

        return view('admin.modify', compact('title', 'subtitle', 'user'));
    }

    /**
     * Dealing with User modify request
     * 
     * @param Request $request
     */
    public function UserModify_Post(Request $request) {
        try {
            $user = Auth::user();

            // Modify name and password
            $this->userService->User_Change($user, $request->only(['name']));
            if($request->input('password') != NULL)
                $this->userService->UserPassword_Change($user, $request->input('password'));
        } catch(RuntimeException $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }

        return redirect()->route('admin.welcome.page')->with('msg', '個人資料維護成功');
    }

    /**
     * Delete User
     * 
     * @param Request $request
     */
    public function UserDelete_Post(Request $request) {
        try {
            $this->userService->Admin_User_Delete(User::find($request->input('id')));
        } catch(RuntimeException $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }

        return redirect()->back()->with('msg', '刪除成功');
    }

    /**
     * Change User role post.
     * 
     * @param Request $request
     */
    public function UserRoleChange_Post(Request $request) {
        try {
            $user = User::find($request->input('id'));
            $role = $request->input('role');

            $this->userService->Admin_UserRole_Change($user, $role);
        } catch(RuntimeException $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }

        return 'success';
    }

    /**
     * Provide user list to manage user.
     * 
     * @param Request $request
     */
    public function UserList_Page(Request $request) {
        $title = '使用者維護';
        $users = User::all();
        return view('admin.users', compact('title', 'users'));
    }
 
    /**
     * Reset User password.
     * 
     * @param Request $request
     */
    public function UserResetPassword(Request $request) {
        $user = User::find($request->input('id'));
        $this->userService->Admin_UserPassword_Change($user);

        return redirect()->route('admin.users.page')->with('msg', $user->name.' 密碼重設成功');
    }

    //----------------------------------------For Login

    /**
     * Provide login page for election administrator.
     * 
     * @param Request $request
     */
    public function AdminLogin_Page(Request $request) {
        $user = Auth::user();
        if($user != null)
            return redirect()->route('election.index.page');

        $title = '管理員登入';
        return view('admin.login', compact('title'));
    }

    /**
     * Dealing with login post request.
     * 
     * @param Request $request
     */
    public function AdminLogin_Post(Request $request) {
        return $this->login($request);
    }

    /**
     * Dealing with logout request.
     * 
     * @param Request $request
     */
    public function AdminLogout(Request $request) {
        return $this->logout($request);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect()->route('admin.login.page');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->route('admin.welcome.page');
    }

}
