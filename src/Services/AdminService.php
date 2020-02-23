<?php

namespace Hanoivip\Shenqu\Services;

use Hanoivip\Shenqu\Models\User;


class AdminService
{
    const DEFAULT_PASSWORD = "ilovewartunepri";
    
    private $authenticator;
    
    public function __construct(ShenquAuthen $auth)
    {
        $this->authenticator = $auth;
    }
    
    private function getUserByIdOrUsername($uid)
    {
        $account = User::where('id', $uid)
        ->get();
        if ($account->isNotEmpty())
        {
            return $account->first();
        }
        // retry with username
        $account = User::where('email', $uid)
        ->get();
        if ($account->isNotEmpty())
        {
            return $account->first();
        }
    }
    
    public function getUserInfo($uid)
    {
       $user = $this->getUserByIdOrUsername($uid);
       return ['id' => $user->id, 'hoten' => $user->email];
    }
    
    public function getUserSecureInfo($uid)
    {
        $user = $this->getUserByIdOrUsername($uid);
        return ['email' => $user->email, 'email_verified' => false];
    }
    
    public function resetDefaultPassword($uid)
    {
        $user = $this->getUserByIdOrUsername($uid);
        if (!empty($user))
        {
            $user->password = md5(md5(self::DEFAULT_PASSWORD));
            $user->save();
            return true;
        }
        return false;
    }
    
    public function generateToken($uid)
    {
        $user = $this->getUserByIdOrUsername($uid);
        if (!empty($user))
        {
            $token = $this->authenticator->authen($user->user_name, $user->password);
            return $token;
        }
    }
}