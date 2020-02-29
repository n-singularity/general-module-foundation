<?php

namespace Nsingularity\GeneralModul\Foundation\Http\Middleware\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiDebug
{
    /**
     * @param Request $request
     * @param $next
     * @return JsonResponse
     */
    public function handle(Request $request, $next)
    {
        $response = $next($request);

        if (env('APP_ENV', 'dev') != 'production' && env('APP_DEBUG', false) && $response instanceof JsonResponse) {
            $content = json_decode($response->getContent(), 1);
            $content['_debugbar'] = app('debugbar')->getData();
            $response->setContent(json_encode($content));
        }

        return $response;
    }

}
