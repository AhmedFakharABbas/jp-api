<?php

namespace App\Http\Middleware;

use Closure;

class Cors
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
        $trusted_domains = [
            "http://localhost:4200",
            "http://localhost:4300",
            "https://jpui.belatent.com",
            "https://jp.privacyforyou.online",
            "http://jp.privacyforyou.online",
        ];

        if(isset($request->server()['HTTP_ORIGIN'])) {
            $origin = $request->server()['HTTP_ORIGIN'];

            if(in_array($origin, $trusted_domains)) {
                header('Access-Control-Allow-Origin: ' .$origin);
                header('Access-Control-Allow-Credentials:true');
                header('Access-Control-Allow-Methods:GET,POST,PUT,PATCH,DELETE,OPTIONS');
                header('Access-Control-Allow-Headers:Origin,Content-Type,X-Requested-With,X-XSRF-TOKEN,Authorization,Accept,X-localization');
            }
        }
        return $next($request);
    }
}





