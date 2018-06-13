<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{
    use ValidatesRequests;
    use AuthenticatesUsers;

    /*
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/posts';
    protected $cache;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Cache $cache)
    {
        parent::__construct();
        $this->cache = $cache;
    }

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
        $this->validate($request, [
            $this->username() => 'required|email|exists:users,' . $this->username() . '',
            'password' => 'required|min:8',
        ],
            [
                $this->username() . '.exists' => 'Email is invalid or the account has been disabled.'
            ]);


    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if (User::$check_attemp) { // check if email exists then increment lock for user with that email
            $this->incrementLoginAttempts($request);
        }
        $this->validateLogin($request);

        return $this->sendFailedLoginResponse($request);
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
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);
        $user = $user->fill($request->input());
        $user->password = Hash::make($user->password);
        $user->permission = 0;
        $user->save();
        return redirect(route('user.login.form'));
    }

}