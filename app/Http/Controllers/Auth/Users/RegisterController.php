<?php

namespace App\Http\Controllers\Auth\Users;


use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class RegisterController extends BaseController
{
    use ValidatesRequests;
    use AuthenticatesUsers;

    protected $cache;
    var $email;

    public function __construct(Cache $cache)
    {
        parent::__construct();
        $this->cache = $cache;
    }

    public function index()
    {
        $model = new User();
        $searchField = $model->getSearchAbleField();
        $model->getSearch('search_users_remember');
        $params = request()->input();
        $user = $model->vailableUsers($params);
        $user = $user->search($params);
        $user = $user->orderBy('.id', 'desc');
        $user = $user->paginate($this->limit);
        $this->view(['searchField' => $searchField, 'pages' => $user, 'listField' => $user]);
    }

    public function form($id = null)
    {
        $user = new User();
        if ($id) {
            $user = User::findOrFail($id);
        }
        if (old()) {
            $user->fill(old());
        }
        (request()->session()->has('usersConfirm') && request()->query('back') == 'true') ? $user = request()->session()->get('usersConfirm') : request()->session()->forget('usersConfirm');
        $user->id = $id;
        return view('pc.auth.users.register_form', ['user' => $user]);
    }

    public function confirm(Request $request)
    {
        $user = new User();
        $user->fill($request->input());
        if ($request->input('id')) {
            $user->id = $request->input('id');
        }
        $request->session()->put('usersConfirm', $user);
        return view('pc.auth.users.register_confirm', ['user' => $user]);
    }

    public function complete()
    {
        if (!request()->session()->has('usersConfirm')) {
            return redirect(route('user.register.form'));
        }
        $usersConfirm = request()->session()->get('usersConfirm');
        $usersConfirm->password = Hash::make($usersConfirm->password);
dd(1);
        if ($usersConfirm->id) {
            $usersConfirm->exists = true;
        }
        $usersConfirm->save();
        dd(1);
        return redirect(route('user.register.index'));
    }







}