<?php

namespace Nsingularity\GeneralModule\Foundation\Services\MainServices;

use App\Entities\User;
use App\Repositories\UserRepository;
use Exception;
use Nsingularity\GeneralModule\Foundation\Exceptions\CustomMessagesException;
use Symfony\Component\HttpFoundation\Cookie;

class GeneralAuthService extends AbstractService
{
    /**
     * @param $username
     * @param $password
     * @param $agent
     * @param bool $rememberMe
     * @return array
     * @throws CustomMessagesException
     */
    public function login($username, $password, $agent, $rememberMe = false)
    {
        $isEmail = !(validator(["email" => $username], ["email" => "email"]))->fails();

        $userRepository = new UserRepository();
        if ($isEmail) {
            $user = $userRepository->showByBasicCriteria(["email" => $username], false, "", false);
        } else {
            $user = $userRepository->showByBasicCriteria(["username" => $username], false, "", false);
        }

        if (is_null($user) || !$user->validatePassword($password)) {
            customException(trans("username and password not match"), false, 401);
        }

        $token = $this::generateTokenAuth($user->getId(), $agent, $rememberMe);

        return [
            "token"  => "Bearer " . $token,
            "cookie" => $this::generateCookieAuth($token)
        ];
    }

    public function logout()
    {
        return [
            "token"  => encrypt([rand(0, 100)]),
            "cookie" => $this::generateCookieAuth(encrypt([rand(0, 100)]))
        ];
    }

    /**
     * @param $session
     * @param $authCookie
     */
    public function loadUserSession($session, $authCookie)
    {
        try {
            $session        = str_replace("Bearer ", "", $session);
            $data           = json_decode(decrypt($session), 1) ? json_decode(decrypt($session), 1) : [];
            $clientIdCookie = decrypt($authCookie);
        } catch (Exception $exception) {
            return;
        }

        $validator = validator($data, [
            "id_user"     => "required",
            "agent"       => "required|in:" . base64_encode(request()->header('User-Agent')),
            "remember_me" => "boolean",
            "expired_at"  => "required|integer|min:" . time(),
        ]);

        if ($validator->fails() || $clientIdCookie != $session) {
            return;
        }

        $userRepository = new UserRepository();
        $user           = $userRepository->show($data["id_user"], [], false);

        if ($user instanceof User) {
            customAuth()->setUser($user);
            customAuth()->setRememberMe($data["remember_me"]);
        }
    }

    /**
     * @param $userId
     * @param $agent
     * @param $rememberMe
     * @return string
     */
    public static function generateTokenAuth($userId, $agent, $rememberMe)
    {
        return encrypt(json_encode([
            "id_user"     => $userId,
            "agent"       => base64_encode($agent),
            "remember_me" => $rememberMe,
            "expired_at"  => self::calculationExpiredTIme($rememberMe),
        ]));
    }

    public static function generateCookieAuth($authKey, $rememberMe = true)
    {
        return (new Cookie("auth", encrypt($authKey), self::calculationExpiredTIme($rememberMe)));
    }

    private static function calculationExpiredTIme($rememberMe)
    {
        return time() + ($rememberMe ? 3600 * 1000 : 600);
    }

}
