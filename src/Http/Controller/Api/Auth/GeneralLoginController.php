<?php

namespace Nsingularity\GeneralModule\Foundation\Http\Controller\Api\Auth;

use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Nsingularity\GeneralModule\Foundation\Exceptions\CustomMessagesException;
use Nsingularity\GeneralModule\Foundation\Http\Controller\Api\GeneralController;

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

        $authService = new AuthService();
        $loginData   = $authService->login(
            $request->input("username"),
            $request->input("password"),
            $request->header('User-Agent'),
            (bool)$request->input('remember_me')
        );

        return $this->response("success")
            ->withHeaders(["Authorization" => $loginData["token"]]);
    }

    /**
     * @return JsonResponse
     */
    public function logout()
    {
        $authService = new AuthService();
        $loginData   = $authService->logout();

        return $this->response("success")
            ->withHeaders(["Authorization" => $loginData["token"]]);
    }
}
