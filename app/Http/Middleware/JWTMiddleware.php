<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
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
        try {
            $user = JWTAuth::parseToken()->authenticate();
			if(!$user){
				
				return response()->json([
					'messages' => 'Authorization Token not found',
					'data' => [],
					'status' => "false",
					'code' => 401
				], 200);
			}
			$lang = \config('settings.DEFAULT_LANG');
			if ($request->get('lang') && !is_array($request->lang)) {
				$lang = $request->get('lang');
			} else if ($user) {
				$lang = $user->language;
			} 
			\App::setLocale($lang);
        }
        catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException)
            {
				return response()->json([
					'messages' => 'Token is Invalid',
					'data' => [],
					'status' => "false",
					'code' => 401
				], 200);
			}
            else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException)
            {
				return response()->json([
					'messages' => 'Token is Expired',
					'data' => [],
					'status' => "false",
					'code' => 401
				], 200);
				
               
            }
            else {

				return response()->json([
					'messages' => 'Authorization Token not found',
					'data' => [],
					'status' => "false",
					'code' => 401
				], 200);
                
            }
        }
        return $next($request);
    }
}
