<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		if ($request->has('hashurl') && $request->hashurl != "" && url()->previous()) {
			$url = url()->previous()."#".$request->hashurl;
			
			if($request->fullUrl() != url()->previous()){
				Session::put('previous_url',$url);
				Session::put('hashtag',$request->hashurl);
			}
        }

        if ($request->get('lang') && !is_array($request->lang)) {
            $lang = $request->get('lang');
            $request->session()->replace(['_lang' => $lang]);

        } else if ($request->session()->has('_lang')) {
            $lang = $request->session()->get('_lang');
        } else {

            if (Auth::check() && Auth::user()->language) {
                $lang = Auth::user()->language;
            } else {
                $lang = \config('settings.DEFAULT_LANG');
            }

        }

        \App::setLocale($lang);
        \Carbon\Carbon::setLocale($lang);

//        if ($request->is('language/*') or $request->is('language')) {
//            return $next($request);
//        }
//
//        if ($request->session()->has('_lang')) {
//            $lang = $request->session()->get('_lang');
//            \App::setLocale($lang);
//        }
        return $next($request);
    }
}
