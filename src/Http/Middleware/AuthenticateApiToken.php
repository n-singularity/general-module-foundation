<?php

namespace Nsingularity\GeneralModule\Foundation\Http\Middleware\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Nsingularity\GeneralModule\Foundation\Entities\User;
use Nsingularity\GeneralModule\Foundation\Services\MainServices\AuthService;

class AuthenticateApiToken
{
    /**
     * @param Request $request
     * @param $next
     * @return JsonResponse
     */
    public function handle(Request $request, $next)
    {
        if (user() instanceof User) {
            /** @var Response $response */
            $response = $next($request);

            if ($response instanceof JsonResponse && is_null($response->headers->get("Authorization"))) {
                $token = AuthService::generateTokenAuth(user()->getId(), $request->header('User-Agent'), customAuth()->isRememberMe());
                $response->withHeaders(["Authorization" => $token])->cookie(AuthService::generateCookieAuth($token));
            }

            return $response;
        }

        return response()->json([
            "status" => false,
            "result" => [
                "error_code"    => 401,
                "error_message" => "Unauthorized."
            ]], 401)->withHeaders(["Authorization" => null]);
    }

}
