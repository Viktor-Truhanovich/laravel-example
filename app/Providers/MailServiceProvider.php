<?php

namespace App\Providers;

use App\Services\MailService;
use Illuminate\Support\ServiceProvider;

class MailServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('custom.mailer', function () {
            return new MailService;
        });
    }
}
