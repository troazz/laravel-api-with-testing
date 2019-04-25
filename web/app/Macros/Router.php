<?php

namespace App\Macros;

use Illuminate\Support\Facades\Route as DefaultRouter;
use Illuminate\Support\Str;

class Router
{
    public static function registerMacros()
    {
        if (! DefaultRouter::hasMacro('api')) {
            DefaultRouter::macro('api', function ($module) {
                $url = str_replace('.', '', Str::plural($module));
                $controller = 'API\\'.Str::studly(str_replace('.', ' ', $module)) . 'Controller';
                DefaultRouter::group([
                    'middleware' => ['auth:api'],
                ], function () use ($url, $controller) {
                    DefaultRouter::post($url . '', $controller . '@create');
                    DefaultRouter::put($url . '/{id}', $controller . '@update')->where('id', '[0-9]+');
                    DefaultRouter::delete($url . '/{id}', $controller . '@delete')->where('id', '[0-9]+');
                    DefaultRouter::get($url . '/{id}', $controller . '@find')->where('id', '[0-9]+');
                    DefaultRouter::get($url, $controller . '@index');
                });
            });
        }
    }
}
