<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class CustomMail extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'custom.mailer';
    }
}
