<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SweetAlertMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Pastikan session flash terisi dengan benar untuk Sweet Alert
        if (session()->has('success')) {
            session()->flash('success', session('success'));
        }
        if (session()->has('error')) {
            session()->flash('error', session('error'));
        }
        if (session()->has('warning')) {
            session()->flash('warning', session('warning'));
        }
        if (session()->has('info')) {
            session()->flash('info', session('info'));
        }

        return $response;
    }
}
