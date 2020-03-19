<?php

namespace Nsingularity\GeneralModule\Foundation\Http\Middleware\Api;

use App\Services\AuthService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Nsingularity\GeneralModule\Foundation\Entities\GeneralUser;

class AuthenticateApiToken
{
    /**
     * @param Request $request
     * @param $next
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function handle(Request $request, $next)
    {
        if (user() instanceof GeneralUser) {
            /** @var Response $response */
            $response = $next($request);

            if ($response instanceof JsonResponse && is_null($response->headers->get("Authorization"))) {
                $userSession =  customAuth()->getUserSession();
                $userSession->generateExpiredAt();
                $userSession->save();

                $token = $userSession->generateToken();
                $response->withHeaders(["Authorization" => $token]);
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
