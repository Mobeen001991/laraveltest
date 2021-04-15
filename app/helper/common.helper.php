<?php
use Illuminate\Support\Facades\Mail;

if (!function_exists('sendMail')) {

function sendMail($array) {
    Mail::send($array['file'], $array['data'], function ($message) use ($request) {
        $message->to($array['email'], $array['email'])->subject
            ($array['subject']);
    });
}

}