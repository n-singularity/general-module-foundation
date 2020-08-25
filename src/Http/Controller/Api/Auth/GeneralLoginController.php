<?php

namespace Nsingularity\GeneralModule\Foundation\Http\Controller\Api\Auth;

use App\Services\AuthService;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Nsingularity\GeneralModule\Foundation\Exceptions\CustomMessagesException;
use Nsingularity\GeneralModule\Foundation\Http\Controller\Api\GeneralController;
use Nsingularity\GeneralModule\Foundation\Http\Requests\AuthRequests\LoginValidatedRequest;
use ReflectionException;

class GeneralLoginController extends GeneralController
{
    /**
     * @param LoginValidatedRequest $request
     * @return JsonResponse
     */
    public function login(LoginValidatedRequest $request)
    {

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
