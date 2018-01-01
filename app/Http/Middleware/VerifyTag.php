<?php

namespace App\Http\Middleware;

use Closure;
use Dingo\Api\Routing\Helpers;
use App\Tag;
class VerifyTag
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
        $tag = Tag::findOrFail($request->id);
        if($tag->user_id != $this->auth->user()->id) {
            return $this->response->error('This tag do not belong to you', 403);
        }
        return $next($request);
    }
}
