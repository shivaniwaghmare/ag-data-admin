<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GetTableNameFromRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $uriArray = array_filter(explode('/', trim(str_replace('-', '_', $request->getRequestUri()), '/')));
        if(!empty($uriArray)){
            $table = strtolower(($uriArray[0]) . '_' . $uriArray[1]);
            $table = str_replace('agriculture_', 'agri_', $table);
            $table = str_replace('agrochemicals_', 'agri_', $table);
            $table = str_replace('commodity_', 'agri_', $table);
            $table = str_replace('geography_', 'geo_', $table);
            $table = str_replace('_farmer', '', $table);
            $request->merge(['table' => $table]);
        }
        return $next($request);
    }
}
