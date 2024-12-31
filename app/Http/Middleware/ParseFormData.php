<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ParseFormData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('PUT') && strpos($request->header('Content-Type'), 'multipart/form-data') !== false) {
            $data = [];
            parse_str(file_get_contents('php://input'), $data);
            $request->merge($data);
        }

        return $next($request);
    }
}
