<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Get language from session, request, or default
        $language = $request->get('lang') 
            ?? Session::get('admin_language') 
            ?? config('app.locale', 'en');
            
        // Get currency from session, request, or default
        $currency = $request->get('currency') 
            ?? Session::get('admin_currency') 
            ?? 'USD';
            
        // Set language
        if (in_array($language, ['en', 'es', 'fr', 'de', 'ar', 'zh'])) {
            App::setLocale($language);
            Session::put('admin_language', $language);
        }
        
        // Set currency
        if (in_array($currency, ['USD', 'EUR', 'GBP', 'JPY'])) {
            Session::put('admin_currency', $currency);
        }
        
        // Share with all views
        view()->share('currentLanguage', $language);
        view()->share('currentCurrency', $currency);
        
        return $next($request);
    }
}