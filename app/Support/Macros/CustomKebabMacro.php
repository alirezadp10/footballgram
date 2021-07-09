<?php


namespace App\Support\Macros;


use Illuminate\Support\Str;

class CustomKebabMacro
{
    public function __invoke()
    {
        Str::macro('customKebab',function ($value) {
            return self::kebab(self::lower($value));
        });
    }
}
