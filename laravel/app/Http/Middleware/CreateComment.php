<?php

namespace App\Http\Middleware;

use Closure;

class CreateComment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $userActions = auth()->user()->userActions()->pluck('title')->toArray();
        if (!in_array('create-comment',$userActions))
            return abort(401,'با عرض پوزش از شما، شما امکان ارسال نظر ندارید.');

        return $next($request);
    }
}
