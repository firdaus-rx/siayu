<?php

use RealRashid\SweetAlert\Facades\Alert;

if (!function_exists('toast_success')) {
    function toast_success(string $message, string $title = 'Berhasil!')
    {
        Alert::success($title, $message)->flash();
        session()->flash('success', $message);
    }
}

if (!function_exists('toast_error')) {
    function toast_error(string $message, string $title = 'Gagal!')
    {
        Alert::error($title, $message)->flash();
        session()->flash('error', $message);
    }
}

if (!function_exists('toast_warning')) {
    function toast_warning(string $message, string $title = 'Peringatan!')
    {
        Alert::warning($title, $message)->flash();
        session()->flash('warning', $message);
    }
}

if (!function_exists('toast_info')) {
    function toast_info(string $message, string $title = 'Informasi')
    {
        Alert::info($title, $message)->flash();
        session()->flash('info', $message);
    }
}
