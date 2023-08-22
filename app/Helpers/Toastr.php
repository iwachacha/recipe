<?php

namespace App\Helpers;

class Toastr
{
    public static function success(string $message)
    {
        return session()->flash('toastr_success', $message);
    }

    public static function info(string $message)
    {
        return session()->flash('toastr_info', $message);
    }

    public static function warning(string $message)
    {
        return session()->flash('toastr_warning', $message);
    }

    public static function error(string $message)
    {
        return session()->flash('toastr_error', $message);
    }
}