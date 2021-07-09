<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('base64', function ($attribute, $value, $parameters, $validator) {
            if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $value)) {
                return TRUE;
            }
                return FALSE;
            
        });

        Validator::extend('base64image', function ($attribute, $value, $parameters, $validator) {
            $explode = explode(',', $value);
            $allow = [
                'png',
                'jpg',
                'jpeg',
                'bmp',
                'svg',
            ];
            $format = str_replace(
                [
                    'data:image/',
                    ';',
                    'base64',
                ],
                [
                    '',
                    '',
                    '',
                ],
                $explode[0]
            );
            // check file format
            if (!in_array($format, $allow)) {
                return FALSE;
            }
            // check base64 format
            if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $explode[1])) {
                return FALSE;
            }
            return TRUE;
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
