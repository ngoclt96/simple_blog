<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{
    use ValidatesRequests;
    use AuthenticatesUsers {
        showLoginForm as protected showLoginForm;
        validateLogin as protected validateLogin;
        logout as protected logout;
    }

    protected $redirectTo = '/admin/posts';
    
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @uses change login view to current project view
     */
    public function showLoginForm()
    {
        return view($this->getViewDir() . '.' . 'user.login');
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request,
            [
                $this->username() => 'required|email|exists:users,' . $this->username() . '',
                'password' => 'required|min:8',
            ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @uses Logout and unset all users session
     */
    public function logout(Request $request)
    {
        $request->session()->forget('permission');
        $this->guard()->logout();
        return redirect(route("home"));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @uses change r view to current project view
     */
    public function showRegisterForm()
    {
        return view($this->getViewDir() . '.' . 'user.register');
    }

    /**
     * Handle a register request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $user = new User();
        $this->validate($request,
            [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required'
            ]);
        $user = $user->fill($request->input());
        $user->password = Hash::make($user->password);
        $user->permission = User::USER;
        $user->save();
        return redirect(route('user.login.form'));
    }

}