<?php

namespace App\Http\Middleware;

use Closure;

class DeleteNews
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
        if (!in_array('delete-news',$userActions))
            return abort(401,'با عرض پوزش از شما، دسترسی شما به این صفحه به حالت تعلیق در آمده است.');

        return $next($request);
    }
}
