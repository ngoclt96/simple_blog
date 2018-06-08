<?php

use App\Http\Controllers\BaseController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Validation\ValidatesRequests;

class RegisterController extends BaseController
{
    use ValidatesRequests;
    use AuthenticatesUsers;
}