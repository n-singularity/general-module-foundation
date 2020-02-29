<?php

namespace Nsingularity\GeneralModul\Foundation\Services\MainServices;

use App\Auth;
use App\Support\TelegramBotNotification;
use App\User;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\Facades\Cache;

abstract class AbstractService
{
    public $auth_token;

    /** @var Auth|User $auth */
    public $auth;

    public function __construct(Auth $auth = null, $isPublic = false, $isLoketOutlet = false)
    {
        $this->auth = $auth;

        if ($isLoketOutlet) {
            if (env("APP_ENV", null) == "local") {
                $this->auth_token = AuthService::getSuperAdminAuthKey();
            } else {
                $this->auth_token = AuthService::getLoketOutletAuthKey();
            }

            $this->auth  = new Auth();
            $this->auth->setAuthKey($this->auth_token);
        } else if ($isPublic) {
            $this->auth_token = AuthService::getSuperAdminAuthKey();
            $this->auth =  new Auth();
            $this->auth->setAuthKey($this->auth_token);
        } else {
            $this->auth_token = $auth ? $auth->getAuthKey() : null;
        }
    }

    /**
     * @param $authKey
     * @param $requestName
     * @param array $inputParameters
     * @param int $attempt
     * @return mixed
     * @throws \App\Exceptions\CustomMessagesException
     */
    protected function createRequestInstance($authKey, $requestName, array $inputParameters = [], $attempt = 0)
    {
        $requestMap = config('apicore.requests');

        if (!array_key_exists($requestName, $requestMap)) {
            customException("Server config api not found, please report to loket support", false, 500);
        }

        // Validate api request registry
        $requestRegistry = $requestMap[$requestName];
        if (array_diff_key(array_flip(['method', 'url', 'parameters']), $requestRegistry)) {
            customException("invalid config api, please report to customer support", false, 500);
        }

        customValidation($inputParameters, $requestRegistry["parameters"]);

        $httpClient    = new HttpClient();
        $apiServerHost = env('API_SERVER_HOST');
        $url           = $apiServerHost . strColonReplace($requestRegistry["url"], $inputParameters);
        $method        = $requestRegistry["method"];

        foreach ($inputParameters as $key => $value) {
            if (is_null($value)) {
                $inputParameters[$key] = "";
            }
        }

        try {

            if ($method == "POST") {
                $response = get_response_content($httpClient->request($method, $url, [
                    "form_params" => coreHtmlTagAllow(antiXss($inputParameters)),
                    'headers'     => [
                        "Authorization"   => $authKey,
                        "Accept-Language" => "id"
                    ]
                ]));
            } else {
                $response = get_response_content($httpClient->request($method, $url, [
                    "query"   => $inputParameters,
                    'headers' => [
                        "Authorization"   => $authKey,
                        "Accept-Language" => "id"
                    ]
                ]));
            }

            return $response;
        } catch (BadResponseException $exception) {
            $code = $exception->getCode();

            $content = @get_response_content($exception->getResponse());

            if(@$content["result"]["error_messages"]){
                unset($content["result"]["error_message"]);
            }

            if ($code >= 500) {
                (new TelegramBotNotification("Core Error " . env("APP_ENV", '')))->sendArrayFormattedException(@$content["result"]["error_debug"]);
                customException("An error occurred on the server", false, 500, "CORE", null, $exception);
            }

            if ($code == 404 && !(@$content["error_message"] || @$content["result"]["error_message"])) {
                customException("Server end point '$requestName' not found, please report to loket support", false, 500);
            }

            if ($code == 403) {
                if (in_array(@$content["result"]["error_code"], [40814, 40829, 40830, 40815, 40831, 40832])) {
                    return throwOriginalMessagesException($content, 400);
                }
            }

            if ($code == 400) {
                if (in_array(@$content["result"]["error_code"], [40811])) {
                    return customException("Email dan password tidak cocok.", false, 400);
                }
            }

            if (($code == 404 || $code == 400) && (@$content["error_message"] || @$content["result"]["error_message"])) {
                $messages = @$content["error_message"] ? $content["error_message"] : $content["result"]["error_message"];

                customException($messages, false, $code, "CORE");
            }

            if ($this->auth instanceof Auth && $attempt === 0 && ($code == 406 || $code == 401)) {
                $authService = new AuthService();

                $input = [
                    "email"    => $this->auth->getEmail(),
                    "password" => $this->auth->getPassword(),
                ];

                if ($input["email"] && $input["password"]) {
                    $login = $authService->signIn($input);
                    $this->auth->setAuthKey($login["result"]["Authorization"]);
                }

                return $this->createRequestInstance($this->auth->getAuthKey(), $requestName, $inputParameters, true);
            }

            return throwOriginalMessagesException($content, $code, $exception);
        }
    }

    protected function cacheService($key, $callback, $cache){
        if ($cache) {
            return Cache::remember(md5($key.$this->auth_token), null, $callback);
        } else {
            $data = $callback();
            Cache::put(md5($key.$this->auth_token), null, $data);
            return $data;
        }
    }
}
