<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class CustomAPI
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

        if (!$this->isValidToken($request->bearerToken())){
            return response()->json([
                'error' => 'Token no valido.'
            ],401);
        }

        return $next($request);
    }

    public function isValidToken(string $token) : bool {

        $stack = [];
        $pairs = [
            '{' => '}',
            '[' => ']',
            '(' => ')',
        ];

        foreach (str_split($token) as $char){
            if (array_key_exists($char,$pairs)){
                $stack[] = $char;
            }
            elseif (in_array($char, $pairs)){
                if (empty($stack)){
                    return false;
                }

                $last = array_pop($stack);

                if ($pairs[$last] !== $char){
                    return false;
                }
            }
        }
        return empty($stack);
    }

}
