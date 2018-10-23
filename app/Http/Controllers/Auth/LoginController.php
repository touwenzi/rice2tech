<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * 重写该方法使得可以使用用户名/手机/邮箱登录
     * @param Request $request
     * @return bool
     */
    public function attemptLogin(Request $request)
    {
        return collect(['username', 'email', 'phone'])->contains(function($value) use ($request) {
            $account = $request->get($this->username());
            $password = $request->get('password');
            return $this->guard()->attempt([$value => $account, 'password' => $password], $request->filled('remember'));
        });
    }

    /**
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
//        $this->validate($request, [
//            $this->username() => 'required|string',
//            'password' => 'required|string',
//        ]);

        $this->validate($request, [
            $this->username() => 'required|string',
            'password'        => 'required|string',
            //            'captcha' => 'required|captcha',
        ], [
//            'captcha.required' => ':attribute 不能为空',
//            'captcha.captcha' => '请输入正确的 :attribute',
        ], [
            $this->username() => '账号',
            'password'        => 'required|string'
            //            'captcha' => '验证码',
        ]);
    }

    public function username()
    {
        return 'account';
    }
}
