<?php

namespace App\Http\Middleware;
use App\Friend;
use Closure;
use Dingo\Api\Routing\Helpers;
class VerifyFriend
{
    use Helpers;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $friend = Friend::where('user1', $this->auth->user()->id)->andwhere('user2', $request->user_id)->get();
        if($friend)
            return $next($request);
        else
            return $this->response->error('You are not friend', 405);
    }
}
