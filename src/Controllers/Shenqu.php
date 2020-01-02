<?php
namespace Hanoivip\Shenqu\Controllers;


use Hanoivip\Shenqu\IShenquAuthen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Exception;


class Shenqu extends Controller
{
    private $auth;
    
    public function __construct(IShenquAuthen $auth)
    {
        $this->auth = $auth;
    }
    
    public function login(Request $request)
    {
        if (Auth::check())
            return redirect('/');
        return view('hanoivip::auth.login');
    }
    
    public function doLogin(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');
        Log::debug("Shenqu user is logining {$username} {$password}");
        try 
        {
            $accessToken = $this->auth->authen($username, $password);
            if (!empty($accessToken))
            {
                Cookie::queue(Cookie::make('access_token',  $accessToken));
                return view('hanoivip::landing');
            }
            else
                return view('hanoivip::auth.login', ['error' => 'Đăng nhập thất bại, kiểm tra tài khoản và mật khẩu']);
        } 
        catch (Exception $e) 
        {
            Log::error("Shenqu login ex:" . $e->getMessage());
            return view('hanoivip::auth.login-exception');
        }
        
    }
    
    public function logout(Request $request)
    {
        if (!Auth::check())
            return redirect('/');
            Cookie::queue(Cookie::forget('access_token'));
            Cookie::queue(Cookie::forget('laravel_session'));
        return view('hanoivip::landing');
    }
    
    public function onLogout(Request $request)
    {
        return view('hanoivip::landing');
    }
    
    public function register(Request $request)
    {
        return view('hanoivip::auth.register');
    }
    
    public function forgotPass(Request $request)
    {
        return view('hanoivip::auth.passwords.reset');
    }
}