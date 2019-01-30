<?php

namespace App\Services;

use App\Models\User;
use Mail;


class MailService
{
    public function sendMailEmployee(User $user,$data) {

        Mail::send(['text'=> $data['template']], $data, function($message) use($user) {
            $message->to($user->email)->subject('Activated account');
            $message->from(env('MAIL_FROM_NAME', 'Example'));
        });

    }
}
