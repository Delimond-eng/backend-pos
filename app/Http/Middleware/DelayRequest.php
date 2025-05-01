<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Closure;

class DelayRequest{

    public function handle(Request $request, Closure $next){
        sleep(15);
        return $next($request);
    }
}