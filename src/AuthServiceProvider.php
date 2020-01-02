<?php

namespace Hanoivip\Shenqu;

use Hanoivip\Shenqu\Extensions\TokenToUserProvider;
use Hanoivip\Shenqu\Extensions\AccessTokenGuard;
use Hanoivip\Shenqu\Services\ShenquAuthen;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/ipd.php' => config_path('ipd.php'),
            __DIR__ . '/../config/auth.php' => config_path('auth.php'),
        ]);
        $this->loadRoutesFrom(__DIR__ . '/../route/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../route/routes.php');
        $this->loadViewsFrom(__DIR__ . '/../views', 'hanoivip');
        $this->loadTranslationsFrom( __DIR__.'/../lang', 'hanoivip');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        // add guard provider
        Auth::provider('token', function ($app, array $config) {
            return new TokenToUserProvider();
        });
        // add custom guard
        Auth::extend('access_token', function ($app, $name, array $config) {
            $userProvider = app(TokenToUserProvider::class);
            $request = app('request');
            return new AccessTokenGuard($userProvider, $request, $config);
        });
    }
    
    public function register()
    {
        $this->app->bind(IShenquAuthen::class, ShenquAuthen::class);
    }
}
