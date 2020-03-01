<?php

namespace Nsingularity\GeneralModule\Foundation\Http\Controller\Api\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Nsingularity\GeneralModule\Foundation\Exceptions\CustomMessagesException;
use Nsingularity\GeneralModule\Foundation\Http\Controller\Api\GeneralController;
use Nsingularity\GeneralModule\Foundation\Services\MainServices\GeneralAuthService;

class GeneralLoginController extends GeneralController
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomMessagesException
     */
    public function login(Request $request)
    {
        customValidationFromRequest([
            "username" => "required",
            "password" => "required",
        ]);

        $authService = new GeneralAuthService();
        $loginData   = $authService->login(
            $request->input("username"),
            $request->input("password"),
            $request->header('User-Agent'),
            (bool)$request->input('remember_me')
        );

        return $this->response("success")
            ->withHeaders(["Authorization" => $loginData["token"]])
            ->cookie($loginData["cookie"]);
    }

    /**
     * @return JsonResponse
     */
    public function logout()
    {
        $authService = new GeneralAuthService();
        $loginData   = $authService->logout();

        return $this->response("success")
            ->withHeaders(["Authorization" => $loginData["token"]])
            ->cookie($loginData["cookie"]);
    }
}
