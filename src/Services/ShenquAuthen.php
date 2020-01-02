<?php

namespace Hanoivip\Shenqu\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Hanoivip\Shenqu\IShenquAuthen;
use Hanoivip\Shenqu\Models\User;

class ShenquAuthen implements IShenquAuthen
{   
    /**
     * 1. Query user in database
     * 2. Generate and cache tokens
     * 3. Return token
     * 
     * @param string $username
     * @param string $password
     * @return string
     */
    public function authen($username, $password)
    {
        $hash = md5(md5($password));
        Log::error("Hash password {$hash}");
        $account = User::where('email', $username)
        ->where('password', $hash)
        ->get();
        if ($account->isNotEmpty())
        {
            $account = $account->first();
            $token = uniqid();
            $account["api_token"] = $token;
            Cache::put($token, $account, Carbon::now()->addDays(7));
            return $token;
        }
    }
    
    /**
     * 1. Check for token in cache
     * 2. Return, if any
     * 
     * @param string $token
     * @return array
     */
    public function getUserByToken($token)
    {
        if (Cache::has($token))
        {
            return Cache::get($token);
        }
    }
}